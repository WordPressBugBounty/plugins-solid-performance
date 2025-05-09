/**
 * @typedef {Object} PreloadState
 * @property {number} cacheCount The number of cached pages.
 * @property {string} preloadId The ID of the preload process.
 * @property {number} progressPercent The progress of the preload process as a percentage.
 * @property {string} source The source of the preload.
 * @property {boolean} isPreloading Whether the preload process is running.
 * @property {{type?: string, code?: string, message?: string}} [notice] Optional notice object with type, code, and message.
 */

/**
 * @typedef {Object} PreloadStoreCallables
 * @property {function(PreloadState): {type: string, newState: PreloadState}} updatePreloadStatus
 * @property {function(): Promise<PreloadState>} refreshPreloadStatus
 * @property {function(boolean): function({dispatch: Function, registry: Object}): Promise<void>} startPreloader
 * @property {function(): function({dispatch: Function, registry: Object}): Promise<void>} cancelPreloader
 * @property {function(): PreloadState} getPreloadStatus
 */

/**
 * @typedef {Object} CacheCountResponse
 * @property {number} count How many cached pages this site has across all compression types.
 */

/**
 * @typedef {Object} ProgressResponse
 * @property {boolean} running Whether the preloader is running.
 * @property {string?} code The response code.
 * @property {string?} message The message to display to the user.
 * @property {string?} preloadId The unique preload ID.
 * @property {number?} progress The current preloading completion progress percent.
 * @property {string?} source The source of what started or canceled the preloader, e.g. 'web' or 'cli'.
 */

/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { store as noticesStore } from '@wordpress/notices';

/**
 * Internal dependencies
 */
import { PreloadId } from '../preload-id';

export const SET_PRELOAD_STATUS = 'SET_PRELOAD_STATUS';
export const PRELOADER_START = 'PRELOADER_START';
export const PRELOADER_CANCEL = 'PRELOADER_CANCEL';

let preloadAbortController = null;

/**
 * Creates an action to update the preload status in the Redux store.
 *
 * @param {PreloadState} newState
 *
 * @return {Object} The action object to update the Redux store.
 */
export const updatePreloadStatus = ( newState ) => {
	return {
		type: SET_PRELOAD_STATUS,
		newState,
	};
};

/**
 * Creates an action to set preloader state to started in the Redux store.
 *
 * @param {PreloadState['notice']} notice
 *
 * @return {Object} The action object to update the Redux store.
 */
export const preloaderStart = ( notice ) => {
	return {
		type: PRELOADER_START,
		notice,
	};
}

/**
 * Creates an action to set preloader state to cancelled in the Redux store.
 *
 * @param {PreloadState['notice']} notice
 *
 * @return {Object} The action object to update the Redux store.
 */
export const preloaderCancel = ( notice ) => {
	return {
		type: PRELOADER_CANCEL,
		notice,
	};
}

/**
 * Fetches the current preload status from the server and updates the Redux store.
 *
 * @return {function({dispatch: *, registry: *}): Promise<{cacheCount: *, preloadId: *, progressPercent: *, source: *, isPreloading: *, notice: {code: *, message: *}}>}
 */
