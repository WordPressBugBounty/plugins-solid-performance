import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { Button, Flex, FlexItem, Modal, Spinner } from '@wordpress/components';
import { useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { copySmall, seen } from '@wordpress/icons';
import CopyableCodeSnippet from '../copyable-code-snippet';
import { copyToClipboard } from '../../utils/copy-to-clipboard';

/**
 * Loads the content of the swpsp-nginx.conf file in a modal.
 *
 * @returns {JSX.Element}
 */
const NginxConfFileViewer = () => {
	const [ isOpen, setOpen ] = useState(false);
	const [ content, setContent ] = useState('');
	const [ isLoading, setIsLoading ] = useState(false);

	const openModal = () => setOpen(true);
	const closeModal = () => setOpen(false);

	const headerActions = <Button
		__next40pxDefaultSize
		icon={copySmall}
		ref={copyToClipboard(content)}
		label={__('Copy to clipboard', 'solid-performance')}
	/>;

	useEffect(() => {
		if (isOpen) {
			setIsLoading(true);

			apiFetch({
				path: addQueryArgs('/solid-performance/v1/page/cache-delivery', {
					nginx_rules: true,
				}),
			}).then(( result ) => {
				setContent(result.cacheDelivery.nginx.rules);
			}).catch(( error ) => {
				console.log(error);
				setContent(`An error occurred: ${error.message}`);
			}).finally(() => {
				setIsLoading(false);
			});
		}
	}, [ isOpen ]);

	return (
		<>
			<Button
				__next40pxDefaultSize
				onClick={openModal}
				icon={seen}
				label={__('View Rules', 'solid-performance')}
				style={{
					position: 'absolute',
					top: '8px',
					right: '8px',
				}}
			/>
			{isOpen && (
				<Modal
					title={__('Viewing swpsp-nginx.conf', 'solid-performance')}
					onRequestClose={closeModal}
					headerActions={headerActions}
				>
					{isLoading ? (
						<Flex
							justify="center"
							align="center"
							style={{ padding: '2rem' }}
						>
							<FlexItem>
								<Spinner
									style={{
										height: 'calc(4px * 10)',
										width: 'calc(4px * 10)',
									}}
								/>
							</FlexItem>
						</Flex>
					) : (
						<CopyableCodeSnippet
							text={content}
							showCopy={false}
						/>
					)}
				</Modal>
			)}
		</>
	);
};

export default NginxConfFileViewer;
