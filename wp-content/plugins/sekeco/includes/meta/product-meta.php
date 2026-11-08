<?php
/**
 * Product post meta.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Product meta fields.
 *
 * @return void
 */
function sekeco_register_product_meta() {
	$fields = array(
		'sekeco_product_url'    => array(
			'type'        => 'string',
			'description' => __( 'Product or repository URL', 'sekeco' ),
			'sanitize'    => 'esc_url_raw',
		),
		'sekeco_product_status' => array(
			'type'        => 'string',
			'description' => __( 'Status, e.g. Live or Open source', 'sekeco' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'sekeco_product_year'   => array(
			'type'        => 'string',
			'description' => __( 'Year or year range', 'sekeco' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'sekeco_product_stack'  => array(
			'type'        => 'string',
			'description' => __( 'Tech stack', 'sekeco' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'sekeco_product_repo'   => array(
			'type'        => 'string',
			'description' => __( 'Repository name', 'sekeco' ),
			'sanitize'    => 'sanitize_text_field',
		),
	);

	foreach ( $fields as $key => $field ) {
		register_post_meta(
			'product',
			$key,
			array(
				'type'              => $field['type'],
				'description'       => $field['description'],
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => $field['sanitize'],
				'auth_callback'     => 'sekeco_meta_auth_callback',
			)
		);
	}
}
add_action( 'init', 'sekeco_register_product_meta', 20 );
