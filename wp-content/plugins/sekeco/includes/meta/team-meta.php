<?php
/**
 * Team post meta.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Team meta fields.
 *
 * @return void
 */
function sekeco_register_team_meta() {
	$fields = array(
		'sekeco_team_role'      => array(
			'type'        => 'string',
			'description' => __( 'Role or position', 'sekeco' ),
			'sanitize'    => 'sanitize_text_field',
		),
		'sekeco_team_email'     => array(
			'type'        => 'string',
			'description' => __( 'Email address', 'sekeco' ),
			'sanitize'    => 'sanitize_email',
		),
		'sekeco_team_github'    => array(
			'type'        => 'string',
			'description' => __( 'GitHub profile URL', 'sekeco' ),
			'sanitize'    => 'esc_url_raw',
		),
		'sekeco_team_linkedin'  => array(
			'type'        => 'string',
			'description' => __( 'LinkedIn profile URL', 'sekeco' ),
			'sanitize'    => 'esc_url_raw',
		),
		'sekeco_team_instagram' => array(
			'type'        => 'string',
			'description' => __( 'Instagram profile URL', 'sekeco' ),
			'sanitize'    => 'esc_url_raw',
		),
	);

	foreach ( $fields as $key => $field ) {
		register_post_meta(
			'team',
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
add_action( 'init', 'sekeco_register_team_meta', 20 );
