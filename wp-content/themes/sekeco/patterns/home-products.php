<?php
/**
 * Title: Home — Portfolio
 * Slug: sekeco/home-products
 * Categories: sekeco
 * Description: Slider of Sekeco's portfolio items. Hidden automatically when no portfolio items are published.
 * Viewport Width: 1400
 * Inserter: no
 */
?>
<!-- wp:group {"align":"full","backgroundColor":"contrast","textColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20","margin":{"bottom":"var:preset|spacing|50"}}},"layout":{"type":"default"}} -->
	<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--50)">
		<!-- wp:paragraph {"className":"is-style-sekeco-eyebrow"} -->
		<p class="is-style-sekeco-eyebrow">Our portfolio</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading {"level":2} -->
		<h2 class="wp-block-heading">Tools we built for ourselves.</h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph {"textColor":"base","fontSize":"large","style":{"color":{"text":"#A1A1AA"}}} -->
		<p class="has-base-color has-text-color has-large-font-size" style="color:#A1A1AA">Platforms, tools, and experiments from the studio shelf.</p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->

	<!-- wp:sekeco/slider {"mode":"query","postType":"product","perPage":9,"perView":3,"gap":24,"showArrows":true,"label":"Sekeco portfolio"} /-->
</div>
<!-- /wp:group -->
