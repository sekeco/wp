<?php
/**
 * Title: Home hero
 * Slug: sekeco/home-hero
 * Categories: sekeco-hero, banner
 * Description: Centred opener: display headline, lead, two actions and a mosaic of the latest Works.
 * Keywords: hero, opener, headline, banner
 * Template Types: front-page, home
 * Viewport Width: 1400
 * Text Domain: sekeco
 */
?>
<!-- wp:sekeco/hero <?php echo serialize_block_attributes( array(
	'width' => 'wide',
	'bg' => 'nebula',
	'kicker' => __( 'One team, from identity to running system', 'sekeco' ),
	'heading' => __( 'Making a well furnished branding <em>solely for what you\'re hoping</em>', 'sekeco' ),
	'lead' => __( 'Engaged in identity design, business development, and IT consultant. Thrive your essential needs by building your foundation with swiftness and ease.', 'sekeco' ),
	'tone' => 'auto',
	'reel' => 'portfolio',
	'reelCount' => 5,
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> -->
<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Let\'s Collaborate', 'sekeco' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline","style":{"border":{"radius":"999px"}}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/portfolio/' ) ); ?>" style="border-radius:999px"><?php esc_html_e( 'See Our Portfolio', 'sekeco' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:sekeco/hero -->
