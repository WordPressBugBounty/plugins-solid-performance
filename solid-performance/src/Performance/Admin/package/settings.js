/**
 * Solid Performance Settings
 */
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from 'react';
import {
	Card,
	CardBody,
	CardHeader,
	Button,
	TextareaControl,
	TextControl,
	ToggleControl,
	TabPanel,
	BaseControl,
	Flex,
	FlexItem
} from '@wordpress/components';
import { useDispatch, useSelect } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';
import Notices from './notices.js';
import { store as noticesStore } from '@wordpress/notices';
import { store as preloadStore } from './preload/store';
import { PreloadingProgress } from './preload/components/progress';
import { PreloadButton } from './preload/components/button';
import { CacheCounter } from './preload/components/cache-counter';
import { PageCacheDelivery } from './components/page-cache-delivery';

/**
 * Import Css
 */
import './editor.scss';


const SolidPerformanceSettings = () => {
	const { createErrorNotice, createSuccessNotice } = useDispatch(noticesStore);
	const [ performanceSettings, setPerformanceSettings ] = useState(swspParams.settings);

	/** @type {PreloadState} */
	const preloadStatus = useSelect(( select ) => select(preloadStore).getPreloadStatus(), []);
	const { isPreloading, cacheCount } = preloadStatus;

	/** @type {PreloadStoreCallables} */
	const storeDispatch = useDispatch(preloadStore);
	const { refreshPreloadStatus } = storeDispatch;

	const fetchSettings = async () => {
		try {
			const { solid_performance_settings } = await apiFetch({
				path: '/wp/v2/settings',
			});
			setPerformanceSettings(solid_performance_settings);
		} catch (error) {
			createErrorNotice(__('Error: Unable to load settings.', 'solid-performance'));
			console.error(error);
		}
	};

	/**
	 * Fetch the current preload status and register any notices.
	 *
	 * @returns {Promise<PreloadState>}
	 */
	const fetchPreloadStatus = async () => {
		return refreshPreloadStatus();
	};

	// Initialize settings and preload status.
	useEffect(() => {
		void fetchSettings();
		void fetchPreloadStatus();
	}, []);

	// Check preloading status more frequently if we are currently preloading.
	useEffect(() => {
		const intervalTime = isPreloading ? 3000 : 8000;
		const interval = setInterval(fetchPreloadStatus, intervalTime);

		return () => clearInterval(interval);
	}, [ preloadStatus ]);

	const handleSubmit = async ( event ) => {
		event.preventDefault();

		await apiFetch({
			path: '/wp/v2/settings',
			method: 'POST',
			data: {
				solid_performance_settings: {
					...performanceSettings,
				},
			},
		}).then(( result ) => {
			const { solid_performance_settings } = result;

			if (solid_performance_settings) {
				createSuccessNotice(__('Settings Saved', 'solid-performance'), {
					type: 'snackbar',
				});
				setPerformanceSettings(solid_performance_settings);
			}
		}).catch(( error ) => {
			// Refresh the UI state as we likely didn't successfully save the settings.
			fetchSettings();

			createErrorNotice(error.message, {
				type: 'snackbar',
			});

			console.error(error);
		});
	};
	const handleClearCache = async () => {
		await apiFetch({
			path: '/solid-performance/v1/page/clear',
			method: 'POST',
		}).catch(( error ) => {
			createErrorNotice(error.message, {
				type: 'snackbar',
			});
			console.error(error);
		});
		// Handle clear cache
		createSuccessNotice(__('Cache Cleared', 'solid-performance'), {
			type: 'snackbar',
		});

		await fetchPreloadStatus();
	};

	const handleAdvancedRegenerate = async () => {
		const regenerated = await apiFetch({
			path: '/solid-performance/v1/page/regenerate',
			method: 'POST',
		}).catch(( error ) => {
			createErrorNotice(__('Error Regenerating', 'solid-performance'), {
				type: 'snackbar',
			});
			console.error(error);
		});

		if (regenerated.code !== 'solid_performance_advanced_cache_regenerated') {
			createErrorNotice(__('Error Regenerating', 'solid-performance'), {
				type: 'snackbar',
			});
			console.error(regenerated);
		} else {
			// Handle advanced cache regeneration
			createSuccessNotice(__('advanced-cache.php Regenerated', 'solid-performance'), {
				type: 'snackbar',
			});

			// Remove our WordPress notice, if it's displayed
			const notice = document.getElementById('solidwp-performance-inactive');
			if (notice) {
				notice.remove();
			}
		}
	};

	const setState = ( newState ) => {
		setPerformanceSettings({
			...performanceSettings,
			...newState,
			page_cache: {
				...performanceSettings?.page_cache,
				...newState?.page_cache,
			},
		});
	};

	const tabs = [
		{
			name: 'basic',
			title: __('Basic', 'solid-performance'),
			className: 'basic-tab',
		},
		{
			name: 'advanced',
			title: __('Advanced', 'solid-performance'),
			className: 'advanced-tab',
		},
	];
	return (
		<>
			<Flex align="baseline">
				<FlexItem>
					<h1>{__('Performance Settings', 'solid-performance')}</h1>
				</FlexItem>
				<FlexItem style={{ paddingRight: '20px' }} className="swpsp-page-count">
					<CacheCounter count={cacheCount} />
				</FlexItem>
			</Flex>
			<Notices/>
			<form className={'swpsp-settings-form'} onSubmit={handleSubmit}>
				<TabPanel
					className="swpsp-settings-section-tabs"
					orientation="vertical"
					tabs={tabs}>
					{
						( tab ) => {
							switch (tab.name) {
								case 'basic':
									return (
										<Card>
											<CardHeader>
												<header>
													<h2>
														{__('Basic Settings', 'solid-performance')}
													</h2>
													{performanceSettings?.page_cache?.enabled && (
														<>
															<Button variant="secondary"
																	onClick={handleClearCache}
																	disabled={isPreloading}>
																{__('Purge Page Cache', 'solid-performance')}
															</Button>
															<PreloadButton
																isPreloading={isPreloading}
																text={__('Preload Uncached Pages', 'solid-performance')}
																force={false}
																hidden={isPreloading}
																label={__(
																	'Generates cache files only for URLs that are not already cached',
																	'solid-performance'
																)}
															/>
															<PreloadButton
																isPreloading={isPreloading}
																text={__('Preload & Refresh All', 'solid-performance')}
																force={true}
																label={__(
																	'Cached URLs are served from the existing cache until each is replaced during the site-wide preloading pass',
																	'solid-performance'
																)}
															/>
														</>
													)}
													<Button
														variant="text"
														href="https://go.solidwp.com/performance-page-caching"
														icon={() => (
															<svg xmlns="http://www.w3.org/2000/svg"
																 viewBox="0 0 24 24"
																 width="24" height="24" aria-hidden="true"
																 focusable="false">
																<path
																	d="M12 4.75a7.25 7.25 0 100 14.5 7.25 7.25 0 000-14.5zM3.25 12a8.75 8.75 0 1117.5 0 8.75 8.75 0 01-17.5 0zM12 8.75a1.5 1.5 0 01.167 2.99c-.465.052-.917.44-.917 1.01V14h1.5v-.845A3 3 0 109 10.25h1.5a1.5 1.5 0 011.5-1.5zM11.25 15v1.5h1.5V15h-1.5z"></path>
															</svg>
														)}
														target="_blank"
														size="small"
														showTooltip={true}
														label="View external documentation"
													/>
												</header>
											</CardHeader>
											<CardBody>
												<PreloadingProgress preloadStatus={preloadStatus} />
												<ToggleControl
													label={__('Enable Page Cache', 'solid-performance')}
													checked={performanceSettings?.page_cache?.enabled ||
														false}
													className={'swpsp-large-toggle'}
													onChange={( value ) => setState(
														{ page_cache: { enabled: value } })}
												/>
												<p className="submit">
													<Button variant="primary" type="submit">
														{__('Save', 'solid-performance')}
													</Button>
												</p>
											</CardBody>
										</Card>
									);
								case 'advanced':
									return (
										<>
											<Card>
												<CardHeader>
													<header>
														<h2>
															{__('Advanced Settings', 'solid-performance')}
														</h2>
														<Button
															variant="text"
															href="https://go.solidwp.com/performance-exclusions"
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
													</header>
												</CardHeader>
												<CardBody>
													<ToggleControl
														label={__('Lazy Load Images', 'solid-performance')}
														checked={performanceSettings?.page_cache?.lazy_loading?.enabled ||
															false}
														help={__('Automatically lazy load inline CSS background images.', 'solid-performance')}
														onChange={( value ) => setState(
															{
																page_cache: {
																	lazy_loading: {
																		enabled: value
																	}
																},
															})}
													/>
													<ToggleControl
														label={__('Cloudflare Image Transformation', 'solid-performance')}
														checked={performanceSettings?.page_cache?.image_transformation?.enabled ||
															false}
														help={__('Rewrite image URLs to use Cloudflare Images to load image sizes directly from the edge. This needs to be enabled in your Cloudflare dashboard.', 'solid-performance')}
														onChange={( value ) => setState(
															{
																page_cache: {
																	image_transformation: {
																		processor: 'cloudflare', // Update this to be dynamic if we add more processors.
																		enabled: value
																	}
																},
															})}
													/>
													<TextareaControl
														label={__('Cache Exclusions', 'solid-performance')}
														help={__(
															'Enter URLs for pages, or wildcard exclusion patterns to exclude from the cache, one per line. Changes purge the entire cache when using htaccess mode.',
															'solid-performance')}
														placeholder={__('/example/*', 'solid-performance')}
														value={performanceSettings?.page_cache?.exclusions?.join(
															'\n') || ''}
														onChange={( value ) => setState(
															{
																page_cache: {
																	exclusions: value.split('\n'),
																},
															})}
													/>
													<p className="submit">
														<Button variant="primary" type="submit">
															{__('Save', 'solid-performance')}
														</Button>
													</p>
												</CardBody>
											</Card>
											<PageCacheDelivery
												performanceSettings={performanceSettings}
												setState={setState}
											/>
											<Card>
												<CardHeader>
													<header>
														<h2>
															{__('Debug', 'solid-performance')}
														</h2>
														<Button
															variant="text"
															href="https://go.solidwp.com/performance-directory"
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
													</header>
												</CardHeader>
												<CardBody>
													<ToggleControl
														label={__('Enable Debug Mode', 'solid-performance')}
														help={__(
															'Enable debug mode to log cache status and performance information.',
															'solid-performance')}
														checked={performanceSettings?.page_cache?.debug ||
															false}
														onChange={( value ) => setState(
															{ page_cache: { debug: value } })}
													/>
													<TextControl
														label={__('Cache File Directory',
															'solid-performance')}
														help={__(
															'The directory where cache files are stored on the server.',
															'solid-performance')}
														value={performanceSettings?.page_cache?.cache_dir || ''}
														onChange={() => {
														}} // Read only
														readOnly
													/>
													<BaseControl>
														<Button variant="secondary"
																onClick={handleAdvancedRegenerate}>
															{__('Regenerate the advanced-cache.php file',
																'solid-performance')}
														</Button>
													</BaseControl>
													<p className="submit">
														<Button variant="primary" type="submit">
															{__('Save', 'solid-performance')}
														</Button>
													</p>
												</CardBody>
											</Card>
										</>
									);
							}
						}
					}
				</TabPanel>
			</form>
		</>
	);
};
export default SolidPerformanceSettings;
