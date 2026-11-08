<?php
/**
 * Title: Portfolio grid
 * Slug: sekeco/product-grid
 * Categories: sekeco
 * Description: Query Loop grid for Sekeco's portfolio items, with status, stack, and link meta.
 * Viewport Width: 1400
 */
?>
<!-- wp:query {"queryId":106,"query":{"perPage":6,"pages":0,"offset":0,"postType":"product","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"layout":{"type":"default"}} -->
<div class="wp-block-query">
	<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:group {"className":"is-style-sekeco-card","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"default"}} -->
		<div class="wp-block-group is-style-sekeco-card" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9","style":{"border":{"radius":"var:preset|border-radius|md"}}} /-->

			<!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"var:preset|font-size|small"}}} /-->

			<!-- wp:post-title {"isLink":true,"level":2,"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.2"}}} /-->

			<!-- wp:post-excerpt {"moreText":"Read more","excerptLength":20,"style":{"typography":{"fontSize":"var:preset|font-size|small"}}} /-->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"sekeco/meta","args":{"key":"sekeco_product_status"}}}},"fontSize":"small","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-small-font-size">Status</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"sekeco/meta","args":{"key":"sekeco_product_stack"}}}},"fontSize":"small","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-small-font-size">Stack</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button {"className":"is-style-sekeco-ghost","fontSize":"small","metadata":{"bindings":{"url":{"source":"sekeco/meta","args":{"key":"sekeco_product_url"}}}}} -->
				<div class="wp-block-button has-custom-font-size is-style-sekeco-ghost has-small-font-size"><a class="wp-block-button__link wp-element-button" href="#">View project</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->

	<!-- wp:query-pagination {"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<!-- wp:query-pagination-previous /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next /-->
	<!-- /wp:query-pagination -->

	<!-- wp:query-no-results -->
		<!-- wp:group {"backgroundColor":"surface","style":{"border":{"radius":"var:preset|border-radius|md"},"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group has-surface-background-color has-background" style="border-radius:var(--wp--preset--border-radius--md);padding-top:var(--wp--preset--spacing--60);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50)">
			<!-- wp:heading {"textAlign":"center","level":2} -->
			<h2 class="wp-block-heading has-text-align-center">No products yet.</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"muted"} -->
			<p class="has-text-align-center has-muted-color has-text-color">Add a product to see it here.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
