<?php
/**
 * Title: Home process
 * Slug: sekeco/home-process
 * Categories: sekeco-content
 * Description: Four numbered steps that describe how a project runs.
 * Keywords: process, steps, workflow
 * Viewport Width: 1400
 * Text Domain: sekeco
 */
?>
<!-- wp:sekeco/section {"tone":"auto","spacing":"md"} -->
<!-- wp:sekeco/section-header <?php echo serialize_block_attributes( array(
	'kicker' => __( 'How we work', 'sekeco' ),
	'title' => __( 'How a project <em>runs.</em>', 'sekeco' ),
	'lead' => __( 'The same four steps whether the job is an identity or an ERP.', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/process -->
<!-- wp:sekeco/process-step <?php echo serialize_block_attributes( array(
	'title' => __( 'Discover', 'sekeco' ),
	'description' => __( 'We listen, map the problem and agree on what success looks like.', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/process-step <?php echo serialize_block_attributes( array(
	'title' => __( 'Design', 'sekeco' ),
	'description' => __( 'Identity and interface take shape in prototypes you can react to.', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/process-step <?php echo serialize_block_attributes( array(
	'title' => __( 'Build', 'sekeco' ),
	'description' => __( 'Engineers turn the approved design into a working, tested system.', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/process-step <?php echo serialize_block_attributes( array(
	'title' => __( 'Run', 'sekeco' ),
	'description' => __( 'We launch, integrate and stay close while it settles into daily use.', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->
<!-- /wp:sekeco/process -->
<!-- /wp:sekeco/section -->
