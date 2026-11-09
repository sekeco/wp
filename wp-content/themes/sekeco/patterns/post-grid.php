<?php
/**
 * Title: Journal grid
 * Slug: sekeco/post-grid
 * Categories: sekeco-content, posts
 * Description: Posts as a three-column grid with pagination, for the Journal, archives and search.
 * Keywords: journal, posts, blog, archive
 * Template Types: home, archive, search, category
 * Viewport Width: 1400
 * Inserter: no
 * Text Domain: sekeco
 */
?>
<!-- wp:query {"queryId":1,"query":{"perPage":9,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"layout":{"type":"default"}} -->
<div class="wp-block-query">
	<!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"default"}} -->
		<div class="wp-block-group">
			<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3"} /-->

			<!-- wp:post-terms {"term":"category","fontSize":"small","textColor":"muted"} /-->

			<!-- wp:post-title {"isLink":true,"level":2,"fontSize":"large","style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}}} /-->

			<!-- wp:post-excerpt {"moreText":"Read more","excerptLength":20,"fontSize":"small","textColor":"muted"} /-->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->

	<!-- wp:query-pagination {"style":{"spacing":{"margin":{"top":"var:preset|spacing|60"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<!-- wp:query-pagination-previous /-->
		<!-- wp:query-pagination-numbers /-->
		<!-- wp:query-pagination-next /-->
	<!-- /wp:query-pagination -->

	<!-- wp:query-no-results -->
		<!-- wp:group {"className":"is-style-sekeco-surface","layout":{"type":"constrained"}} -->
		<div class="wp-block-group is-style-sekeco-surface">
			<!-- wp:heading {"level":3} -->
			<h3 class="wp-block-heading"><?php esc_html_e( 'Nothing here yet.', 'sekeco' ); ?></h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph {"textColor":"muted"} -->
			<p class="has-muted-color has-text-color"><?php esc_html_e( 'Try a different search or head back to the homepage.', 'sekeco' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
