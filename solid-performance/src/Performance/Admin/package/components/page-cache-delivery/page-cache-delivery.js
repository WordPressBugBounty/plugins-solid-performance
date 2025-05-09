import { __ } from '@wordpress/i18n';
import {
	Card,
	CardBody,
	CardHeader,
	SelectControl,
	Button,
	BaseControl,
} from '@wordpress/components';
import { useDispatch } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';
import { store as noticesStore } from '@wordpress/notices';
import { useState } from 'react';
import { METHOD_HTACCESS, METHOD_PHP } from './constants';

/**
 * The Page Cache Delivery card and related functionality.
 *
 * @param performanceSettings
 * @param setState
 * @returns {JSX.Element}
 * @constructor
 */
const PageCacheDelivery = ( { performanceSettings, setState } ) => {
	const [ isSaved, setIsSaved ] = useState(true);
	const { createErrorNotice, createSuccessNotice } = useDispatch(noticesStore);
	const { method } = performanceSettings?.page_cache?.cache_delivery ?? '';
	// Global from wp_localize_script().
	const supported = swspParams.cacheDelivery.htaccess.supported;

	const cacheDeliveryOptions = [
		{
			disabled: true,
			label: __('Select a Page Cache Method', 'solid-performance'),
			value: '',
		},
		Boolean(supported) && {
			label: __('htaccess', 'solid-performance'),
			value: METHOD_HTACCESS,
		},
		{
			label: __('php', 'solid-performance'),
			value: METHOD_PHP,
		},
	].filter(Boolean);

	const handleHtaccessRegenerate = async () => {
		await apiFetch({
			path: '/solid-performance/v1/page/htaccess',
			method: 'POST',
		}).then(( result ) => {
			createSuccessNotice(result.message, {
				type: 'snackbar',
			});
		}).catch(( error ) => {
			createErrorNotice(error.message, {
				type: 'snackbar',
			});
			console.error(error);
		});
	};

	const handleHtaccessRemove = async () => {
		await apiFetch({
			path: '/solid-performance/v1/page/htaccess',
			method: 'DELETE',
		}).then(( result ) => {
			createSuccessNotice(result.message, {
				type: 'snackbar',
			});
		}).catch(( error ) => {
			createErrorNotice(error.message, {
				type: 'snackbar',
			});
			console.error(error);
		});
	};

	return (
		<Card>
			<CardHeader>
				<h2>{__('Page Cache Delivery', 'solid-performance')}</h2>
				<Button
					variant="text"
					href="https://go.solidwp.com/performance-page-cache-delivery"
					icon={() => (
						<svg xmlns="http://www.w3.org/2000/svg"
							 viewBox="0 0 24 24" width="24"
							 height="24"
							 aria-hidden="true" focusable="false">
							<path
								d="M12 4.75a7.25 7.25 0 100 14.5 7.25 7.25 0 000-14.5zM3.25 12a8.75 8.75 0 1117.5 0 8.75 8.75 0 01-17.5 0zM12 8.75a1.5 1.5 0 01.167 2.99c-.465.052-.917.44-.917 1.01V14h1.5v-.845A3 3 0 109 10.25h1.5a1.5 1.5 0 011.5-1.5zM11.25 15v1.5h1.5V15h-1.5z"></path>
						</svg>
					)}
					target="_blank"
					size="small"
					showTooltip={true}
					label="View external documentation"
				/>
			</CardHeader>
			<CardBody>
				<SelectControl
					__next40pxDefaultSize
					__nextHasNoMarginBottom
					label={__('Page Cache Method', 'solid-performance')}
					help={__('The "htaccess" method bypasses PHP and should provide better performance, but it disables any response header caching (these will be stripped). Defaults to "htaccess" if Apache is detected on the hosting server.', 'solid-performance')}
					onChange={( value ) => {
						setState({
							page_cache: {
								cache_delivery: {
									method: value,
								},
							},
						});
						setIsSaved(false);
					}}
					value={method}
					options={cacheDeliveryOptions}
				/>
				{supported && method === METHOD_HTACCESS && isSaved && (
					<>
						<BaseControl>
							<Button variant="secondary"
									onClick={handleHtaccessRegenerate}>
								{__('Regenerate htaccess rules', 'solid-performance')}
							</Button>

							<Button variant="tertiary"
									onClick={handleHtaccessRemove}>
								{__('Remove htaccess rules', 'solid-performance')}
							</Button>
						</BaseControl>
					</>
				)}
				<p className="submit">
					<Button
						variant="primary"
						type="submit"
						onClick={() => setIsSaved(true)}
					>
						{__('Save', 'solid-performance')}
					</Button>
				</p>
			</CardBody>
		</Card>
	);
};

export default PageCacheDelivery;
