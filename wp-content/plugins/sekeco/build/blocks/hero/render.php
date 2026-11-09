<?php
/**
 * Server render for the Sekeco Hero block.
 *
 * @package Sekeco
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Serialized inner blocks (actions).
 * @var WP_Block $block      Block instance.
 */

defined( 'ABSPATH' ) || exit;

$kicker  = isset( $attributes['kicker'] ) ? wp_strip_all_tags( $attributes['kicker'] ) : '';
$heading = isset( $attributes['heading'] ) ? sekeco_kses_inline( $attributes['heading'] ) : '';
$lead    = isset( $attributes['lead'] ) ? wp_strip_all_tags( $attributes['lead'] ) : '';
$tone    = isset( $attributes['tone'] ) ? $attributes['tone'] : 'auto';
$tone    = in_array( $tone, array( 'auto', 'ink', 'paper', 'accent' ), true ) ? $tone : 'auto';
$reel    = isset( $attributes['reel'] ) ? $attributes['reel'] : 'portfolio';
$count   = isset( $attributes['reelCount'] ) ? max( 3, min( 12, (int) $attributes['reelCount'] ) ) : 5;

list( $bg_class, $bg_canvas ) = sekeco_background( $attributes );

$classes = 'sk-hero' . ( 'auto' === $tone ? '' : ' sk-tone--' . $tone ) . sekeco_width_class( $attributes ) . $bg_class;

$reel_html = '';

if ( in_array( $reel, array( 'portfolio', 'product' ), true ) ) {
	$reel_html = render_block(
		array(
			'blockName'    => 'sekeco/project-grid',
			'attrs'        => array(
				'postType' => $reel,
				'layout'   => 'mosaic',
				'perPage'  => $count,
			),
			'innerBlocks'  => array(),
			'innerHTML'    => '',
			'innerContent' => array(),
		)
	);
}

$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $classes ) );
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<?php echo $bg_canvas; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<div class="sk-hero__stage">
		<?php if ( '' !== $kicker ) : ?>
			<p class="sk-hero__badge">
				<span class="sk-hero__badge-mark"><?php echo sekeco_icon( 'layers', array( 'size' => 14 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php echo esc_html( $kicker ); ?>
			</p>
		<?php endif; ?>
		<?php if ( '' !== $heading ) : ?>
			<h1 class="sk-hero__title" data-sk-lines><?php echo $heading; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
		<?php endif; ?>
		<div class="sk-hero__foot">
			<?php if ( '' !== $lead ) : ?>
				<p class="sk-hero__lead"><?php echo esc_html( $lead ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== trim( $content ) ) : ?>
				<div class="sk-hero__actions"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( '' !== $reel_html ) : ?>
		<div class="sk-hero__reel"><?php echo $reel_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endif; ?>
</section>
