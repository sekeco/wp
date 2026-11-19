<?php
/**
 * Plugin Name:       Matholek
 * Description:       Blok pendamping, SEO, dan format gambar untuk tema Matholek (Perguruan Islam Mathali'ul Falah).
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Sekeco
 * Author URI:        https://sekeco.github.io
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       matholek
 *
 * @package Matholek
 */

defined( 'ABSPATH' ) || exit;

define( 'MATHOLEK_VERSION', '0.1.0' );
define( 'MATHOLEK_PATH', plugin_dir_path( __FILE__ ) );
define( 'MATHOLEK_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load feature modules. Missing files are skipped.
 *
 * @return void
 */
function matholek_load_modules() {
	$modules = apply_filters(
		'matholek_modules',
		array(
			'media.php',
			'seo/settings.php',
			'seo/meta.php',
			'seo/schema.php',
			'bindings.php',
			'blocks.php',
		)
	);

	foreach ( $modules as $module ) {
		$path = MATHOLEK_PATH . 'includes/' . ltrim( $module, '/' );

		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
}
add_action( 'plugins_loaded', 'matholek_load_modules' );

/**
 * Register the Matholek block category.
 *
 * @param array $categories Existing block categories.
 * @return array
 */
function matholek_register_block_category( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'matholek',
				'title' => __( 'Matholek', 'matholek' ),
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'matholek_register_block_category' );
