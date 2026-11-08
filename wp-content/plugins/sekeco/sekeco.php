<?php
/**
 * Plugin Name:       Sekeco
 * Plugin URI:        https://sekeco.github.io
 * Description:       Native content models, motion enhancements, and gap blocks for the Sekeco theme.
 * Version:           0.16.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Sekeco
 * Author URI:        https://sekeco.github.io
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       sekeco
 * Domain Path:       /languages
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

define( 'SEKECO_VERSION', '0.16.0' );
define( 'SEKECO_FILE', __FILE__ );
define( 'SEKECO_PATH', plugin_dir_path( __FILE__ ) );
define( 'SEKECO_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load feature modules.
 *
 * Modules are added in later phases:
 * - content/portfolio.php
 * - content/product.php
 * - content/team.php
 * - meta/*
 * - bindings/*
 * - motion/motion.php
 *
 * Each module owns its hooks. Missing files are ignored so the plugin stays
 * usable while phases are implemented incrementally.
 *
 * @return void
 */
function sekeco_load_modules() {
	$modules = apply_filters(
		'sekeco_modules',
		array(
			// Content models.
			'content/portfolio.php',
			'content/product.php',
			'content/team.php',
			// Meta and block bindings.
			'meta/auth.php',
			'meta/portfolio-meta.php',
			'meta/product-meta.php',
			'meta/team-meta.php',
			'bindings/register-bindings.php',
			// Shared helpers.
			'icons.php',
			// Forms and surveys.
			'forms/forms.php',
			'forms/render.php',
			'forms/submit.php',
			// Enhancements and gap blocks.
			'motion/motion.php',
			'blocks/form.php',
			'blocks/components.php',
			// Admin and redirects.
			'admin/category-menu.php',
			'redirects.php',
		)
	);

	foreach ( $modules as $module ) {
		$path = SEKECO_PATH . 'includes/' . ltrim( $module, '/' );

		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
}
add_action( 'plugins_loaded', 'sekeco_load_modules' );

/**
 * Flush rewrite rules once per plugin version.
 *
 * CPTs and taxonomies are registered on init, so flushing on activation alone
 * does not always capture their rules. A stored version avoids flushing on
 * every request.
 *
 * @return void
 */
function sekeco_maybe_flush_rewrite_rules() {
	if ( get_option( 'sekeco_rewrite_version' ) !== SEKECO_VERSION ) {
		flush_rewrite_rules();
		update_option( 'sekeco_rewrite_version', SEKECO_VERSION );
	}
}
add_action( 'init', 'sekeco_maybe_flush_rewrite_rules', 99 );

/**
 * Register the Sekeco block categories: layout, content and utility.
 *
 * @param array $categories Existing block categories.
 * @return array
 */
function sekeco_register_block_category( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'sekeco-layout',
				'title' => __( 'Sekeco: Layout', 'sekeco' ),
			),
			array(
				'slug'  => 'sekeco-content',
				'title' => __( 'Sekeco: Content', 'sekeco' ),
			),
			array(
				'slug'  => 'sekeco-utility',
				'title' => __( 'Sekeco: Utility', 'sekeco' ),
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'sekeco_register_block_category' );
