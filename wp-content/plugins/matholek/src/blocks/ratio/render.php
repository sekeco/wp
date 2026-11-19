<?php
/**
 * Donut ratio: a ring filled to `value` percent.
 *
 * @package Matholek
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$value = isset( $attributes['value'] ) ? max( 0, min( 100, (float) $attributes['value'] ) ) : 70;
$label = isset( $attributes['label'] ) && '' !== $attributes['label']
	? $attributes['label']
	/* translators: %s: percentage. */
	: sprintf( __( '%s persen', 'matholek' ), $value );
?>
<svg <?php echo get_block_wrapper_attributes( array( 'class' => 'mk-ratio' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> viewBox="0 0 36 36" role="img" aria-label="<?php echo esc_attr( $label ); ?>"><circle class="mk-ratio__track" cx="18" cy="18" r="15.9" fill="none" stroke-width="4"/><circle class="mk-ratio__bar" cx="18" cy="18" r="15.9" fill="none" stroke-width="4" stroke-dasharray="<?php echo esc_attr( $value . ' ' . ( 100 - $value ) ); ?>" stroke-linecap="round"/></svg>
