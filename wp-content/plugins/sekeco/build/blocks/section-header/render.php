<?php
/**
 * Server render for the Sekeco Section header block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$kicker = isset( $attributes['kicker'] ) ? wp_strip_all_tags( $attributes['kicker'] ) : '';
$title = isset( $attributes['title'] ) ? sekeco_kses_inline( $attributes['title'] ) : '';
$lead  = isset( $attributes['lead'] ) ? wp_strip_all_tags( $attributes['lead'] ) : '';
$label = isset( $attributes['linkLabel'] ) ? trim( $attributes['linkLabel'] ) : '';
$url   = isset( $attributes['linkUrl'] ) ? trim( $attributes['linkUrl'] ) : '';
$level = isset( $attributes['level'] ) ? max( 1, min( 3, (int) $attributes['level'] ) ) : 2;

if ( '' === $title && '' === $lead ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'sk-section-header' ) );
?>
<header <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( '' !== $kicker ) : ?>
		<p class="sk-kicker sk-section-header__kicker"><?php echo esc_html( $kicker ); ?></p>
	<?php endif; ?>
	<?php if ( '' !== $title ) : ?>
		<h<?php echo (int) $level; ?> class="sk-section-header__title"><?php echo $title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h<?php echo (int) $level; ?>>
	<?php endif; ?>
	<?php if ( '' !== $lead || ( '' !== $label && '' !== $url ) ) : ?>
		<div class="sk-section-header__aside">
			<?php if ( '' !== $lead ) : ?>
				<p class="sk-section-header__lead"><?php echo esc_html( $lead ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== $label && '' !== $url ) : ?>
				<a class="sk-link" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?><?php echo sekeco_icon( 'arrow-right', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</header>
