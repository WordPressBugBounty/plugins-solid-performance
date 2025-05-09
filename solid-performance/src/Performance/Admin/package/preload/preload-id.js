/**
 * Store the unique preload ID in order to prevent notifications from
 * displaying over and over when the REST endpoint is polled.
 */
const preloadIdKey = 'swpsp-preload-id';

export const PreloadId = {
	/**
	 * Retrieve the preload ID from sessionStorage.
	 *
	 * @returns {string|null} The stored preload ID, or `null` if it doesn't exist.
	 */
	get: () => sessionStorage.getItem(preloadIdKey),

	/**
	 * Set the preload ID in sessionStorage.
	 *
	 * @param {string} id The preload ID to store.
	 */
	set: (id) => sessionStorage.setItem(preloadIdKey, id),

	/**
	 * Check if the preload ID exists in sessionStorage.
	 *
	 * @returns {boolean} `true` if the preload ID exists, otherwise `false`.
	 */
	has: () => sessionStorage.getItem(preloadIdKey) !== null,

	/**
	 * Clear the preload ID.
	 */
	clear: () => sessionStorage.removeItem(preloadIdKey),
};
