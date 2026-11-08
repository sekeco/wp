<?php
/**
 * Server render for the Sekeco Marquee block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks.
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$items = isset( $attributes['items'] ) ? preg_split( '/\r\n|\r|\n/', (string) $attributes['items'] ) : array();
$items = array_values( array_filter( array_map( 'trim', (array) $items ) ) );

if ( ! $items ) {
	return;
}

$duration = isset( $attributes['duration'] ) ? max( 10, min( 120, (int) $attributes['duration'] ) ) : 40;

$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => 'sk-marquee',
		'style' => '--sk-marquee-duration:' . $duration . 's',
	)
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php foreach ( array( false, true ) as $is_copy ) : ?>
		<ul class="sk-marquee__track"<?php echo $is_copy ? ' aria-hidden="true"' : ''; ?>>
			<?php foreach ( $items as $item ) : ?>
				<li><?php echo esc_html( $item ); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
</div>
