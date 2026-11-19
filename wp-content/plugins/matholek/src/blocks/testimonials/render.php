<?php
/**
 * Testimonial slider card. Renders nothing until one testimonial has a quote.
 *
 * @package Matholek
 *
 * @var array  $attributes Block attributes.
 * @var string $content    Rendered testimonials (empty ones render nothing).
 */

defined( 'ABSPATH' ) || exit;

$count = preg_match_all( '/class="mk-slide"/', $content );

if ( ! $count ) {
	return;
}

$label = isset( $attributes['label'] ) ? $attributes['label'] : '';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'mk-testi' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-mk-slider>
	<?php if ( '' !== $label ) : ?>
		<p class="mk-testi__label"><?php echo wp_kses_post( $label ); ?></p>
	<?php endif; ?>
	<div class="mk-testi__track"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Rendered inner blocks. ?></div>
	<?php if ( $count > 1 ) : ?>
		<div class="mk-testi__nav">
			<div class="mk-testi__dots" aria-hidden="true">
				<?php for ( $i = 0; $i < $count; $i++ ) : ?>
					<span class="mk-testi__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"></span>
				<?php endfor; ?>
			</div>
			<div class="mk-testi__btns">
				<button type="button" class="mk-testi__prev" data-mk-prev aria-label="<?php esc_attr_e( 'Testimoni sebelumnya', 'matholek' ); ?>">←</button>
				<button type="button" class="mk-testi__next" data-mk-next aria-label="<?php esc_attr_e( 'Testimoni berikutnya', 'matholek' ); ?>">→</button>
			</div>
		</div>
	<?php endif; ?>
</div>
