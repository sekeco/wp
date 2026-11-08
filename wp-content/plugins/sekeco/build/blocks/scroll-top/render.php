<?php
/**
 * Server render for the Sekeco Scroll to top block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'               => 'sk-scrolltop',
		'type'                => 'button',
		'aria-label'          => __( 'Back to top', 'sekeco' ),
		'data-sk-scroll-top'  => '',
		'hidden'              => '',
	)
);
?>
<button <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php echo sekeco_icon( 'arrow-up', array( 'size' => 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</button>
