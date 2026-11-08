<?php
/**
 * Portfolio post meta.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register portfolio meta fields.
 *
 * @return void
 */
function sekeco_register_portfolio_meta() {
	$fields = array(
		'sekeco_portfolio_client' => array(
			'type'              => 'string',
			'description'       => __( 'Client name', 'sekeco' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'sekeco_portfolio_year'   => array(
			'type'              => 'string',
			'description'       => __( 'Project year or range', 'sekeco' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'sekeco_portfolio_role'   => array(
			'type'              => 'string',
			'description'       => __( 'Role on the project', 'sekeco' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
		'sekeco_portfolio_url'    => array(
			'type'              => 'string',
			'description'       => __( 'Project URL', 'sekeco' ),
			'sanitize_callback' => 'esc_url_raw',
		),
	);

	foreach ( $fields as $key => $field ) {
		register_post_meta(
			'portfolio',
			$key,
			array(
				'type'              => $field['type'],
				'description'       => $field['description'],
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => $field['sanitize_callback'],
				'auth_callback'     => 'sekeco_meta_auth_callback',
			)
		);
	}
}
add_action( 'init', 'sekeco_register_portfolio_meta', 20 );
