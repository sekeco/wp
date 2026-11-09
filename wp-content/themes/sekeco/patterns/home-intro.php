<?php
/**
 * Title: Home intro
 * Slug: sekeco/home-intro
 * Categories: sekeco-content
 * Description: Statement about the team with counted facts from published content.
 * Keywords: statement, stats, intro, facts
 * Viewport Width: 1400
 * Text Domain: sekeco
 */
?>
<!-- wp:sekeco/section {"tone":"auto","spacing":"md"} -->
<!-- wp:sekeco/statement <?php echo serialize_block_attributes( array(
	'text' => __( 'One team holds the brand, the interface and the engineering. <em>Specialists join only where the work needs them, so the team is exactly as big as the job.</em>', 'sekeco' ),
	'linkLabel' => __( 'View all work', 'sekeco' ),
	'linkUrl' => home_url( '/works/' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/stats -->
<!-- wp:sekeco/stat <?php echo serialize_block_attributes( array(
	'source' => 'portfolio',
	'label' => __( 'Works shipped', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/stat <?php echo serialize_block_attributes( array(
	'source' => 'product',
	'label' => __( 'Products in the portfolio', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/stat <?php echo serialize_block_attributes( array(
	'source' => 'team',
	'label' => __( 'People on the team', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->
<!-- /wp:sekeco/stats -->
<!-- /wp:sekeco/section -->
