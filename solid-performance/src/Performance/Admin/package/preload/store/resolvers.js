/**
 * Internal dependencies
 */
import { refreshPreloadStatus } from './actions';

/**
 * Resolver function to fetch and update the preload status.
 * Logs the action and dispatches the `refreshPreloadStatus` action.
 *
 * @returns {Function} A thunk action that dispatches the `refreshPreloadStatus` action.
 */
export const getPreloadStatus = () => async ( { dispatch } ) => {
	await dispatch(refreshPreloadStatus());
};
