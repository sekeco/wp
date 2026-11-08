/**
 * Sekeco build config.
 *
 * Entry points:
 * - index: editor extension (Motion controls for core/group and core/cover)
 * - motion-view: lightweight frontend reveal script + styles
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' );

const blockEntries = getWebpackEntryPoints( 'script' )();

module.exports = {
	...defaultConfig,
	entry: {
		...blockEntries,
		index: './src/index.js',
		'motion-view': './src/motion/view.js',
	},
};
