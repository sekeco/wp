<?php
/**
 * Title: Kurikulum
 * Slug: matholek/kurikulum
 * Categories: matholek, text
 * Description: Judul tengah, kartu donat 70 : 30, nilai Sholih dan Akrom, dan empat bidang ilmu. Ikon bidang diganti lewat kelas mk-icon--nama (Advanced → Additional CSS class).
 * Keywords: kurikulum, ilmu, kitab, nilai
 * Viewport Width: 1280
 */

$matholek_features = array(
	array( 'book-2', 'Kitab Salaf', 'Fiqh, tauhid, tafsir, hadits, tasawuf.' ),
	array( 'text-square', 'Ilmu Alat', 'Nahwu-sharaf sejak Ibtidaiyah, balaghah di Aliyah.' ),
	array( 'pen-new-square', 'Karya Tulis Arab', 'Wajib bagi kelas III Aliyah.' ),
	array( 'test-tube', 'Ilmu Umum', 'Matematika, sains, bahasa.' ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"kurikulum","className":"mk-kurikulum mk-wrap","layout":{"type":"default"}} -->
<section id="kurikulum" class="wp-block-group mk-kurikulum mk-wrap"><!-- wp:group {"className":"mk-kurikulum__head","layout":{"type":"default"}} -->
<div class="wp-block-group mk-kurikulum__head"><!-- wp:paragraph {"className":"mk-kicker"} -->
<p class="mk-kicker">RINGKASAN KURIKULUM</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"mk-h2"} -->
<h2 class="wp-block-heading mk-h2">Tafaqquh fiddin menuju Insan Sholih-Akrom</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"mk-kurikulum__grid","layout":{"type":"default"}} -->
<div class="wp-block-group mk-kurikulum__grid"><!-- wp:group {"className":"mk-card mk-ratio-card","layout":{"type":"default"}} -->
<div class="wp-block-group mk-card mk-ratio-card"><!-- wp:matholek/ratio {"value":70,"label":"70 persen ilmu agama, 30 persen ilmu umum"} /-->

<!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"mk-ratio-card__value"} -->
<p class="mk-ratio-card__value">70 : 30</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mk-ratio-card__text"} -->
<p class="mk-ratio-card__text">Ilmu agama berbasis kitab salaf dibanding ilmu umum. Hafalan kitab menjadi syarat kenaikan kelas.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"mk-value mk-value--teal","layout":{"type":"default"}} -->
<div class="wp-block-group mk-value mk-value--teal"><!-- wp:paragraph {"className":"mk-value__arab"} -->
<p class="mk-value__arab">صالح</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mk-value__name"} -->
<p class="mk-value__name">Sholih</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mk-value__text"} -->
<p class="mk-value__text">Taat, jujur, amanah.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"mk-value mk-value--gold","layout":{"type":"default"}} -->
<div class="wp-block-group mk-value mk-value--gold"><!-- wp:paragraph {"className":"mk-value__arab"} -->
<p class="mk-value__arab">أكرم</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mk-value__name"} -->
<p class="mk-value__name">Akrom</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mk-value__text"} -->
<p class="mk-value__text">Mulia &amp; bermanfaat bagi umat.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php foreach ( $matholek_features as $matholek_feature ) : ?><!-- wp:group {"className":"mk-feature mk-icon--<?php echo esc_attr( $matholek_feature[0] ); ?>","layout":{"type":"default"}} -->
<div class="wp-block-group mk-feature mk-icon--<?php echo esc_attr( $matholek_feature[0] ); ?>"><!-- wp:paragraph {"className":"mk-feature__title"} -->
<p class="mk-feature__title"><?php echo esc_html( $matholek_feature[1] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mk-feature__text"} -->
<p class="mk-feature__text"><?php echo esc_html( $matholek_feature[2] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php endforeach; ?></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
