/**
 * Solid Performance Settings
 *
 */
import { createRoot } from '@wordpress/element';
import SolidPerformanceSettings from './settings.js';
import { solidTheme as theme, Root } from '@ithemes/ui';

const rootElement = document.querySelector('.solidwp-performance-settings-main');
if ( rootElement ) {
	createRoot( rootElement ).render(
		<Root theme={ theme }>
			<SolidPerformanceSettings />
		</Root>
	);
}
