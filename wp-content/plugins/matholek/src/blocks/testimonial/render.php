<?php
/**
 * One testimonial slide. Nothing without a quote.
 *
 * @package Matholek
 *
 * @var array $attributes Block attributes.
 */

defined( 'ABSPATH' ) || exit;

$quote = isset( $attributes['quote'] ) ? trim( wp_strip_all_tags( $attributes['quote'] ) ) : '';

if ( '' === $quote ) {
	return;
}

$name = isset( $attributes['name'] ) ? trim( $attributes['name'] ) : '';
$meta = isset( $attributes['meta'] ) ? trim( $attributes['meta'] ) : '';
?>
<figure class="mk-slide">
	<blockquote class="mk-slide__quote">&quot;<?php echo wp_kses_post( $attributes['quote'] ); ?>&quot;</blockquote>
	<?php if ( '' !== $name || '' !== $meta ) : ?>
		<figcaption class="mk-slide__by">
			<?php if ( '' !== $name ) : ?>
				<b><?php echo wp_kses_post( $name ); ?></b>
			<?php endif; ?>
			<?php if ( '' !== $name && '' !== $meta ) : ?>
				&middot;
			<?php endif; ?>
			<?php echo wp_kses_post( $meta ); ?>
		</figcaption>
	<?php endif; ?>
</figure>
