<?php
/**
 * Sekeco theme functions and definitions.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme setup.
 *
 * @return void
 */
function sekeco_setup() {
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'quote', 'video' ) );
	add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'sekeco_setup' );

/**
 * Enqueue the theme stylesheet on the frontend.
 *
 * @return void
 */
function sekeco_enqueue_styles() {
	wp_enqueue_style(
		'sekeco-style',
		get_parent_theme_file_uri( 'style.css' ),
		array(),
		wp_get_theme()->get( 'Version' )
	);
	wp_style_add_data( 'sekeco-style', 'path', get_parent_theme_file_path( 'style.css' ) );
}
add_action( 'wp_enqueue_scripts', 'sekeco_enqueue_styles' );

/**
 * Preload the variable fonts declared in theme.json.
 *
 * @return void
 */
function sekeco_preload_fonts() {
	$fonts = array(
		'assets/fonts/geist-latin-wght-normal.woff2',
		'assets/fonts/bricolage-grotesque-latin-wght-normal.woff2',
	);

	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_parent_theme_file_uri( $font ) )
		);
	}
}
add_action( 'wp_head', 'sekeco_preload_fonts', 1 );

/**
 * Check whether a post type has at least one published entry.
 *
 * @param string $post_type Post type slug.
 * @return bool
 */
function sekeco_post_type_has_content( $post_type ) {
	static $cache = array();

	if ( isset( $cache[ $post_type ] ) ) {
		return $cache[ $post_type ];
	}

	$counts = wp_count_posts( $post_type );

	$cache[ $post_type ] = isset( $counts->publish ) && (int) $counts->publish > 0;

	return $cache[ $post_type ];
}

/**
 * Hide homepage sections that depend on content which does not exist yet.
 *
 * Keeps the front page clean on a fresh install while still filling in
 * automatically once projects or products are published.
 *
 * @param string $block_content Rendered pattern markup.
 * @param array  $block         Parsed block.
 * @return string
 */
function sekeco_filter_empty_home_sections( $block_content, $block ) {
	$slug = isset( $block['attrs']['slug'] ) ? (string) $block['attrs']['slug'] : '';

	$requirements = array(
		'sekeco/home-work'     => 'portfolio',
		'sekeco/home-products' => 'product',
	);

	if ( ! isset( $requirements[ $slug ] ) ) {
		return $block_content;
	}

	return sekeco_post_type_has_content( $requirements[ $slug ] )
		? $block_content
		: '';
}
add_filter( 'render_block_core/pattern', 'sekeco_filter_empty_home_sections', 10, 2 );

/**
 * Hide buttons whose bound URL is empty.
 *
 * Project, product, and team templates bind a button URL to post meta. When the
 * meta is missing the button would render with an empty href, so hide it
 * instead of shipping a dead link.
 *
 * @param string $block_content Rendered button markup.
 * @param array  $block         Parsed block.
 * @return string
 */
function sekeco_hide_empty_bound_buttons( $block_content, $block ) {
	$bindings = isset( $block['attrs']['metadata']['bindings'] )
		? $block['attrs']['metadata']['bindings']
		: array();

	if ( ! isset( $bindings['url'] ) ) {
		return $block_content;
	}

	return false === strpos( $block_content, 'href=""' ) ? $block_content : '';
}
add_filter( 'render_block_core/button', 'sekeco_hide_empty_bound_buttons', 10, 2 );

/**
 * Register the theme pattern category and custom block styles.
 *
 * @return void
 */
function sekeco_register_theme_extras() {
	register_block_pattern_category(
		'sekeco',
		array(
			'label'       => __( 'Sekeco', 'sekeco' ),
			'description' => __( 'Patterns designed for the Sekeco theme.', 'sekeco' ),
		)
	);

	register_block_style(
		'core/group',
		array(
			'name'  => 'sekeco-card',
			'label' => __( 'Sekeco card', 'sekeco' ),
		)
	);

	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'sekeco-eyebrow',
			'label' => __( 'Sekeco eyebrow', 'sekeco' ),
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'  => 'sekeco-pill',
			'label' => __( 'Pill', 'sekeco' ),
		)
	);

	register_block_style(
		'core/button',
		array(
			'name'  => 'sekeco-ghost',
			'label' => __( 'Ghost', 'sekeco' ),
		)
	);
}
add_action( 'init', 'sekeco_register_theme_extras' );
