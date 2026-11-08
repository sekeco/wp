<?php
/**
 * Server render for the Sekeco FAQ item block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks (the answer).
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$question = isset( $attributes['question'] ) ? wp_strip_all_tags( $attributes['question'] ) : '';

if ( '' === $question ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'sk-faq-item' ) );
?>
<details <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<summary>
		<span class="sk-faq-item__question"><?php echo esc_html( $question ); ?></span>
		<span class="sk-faq-item__icon"><?php echo sekeco_icon( 'alt-arrow-down', array( 'size' => 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	</summary>
	<div class="sk-faq-item__answer"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
</details>
