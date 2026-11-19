<?php
/**
 * Play button over a photo card. A link when a URL is set, decoration otherwise.
 *
 * @package Matholek
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$url   = isset( $attributes['url'] ) ? trim( (string) $attributes['url'] ) : '';
$label = isset( $attributes['label'] ) && '' !== $attributes['label'] ? $attributes['label'] : __( 'Putar video', 'matholek' );
$play  = '<span class="mk-video__play" aria-hidden="true">▶</span>';

if ( '' !== $url ) {
	printf(
		'<a %1$s href="%2$s" target="_blank" rel="noopener" aria-label="%3$s">%4$s</a>',
		get_block_wrapper_attributes( array( 'class' => 'mk-video' ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		esc_url( $url ),
		esc_attr( $label ),
		$play // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
} else {
	printf(
		'<span %1$s>%2$s</span>',
		get_block_wrapper_attributes( array( 'class' => 'mk-video' ) ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		$play // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}
