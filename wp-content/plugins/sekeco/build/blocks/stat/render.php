<?php
/**
 * Server render for the Sekeco Stat block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$source = isset( $attributes['source'] ) ? sanitize_key( $attributes['source'] ) : 'portfolio';
$label  = isset( $attributes['label'] ) ? wp_strip_all_tags( $attributes['label'] ) : '';
$suffix = isset( $attributes['suffix'] ) ? wp_strip_all_tags( $attributes['suffix'] ) : '';

if ( 'custom' === $source ) {
	$value = isset( $attributes['value'] ) ? trim( wp_strip_all_tags( $attributes['value'] ) ) : '';
} elseif ( in_array( $source, array( 'portfolio', 'product', 'team', 'post' ), true ) ) {
	$counts = wp_count_posts( $source );
	$value  = isset( $counts->publish ) ? (string) (int) $counts->publish : '0';
} else {
	$value = '';
}

// An empty count is not a fact worth showing.
if ( '' === $value || '0' === $value ) {
	return;
}

$numeric = ctype_digit( $value );

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'sk-stat' ) );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> role="listitem">
	<span class="sk-stat__value"<?php echo $numeric ? ' data-sk-count="' . esc_attr( $value ) . '"' : ''; ?>><?php echo esc_html( $value ); ?></span><?php if ( '' !== $suffix ) : ?><span class="sk-stat__suffix"><?php echo esc_html( $suffix ); ?></span><?php endif; ?>
	<?php if ( '' !== $label ) : ?>
		<span class="sk-stat__label"><?php echo esc_html( $label ); ?></span>
	<?php endif; ?>
</div>
