<?php
/**
 * Slider block registration.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Slider block from its build metadata.
 *
 * @return void
 */
function sekeco_register_slider_block() {
	$block_dir = SEKECO_PATH . 'build/blocks/slider';

	if ( file_exists( $block_dir . '/block.json' ) ) {
		register_block_type( $block_dir );
	}
}
add_action( 'init', 'sekeco_register_slider_block' );
