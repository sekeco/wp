<?php
/**
 * Title: Closing call to action
 * Slug: sekeco/cta
 * Categories: sekeco-cta, call-to-action
 * Description: Compact brand band: headline, one line of copy and one action.
 * Keywords: cta, closing, contact, enquiry
 * Viewport Width: 1400
 * Text Domain: sekeco
 */
?>
<!-- wp:sekeco/cta <?php echo serialize_block_attributes( array(
	'heading' => __( 'Got a project? <em>Let\'s talk.</em>', 'sekeco' ),
	'lead' => __( 'Tell us what you are building and where it should end up.', 'sekeco' ),
	'tone' => 'auto',
	'bg' => 'dusk',
	'width' => 'full',
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Let\'s Collaborate', 'sekeco' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:sekeco/cta -->
