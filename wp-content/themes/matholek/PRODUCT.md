# Product

## Platform

web

## Users

Calon santri dan wali santri, alumni (mutakhorrijin), dan lembaga mitra yang ingin
mengenal Perguruan Islam Mathali'ul Falah (PIM), Kajen, Pati: sejarahnya, kurikulum
salafiyah, fasilitas, kabar terbaru, dan cara mendaftar. Mayoritas pengunjung dari
Indonesia, banyak lewat ponsel.

## Product Purpose

Satu landing page yang memperkenalkan PIM dan mengarahkan ke pendaftaran santri baru.
Keberhasilan: pengunjung menemukan info pendaftaran atau menghubungi panitia.

## Operating Context

Diedit oleh pengurus non-developer di Site Editor WordPress. Berita ditulis sebagai
Posts. Tidak ada form internal; pendaftaran lewat tautan (halaman info, WhatsApp
panitia, atau formulir eksternal).

## Content Rules

- Semua teks UI berbahasa Indonesia; frasa Arab hanya sebagai aksen.
- Tidak boleh mengarang angka, testimoni, logo mitra, atau foto. Bagian yang datanya
  belum ada menyembunyikan diri sampai diisi.
- Foto di mockup berasal dari Unsplash dan **bukan** foto PIM; jangan dibundel ke tema.
- Fakta yang dipakai pattern: berdiri 1912, enam jenjang, rasio 70 : 30, urutan
  masyayikh 1912 hingga kini, nilai Sholih-Akrom.
- Testimoni hanya dari alumni yang sudah memberi izin.

## Design Commitment

Tampilan meniru persis `reference/variasi-3d-bento-teal.html` (bento teal + emas,
Manrope + Amiri, motif geometri). Tambahan yang disepakati: mode gelap dengan toggle,
ikon Solar pengganti emoji, aksesibilitas slider tanpa tombol pause.

## Accessibility

Navigasi papan ketik, fokus terlihat, `prefers-reduced-motion` mematikan autoplay
slider dan scroll halus. Catatan: beberapa teks redup di mockup (teks 60% dan label
emas) berada di bawah kontras WCAG AA di mode terang; dipertahankan demi kesamaan
dengan mockup sampai diputuskan lain.
