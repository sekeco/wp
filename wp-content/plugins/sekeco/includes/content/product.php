<?php
/**
 * Product content model.
 *
 * Products are Sekeco's own internal builds: platforms, tools, and experiments
 * shipped by the studio. They share the core Category taxonomy with Portfolio.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Product post type.
 *
 * @return void
 */
function sekeco_register_product() {
	register_post_type(
		'product',
		array(
			'labels'             => array(
				'name'               => _x( 'Portfolio', 'Post type general name', 'sekeco' ),
				'singular_name'      => _x( 'Portfolio item', 'Post type singular name', 'sekeco' ),
				'menu_name'          => _x( 'Portfolio', 'Admin menu text', 'sekeco' ),
				'name_admin_bar'     => _x( 'Portfolio item', 'Add new on toolbar', 'sekeco' ),
				'add_new'            => __( 'Add New', 'sekeco' ),
				'add_new_item'       => __( 'Add New Portfolio Item', 'sekeco' ),
				'new_item'           => __( 'New Portfolio Item', 'sekeco' ),
				'edit_item'          => __( 'Edit Portfolio Item', 'sekeco' ),
				'view_item'          => __( 'View Portfolio Item', 'sekeco' ),
				'all_items'          => __( 'All Portfolio', 'sekeco' ),
				'search_items'       => __( 'Search Portfolio', 'sekeco' ),
				'not_found'          => __( 'No portfolio items found.', 'sekeco' ),
				'not_found_in_trash' => __( 'No portfolio items found in Trash.', 'sekeco' ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_admin_bar'  => true,
			'show_in_rest'       => true,
			'menu_position'      => 21,
			'menu_icon'          => 'none',
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
			'taxonomies'         => array( 'category' ),
			'has_archive'        => true,
			'rewrite'            => array(
				'slug'       => 'portfolio',
				'with_front' => false,
			),
			'query_var'          => true,
			'template'           => array(
				array(
					'core/paragraph',
					array(
						'placeholder' => __( 'Describe this portfolio item…', 'sekeco' ),
					),
				),
			),
		)
	);
}
add_action( 'init', 'sekeco_register_product' );
