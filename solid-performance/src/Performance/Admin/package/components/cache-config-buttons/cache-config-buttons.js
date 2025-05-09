import { Button, BaseControl } from '@wordpress/components';
import { MessageList } from '@ithemes/ui';
import { METHOD_HTACCESS } from '../page-cache-delivery/constants';
import apiFetch from '@wordpress/api-fetch';
import { useDispatch } from '@wordpress/data';
import { store as noticesStore } from '@wordpress/notices';

/**
 * CacheConfigButtons component for regenerating or removing cache delivery rules.
 *
 * This component renders buttons that allow the user to regenerate or remove
 * caching rules for a specific method (e.g., htaccess, nginx). It also displays
 * messages based on whether the rules are found or missing.
 *
 * @param {Object} props - The props for the component.
 * @param {string} props.method - The cache delivery method (e.g., 'htaccess', 'nginx').
 * @param {Object} props.cacheDelivery - The cache delivery object.
 * @param {function} props.setCacheDeliveryState - Function to update the cache delivery state.
 * @param {string} props.regenerateLabel - Label for the regenerate button.
 * @param {string} props.removeLabel - Label for the remove button.
 * @param {string} props.rulesFoundMessage - Message displayed when rules are found.
 * @param {string} props.rulesMissingMessage - Message displayed when rules are missing.
 *
 * @returns {JSX.Element} The rendered CacheConfigButtons component.
 */
const CacheConfigButtons = ( {
	method,
	cacheDelivery,
	setCacheDeliveryState,
	regenerateLabel,
	removeLabel,
	rulesFoundMessage,
	rulesMissingMessage,
} ) => {
	const hasRules = cacheDelivery[method].hasRules;
	const { createErrorNotice, createSuccessNotice } = useDispatch(noticesStore);

	const handleCacheConfigRegenerate = async (method = METHOD_HTACCESS) => {
		await apiFetch({
			path: `/solid-performance/v1/page/${method}`,
			method: 'POST',
		}).then(( result ) => {
			createSuccessNotice(result.message, {
				type: 'snackbar',
			});

			setCacheDeliveryState({
				[method]: {
					hasRules: true,
				}
			});
		}).catch(( error ) => {
			createErrorNotice(error.message, {
				type: 'snackbar',
			});
			console.error(error);
		});
	};

	const handleCacheConfigRemove = async (method = METHOD_HTACCESS) => {
		await apiFetch({
			path: `/solid-performance/v1/page/${method}`,
			method: 'DELETE',
		}).then(( result ) => {
			createSuccessNotice(result.message, {
				type: 'snackbar',
			});

			setCacheDeliveryState({
				[method]: {
					hasRules: false,
				}
			});
		}).catch(( error ) => {
			createErrorNotice(error.message, {
				type: 'snackbar',
			});
			console.error(error);
		});
	};

	return (
		<>
			<BaseControl>
				<Button variant="secondary" onClick={() => handleCacheConfigRegenerate(method)}>
					{regenerateLabel}
				</Button>

				<Button variant="tertiary" onClick={() => handleCacheConfigRemove(method)}>
					{removeLabel}
				</Button>
			</BaseControl>
			<MessageList
				type={hasRules ? 'success' : 'warning'}
				hasBorder={true}
				messages={[
					hasRules ? rulesFoundMessage : rulesMissingMessage,
				]}
			/>
		</>
	);
};

export default CacheConfigButtons;
