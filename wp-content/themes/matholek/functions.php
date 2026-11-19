<?php
/**
 * Matholek theme functions.
 *
 * @package Matholek
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme setup: same stylesheets inside the editor.
 *
 * @return void
 */
function matholek_setup() {
	add_theme_support( 'responsive-embeds' );
	add_editor_style( array( 'style.css', 'assets/css/icons.css' ) );
}
add_action( 'after_setup_theme', 'matholek_setup' );

/**
 * Enqueue the theme stylesheets, versioned by modified time.
 *
 * @return void
 */
function matholek_enqueue_styles() {
	$ver = static function ( $file ) {
		$path = get_parent_theme_file_path( $file );
		return wp_get_theme()->get( 'Version' ) . '.' . ( file_exists( $path ) ? filemtime( $path ) : 0 );
	};

	wp_enqueue_style( 'matholek-style', get_parent_theme_file_uri( 'style.css' ), array(), $ver( 'style.css' ) );
	wp_style_add_data( 'matholek-style', 'path', get_parent_theme_file_path( 'style.css' ) );
	wp_enqueue_style( 'matholek-icons', get_parent_theme_file_uri( 'assets/css/icons.css' ), array( 'matholek-style' ), $ver( 'assets/css/icons.css' ) );
}
add_action( 'wp_enqueue_scripts', 'matholek_enqueue_styles' );

/**
 * Set the colour mode before first paint. First visit is light; a stored
 * choice from the theme toggle wins.
 *
 * @return void
 */
function matholek_print_mode_script() {
	?>
	<script>
	(function(){var d=document.documentElement;try{var t=localStorage.getItem('matholek-theme');if(t!=='light'&&t!=='dark'){t='light';}d.setAttribute('data-theme',t);d.style.colorScheme=t;}catch(e){d.setAttribute('data-theme','light');}})();
	</script>
	<?php
}
add_action( 'wp_head', 'matholek_print_mode_script', 0 );

/**
 * Preload the text font; the Arabic face loads on demand (unicode-range).
 *
 * @return void
 */
function matholek_preload_fonts() {
	printf(
		'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
		esc_url( get_parent_theme_file_uri( 'assets/fonts/manrope-latin-wght-normal.woff2' ) )
	);
}
add_action( 'wp_head', 'matholek_preload_fonts', 1 );

/**
 * Pattern category and button styles.
 *
 * @return void
 */
function matholek_register_theme_extras() {
	register_block_pattern_category(
		'matholek',
		array(
			'label'       => __( 'Matholek', 'matholek' ),
			'description' => __( 'Section landing page Perguruan Islam Mathali\'ul Falah.', 'matholek' ),
		)
	);

	$styles = array(
		'gold'  => __( 'Emas', 'matholek' ),
		'glass' => __( 'Kaca', 'matholek' ),
		'card'  => __( 'Kartu', 'matholek' ),
		'small' => __( 'Kecil', 'matholek' ),
	);

	foreach ( $styles as $name => $label ) {
		register_block_style(
			'core/button',
			array(
				'name'  => $name,
				'label' => $label,
			)
		);
	}
}
add_action( 'init', 'matholek_register_theme_extras' );

/**
 * Hide blocks that are still waiting for real content.
 *
 * - `mk-hide-empty`: hidden when an inner `mk-key` element is empty, or when
 *   it holds no text and no media at all (an empty partner tile, a query
 *   without posts).
 * - `mk-needs-content`: hidden when none of its `mk-hide-empty` children
 *   survived (a partner band without partners, news without posts).
 *
 * Inner blocks render first, so the parent sees its children's result.
 *
 * @param string $html  Rendered block.
 * @param array  $block Parsed block.
 * @return string
 */
function matholek_hide_empty_blocks( $html, $block ) {
	$class = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';

	if ( '' === $class || false === strpos( $class, 'mk-' ) ) {
		return $html;
	}

	if ( preg_match( '/(^|\s)mk-hide-empty(\s|$)/', $class ) ) {
		if ( preg_match( '#<(\w+)\b[^>]*\bclass="[^"]*\bmk-key\b[^"]*"[^>]*>\s*</\1>#', $html ) ) {
			return '';
		}

		if ( '' === trim( html_entity_decode( wp_strip_all_tags( $html ) ), " \t\n\r\0\x0B\xC2\xA0" ) && ! preg_match( '/<(img|svg|iframe|video)\b/i', $html ) ) {
			return '';
		}
	}

	if ( preg_match( '/(^|\s)mk-needs-content(\s|$)/', $class ) && false === strpos( $html, 'mk-hide-empty' ) ) {
		return '';
	}

	return $html;
}
add_filter( 'render_block', 'matholek_hide_empty_blocks', 10, 2 );

/**
 * URL of the news index: the Posts page when set, else the front page.
 *
 * @return string
 */
function matholek_news_url() {
	$page = (int) get_option( 'page_for_posts' );

	return $page ? (string) get_permalink( $page ) : home_url( '/' );
}

/*
 * Keep straight quotes and apostrophes as typed (Mathali'ul), as in the
 * reference design.
 */
add_filter( 'run_wptexturize', '__return_false' );
