<?php
/**
 * Title: Home team
 * Slug: sekeco/home-team
 * Categories: sekeco-work
 * Description: The people behind the work: the lead as a cover card, the crew as compact cards. Hidden until a team member is published.
 * Keywords: team, people, crew
 * Viewport Width: 1400
 * Text Domain: sekeco
 */
?>
<!-- wp:sekeco/section {"tone":"auto","spacing":"md","requires":"team"} -->
<!-- wp:sekeco/section-header <?php echo serialize_block_attributes( array(
	'kicker' => __( 'Team', 'sekeco' ),
	'title' => __( 'The people <em>behind it.</em>', 'sekeco' ),
	'linkLabel' => __( 'Whole team', 'sekeco' ),
	'linkUrl' => home_url( '/team/' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/project-grid <?php echo serialize_block_attributes( array(
	'postType' => 'team',
	'layout' => 'crew',
	'perPage' => 8,
	'crewNote' => __( 'Plus hand-picked specialists, pulled in per project.', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->
<!-- /wp:sekeco/section -->
