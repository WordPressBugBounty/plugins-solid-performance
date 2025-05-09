/**
 * WordPress dependencies
 */
import { Button, Tooltip } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { store as preloadStore } from '../../store';
import { useDispatch } from '@wordpress/data';

/**
 * @param {boolean} isPreloading Whether we are preloading.
 * @param {string} text The translated button text.
 * @param {string} label The translated Tooltip text.
 * @param {boolean} force Whether we are force preloading the entire site.
 * @param {boolean} hidden If we are preloading, one of the buttons should be hidden.
 */
export default function PreloadButton( { isPreloading, text, label, force = false, hidden = false } ) {
	if (hidden) {
		return null;
	}

	const { startPreloader, cancelPreloader } = useDispatch(preloadStore);

	const handleClick = async () => {
		if (isPreloading) {
			await cancelPreloader();
		} else {
			await startPreloader(force);
		}
	};

	const buttonText = isPreloading
		? __('Cancel Preloading', 'solid-performance')
		: text;

	const button = (
		<Button
			variant="secondary"
			onClick={handleClick}
		>
			{buttonText}
		</Button>
	);

	const isLongLabel = label?.length > 80;
	const tooltipClassName = isLongLabel ? 'swpsp-preload-long-tooltip' : '';

	return label ? (
		<Tooltip text={label} className={tooltipClassName}>
			{button}
		</Tooltip>
	) : (
		button
	);
};
