<?php
/**
 * Title: Berita terbaru
 * Slug: matholek/berita
 * Categories: matholek, query
 * Description: Satu berita utama bergambar dan tiga berita berikutnya dalam daftar, dari Posts. Section tidak tampil selama belum ada berita.
 * Keywords: berita, kabar, posts, query
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"tagName":"section","anchor":"berita","className":"mk-berita mk-wrap mk-needs-content","layout":{"type":"default"}} -->
<section id="berita" class="wp-block-group mk-berita mk-wrap mk-needs-content"><!-- wp:group {"className":"mk-head","layout":{"type":"default"}} -->
<div class="wp-block-group mk-head"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"mk-kicker"} -->
<p class="mk-kicker">BERITA</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"mk-h2"} -->
<h2 class="wp-block-heading mk-h2">Kabar terbaru</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-card mk-btn-sm"} -->
<div class="wp-block-button is-style-card mk-btn-sm"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( matholek_news_url() ); ?>">Semua →</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"mk-news","layout":{"type":"default"}} -->
<div class="wp-block-group mk-news"><!-- wp:query {"queryId":11,"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"mk-news__lead mk-hide-empty"} -->
<div class="wp-block-query mk-news__lead mk-hide-empty"><!-- wp:post-template -->
<!-- wp:group {"tagName":"article","className":"mk-news__feature mk-media mk-shade-news","layout":{"type":"default"}} -->
<article class="wp-block-group mk-news__feature mk-media mk-shade-news"><!-- wp:post-featured-image {"className":"mk-media__img"} /-->

<!-- wp:group {"className":"mk-news__feature-body","layout":{"type":"default"}} -->
<div class="wp-block-group mk-news__feature-body"><!-- wp:post-terms {"term":"category","className":"mk-badge"} /-->

<!-- wp:post-title {"level":3,"isLink":true,"className":"mk-news__feature-title"} /-->

<!-- wp:post-date {"format":"j F Y","className":"mk-news__feature-date"} /--></div>
<!-- /wp:group --></article>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query -->

<!-- wp:query {"queryId":12,"query":{"perPage":3,"pages":0,"offset":1,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"mk-news__list"} -->
<div class="wp-block-query mk-news__list"><!-- wp:post-template -->
<!-- wp:group {"tagName":"article","className":"mk-news__item","layout":{"type":"default"}} -->
<article class="wp-block-group mk-news__item"><!-- wp:group {"className":"mk-news__thumb mk-media","layout":{"type":"default"}} -->
<div class="wp-block-group mk-news__thumb mk-media"><!-- wp:post-featured-image {"isLink":true,"className":"mk-media__img"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:group {"className":"mk-news__meta","layout":{"type":"default"}} -->
<div class="wp-block-group mk-news__meta"><!-- wp:post-terms {"term":"category"} /-->

<!-- wp:post-date {"format":"j M Y"} /--></div>
<!-- /wp:group -->

<!-- wp:post-title {"level":3,"isLink":true,"className":"mk-news__title"} /--></div>
<!-- /wp:group --></article>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
