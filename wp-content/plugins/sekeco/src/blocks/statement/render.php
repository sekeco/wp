<?php
/**
 * Server render for the Sekeco Statement block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$text  = isset( $attributes['text'] ) ? sekeco_kses_inline( $attributes['text'] ) : '';
$label = isset( $attributes['linkLabel'] ) ? trim( $attributes['linkLabel'] ) : '';
$url   = isset( $attributes['linkUrl'] ) ? trim( $attributes['linkUrl'] ) : '';

if ( '' === $text ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'sk-statement' ) );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<p class="sk-statement__text" data-sk-words><?php echo $text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	<?php if ( '' !== $label && '' !== $url ) : ?>
		<a class="sk-link" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?><?php echo sekeco_icon( 'arrow-right', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	<?php endif; ?>
</div>
