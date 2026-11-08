<?php
/**
 * Server render for the Sekeco CTA block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks (actions).
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$kicker  = isset( $attributes['kicker'] ) ? wp_strip_all_tags( $attributes['kicker'] ) : '';
$heading = isset( $attributes['heading'] ) ? sekeco_kses_inline( $attributes['heading'] ) : '';
$lead    = isset( $attributes['lead'] ) ? wp_strip_all_tags( $attributes['lead'] ) : '';
$tone    = isset( $attributes['tone'] ) ? $attributes['tone'] : 'accent';
$tone    = in_array( $tone, array( 'auto', 'ink', 'paper', 'accent' ), true ) ? $tone : 'accent';

if ( '' === $heading ) {
	return;
}

$classes = 'sk-cta' . ( 'auto' === $tone ? '' : ' sk-tone--' . $tone );

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $classes ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="sk-cta__inner">
		<?php if ( '' !== $kicker ) : ?>
			<p class="sk-kicker sk-cta__kicker"><?php echo esc_html( $kicker ); ?></p>
		<?php endif; ?>
		<h2 class="sk-cta__title"><?php echo $heading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		<?php if ( '' !== $lead ) : ?>
			<p class="sk-cta__lead"><?php echo esc_html( $lead ); ?></p>
		<?php endif; ?>
		<?php if ( '' !== trim( $content ) ) : ?>
			<div class="sk-cta__actions"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>
	</div>
</section>
