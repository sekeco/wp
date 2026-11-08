<?php
/**
 * Server render for the Sekeco Capability block.
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
$icon  = isset( $attributes['icon'] ) ? sanitize_key( $attributes['icon'] ) : 'code-square';
$url   = isset( $attributes['linkUrl'] ) ? trim( $attributes['linkUrl'] ) : '';
$tags  = isset( $attributes['tags'] ) ? array_filter( array_map( 'trim', explode( ',', (string) $attributes['tags'] ) ) ) : array();

if ( '' === $title ) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => 'sk-capability sk-glow' ) );
?>
<article <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> role="listitem">
	<span class="sk-capability__icon"><?php echo sekeco_icon( $icon, array( 'size' => 24 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	<h3 class="sk-capability__title">
		<?php if ( '' !== $url ) : ?>
			<a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a>
		<?php else : ?>
			<?php echo esc_html( $title ); ?>
		<?php endif; ?>
	</h3>
	<div class="sk-capability__text">
		<?php if ( '' !== $desc ) : ?>
			<p class="sk-capability__desc"><?php echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php endif; ?>
		<?php if ( $tags ) : ?>
			<ul class="sk-capability__tags">
				<?php foreach ( $tags as $tag ) : ?>
					<li><?php echo esc_html( $tag ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php if ( '' !== $url ) : ?>
		<span class="sk-capability__go"><?php echo sekeco_icon( 'arrow-right-up', array( 'size' => 28 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
	<?php endif; ?>
</article>
