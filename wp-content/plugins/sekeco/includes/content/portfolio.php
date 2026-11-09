<?php
/**
 * Portfolio content model.
 *
 * Portfolio shares the core Category taxonomy with Products and Posts, so one
 * set of categories covers the whole site.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Portfolio post type.
 *
 * @return void
 */
function sekeco_register_portfolio() {
	register_post_type(
		'portfolio',
		array(
			'labels'             => array(
				'name'               => _x( 'Works', 'Post type general name', 'sekeco' ),
				'singular_name'      => _x( 'Work', 'Post type singular name', 'sekeco' ),
				'menu_name'          => _x( 'Works', 'Admin menu text', 'sekeco' ),
				'name_admin_bar'     => _x( 'Work', 'Add new on toolbar', 'sekeco' ),
				'add_new'            => __( 'Add New', 'sekeco' ),
				'add_new_item'       => __( 'Add New Work', 'sekeco' ),
				'new_item'           => __( 'New Work', 'sekeco' ),
				'edit_item'          => __( 'Edit Work', 'sekeco' ),
				'view_item'          => __( 'View Work', 'sekeco' ),
				'all_items'          => __( 'All Works', 'sekeco' ),
				'search_items'       => __( 'Search Works', 'sekeco' ),
				'not_found'          => __( 'No works found.', 'sekeco' ),
				'not_found_in_trash' => __( 'No works found in Trash.', 'sekeco' ),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => true,
			'show_in_admin_bar'  => true,
			'show_in_rest'       => true,
			'menu_position'      => 20,
			'menu_icon'          => 'dashicons-portfolio',
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
			'taxonomies'         => array( 'category' ),
			'has_archive'        => true,
			'rewrite'            => array(
				'slug'       => 'works',
				'with_front' => false,
			),
			'query_var'          => true,
			'template'           => array(
				array(
					'core/heading',
					array(
						'level'       => 2,
						'placeholder' => __( 'Project overview', 'sekeco' ),
					),
				),
				array(
					'core/paragraph',
					array(
						'placeholder' => __( 'Describe the project, the challenge, and the outcome…', 'sekeco' ),
					),
				),
			),
		)
	);
}
add_action( 'init', 'sekeco_register_portfolio' );

/**
 * Include every post type that shares the Category taxonomy in category archives.
 *
 * WordPress only queries posts on taxonomy archives by default, which would
 * leave Portfolio and Product category archives empty.
 *
 * @param WP_Query $query Query object.
 * @return void
 */
function sekeco_category_archive_post_types( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_category() ) {
		return;
	}

	$query->set( 'post_type', array( 'post', 'portfolio', 'product' ) );
}
add_action( 'pre_get_posts', 'sekeco_category_archive_post_types' );
