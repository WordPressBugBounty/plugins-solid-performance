import { __ } from '@wordpress/i18n';
import NginxConfFileViewer from './nginx-conf-file-viewer';
import CopyableCodeSnippet from '../copyable-code-snippet';

/**
 * Render the nginx.conf include code.
 *
 * @param {string} path - The server path to the swpsp-nginx.conf file.
 *
 * @returns {JSX.Element}
 */
const NginxConf = ( { path } ) => {
	const conf = `# Include the Solid Performance cache configuration.
include ${path};`;

	return (
		<>
			<h4>{__('Add this line to your Nginx server{} block:', 'solid-performance')}</h4>
			<div style={{ position: 'relative' }}>
				<CopyableCodeSnippet
					text={conf}
					buttonStyle={{
						position: 'absolute',
						top: '8px',
						right: '34px',
					}}
				/>
				<NginxConfFileViewer />
			</div>
		</>
	);
};

export default NginxConf;
