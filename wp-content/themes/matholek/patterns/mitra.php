<?php
/**
 * Title: Mitra
 * Slug: matholek/mitra
 * Categories: matholek
 * Description: Pita lembaga mitra. Isi tiap ubin dengan nama atau logo resmi; ubin kosong tidak tampil, dan pita disembunyikan selama semua ubin kosong.
 * Keywords: mitra, kerja sama, logo, partner
 * Viewport Width: 1280
 */
?>
<!-- wp:group {"tagName":"section","className":"mk-mitra mk-wrap mk-needs-content","layout":{"type":"default"}} -->
<section class="wp-block-group mk-mitra mk-wrap mk-needs-content"><!-- wp:group {"className":"mk-mitra__box","layout":{"type":"default"}} -->
<div class="wp-block-group mk-mitra__box"><!-- wp:paragraph {"className":"mk-mitra__title"} -->
<p class="mk-mitra__title">Lembaga yang<br><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-gold-400-color">bekerja sama</mark></p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"mk-mitra__list","layout":{"type":"default"}} -->
<div class="wp-block-group mk-mitra__list"><?php for ( $matholek_i = 0; $matholek_i < 6; $matholek_i++ ) : ?><!-- wp:group {"className":"mk-partner mk-hide-empty","layout":{"type":"default"}} -->
<div class="wp-block-group mk-partner mk-hide-empty"><!-- wp:paragraph {"placeholder":"Nama atau logo mitra"} -->
<p></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php endfor; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
