/**
 * WordPress dependencies
 */
import { _n } from '@wordpress/i18n';
import { Icon, info } from '@wordpress/icons';
import { createInterpolateElement } from '@wordpress/element';

/**
 * Display the count of the number of cached files.
 *
 * @param {number} count The current cache file count.
 *
 * @returns {JSX.Element}
 */
export default function CacheCounter( { count } ) {
	return (
		<p>
			<Icon
				icon={info}
				size={32}
				style={{ verticalAlign: 'bottom', fill: 'var(--grape-purple-110)' }}
			/>
			{createInterpolateElement(
				_n(
					'<Number /> page cached',
					'<Number /> pages cached',
					count,
					'solid-performance',
				),
				{
					Number: <b>{count}</b>,
				},
			)}
		</p>
	);
};
