<?php
/**
 * Title: Contact intro and form
 * Slug: sekeco/contact
 * Categories: sekeco-content
 * Description: Page opener with the Sekeco enquiry form.
 * Keywords: contact, form, enquiry
 * Viewport Width: 1400
 * Inserter: no
 * Text Domain: sekeco
 */
?>
<!-- wp:sekeco/section {"tone":"auto","spacing":"md","width":"narrow","className":"sk-page-head"} -->
<!-- wp:sekeco/section-header <?php echo serialize_block_attributes( array(
	'kicker' => __( 'Say hello', 'sekeco' ),
	'title' => __( 'Tell us what you\'re <em>building.</em>', 'sekeco' ),
	'lead' => __( 'Describe the goal and any deadline. We reply with questions and next steps.', 'sekeco' ),
	'level' => 1,
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/form {"formId":177} /-->
<!-- /wp:sekeco/section -->
