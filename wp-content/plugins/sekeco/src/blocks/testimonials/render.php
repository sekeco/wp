<?php
/**
 * Server render for the Sekeco Testimonials block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized Testimonial blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

// Items without a quote render nothing, so an empty set hides the whole block.
if ( '' === trim( $content ) ) {
	return;
}

$layout   = isset( $attributes['layout'] ) ? $attributes['layout'] : 'slider';
$layout   = in_array( $layout, array( 'slider', 'cards', 'marquee' ), true ) ? $layout : 'slider';
$duration = isset( $attributes['duration'] ) ? max( 20, min( 180, (int) $attributes['duration'] ) ) : 60;

$extra = array( 'class' => 'sk-testimonials sk-testimonials--' . $layout );

if ( 'marquee' === $layout ) {
	$extra['style'] = '--sk-tm-duration:' . $duration . 's';
}

if ( 'slider' === $layout ) {
	$extra['data-sk-slider'] = '';
}

$wrapper_attributes = get_block_wrapper_attributes( $extra );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="sk-testimonials__track"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php if ( 'marquee' === $layout ) : ?>
		<div class="sk-testimonials__track sk-testimonials__track--copy" aria-hidden="true"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endif; ?>
	<?php if ( 'slider' === $layout ) : ?>
		<div class="sk-testimonials__nav" hidden>
			<button type="button" class="sk-testimonials__arrow" data-dir="-1" aria-label="<?php esc_attr_e( 'Previous testimonial', 'sekeco' ); ?>">
				<?php echo sekeco_icon( 'arrow-left', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
			<div class="sk-testimonials__dots" role="group" aria-label="<?php esc_attr_e( 'Choose a testimonial', 'sekeco' ); ?>"></div>
			<button type="button" class="sk-testimonials__arrow" data-dir="1" aria-label="<?php esc_attr_e( 'Next testimonial', 'sekeco' ); ?>">
				<?php echo sekeco_icon( 'arrow-right', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>
	<?php endif; ?>
</div>
