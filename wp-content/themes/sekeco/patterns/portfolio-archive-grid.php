<?php
/**
 * Title: Works archive grid
 * Slug: sekeco/portfolio-archive-grid
 * Categories: sekeco
 * Description: Works card grid that inherits the current archive query, with pagination.
 * Viewport Width: 1400
 * Inserter: no
 */
?>
<!-- wp:query {"queryId":105,"query":{"perPage":12,"pages":0,"offset":0,"postType":"portfolio","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"layout":{"type":"default"}} -->
<div class="wp-block-query">
	<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:group {"className":"is-style-sekeco-card","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"default"}} -->
		<div class="wp-block-group is-style-sekeco-card" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","style":{"border":{"radius":"var:preset|border-radius|md"}}} /-->

			<!-- wp:post-terms {"term":"category","style":{"typography":{"fontSize":"var:preset|font-size|small"}}} /-->

			<!-- wp:post-title {"isLink":true,"level":2,"style":{"typography":{"fontSize":"var:preset|font-size|large","lineHeight":"1.2"}}} /-->

			<!-- wp:post-excerpt {"moreText":"Read more","excerptLength":18,"style":{"typography":{"fontSize":"var:preset|font-size|small"}}} /-->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"sekeco/meta","args":{"key":"sekeco_portfolio_client"}}}},"fontSize":"small","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-small-font-size">Client</p>
				<!-- /wp:paragraph -->

				<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"sekeco/meta","args":{"key":"sekeco_portfolio_year"}}}},"fontSize":"small","textColor":"muted"} -->
				<p class="has-muted-color has-text-color has-small-font-size">Year</p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
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
			<h2 class="wp-block-heading has-text-align-center">No projects in this category yet.</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"align":"center","textColor":"muted"} -->
			<p class="has-text-align-center has-muted-color has-text-color">Browse all projects or pick a different category.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
