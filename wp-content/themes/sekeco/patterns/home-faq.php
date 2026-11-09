<?php
/**
 * Title: Home FAQ
 * Slug: sekeco/home-faq
 * Categories: sekeco-content
 * Description: Common questions as an accessible accordion.
 * Keywords: faq, questions, accordion
 * Viewport Width: 1400
 * Text Domain: sekeco
 */
?>
<!-- wp:sekeco/section {"tone":"auto","spacing":"md","width":"narrow"} -->
<!-- wp:sekeco/section-header <?php echo serialize_block_attributes( array(
	'kicker' => __( 'FAQ', 'sekeco' ),
	'title' => __( 'Questions, <em>answered.</em>', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/faq -->
<!-- wp:sekeco/faq-item <?php echo serialize_block_attributes( array(
	'question' => __( 'What can we start with?', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> -->
<!-- wp:paragraph -->
<p><?php esc_html_e( 'Anything from a single identity or interface to a complete system. Tell us the outcome you need and we will shape the scope with you.', 'sekeco' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:sekeco/faq-item -->

<!-- wp:sekeco/faq-item <?php echo serialize_block_attributes( array(
	'question' => __( 'Can you build on what we already have?', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> -->
<!-- wp:paragraph -->
<p><?php esc_html_e( 'Yes. We can extend, take over or integrate with an existing website, app or system through its APIs.', 'sekeco' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:sekeco/faq-item -->

<!-- wp:sekeco/faq-item <?php echo serialize_block_attributes( array(
	'question' => __( 'Who will we work with?', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> -->
<!-- wp:paragraph -->
<p><?php esc_html_e( 'A small core team stays with your project from the first call to launch. Specialists join for the parts that need them.', 'sekeco' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:sekeco/faq-item -->

<!-- wp:sekeco/faq-item <?php echo serialize_block_attributes( array(
	'question' => __( 'How do we get started?', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> -->
<!-- wp:paragraph -->
<p><?php esc_html_e( 'Send the enquiry form. Describe your goal and any deadline, and we will reply with questions and next steps.', 'sekeco' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:sekeco/faq-item -->
<!-- /wp:sekeco/faq -->
<!-- /wp:sekeco/section -->
