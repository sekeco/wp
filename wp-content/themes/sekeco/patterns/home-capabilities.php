<?php
/**
 * Title: Home capabilities
 * Slug: sekeco/home-capabilities
 * Categories: sekeco-content
 * Description: Header and the four lines of work as a ruled list.
 * Keywords: services, capabilities, offerings
 * Viewport Width: 1400
 * Text Domain: sekeco
 */
?>
<!-- wp:sekeco/section {"tone":"auto","spacing":"md"} -->
<!-- wp:sekeco/section-header <?php echo serialize_block_attributes( array(
	'kicker' => __( 'What we do', 'sekeco' ),
	'title' => __( 'Four lines of work, <em>one team.</em>', 'sekeco' ),
	'lead' => __( 'Pick one line or run the whole path from identity to a working system.', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/capabilities -->
<!-- wp:sekeco/capability <?php echo serialize_block_attributes( array(
	'icon' => 'code-square',
	'title' => __( 'Web & e-commerce', 'sekeco' ),
	'description' => __( 'Websites, web applications and online stores that load fast, read clearly and are easy to run.', 'sekeco' ),
	'tags' => __( 'Websites, Web apps, E-commerce', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/capability <?php echo serialize_block_attributes( array(
	'icon' => 'smartphone',
	'title' => __( 'Mobile apps', 'sekeco' ),
	'description' => __( 'iOS and Android products designed together with the web they connect to, so every screen feels like one system.', 'sekeco' ),
	'tags' => __( 'iOS, Android', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/capability <?php echo serialize_block_attributes( array(
	'icon' => 'server-square',
	'title' => __( 'ERP & business systems', 'sekeco' ),
	'description' => __( 'Custom systems for the operations behind the brand: admissions, inventory, finance and the APIs that connect them.', 'sekeco' ),
	'tags' => __( 'Operations, Integrations, APIs', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->

<!-- wp:sekeco/capability <?php echo serialize_block_attributes( array(
	'icon' => 'palette-round',
	'title' => __( 'Identity & UI/UX design', 'sekeco' ),
	'description' => __( 'Brand identity, interface design and design systems that carry across every touchpoint.', 'sekeco' ),
	'tags' => __( 'Identity, Interface, Design systems', 'sekeco' ),
) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> /-->
<!-- /wp:sekeco/capabilities -->
<!-- /wp:sekeco/section -->
