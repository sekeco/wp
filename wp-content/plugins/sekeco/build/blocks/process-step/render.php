<?php
/**
 * Server render for the Sekeco Process step block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$title = isset( $attributes['title'] ) ? wp_strip_all_tags( $attributes['title'] ) : '';
$desc  = isset( $attributes['description'] ) ? sekeco_kses_inline( $attributes['description'] ) : '';

if ( '' === $title ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'sk-process-step sk-glow' ) );
?>
<li <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<h3 class="sk-process-step__title"><?php echo esc_html( $title ); ?></h3>
	<?php if ( '' !== $desc ) : ?>
		<p class="sk-process-step__desc"><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php endif; ?>
</li>
