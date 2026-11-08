<?php
/**
 * Motion extension for core/group and core/cover.
 *
 * The extension does not change saved markup. It adds classes, data attributes,
 * and CSS custom properties at render time, so disabling the plugin leaves the
 * original core block content intact.
 *
 * @package Sekeco
 */

defined( 'ABSPATH' ) || exit;

/**
 * Blocks that support motion.
 *
 * @return string[]
 */
function sekeco_motion_supported_blocks() {
	return array( 'core/group', 'core/cover' );
}

/**
 * Allowed motion slugs.
 *
 * @return string[]
 */
function sekeco_motion_allowed() {
	return array( 'fade', 'fade-up', 'fade-down', 'slide-left', 'slide-right', 'zoom' );
}

/**
 * Enqueue frontend motion assets.
 *
 * @return void
 */
function sekeco_motion_enqueue_frontend_assets() {
	$asset_file = SEKECO_PATH . 'build/motion-view.asset.php';

	if ( ! file_exists( $asset_file ) ) {
		return;
	}

	$asset = require $asset_file;

	wp_enqueue_script(
		'sekeco-motion-view',
		SEKECO_URL . 'build/motion-view.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);

	$style_file = SEKECO_PATH . 'build/style-motion-view.css';

	if ( file_exists( $style_file ) ) {
		wp_enqueue_style(
			'sekeco-motion-view',
			SEKECO_URL . 'build/style-motion-view.css',
			array(),
			$asset['version']
		);
	}
}
add_action( 'wp_enqueue_scripts', 'sekeco_motion_enqueue_frontend_assets' );

/**
 * Enqueue editor motion assets.
 *
 * @return void
 */
function sekeco_motion_enqueue_editor_assets() {
	$asset_file = SEKECO_PATH . 'build/index.asset.php';

	if ( ! file_exists( $asset_file ) ) {
		return;
	}

	$asset = require $asset_file;

	wp_enqueue_script(
		'sekeco-motion-editor',
		SEKECO_URL . 'build/index.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);

	wp_set_script_translations(
		'sekeco-motion-editor',
		'sekeco',
		SEKECO_PATH . 'languages'
	);
}
add_action( 'enqueue_block_editor_assets', 'sekeco_motion_enqueue_editor_assets' );

/**
 * Add motion classes, data attributes, and CSS variables at render time.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block.
 * @return string
 */
function sekeco_motion_render_block( $block_content, $block ) {
	if (
		empty( $block['blockName'] ) ||
		! in_array( $block['blockName'], sekeco_motion_supported_blocks(), true )
	) {
		return $block_content;
	}

	$motion = isset( $block['attrs']['sekecoMotion'] )
		? sanitize_key( $block['attrs']['sekecoMotion'] )
		: '';

	if ( ! in_array( $motion, sekeco_motion_allowed(), true ) ) {
		return $block_content;
	}

	$duration = isset( $block['attrs']['sekecoDuration'] )
		? (int) $block['attrs']['sekecoDuration']
		: 700;
	$delay    = isset( $block['attrs']['sekecoDelay'] )
		? (int) $block['attrs']['sekecoDelay']
		: 0;
	$trigger  = isset( $block['attrs']['sekecoTrigger'] )
		? $block['attrs']['sekecoTrigger']
		: 'scroll';

	$duration = max( 100, min( 2000, $duration ) );
	$delay    = max( 0, min( 2000, $delay ) );
	$trigger  = in_array( $trigger, array( 'scroll', 'load' ), true )
		? $trigger
		: 'scroll';

	$processor = new WP_HTML_Tag_Processor( $block_content );

	if ( ! $processor->next_tag() ) {
		return $block_content;
	}

	$processor->add_class( 'sekeco-motion' );
	$processor->add_class( 'sekeco-motion--' . $motion );
	$processor->add_class( 'sekeco-motion--trigger-' . $trigger );
	$processor->set_attribute( 'data-sekeco-motion', $motion );
	$processor->set_attribute( 'data-sekeco-trigger', $trigger );
	$processor->set_attribute( 'data-sekeco-duration', (string) $duration );
	$processor->set_attribute( 'data-sekeco-delay', (string) $delay );

	$existing_style = $processor->get_attribute( 'style' );
	$motion_style   = '--sekeco-motion-duration:' . $duration . 'ms;'
		. '--sekeco-motion-delay:' . $delay . 'ms;';

	$processor->set_attribute(
		'style',
		$existing_style
			? rtrim( $existing_style, ';' ) . ';' . $motion_style
			: $motion_style
	);

	return $processor->get_updated_html();
}
add_filter( 'render_block', 'sekeco_motion_render_block', 10, 2 );
