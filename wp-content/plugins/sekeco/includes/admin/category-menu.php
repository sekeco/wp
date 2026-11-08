<?php
/**
 * Admin menu adjustments for the shared Category taxonomy.
 *
 * Core adds a "Categories" submenu under every post type that uses the
 * Category taxonomy. One shared menu under Posts is enough.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Remove the duplicate Category submenus.
 *
 * @return void
 */
function sekeco_remove_shared_category_submenus() {
	foreach ( array( 'portfolio', 'product' ) as $post_type ) {
		remove_submenu_page(
			'edit.php?post_type=' . $post_type,
			'edit-tags.php?taxonomy=category&amp;post_type=' . $post_type
		);
	}
}
add_action( 'admin_menu', 'sekeco_remove_shared_category_submenus', 999 );
