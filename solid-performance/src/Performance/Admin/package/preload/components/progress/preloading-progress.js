/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { ProgressBar } from '@wordpress/components';

/**
 * @param {PreloadState} preloadStatus
 */
export default function PreloadingProgress( { preloadStatus } ) {
	const { isPreloading, source, progressPercent } = preloadStatus;

	if (!isPreloading) {
		return null;
	}

	return (
		<div className="preloading-progress">
			<p>
				{source
					? sprintf(
						/* translators: %s: The source of what initiated or canceled the preloader. */
						__('Preloading via %s in progress', 'solid-performance'),
						source,
					)
					: __('Starting preloader', 'solid-performance')}
				<span className="dots"/>
			</p>
			<div>
				<ProgressBar
					value={progressPercent}
					className="preload-progress-bar"
				/>
			</div>
		</div>
	);
};
