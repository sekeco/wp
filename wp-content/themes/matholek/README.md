# Matholek — WordPress

Landing page satu halaman untuk **Perguruan Islam Mathali'ul Falah**, Kajen, Pati:
satu **block theme** (`matholek`) dan satu **plugin pendamping** (`matholek`).
Tampilan meniru persis mockup `reference/variasi-3d-bento-teal.html`, tetapi setiap
teks, foto, dan tautan bisa diedit di Gutenberg (Site Editor).

| Bagian | Lokasi | Versi |
| --- | --- | --- |
| Tema | `wp-content/themes/matholek/` | 0.1.0 |
| Plugin | `wp-content/plugins/matholek/` | 0.1.0 |

Kontrak minimum: WordPress **6.7+**, PHP **7.4+**. Tema membutuhkan plugin
(pattern memakai blok `matholek/*`). Plugin Sekeco **tidak** dipakai.

Lihat juga [PRODUCT.md](PRODUCT.md) (untuk siapa dan aturan konten) dan
[DESIGN.md](DESIGN.md) (token dan aturan visual).

## 1. Instalasi lokal

Memakai stack Docker yang sama dengan Sekeco (`compose.yml` di root):

1. `docker compose up -d`, buka `http://localhost`.
2. **Appearance → Themes** → aktifkan **Matholek**.
3. **Plugins** → aktifkan **Matholek**, nonaktifkan **Sekeco** (CPT dan blok
   Sekeco tidak dipakai di sini).
4. **Settings → General → Site Language** → *Bahasa Indonesia* (nama bulan berita
   jadi "Okt", bukan "Oct").
5. **Settings → Reading**: biarkan *Your latest posts*, atau pakai halaman statis
   dan atur *Posts page* ke halaman "Berita" (tujuan tombol "Semua →").

## 2. Development

Tooling ada di plugin:

```sh
cd wp-content/plugins/matholek
bun install
bun run build      # blok -> build/ (di-commit)
bun run lint:js
bun run lint:css   # memeriksa themes/matholek/style.css
bun run icons      # ikon Solar -> themes/matholek/assets/css/icons.css
bun run fonts      # Manrope + Amiri woff2 -> themes/matholek/assets/fonts/
```

## 3. Tema

- `theme.json` v3: palet dari mockup (`mist`, `surface`, `ink`, `teal-50…900`,
  `gold-100…500`, `white`), font Manrope (teks) dan Amiri (aksen Arab), self-host.
- `style.css`: semua ukuran adalah nilai Tailwind yang dipakai mockup, ditulis ulang
  sebagai CSS biasa dengan kelas `mk-*`. Kelas ada di blok core (Group, Paragraph,
  Heading, Image, Buttons, Query), jadi isi bisa diedit sementara tata letak tetap.
- Template: `front-page` (landing page), `home` (indeks berita), `singular`
  (satu berita atau halaman), `index` (arsip/fallback), `404`.
- Parts: `header` (merek, menu anchor, toggle, tombol Pendaftaran), `footer`.
- Pattern (`patterns/`): `hero`, `riwayat`, `mitra`, `kurikulum`, `testimoni`,
  `fasilitas`, `berita`, `daftar`, `footer`.
- **Mode terang/gelap**: kunjungan pertama terang; pilihan tersimpan
  (`localStorage` `matholek-theme`). Toggle di header mulai 1024 px; di bawahnya
  toggle melayang di kiri bawah supaya header tetap sama dengan mockup.
- **Konten kosong menyembunyikan diri** (filter `render_block` di `functions.php`):
  - `mk-hide-empty`: blok hilang bila elemen `mk-key` di dalamnya kosong, atau bila
    tidak ada teks maupun media sama sekali.
  - `mk-needs-content`: section hilang bila tidak ada anak `mk-hide-empty` yang
    tersisa (pita mitra tanpa mitra, berita tanpa post).
- **Usia otomatis**: "114 th" dan "114 Tahun mengabdi" di-bind ke `matholek/since`
  (tahun sekarang − 1912), jadi tidak perlu diedit setiap tahun.
- Ikon bidang kurikulum: kelas `mk-icon--<nama>` di kartu (Advanced → Additional
  CSS class). Nama yang tersedia ada di `plugins/matholek/scripts/build-icons.mjs`.

## 4. Plugin

| Kebutuhan | Implementasi |
| --- | --- |
| Donat 70 : 30 | blok `matholek/ratio` |
| Tombol video | blok `matholek/video` (URL kosong: tombol tetap tampil sebagai hiasan) |
| Testimoni | `matholek/testimonials` + `matholek/testimonial` (autoplay 6 detik, berhenti saat hover/fokus, mati untuk reduced motion) |
| Toggle terang/gelap | blok `matholek/theme-toggle` |
| Usia dari tahun | binding `matholek/since` (`year`, `suffix`) |
| SEO | meta description, Open Graph, Twitter card; **Settings → SEO Matholek** |
| Data terstruktur | JSON-LD `EducationalOrganization`, `WebSite`, `Article` |
| Gambar | ukuran turunan AVIF/WebP untuk upload baru |

Tidak ada CPT Works/Portfolio/Team, form builder, pencarian, maupun motion GSAP.
Pendaftaran memakai tautan biasa di tombol (halaman info, WhatsApp, atau formulir
eksternal).

## 5. Mengisi konten

Semua dari **Appearance → Editor → Templates → Front Page**:

- Foto hero, kartu video, dan lima ubin fasilitas: pilih blok Image di dalam kartu
  lalu unggah. Selama kosong, kartu tampil dengan gradasi teal ke emas.
- Angka "Mutakhorrijin" dan "Capaian prestasi": ketik di paragraf angka; selama
  kosong, kartunya tidak tampil.
- Mitra: isi ubin dengan nama atau ganti paragrafnya dengan blok Image logo.
- Testimoni: hanya kutipan alumni yang sudah memberi izin.
- Tombol "Info Pendaftaran" dan "WhatsApp Panitia": isi tautannya.
- Berita: tulis di **Posts**; kategori tampil sebagai label.

## 6. Uji tampilan

Bandingkan `http://localhost/` dengan
`http://localhost/wp-content/themes/matholek/reference/variasi-3d-bento-teal.html`
di lebar yang sama, dengan konten yang sama seperti mockup. Selisih yang disengaja:

- toggle terang/gelap (tambahan, tidak menggeser elemen lain);
- emoji kurikulum diganti ikon Solar;
- slider memberi `inert` pada slide tersembunyi dan tombol berlabel.
