/**
 * Matholek build config.
 *
 * Entry points: every block under src/blocks (from block.json), plus `index`,
 * the editor script for the `matholek/since` binding.
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' );

module.exports = {
	...defaultConfig,
	entry: {
		...getWebpackEntryPoints( 'script' )(),
		index: './src/index.js',
	},
};
