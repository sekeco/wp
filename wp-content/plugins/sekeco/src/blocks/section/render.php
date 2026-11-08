<?php
/**
 * Server render for the Sekeco Section block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$tone    = isset( $attributes['tone'] ) ? $attributes['tone'] : 'auto';
$tone    = in_array( $tone, array( 'auto', 'ink', 'paper', 'surface', 'accent' ), true ) ? $tone : 'auto';
$spacing = isset( $attributes['spacing'] ) ? $attributes['spacing'] : 'md';
$spacing = in_array( $spacing, array( 'none', 'sm', 'md', 'lg' ), true ) ? $spacing : 'md';
$width   = isset( $attributes['width'] ) ? $attributes['width'] : 'default';
$width   = in_array( $width, array( 'narrow', 'default', 'full' ), true ) ? $width : 'default';

$requires = isset( $attributes['requires'] ) ? sanitize_key( $attributes['requires'] ) : '';

// A section that depends on content disappears while that content does not exist.
if ( '' !== $requires ) {
	$counts = wp_count_posts( $requires );

	if ( empty( $counts->publish ) ) {
		return;
	}
}

$classes = 'sk-section sk-section--space-' . $spacing . ( 'auto' === $tone ? '' : ' sk-tone--' . $tone );

if ( ! empty( $attributes['stacked'] ) ) {
	$classes .= ' sk-section--stacked';
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $classes ) );

printf(
	'<section %1$s><div class="sk-section__inner sk-section__inner--%2$s">%3$s</div></section>',
	$wrapper_attributes, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	esc_attr( $width ),
	$content // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
);
