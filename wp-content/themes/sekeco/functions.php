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
	add_theme_support( 'responsive-embeds' );

	// Same tokens and component parts inside the editor.
	add_editor_style( array( 'style.css', 'assets/css/buttons.css', 'assets/css/solar-icons.css' ) );
}
add_action( 'after_setup_theme', 'sekeco_setup' );

/**
 * Enqueue the theme stylesheets on the frontend.
 *
 * @return void
 */
function sekeco_enqueue_styles() {
	// Version each file by its modified time so an edit is never hidden by a cached copy.
	$ver = static function ( $file ) {
		$path = get_parent_theme_file_path( $file );
		return wp_get_theme()->get( 'Version' ) . '.' . ( file_exists( $path ) ? filemtime( $path ) : 0 );
	};

	wp_enqueue_style( 'sekeco-style', get_parent_theme_file_uri( 'style.css' ), array(), $ver( 'style.css' ) );
	wp_style_add_data( 'sekeco-style', 'path', get_parent_theme_file_path( 'style.css' ) );

	wp_enqueue_style( 'sekeco-buttons', get_parent_theme_file_uri( 'assets/css/buttons.css' ), array( 'sekeco-style' ), $ver( 'assets/css/buttons.css' ) );

	wp_enqueue_style( 'sekeco-icons', get_parent_theme_file_uri( 'assets/css/solar-icons.css' ), array( 'sekeco-style' ), $ver( 'assets/css/solar-icons.css' ) );
}
add_action( 'wp_enqueue_scripts', 'sekeco_enqueue_styles' );

/**
 * Set the colour mode and the motion flag before first paint.
 *
 * The stored choice wins; first-time visitors get the dark mode, as in the reference.
 * The theme toggle block keeps data-theme and localStorage in sync.
 *
 * @return void
 */
function sekeco_print_mode_script() {
	?>
	<script>
	(function(){var d=document.documentElement;d.classList.add('sekeco-js');try{var t=localStorage.getItem('sekeco-theme');if(t!=='light'&&t!=='dark'){t='dark';}d.setAttribute('data-theme',t);d.style.colorScheme=t;}catch(e){}})();
	</script>
	<?php
}
add_action( 'wp_head', 'sekeco_print_mode_script', 0 );

/**
 * Use the brand mark as the favicon until a site icon is set.
 *
 * @return void
 */
function sekeco_brand_favicon() {
	if ( has_site_icon() ) {
		return;
	}

	printf(
		'<link rel="icon" href="%s" type="image/svg+xml">' . "\n",
		esc_url( get_parent_theme_file_uri( 'assets/images/sekeco-mark.svg' ) )
	);
}
add_action( 'wp_head', 'sekeco_brand_favicon', 2 );

/**
 * Preload the variable fonts declared in theme.json.
 *
 * @return void
 */
function sekeco_preload_fonts() {
	$fonts = array(
		'assets/fonts/dm-sans-latin-wght-normal.woff2',
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
 * Register the pattern category and block styles.
 *
 * @return void
 */
function sekeco_register_theme_extras() {
	register_block_pattern_category(
		'sekeco',
		array(
			'label'       => __( 'Sekeco', 'sekeco' ),
			'description' => __( 'Sections designed for the Sekeco theme.', 'sekeco' ),
		)
	);

	// Button styles: Fill and Outline come from core; these add six gradient looks.
	// Sizes are a separate Size control (a class), added by the Sekeco plugin.
	$button_styles = array(
		'gradient'         => __( 'Gradient', 'sekeco' ),
		'gradient-soft'    => __( 'Gradient soft', 'sekeco' ),
		'gradient-outline' => __( 'Gradient outline', 'sekeco' ),
		'gradient-aurora'  => __( 'Gradient aurora', 'sekeco' ),
		'gradient-arrow'   => __( 'Gradient arrow', 'sekeco' ),
		'gradient-sheen'   => __( 'Gradient sheen', 'sekeco' ),
	);

	foreach ( $button_styles as $name => $label ) {
		register_block_style(
			'core/button',
			array(
				'name'  => $name,
				'label' => $label,
			)
		);
	}

	register_block_style(
		'core/group',
		array(
			'name'  => 'sekeco-surface',
			'label' => __( 'Surface', 'sekeco' ),
		)
	);
}
add_action( 'init', 'sekeco_register_theme_extras' );
