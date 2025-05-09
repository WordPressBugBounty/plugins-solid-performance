import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { copySmall } from '@wordpress/icons';
import { copyToClipboard } from '../../utils/copy-to-clipboard';

const preStyles = {
	whiteSpace: 'pre-wrap',
	background: '#fcfcfc',
	padding: '0.5rem',
	borderRadius: '4px',
	fontFamily: 'monospace',
	overflowX: 'auto',
	border: '1px solid #cacaca',
	margin: 0,
};

/**
 * Displays text in a <pre /> element with an optional copy-to-clipboard button.
 *
 * @param {string} text - The text to display inside the <pre /> block.
 * @param {Object} buttonStyle - Inline styles for the copy button.
 * @param {boolean} showCopy -  Whether to show the copy button.
 *
 * @returns {JSX.Element}
 */
const CopyableCodeSnippet = ( { text, buttonStyle = {}, showCopy = true } ) => {
	const copyButtonRef = copyToClipboard(text);

	return (
		<>
			<pre style={preStyles}>{text}</pre>
			{showCopy && (
				<Button
					__next40pxDefaultSize
					icon={copySmall}
					ref={copyButtonRef}
					label={__('Copy to clipboard', 'solid-performance')}
					style={buttonStyle}
				/>
			)}
		</>
	);
};

export default CopyableCodeSnippet;
