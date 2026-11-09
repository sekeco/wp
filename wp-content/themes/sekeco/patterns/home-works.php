<?php
/**
 * Title: Home works
 * Slug: sekeco/home-works
 * Categories: sekeco-work
 * Description: Selected works in an asymmetric grid. Hidden until a Work is published.
 * Keywords: works, case studies, projects
 * Viewport Width: 1400
 * Text Domain: sekeco
 */
?>
<!-- wp:sekeco/section {"tone":"auto","spacing":"md","requires":"portfolio"} -->
<!-- wp:sekeco/section-header <?php echo serialize_block_attributes( array(
	'title' => __( 'Selected <em>works.</em>', 'sekeco' ),
	'lead' => __( 'Identity, product and platform projects, shipped.', 'sekeco' ),
	'linkLabel' => __( 'All works', 'sekeco' ),
	'linkUrl' => home_url( '/works/' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/project-grid {"postType":"portfolio","layout":"grid","columns":2,"perPage":6} /-->
<!-- /wp:sekeco/section -->
