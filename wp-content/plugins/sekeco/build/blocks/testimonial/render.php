<?php
/**
 * Server render for the Sekeco Testimonial block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Unused.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$quote  = isset( $attributes['quote'] ) ? sekeco_kses_inline( $attributes['quote'] ) : '';
$name   = isset( $attributes['name'] ) ? wp_strip_all_tags( $attributes['name'] ) : '';
$role   = isset( $attributes['role'] ) ? wp_strip_all_tags( $attributes['role'] ) : '';
$source = isset( $attributes['source'] ) ? wp_strip_all_tags( $attributes['source'] ) : '';
$topic  = isset( $attributes['topic'] ) ? wp_strip_all_tags( $attributes['topic'] ) : '';
$image  = isset( $attributes['imageId'] ) ? (int) $attributes['imageId'] : 0;

// A testimonial without words has nothing to show.
if ( '' === trim( wp_strip_all_tags( $quote ) ) ) {
	return;
}

$avatar = '';

if ( $image ) {
	$avatar = wp_get_attachment_image(
		$image,
		'thumbnail',
		false,
		array(
			'class'    => 'sk-testimonial__avatar',
			'alt'      => '',
			'loading'  => 'lazy',
			'decoding' => 'async',
		)
	);
}

if ( '' === $avatar && '' !== $name ) {
	$avatar = '<span class="sk-testimonial__avatar sk-testimonial__avatar--mark" aria-hidden="true">' . esc_html( sekeco_initials( $name ) ) . '</span>';
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'sk-testimonial' ) );
?>
<figure <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( '' !== $source || '' !== $topic ) : ?>
		<p class="sk-testimonial__meta">
			<?php if ( '' !== $source ) : ?>
				<span class="sk-testimonial__source"><?php echo esc_html( $source ); ?></span>
			<?php endif; ?>
			<?php if ( '' !== $topic ) : ?>
				<span class="sk-testimonial__topic"><?php echo esc_html( $topic ); ?></span>
			<?php endif; ?>
		</p>
	<?php endif; ?>
	<blockquote class="sk-testimonial__quote"><p><?php echo $quote; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p></blockquote>
	<?php if ( '' !== $name || '' !== $role ) : ?>
		<figcaption class="sk-testimonial__who">
			<?php echo $avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span class="sk-testimonial__id">
				<?php if ( '' !== $name ) : ?>
					<span class="sk-testimonial__name"><?php echo esc_html( $name ); ?></span>
				<?php endif; ?>
				<?php if ( '' !== $role ) : ?>
					<span class="sk-testimonial__role"><?php echo esc_html( $role ); ?></span>
				<?php endif; ?>
			</span>
		</figcaption>
	<?php endif; ?>
</figure>
