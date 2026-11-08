<?php
/**
 * Server render for the Sekeco Form block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$form_id = isset( $attributes['formId'] ) ? absint( $attributes['formId'] ) : 0;

if ( ! $form_id && is_singular( 'sekeco_form' ) ) {
	$form_id = (int) get_the_ID();
}

if ( ! $form_id ) {
	return;
}

$wrapper = array( 'class' => 'sekeco-form-embed' );

if ( empty( $attributes['anchor'] ) ) {
	$wrapper['id'] = 'sekeco-form-' . $form_id;
}

printf(
	'<div %s>%s</div>',
	get_block_wrapper_attributes( $wrapper ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	sekeco_render_form( $form_id ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
);