export const refreshPreloadStatus = () => async ( { select, dispatch, registry } ) => {
	/** @returns {CacheCountResponse} */
	const pageCache = await apiFetch({ path: '/solid-performance/v1/page/cache-count' });
	/** @returns {ProgressResponse} */
	const progress = await apiFetch({ path: '/solid-performance/v1/page/preload' });

	let apiData = {
		isPreloading: progress.running,
		cacheCount: pageCache.count,
		progressPercent: progress.progress,
		source: progress.source,
		preloadId: progress.preloadId,
	};

	// Note: this is polled and always returns the last state.
	switch (progress.code) {
		// These states "stick", so we need to make sure we don't spam the notices.
		case 'solid_performance_preloader_completed':
		case 'solid_performance_preloader_canceled': {
			const preloadStatus = await select.getPreloadStatus();

			// If the preloadId has been cleared elsewhere and the last status is that we are preloading,
			// send the completion/cancellation notification.
			if (!PreloadId.has() && preloadStatus.isPreloading) {
				if (progress.code === 'solid_performance_preloader_canceled') {
					registry.dispatch(noticesStore).createInfoNotice(progress.message);
				} else {
					registry.dispatch(noticesStore).createSuccessNotice(progress.message);
				}

				PreloadId.set(progress.preloadId);
			}

			// If we have no preloadId and we aren't preloading, store a preloadId to prevent infinite notifications.
			if (!PreloadId.has() && !preloadStatus.isPreloading) {
				PreloadId.set(progress.preloadId);
			}

			break;
		}

		// The preloader stalled but is retrying.
		case 'solid_performance_preloader_stalled': {
			registry.dispatch(noticesStore).createWarningNotice(progress.message, {
				type: 'snackbar',
			});

			break;
		}

		// The preloader failed or we reached the maximum number of stalled retries.
		case 'solid_performance_preloader_failed': {
			registry.dispatch(noticesStore).createErrorNotice(progress.message);

			break;
		}
	}


	apiData = {
		...apiData,
		notice: {
			code: progress.code,
			message: progress.message,
		}
	}

	dispatch(updatePreloadStatus(apiData));

	return apiData;
};

/**
 * Starts the preloader process and updates the Redux store with its status.
 *
 * @param {boolean} force Whether we are force preloading the entire site.
 *
 * @return Promise<void>
 */
export const startPreloader = ( force = false ) => async ( { dispatch, registry } ) => {
	let notice;

	// If a previous request is ongoing, abort it.
	if (preloadAbortController) {
		preloadAbortController.abort();
	}

	preloadAbortController = new AbortController();

	// Force the UI to update its preloading status immediately.
	dispatch(updatePreloadStatus({ isPreloading: true }));

	try {
		const preload = await apiFetch({
			path: '/solid-performance/v1/page/preload',
			method: 'POST',
			data: { force },
			signal: preloadAbortController.signal, // Attach the abort signal.
		});

		if (preload && preload.code === 'solid_performance_preloader_started') {
			notice = {
				type: 'success',
				code: preload.code,
				message: preload.message,
			};

			dispatch(preloaderStart(notice));
			dispatch(refreshPreloadStatus());
		} else {
			notice = {
				type: 'error',
				message: __('Error starting preloader.', 'solid-performance'),
			};
		}
	} catch (error) {
		// Request was aborted, do not display any notice.
		if (error.name === 'AbortError') {
			return;
		}
		notice = {
			type: 'error',
			message: error.message || __('An unexpected error occurred.', 'solid-performance'),
		};
	}

	registry.dispatch(noticesStore).createNotice(notice.type, notice.message, {
		type: 'snackbar',
	});
};

/**
 * Cancels the preloader process and updates the Redux store with its status.
 *
 * @return Promise<void>
 */
export const cancelPreloader = () => async ( { dispatch, registry } ) => {
	let notice;

	// Abort the ongoing preload request if it exists.
	if (preloadAbortController) {
		preloadAbortController.abort();
		preloadAbortController = null;
	}

	// Force the UI to update its preloading status immediately.
	dispatch(updatePreloadStatus({ isPreloading: false }));

	try {
		const preload = await apiFetch({
			path: '/solid-performance/v1/page/preload',
			method: 'DELETE',
		});

		if (preload && preload.message) {
			notice = {
				type: 'success',
				code: preload.code,
				message: preload.message,
			};

			dispatch(preloaderCancel(notice));
			dispatch(refreshPreloadStatus());
		} else {
			notice = {
				type: 'error',
				message: __('Error canceling preloader.', 'solid-performance'),
			};
		}
	} catch (error) {
		// Ignore abort errors for DELETE requests.
		if (error.name === 'AbortError') {
			return;
		}
		notice = {
			type: 'error',
			message: error.message || __('An unexpected error occurred.', 'solid-performance'),
		};
	}

	registry.dispatch(noticesStore).createNotice(notice.type, notice.message, {
		type: 'snackbar',
	});
};
