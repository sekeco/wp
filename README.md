# Sekeco — WordPress

Monorepo untuk situs **Sekeco**: satu **block theme** (`Sekeco`) dan satu
**plugin pendamping** (`Sekeco`). Semua tampilan dibangun dengan blok native
Gutenberg — tanpa page builder pihak ketiga.

| Bagian | Lokasi | Versi |
| --- | --- | --- |
| Tema | `wp-content/themes/sekeco/` | 2.5.0 |
| Plugin | `wp-content/plugins/sekeco/` | 0.19.0 |
| WordPress | image Docker `wordpress:latest` (diuji 7.1.4) | — |
| PHP | container | 8.3 |
| Bun | `packageManager` di plugin | 1.4.4 |

Kontrak minimum: WordPress **6.7+**, PHP **7.4+**.

Repo ini juga memuat situs kedua, **Matholek** (landing page Perguruan Islam
Mathali'ul Falah): tema dan plugin `matholek`, terpisah dari Sekeco. Lihat
[wp-content/themes/matholek/README.md](wp-content/themes/matholek/README.md).

---

## 1. Persyaratan

- Docker Desktop (atau Docker Engine + Compose v2)
- Bun 1.4+ — hanya untuk build aset plugin
- Port **80** dan **8080** bebas

## 2. Instalasi lokal

```sh
git clone git@github.com:sekeco/wp.git
cd wp
docker compose up -d
```

Buka `http://localhost` dan selesaikan instalasi WordPress (judul situs, user
admin). Lalu:

1. **Appearance → Themes** → aktifkan **Sekeco**.
2. **Plugins** → aktifkan **Sekeco**.
3. **Settings → Permalinks** → pilih *Post name* → **Save** (memastikan rewrite
   CPT ter-flush).
4. Set halaman depan: **Settings → Reading** → *A static page*.

Database ada di volume `.db/` dan **tidak** dilacak git. Clone baru = instalasi
kosong: konten, media, menu, dan form harus dibuat ulang (atau impor dari
database lokal Anda sendiri). Yang dilacak git hanya kode tema dan plugin.

Adminer: `http://localhost:8080` — server `db`, user `root`, password
`password` (kredensial development, lihat `compose.yml`).

> Container **tidak** menyertakan WP-CLI. Gunakan pola berikut untuk perintah
> PHP satu baris:
>
> ```sh
> docker compose exec -T wordpress php -r 'require "/var/www/html/wp-load.php"; …'
> ```

## 3. Development

Semua tooling ada di plugin (`wp-content/plugins/sekeco`):

```sh
cd wp-content/plugins/sekeco
bun install

bun run start      # watch build (webpack)
bun run build      # build produksi -> build/
bun run format     # format JS/CSS/JSON
bun run lint:js
bun run lint:css
bun run icons       # hasilkan ikon Solar (JSON, CSS admin, CSS tema)
```

`build/` **di-commit** karena image produksi tidak menjalankan Bun. Setelah
mengubah apa pun di `src/`, jalankan lint + build dan ikutkan hasil `build/`.

Aset admin form builder (`assets/admin/`) dimuat langsung tanpa build.

Pemeriksaan PHP:

```sh
php -l wp-content/plugins/sekeco/includes/forms/render.php
```

## 4. Struktur repositori

```text
compose.yml               WordPress + MariaDB + Adminer
AGENTS.md                 aturan kerja, format commit, larangan
README.md                 dokumen ini
wp-content/
  themes/sekeco/
    theme.json            design token: warna, tipografi, spacing, radius
    style.css             mode gelap, scope tone, header pill, menu overlay
    functions.php         skrip mode (terang/gelap), font preload, hook tema
    templates/            template block (front-page, archive-*, single-*, …)
    parts/                header, footer, sekeco-drawer + sekeco-menu (navigation overlay)
    patterns/             section beranda (home-*), cta, contact, post-grid
    assets/fonts/         DM Sans (teks) + Bricolage Grotesque (judul), woff2 variabel
    assets/images/        sekeco-mark.svg (logo gradasi, favicon bawaan)
    assets/css/           solar-icons.css (dihasilkan, jangan diedit)
  plugins/sekeco/
    sekeco.php            header plugin + module loader
    includes/             PHP: content, meta, bindings, motion, blocks, forms, icons
    scripts/              build-icons.mjs (ikon Solar Line Duotone)
    src/                  sumber blok, runtime GSAP & panel editor
    build/                hasil build (di-commit)
    assets/admin/         CSS/JS form builder klasik
```

## 5. Tema

- **theme.json v3** — palette brand dari logo: `accent` (biru `#4D68E4`), `violet`
  (`#A33FBD`), `red` (`#FD065A`), `cyan` (`#09C4F7`), plus `base`, `contrast`,
  `surface`, `muted`, `border`, `ink`, `paper`; gradient preset `brand` dan
  `brand-soft` (dipakai tombol); spacing `20`–`80`; judul Bricolage Grotesque,
  teks DM Sans.
- **Mode terang/gelap** — `html[data-theme]` diatur skrip di `<head>` (pilihan
  tersimpan; kunjungan pertama selalu gelap, seperti referensi). Mode gelap menimpa variabel preset
  `--wp--preset--color--*`, jadi semua blok ikut. Scope `.sk-tone--ink|paper|accent`
  memaksa satu ground tertentu untuk section/hero/CTA.
- **Template** — `front-page`, `home`, `index`, `page`, `single`, `single-portfolio`, `single-product`,
  `single-team`, `single-sekeco_form`, `form-canvas`, `archive`,
  `archive-portfolio`, `archive-product`, `archive-team`, `search`, `404`.
- **Style variation** — `styles/orchid.json` dan `styles/ocean.json` (Site Editor →
  Styles → Browse styles). Keduanya menukar preset `accent`, gradient brand, dan
  token `--sk-accent`/`--sk-grad` yang di-hardcode `style.css`, lewat `styles.css`
  dengan spesifisitas tinggi agar menang atas scope terang, gelap, dan tone. Shader
  Hero/CTA tetap memakai palet brand.
- **404 dan pencarian kosong** — template `404` dan pesan *no results* menampilkan
  kotak cari dan empat Works terbaru (`sekeco/project-grid`, layout `list`).
- **Satu tata bahasa, lima tujuan** — halaman di bawah homepage memakai kelas bersama
  (`sk-wrap`, `sk-head`, `sk-story`, dari `assets/css/pages.css`) tetapi tiap template
  dibangun untuk pekerjaannya sendiri. **Works single**: sampul penuh, ledger fakta
  (blok `sekeco/facts`) menempel di samping cerita, karya sebelumnya/berikutnya sebagai
  jalan keluar. **Portfolio single**: lembar produk (teks dan media bersisian, strip fakta,
  daftar produk lain). **Team single**: profil dengan potret yang menempel. **Journal
  single**: satu kolom baca, byline, garis progres baca (CSS scroll-driven), tiga judul
  "Keep reading" (Query dengan `"sekecoRelated": true`). **Arsip**: Works mosaik berfilter,
  Portfolio baris, Team roster, Journal indeks (entri terbaru memimpin); search berupa
  daftar dengan label jenis (binding `sekeco/post-type`). Indeks Journal (`home.html`) baru
  tampil bila Settings → Reading → Posts page diatur ke halaman Journal.
  Judul `Nama: detail` dipecah di single (`sk-split-title`), `post-excerpt` berkelas
  `sk-lead` hanya tampil bila excerpt ditulis manual, dan penulis yang namanya hanya login
  (mis. `admin`) disembunyikan.
- **Pattern** — namespace `sekeco/*`, disusun dari blok komponen plugin, jadi
  tema dan plugin saling bergantung dan harus kompatibel (commit terpisah).
- **Menu** — core Navigation dengan overlay `sekeco-drawer` (panel samping, bawaan header)
  atau `sekeco-menu` (layar penuh); keduanya template part area *navigation-overlay*
  dan bisa dipilih di pengaturan blok Navigation. Menu tetap dikelola editor, tidak
  ada link hardcode. Klik area di luar panel menutup drawer.
- `customTemplates` di `theme.json` mendaftarkan semua template di luar hierarki
  bawaan WordPress agar bisa dipilih user: `page-no-title`, `page-wide`,
  `page-landing`, `page-canvas` (page) dan `form-canvas` (`sekeco_form`). Template
  arsip dan single CPT (`archive-portfolio`, `archive-product`, `archive-team`,
  `single-*`) juga didaftarkan dengan `"postTypes": []` supaya judulnya rapi di Site
  Editor tanpa muncul di dropdown template halaman. Template
  hierarki (`front-page`, `single-portfolio`, `archive-team`, …) otomatis terdaftar.
- **Interaksi pointer** — diport dari React Bits dan Magic UI, ditulis ulang dengan
  JS murni + GSAP (`src/motion/interactions.js`): *Spotlight Card* (cahaya lembut
  netral + cahaya tepi yang mengikuti kursor, class `.sk-glow` pada kartu proyek,
  capability, process step), *Glare Hover* (kilau menyapu kartu foto), *Tilted Card*
  (miring 3D), dan *Border Beam*
  (cahaya mengitari tepi kartu CTA, CSS murni). Hanya untuk pointer halus;
  reduced-motion hanya menyisakan spotlight.
- **Pencarian layar penuh** — blok `sekeco/search` (tombol melayang kiri bawah, juga `/`
  atau Ctrl/Cmd+K): `<dialog>` native dengan hasil live dari REST `sekeco/v1/search`,
  rekomendasi acak di bawah input, dan masonry Works/Portfolio/Journal acak di sisi
  kanan (desktop) dari `sekeco/v1/suggest`.
- **Scroll to top** — blok `sekeco/scroll-top` di kanan bawah dengan cincin progres
  gradasi brand; tombol melayang otomatis berganti ke tone gelap di atas
  footer, hero, dan kartu CTA.
- **Tombol primary** — pil solid (warna foreground halaman) dengan titik gradasi brand
  yang membesar mengisi pil saat hover (ide *Interactive Hover Button* Magic UI, CSS
  murni). Tidak ada glow maupun gerak mengikuti kursor.
- **Header & tombol melayang** — header pill dengan menu inline (core Navigation) +
  hamburger yang membuka drawer `sekeco-drawer`. Di desktop, part `footer` memuat
  toggle Dark/Light berupa ikon saja (`theme-toggle` dengan `floating`) di atas
  tombol cari kiri bawah, serta *Scroll to top* di kanan bawah. Pergantian tema
  memakai View Transitions: *Sunrise*, tema baru naik dari bawah di balik garis cakrawala
  melengkung dengan glow tipis (hangat ke terang, dingin ke gelap), digerakkan GSAP
  lewat variabel `--sk-h` (`src/motion/theme-switch.js`);
  tanpa View Transitions atau dengan reduced motion, tema berganti langsung. Di
  layar kecil toggle ada di drawer.

## 6. Plugin

| Kebutuhan | Implementasi |
| --- | --- |
| Konten Works | CPT `portfolio`, archive `/works/` |
| Konten Portfolio | CPT `product`, archive `/portfolio/` |
| Team | CPT `team`, archive `/team/` |
| Kategori | core `category` dipakai bersama ketiga CPT |
| Binding | `sekeco/meta` untuk field kustom di template |
| Komponen | 19 blok dinamis dalam tiga kategori inserter: *Sekeco: Layout* (`section`, `section-header`, `hero`, `cta`, `marquee`), *Sekeco: Content* (`statement`, `capabilities`, `process`, `faq`, `testimonials`, `stats`, `project-grid`), *Sekeco: Utility* (`form`, `theme-toggle`, `search`, `scroll-top`) |
| Animasi | runtime GSAP (`src/motion/runtime.js`): hero, statement, parallax, counter, reveal; panel Motion untuk `core/group` & `core/cover` |
| Ikon | Solar Line Duotone via `bun run icons`; PHP `sekeco_icon()` |
| Filter | `sekeco/project-grid` dengan opsi *Category filter* |
| Form | CPT `sekeco_form` + `sekeco_submission` |

Modul dimuat oleh `sekeco_load_modules()`; file modul yang belum ada dilewati,
sehingga pengembangan bisa bertahap.

### SEO dan data terstruktur

- `includes/seo/meta.php` mencetak `meta description`, Open Graph, dan Twitter card
  (canonical, robots, dan judul tetap dari core). Deskripsi diambil dari excerpt atau
  isi, ditambah peran untuk Team; gambar dari featured image, lalu gambar default,
  lalu ikon situs. Fallback diatur di **Settings → Sekeco SEO** (deskripsi, gambar
  bagikan 1200 × 630, handle X). Ubah per halaman lewat filter `sekeco_seo_context`.
- `includes/seo/schema.php` mencetak satu `@graph` JSON-LD: `Organization` dan
  `WebSite` di semua halaman; `CreativeWork` (Works), `SoftwareApplication`
  (Portfolio), `Person` (Team, tanpa email), `Article` (Journal); satu `FAQPage` dari
  blok FAQ di halaman itu. Filter: `sekeco_schema_graph`.
- Tidak ada tag di 404, feed, embed, hasil cari, atau saat situs diatur *discourage
  search engines*.

### Gambar dan performa

- `includes/media.php`: ukuran turunan upload baru dibuat sebagai AVIF (JPEG) atau WebP
  (PNG); file asli tetap. Gambar lama baru ikut setelah di-regenerate. Filter
  `sekeco_image_output_formats` untuk mengubah atau mematikan.
- Kartu `project-grid` mengirim atribut `sizes` sesuai layout (grid, mosaic, reel,
  crew) dan `fetchpriority="high"` untuk kartu eager.
- Shader Hero/CTA: setengah resolusi dan 30fps, jeda di luar layar, satu frame untuk
  reduced-motion dan Data Saver; ponsel serta perangkat ≤ 4 GB RAM atau ≤ 4 core turun
  ke 35% resolusi dan 20fps. Shader gagal compile atau context WebGL hilang membuat
  blok kembali ke ground normalnya.

### Properti, katalog, dan daftar isi

- **Properti Works** (panel *Work details*): Client, Year, Role, Project URL, plus Services,
  Industry, Duration, Tech stack, Team (nama dipisah koma; nama yang cocok dengan entri
  Team menjadi tautan), Outcome (kalimat sendiri, bukan angka), dan Case study URL.
- **Properti Portfolio** (panel *Portfolio details*): Type (SaaS, Plugin, Tool, Library, App,
  Template, Other; daftar bisa diubah lewat filter `sekeco_product_types`), Status, Version,
  Platform, Stack, Year, Repository, dan tautan Website, Docs, Demo, Download, Changelog.
- Semua properti tampil lewat blok **`sekeco/facts`** (layout ledger, strip, atau links) dan
  field kosong disembunyikan. Tombol aksi di halaman produk memakai binding `sekeco/meta`,
  jadi tombol tanpa tautan tidak tampil.
- **Project grid layout `catalog`**: satu baris per produk (nama, satu kalimat dari excerpt
  atau paragraf pertama, jenis, status, tahun), filter jenis berhitung, dan kolom cari.
  Produk tanpa Type masuk kelompok *Other*; selama semuanya satu kelompok, filter diganti
  hitungan "N products". Batas query blok ini 200 entri.
- **Blok `sekeco/toc`** (*On this page*): daftar isi dari heading H2 dan H3 entri, sticky di
  layar lebar dan disclosure tertutup di layar kecil, menandai heading yang sedang dibaca,
  dan menyembunyikan diri bila heading kurang dari tiga. `includes/blocks/toc.php` memberi
  `id` pada heading di dalam Post Content yang belum punya anchor.
- Menu situs (termasuk Journal) hidup di database sebagai entri Navigation, bukan di repo;
  hapus atau tambah item lewat Site Editor.

### Form

- Builder berupa meta box di editor klasik (bukan block editor): kartu field
  yang bisa di-drag, collapse, duplikat, dan diurutkan.
- Form publik di `/form/{slug}/` dan bisa disematkan lewat blok `sekeco/form`
  atau shortcode `[sekeco_form id="123"]`.
- Dua mode: **form** (satu halaman) dan **survey** (satu pertanyaan per layar,
  dengan counter, progress bar, dan transisi).
- Kirim via REST `POST /wp-json/sekeco/v1/forms/{id}/submit`; fallback
  `admin-post.php` untuk pengunjung tanpa JavaScript.
- Anti-spam: honeypot, jendela waktu kirim (2 detik–12 jam), rate limit
  5 kiriman / 10 menit per IP.
- Entri tersimpan sebagai `sekeco_submission` dan bisa diekspor CSV dari meta
  box form.

### Routing khusus

| URL | Tujuan |
| --- | --- |
| `/products/…` | `/portfolio/…` |
| `/portfolio/{slug}/` | `/works/{slug}/` |
| 404 yang cocok slug form | `/form/{slug}/` |

## 7. Konvensi kerja

Baca `AGENTS.md`. Ringkasnya:

- Satu commit = satu concern, jangan campur tema dan plugin.
- Format: `type(scope): ringkasan imperatif` — scope `theme`, `plugin`, `repo`,
  `docs`; pesan bahasa Inggris.
- Naikkan versi setiap perubahan perilaku: tema di `style.css`, plugin di
  `sekeco.php` (header + `SEKECO_VERSION`).
- Jangan commit `wp-content/uploads/`, `.db/`, `node_modules/`, plugin pihak
  ketiga, atau `wp-content/debug.log`.

## 8. Verifikasi cepat

```sh
# versi & status situs
docker compose exec -T wordpress php -r 'require "/var/www/html/wp-load.php"; echo get_bloginfo("version"), " ", wp_get_theme()->get("Version"), "\n";'

# permalink CPT
curl -s -o /dev/null -w '%{http_code}\n' http://localhost/works/
curl -s -o /dev/null -w '%{http_code}\n' http://localhost/portfolio/
curl -s -o /dev/null -w '%{http_code}\n' http://localhost/team/

# tidak ada error PHP saat request
ls wp-content/debug.log 2>/dev/null || echo "bersih"
```

Skrip smoke (read-only, tidak membuat konten) memeriksa arsip, single, redirect legacy,
tag SEO dan JSON-LD, REST, 404, dan saran di pencarian kosong:

```sh
wp-content/plugins/sekeco/scripts/smoke.sh            # http://localhost
wp-content/plugins/sekeco/scripts/smoke.sh https://staging.example.com
```

## 9. Troubleshooting

| Gejala | Penyebab & solusi |
| --- | --- |
| Template/pattern baru tidak muncul | Cache tema: `wp_get_theme()->cache_delete()` lalu `delete_transient( 'theme_files' )` |
| CPT 404 setelah ubah slug | Flush rewrite: simpan **Settings → Permalinks**, atau hapus option `sekeco_rewrite_version` |
| Perubahan `theme.json` tidak terlihat | Customization user di Site Editor menang atas default tema |
| Submit form balas *session expired* | Sudah diperbaiki di 0.13.0: request REST mengirim `X-WP-Nonce` untuk user login. Pastikan `build/` terbaru |
| Submit langsung "sukses" tapi tidak tersimpan | Jendela waktu 2 detik (anti-bot). Isi form secara normal |
| Blok jadi *invalid* setelah ubah markup | Markup tersimpan berubah tanpa `deprecated`; tambahkan deprecation, jangan hapus atribut |
| Email notifikasi tidak terkirim | Container tidak punya SMTP; submission tetap tersimpan |
