<?php
/**
 * Title: Home portfolio
 * Slug: sekeco/home-products
 * Categories: sekeco-work
 * Description: Products Sekeco runs, on a raised surface. Hidden until one is published.
 * Keywords: portfolio, products, projects
 * Viewport Width: 1400
 * Text Domain: sekeco
 */
?>
<!-- wp:sekeco/section {"width":"wide","tone":"surface","spacing":"md","requires":"product"} -->
<!-- wp:sekeco/section-header <?php echo serialize_block_attributes( array(
	'kicker' => __( 'Portfolio', 'sekeco' ),
	'title' => __( 'Products we <em>run.</em>', 'sekeco' ),
	'lead' => __( 'Software we build and operate ourselves.', 'sekeco' ),
	'linkLabel' => __( 'Portfolio', 'sekeco' ),
	'linkUrl' => home_url( '/portfolio/' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/project-grid {"postType":"product","layout":"grid","columns":3,"perPage":3} /-->
<!-- /wp:sekeco/section -->
