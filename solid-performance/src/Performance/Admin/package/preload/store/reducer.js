/**
 * Internal dependencies
 */
import { SET_PRELOAD_STATUS, PRELOADER_START, PRELOADER_CANCEL } from './actions';
import { PreloadId } from '../preload-id';

const DEFAULT_STATE = {
	isPreloading: false,
	cacheCount: 0,
	progressPercent: 0,
	source: '',
	preloadId: '',
	notice: {
		type: '',
		code: '',
		message: '',
	},
};

const reducer = ( state = DEFAULT_STATE, action ) => {
	switch (action.type) {
		case SET_PRELOAD_STATUS:
			// Clear the preload ID while preloading, so the final notice can be sent.
			if (action.newState.isPreloading) {
				PreloadId.clear();
			}

			return {
				...state,
				...action.newState,
			};
		case PRELOADER_START:
			return {
				...state,
				isPreloading: true,
				notice: action.notice,
			};
		case PRELOADER_CANCEL:
			return {
				...state,
				isPreloading: false,
				notice: action.notice,
			};
		default:
			return state;
	}
};

export default reducer;
