<?php
/**
 * Redirects for URLs that changed after the Works/Portfolio rename.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Send old product and work paths to their current permalinks.
 *
 * - /products/<slug>/  → /portfolio/<slug>/
 * - /portfolio/<slug>/ → /works/<slug>/ when no product matches
 *
 * @return void
 */
function sekeco_legacy_path_redirects() {
	if ( ! is_404() ) {
		return;
	}

	$path = isset( $_SERVER['REQUEST_URI'] )
		? trim( (string) wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ), '/' )
		: '';

	if ( '' === $path ) {
		return;
	}

	if ( 'products' === $path ) {
		wp_safe_redirect( home_url( '/portfolio/' ), 301 );
		exit;
	}

	$routes = array(
		'#^products/(.+)$#'  => 'product',
		'#^portfolio/(.+)$#' => 'portfolio',
	);

	foreach ( $routes as $pattern => $post_type ) {
		if ( ! preg_match( $pattern, $path, $matches ) ) {
			continue;
		}

		$post = get_page_by_path( sanitize_title( $matches[1] ), OBJECT, $post_type );

		if ( $post ) {
			wp_safe_redirect( get_permalink( $post ), 301 );
			exit;
		}
	}

	// A removed page that matches a form slug goes to the form page.
	$form = get_page_by_path( $path, OBJECT, 'sekeco_form' );

	if ( $form ) {
		wp_safe_redirect( get_permalink( $form ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'sekeco_legacy_path_redirects' );
