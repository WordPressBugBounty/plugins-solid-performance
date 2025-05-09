import { __ } from '@wordpress/i18n';
import { useCopyToClipboard } from '@wordpress/compose';
import { useDispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';

/**
 * Copies text to the clipboard and displays a snackbar notice.
 *
 * @param {string} text - The text to copy to the clipboard.
 *
 * @returns {import('react').Ref<HTMLElement>}
 */
export const copyToClipboard = ( text ) => {
	const { createInfoNotice } = useDispatch(noticesStore);

	return useCopyToClipboard(text, () => {
		createInfoNotice(__('Copied to clipboard', 'solid-performance'), {
			isDismissible: true,
			type: 'snackbar',
		});
	});
};
