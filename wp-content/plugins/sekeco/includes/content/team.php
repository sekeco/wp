<?php
/**
 * Team content model.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Team post type.
 *
 * @return void
 */
function sekeco_register_team() {
	register_post_type(
		'team',
		array(
			'labels'             => array(
				'name'               => _x( 'Team', 'Post type general name', 'sekeco' ),
				'singular_name'      => _x( 'Team member', 'Post type singular name', 'sekeco' ),
				'menu_name'          => _x( 'Team', 'Admin menu text', 'sekeco' ),
				'name_admin_bar'     => _x( 'Team member', 'Add new on toolbar', 'sekeco' ),
				'add_new'            => __( 'Add New', 'sekeco' ),
				'add_new_item'       => __( 'Add New Team Member', 'sekeco' ),
				'new_item'           => __( 'New Team Member', 'sekeco' ),
				'edit_item'          => __( 'Edit Team Member', 'sekeco' ),
				'view_item'          => __( 'View Team Member', 'sekeco' ),
				'all_items'          => __( 'All Team Members', 'sekeco' ),
				'search_items'       => __( 'Search Team', 'sekeco' ),
				'not_found'          => __( 'No team members found.', 'sekeco' ),
				'not_found_in_trash' => __( 'No team members found in Trash.', 'sekeco' ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_admin_bar'     => true,
			'show_in_admin_bar'  => true,
			'show_in_rest'       => true,
			'menu_position'      => 22,
			'menu_icon'          => 'dashicons-groups',
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions', 'page-attributes' ),
			'has_archive'        => true,
			'rewrite'            => array(
				'slug'       => 'team',
				'with_front' => false,
			),
			'query_var'          => true,
			'template'           => array(
				array(
					'core/paragraph',
					array(
						'placeholder' => __( 'Short bio…', 'sekeco' ),
					),
				),
			),
		)
	);
}
add_action( 'init', 'sekeco_register_team' );
