<?php
/**
 * Title: Fasilitas
 * Slug: matholek/fasilitas
 * Categories: matholek, gallery
 * Description: Galeri bento lima ubin foto dengan label. Unggah foto asli ke tiap ubin; selama kosong, ubin tampil dengan gradasi teal ke emas.
 * Keywords: fasilitas, galeri, foto, masjid, perpustakaan
 * Viewport Width: 1280
 */

$matholek_tiles = array(
	array( 'tall', 'Masjid', 'Jamaah &amp; pengajian kitab' ),
	array( 'wide', 'Perpustakaan Kitab', '' ),
	array( 'tall', 'Laboratorium', 'IPA &amp; komputer' ),
	array( '', 'Ruang Kelas', '' ),
	array( '', 'Olahraga', '' ),
);
?>
<!-- wp:group {"tagName":"section","anchor":"fasilitas","className":"mk-fasilitas mk-wrap","layout":{"type":"default"}} -->
<section id="fasilitas" class="wp-block-group mk-fasilitas mk-wrap"><!-- wp:group {"className":"mk-head","layout":{"type":"default"}} -->
<div class="wp-block-group mk-head"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"mk-kicker"} -->
<p class="mk-kicker">FASILITAS</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"mk-h2"} -->
<h2 class="wp-block-heading mk-h2">Nyaman untuk ibadah &amp; belajar</h2>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"mk-tiles","layout":{"type":"default"}} -->
<div class="wp-block-group mk-tiles"><?php
foreach ( $matholek_tiles as $matholek_tile ) :
	$matholek_class = 'mk-tile mk-media' . ( $matholek_tile[0] ? ' mk-tile--' . $matholek_tile[0] : '' );
	?><!-- wp:group {"className":"<?php echo esc_attr( $matholek_class ); ?>","layout":{"type":"default"}} -->
<div class="wp-block-group <?php echo esc_attr( $matholek_class ); ?>"><!-- wp:image {"sizeSlug":"large","className":"mk-media__img"} -->
<figure class="wp-block-image size-large mk-media__img"><img alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"mk-tile__cap","layout":{"type":"default"}} -->
<div class="wp-block-group mk-tile__cap"><!-- wp:paragraph {"className":"mk-tile__name"} -->
<p class="mk-tile__name"><?php echo $matholek_tile[1]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static. ?></p>
<!-- /wp:paragraph --><?php if ( $matholek_tile[2] ) : ?>

<!-- wp:paragraph {"className":"mk-tile__note"} -->
<p class="mk-tile__note"><?php echo $matholek_tile[2]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static. ?></p>
<!-- /wp:paragraph --><?php endif; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<?php endforeach; ?></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
