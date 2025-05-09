import { __ } from '@wordpress/i18n';
import {
	Card,
	CardBody,
	CardHeader,
	SelectControl,
	Button,
} from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { useEffect, useState } from 'react';
import { METHOD_HTACCESS, METHOD_NGINX, METHOD_PHP } from './constants';
import { CacheConfigButtons } from '../cache-config-buttons';
import { createInterpolateElement } from '@wordpress/element';
import NginxConf from './nginx-conf';

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
	const { method } = performanceSettings?.page_cache?.cache_delivery ?? '';
	// Global from wp_localize_script().
	const htaccessSupported = swspParams.cacheDelivery.htaccess.supported;
	const nginxSupported = swspParams.cacheDelivery.nginx.supported;
	const [ cacheDelivery, setCacheDelivery ] = useState(swspParams.cacheDelivery);

	const cacheDeliveryOptions = [
		{
			disabled: true,
			label: __('Select a Page Cache Method', 'solid-performance'),
			value: '',
		},
		Boolean(htaccessSupported) && {
			label: __('htaccess', 'solid-performance'),
			value: METHOD_HTACCESS,
		},
		Boolean(nginxSupported) && {
			label: __('nginx', 'solid-performance'),
			value: METHOD_NGINX,
		},
		{
			label: __('php', 'solid-performance'),
			value: METHOD_PHP,
		},
	].filter(Boolean);

	const setCacheDeliveryState = ( newState ) => {
		setCacheDelivery({
			[METHOD_HTACCESS]: {
				...cacheDelivery[METHOD_HTACCESS],
				...newState[METHOD_HTACCESS],
			},
			[METHOD_NGINX]: {
				...cacheDelivery[METHOD_NGINX],
				...newState[METHOD_NGINX],
			},
		});
	};

	const updateCacheDeliveryState = async () => {
		await apiFetch({
			path: `/solid-performance/v1/page/cache-delivery`,
		}).then(( result ) => {
			setCacheDeliveryState(result.cacheDelivery);
		}).catch(( error ) => {
			console.log(error);
		});
	};

	useEffect(() => {
		if (isSaved) {
			console.log('Updating cache delivery state...');
			void updateCacheDeliveryState();
		}
	}, [ isSaved, performanceSettings.page_cache.cache_delivery ]);

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
					help={
						({
							[ METHOD_HTACCESS ]: __(
								'The "htaccess" method bypasses PHP and provides better performance, but it disables any response header caching (these will be stripped). Defaults to "htaccess" if Apache is detected on the hosting server.',
								'solid-performance'
							),
							[ METHOD_PHP ]: __(
								'The "php" method runs through WordPress. It supports all headers, but it is slightly slower than the "htaccess" or "nginx" method.',
								'solid-performance'
							),
							[ METHOD_NGINX ]: createInterpolateElement(
								__(
									'The "nginx" method bypasses PHP and provides better performance, but it disables any response header caching (these will be stripped).<br />⚠️ <strong>This configuration requires root access and should only be carried out by experienced system administrators.</strong>',
									'solid-performance'
								),
								{
									br: <br />,
									strong: <strong />,
								}
							),
						})[method] ??
						__(
							'Choose a method for serving cached pages.',
							'solid-performance'
						)
					}
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
				{htaccessSupported && method === METHOD_HTACCESS && isSaved && (
					<CacheConfigButtons
						method={METHOD_HTACCESS}
						cacheDelivery={cacheDelivery}
						setCacheDeliveryState={setCacheDeliveryState}
						regenerateLabel={__('Regenerate htaccess rules', 'solid-performance')}
						removeLabel={__('Remove htaccess rules', 'solid-performance')}
						rulesFoundMessage={__('Solid Performance rules found in your .htaccess file!', 'solid-performance')}
						rulesMissingMessage={__('The Solid Performance rules are missing from your .htaccess file. Try regenerating the rules to restore full caching functionality.', 'solid-performance')}
					/>
				)}

				{nginxSupported && method === METHOD_NGINX && isSaved && (
					<>
						<CacheConfigButtons
							method={METHOD_NGINX}
							cacheDelivery={cacheDelivery}
							setCacheDeliveryState={setCacheDeliveryState}
							regenerateLabel={__('Regenerate Nginx rules', 'solid-performance')}
							removeLabel={__('Bypass Nginx cache', 'solid-performance')}
							rulesFoundMessage={createInterpolateElement(
								__('Solid Performance Nginx rules found! <a>Get help</a> configuring your server. <em>Note: Nginx caching will not be active until the rules have been included in your server configuration</em>.', 'solid-performance'),
								{
									a: (
										<a
											href="https://go.solidwp.com/performance-page-cache-delivery#using-nginx"
											target="_blank"
											rel="noopener noreferrer"
										/>
									),
									em: <em />,
								}
							)}
							rulesMissingMessage={createInterpolateElement(
								__('The Solid Performance Nginx rules are currently set to bypass server-level caching. Instead, caching will be managed by PHP. <em>Note: This change will not take effect until you reload your Nginx server</em>.', 'solid-performance'),
								{
									em: <em />,
								}
							)}
						/>
						<NginxConf path={cacheDelivery[METHOD_NGINX].path} />
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
