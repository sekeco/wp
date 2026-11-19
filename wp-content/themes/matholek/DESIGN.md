---
name: Matholek
description: Bento teal dan emas yang bersih dan islami. Kartu bersudut 2rem di atas latar kabut, motif geometri emas tipis, Manrope tebal dengan aksen Arab Amiri.
colors:
  mist: "#F5F7F7"
  surface: "#FFFFFF"
  ink: "#0A3340"
  teal-50: "#EEF6F7"
  teal-100: "#D6EBEE"
  teal-500: "#1B7A8C"
  teal-700: "#0F4C5C"
  teal-900: "#0A3340"
  gold-100: "#FAF1D9"
  gold-400: "#D4A72C"
  gold-500: "#B88A12"
  dark-mist: "#071C22"
  dark-surface: "#0D2A33"
  dark-ink: "#E4EFF0"
typography:
  text: "Manrope 400–800, 16px / 1.5"
  arabic: "Amiri 400"
  display: "800, 3.75rem / 1, -0.025em (mobile 2.25rem / 1.05)"
  h2: "800, 3rem / 1, -0.025em (mobile 2.25rem / 2.5rem)"
  kicker: "700, 0.875rem / 1.25rem, gold-500, huruf kapital"
radius:
  card: "2rem"
  cta: "2.5rem"
  button: "1rem"
  tile: "0.75rem"
layout:
  container: "72rem, padding 1rem"
  breakpoints: "640 / 768 / 1024 px (Tailwind sm / md / lg)"
---

# Design

Sumber kebenaran visual adalah `reference/variasi-3d-bento-teal.html`. Setiap ukuran
di `style.css` adalah nilai Tailwind yang dipakai mockup.

## Aturan

- **Kartu**: sudut 2rem, tanpa bayangan dan border. Kontras datang dari warna latar
  (putih, teal-700 bermotif geometri, emas-400, emas-100).
- **Motif geometri** (`mk-geo`): bintang delapan emas 25% di ubin 48px, hanya di
  latar teal gelap.
- **Foto**: selalu `object-fit: cover` di dalam kartu; tanpa foto tampil gradasi
  teal-700 ke emas-400 (135°).
- **Overlay foto**: hero gradasi kiri ke kanan teal-900 90→50→0%; berita dari bawah
  teal-900 95→0%; kartu video teal-900 40%.
- **Tombol**: radius 1rem, tebal 700. Gaya `gold`, `glass` (putih 15% + blur 8px),
  `card` (latar kartu), dan `small` (px 1.25rem, py 0.625rem, 14px).
- **Teks redup**: ink 50/60/65/70% dengan `color-mix`.
- **Gerak**: hanya zoom foto berita 1.05 (500ms) dan fade slide 0.4s. Tidak ada
  animasi lain.

## Mode gelap

Mengganti variabel preset di `html[data-theme="dark"]`: mist, surface, ink, teal-50,
teal-100, gold-100, gold-500, plus aksen teks `#5EC0CF`. Kartu teal, emas, dan
teal-900 tetap sama di kedua mode.
