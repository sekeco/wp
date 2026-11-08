<?php
/**
 * Server render for the Sekeco Theme toggle block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$variant = isset( $attributes['variant'] ) ? $attributes['variant'] : 'icon';
$variant = in_array( $variant, array( 'icon', 'pill', 'rail' ), true ) ? $variant : 'icon';

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'                => 'sk-theme-toggle sk-theme-toggle--' . $variant,
		'type'                 => 'button',
		'aria-label'           => __( 'Switch between light and dark', 'sekeco' ),
		'data-sk-theme-toggle' => '',
	)
);
?>
<button <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php if ( 'icon' === $variant ) : ?>
		<?php echo sekeco_icon( 'sun-2', array( 'size' => 22, 'class' => 'sk-theme-toggle__sun' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php echo sekeco_icon( 'moon', array( 'size' => 22, 'class' => 'sk-theme-toggle__moon' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php else : ?>
		<span class="sk-theme-toggle__opt" data-mode="dark"><?php echo sekeco_icon( 'moon', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Dark', 'sekeco' ); ?></span>
		<span class="sk-theme-toggle__opt" data-mode="light"><?php echo sekeco_icon( 'sun-2', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Light', 'sekeco' ); ?></span>
	<?php endif; ?>
</button>
