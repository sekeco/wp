<?php
/**
 * Matholek blocks, registered from their build metadata.
 *
 * @package Matholek
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register every block built to build/blocks/<name>, and the editor script.
 *
 * @return void
 */
function matholek_register_blocks() {
	foreach ( glob( MATHOLEK_PATH . 'build/blocks/*/block.json' ) as $file ) {
		register_block_type( dirname( $file ) );
	}
}
add_action( 'init', 'matholek_register_blocks' );

/**
 * Editor-only script: binding sources shown in the editor.
 *
 * @return void
 */
function matholek_enqueue_editor() {
	$asset = MATHOLEK_PATH . 'build/index.asset.php';

	if ( ! file_exists( $asset ) ) {
		return;
	}

	$asset = require $asset;

	wp_enqueue_script( 'matholek-editor', MATHOLEK_URL . 'build/index.js', $asset['dependencies'], $asset['version'], true );
}
add_action( 'enqueue_block_editor_assets', 'matholek_enqueue_editor' );

/**
 * Version a block's assets by build time, so a rebuilt file is never hidden
 * by a cached copy carrying the fixed block.json version.
 *
 * @param array $metadata Block metadata.
 * @return array
 */
function matholek_block_asset_version( $metadata ) {
	if ( isset( $metadata['name'], $metadata['file'] ) && 0 === strpos( $metadata['name'], 'matholek/' ) ) {
		$built = (int) @filemtime( $metadata['file'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

		if ( $built ) {
			$metadata['version'] = MATHOLEK_VERSION . '.' . $built;
		}
	}

	return $metadata;
}
add_filter( 'block_type_metadata', 'matholek_block_asset_version' );
