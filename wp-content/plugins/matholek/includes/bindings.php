<?php
/**
 * Block binding `matholek/since`: years elapsed since a given year.
 *
 * Bind a paragraph or heading's content to it so an age such as "114" stays
 * correct every year without an edit. Args: `year` (required), `suffix`.
 *
 * @package Matholek
 */

defined( 'ABSPATH' ) || exit;

/**
 * Years since `year`, plus an optional suffix.
 *
 * @param array $args Binding args.
 * @return string|null
 */
function matholek_since_value( $args ) {
	$year = isset( $args['year'] ) ? (int) $args['year'] : 0;

	if ( $year < 1 ) {
		return null;
	}

	$suffix = isset( $args['suffix'] ) ? (string) $args['suffix'] : '';

	return esc_html( ( (int) wp_date( 'Y' ) - $year ) . $suffix );
}

/**
 * Register the binding source.
 *
 * @return void
 */
function matholek_register_bindings() {
	if ( ! function_exists( 'register_block_bindings_source' ) ) {
		return;
	}

	register_block_bindings_source(
		'matholek/since',
		array(
			'label'              => __( 'Tahun sejak', 'matholek' ),
			'get_value_callback' => 'matholek_since_value',
		)
	);
}
add_action( 'init', 'matholek_register_bindings' );
