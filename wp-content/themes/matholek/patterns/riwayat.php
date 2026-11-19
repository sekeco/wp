<?php
/**
 * Title: Riwayat
 * Slug: matholek/riwayat
 * Categories: matholek, about
 * Description: Pengantar sejarah, tiga kartu angka, dan garis waktu lima periode masyayikh. Kartu angka yang nilainya kosong tidak tampil.
 * Keywords: riwayat, sejarah, timeline, masyayikh
 * Viewport Width: 1280
 */

$matholek_steps = array(
	array( '1912', 'KH Abdussalam' ),
	array( '1922–1944', 'KH Mahfudh Salam' ),
	array( '1945–1963', 'KH Abdullah Zen Salam &amp; KH Muhammadun Abdul Hadi' ),
	array( '1967–2014', 'KH MA Sahal Mahfudh' ),
	array( '2014–kini', 'KH Ahmad Nafi\' Abdillah' ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"riwayat","className":"mk-riwayat mk-wrap","layout":{"type":"default"}} -->
<section id="riwayat" class="wp-block-group mk-riwayat mk-wrap"><!-- wp:group {"className":"mk-riwayat__grid","layout":{"type":"default"}} -->
<div class="wp-block-group mk-riwayat__grid"><!-- wp:group {"className":"mk-card mk-riwayat__intro","layout":{"type":"default"}} -->
<div class="wp-block-group mk-card mk-riwayat__intro"><!-- wp:paragraph {"className":"mk-kicker"} -->
<p class="mk-kicker">RIWAYAT</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"mk-h2"} -->
<h2 class="wp-block-heading mk-h2">Lima generasi masyayikh, satu visi.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"mk-riwayat__intro-text"} -->
<p class="mk-riwayat__intro-text">Dari "Sekolah Arab" rintisan KH Abdussalam di sebelah barat makam Syeikh Ahmad Mutamakkin, hingga perguruan berjenjang hari ini.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"mk-stats","layout":{"type":"default"}} -->
<div class="wp-block-group mk-stats"><!-- wp:group {"className":"mk-stat mk-stat--teal mk-hide-empty","layout":{"type":"default"}} -->
<div class="wp-block-group mk-stat mk-stat--teal mk-hide-empty"><!-- wp:paragraph {"placeholder":"25K+","className":"mk-stat__value mk-key"} -->
<p class="mk-stat__value mk-key"></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mk-stat__label"} -->
<p class="mk-stat__label">Mutakhorrijin</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"mk-stat mk-hide-empty","layout":{"type":"default"}} -->
<div class="wp-block-group mk-stat mk-hide-empty"><!-- wp:paragraph {"placeholder":"120+","className":"mk-stat__value mk-key"} -->
<p class="mk-stat__value mk-key"></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mk-stat__label"} -->
<p class="mk-stat__label">Capaian prestasi</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"mk-stat mk-stat--gold","layout":{"type":"default"}} -->
<div class="wp-block-group mk-stat mk-stat--gold"><!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"matholek/since","args":{"year":1912}}}},"className":"mk-stat__value"} -->
<p class="mk-stat__value">114</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mk-stat__label"} -->
<p class="mk-stat__label">Tahun mengabdi</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"mk-card mk-timeline","layout":{"type":"default"}} -->
<div class="wp-block-group mk-card mk-timeline"><!-- wp:group {"className":"mk-timeline__grid","layout":{"type":"default"}} -->
<div class="wp-block-group mk-timeline__grid"><?php foreach ( $matholek_steps as $matholek_step ) : ?><!-- wp:group {"className":"mk-step","layout":{"type":"default"}} -->
<div class="wp-block-group mk-step"><!-- wp:paragraph {"className":"mk-step__year"} -->
<p class="mk-step__year"><?php echo $matholek_step[0]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static. ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"mk-step__who"} -->
<p class="mk-step__who"><?php echo $matholek_step[1]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static. ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<?php endforeach; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
