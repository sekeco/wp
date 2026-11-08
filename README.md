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
    style.css             mode gelap, scope tone, header pill, rail kiri, menu overlay
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
- **Pattern** — namespace `sekeco/*`, disusun dari blok komponen plugin, jadi
  tema dan plugin saling bergantung dan harus kompatibel (commit terpisah).
- **Menu** — core Navigation dengan overlay `sekeco-drawer` (panel samping, bawaan header)
  atau `sekeco-menu` (layar penuh); keduanya template part area *navigation-overlay*
  dan bisa dipilih di pengaturan blok Navigation. Menu tetap dikelola editor, tidak
  ada link hardcode. Klik area di luar panel menutup drawer.
- `customTemplates` di `theme.json` mendaftarkan semua template di luar hierarki
  bawaan WordPress agar bisa dipilih user: `page-no-title`, `page-wide`,
  `page-landing`, `page-canvas` (page) dan `form-canvas` (`sekeco_form`). Template
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
  gradasi brand; rail dan tombol melayang otomatis berganti ke tone gelap di atas
  footer, hero, dan kartu CTA.
- **Tombol primary** — pil solid (warna foreground halaman) dengan titik gradasi brand
  yang membesar mengisi pil saat hover (ide *Interactive Hover Button* Magic UI, CSS
  murni). Tidak ada glow maupun gerak mengikuti kursor.
- **Rail & header** — header pill dengan menu inline (core Navigation) + hamburger
  yang membuka drawer `sekeco-drawer`; rail kiri (toggle Dark/Light vertikal,
  *Scroll to top*, tombol cari) ada di part `footer`.

## 6. Plugin

| Kebutuhan | Implementasi |
| --- | --- |
| Konten Works | CPT `portfolio`, archive `/works/` |
| Konten Portfolio | CPT `product`, archive `/portfolio/` |
| Team | CPT `team`, archive `/team/` |
| Kategori | core `category` dipakai bersama ketiga CPT |
| Binding | `sekeco/meta` untuk field kustom di template |
| Komponen | 17 blok dinamis dalam tiga kategori inserter: *Sekeco: Layout* (`section`, `section-header`, `hero`, `cta`, `marquee`), *Sekeco: Content* (`statement`, `capabilities`, `process`, `faq`, `stats`, `project-grid`), *Sekeco: Utility* (`form`, `theme-toggle`, `search`, `scroll-top`) |
| Animasi | runtime GSAP (`src/motion/runtime.js`): hero, statement, parallax, counter, reveal; panel Motion untuk `core/group` & `core/cover` |
| Ikon | Solar Line Duotone via `bun run icons`; PHP `sekeco_icon()` |
| Filter | `sekeco/project-grid` dengan opsi *Category filter* |
| Form | CPT `sekeco_form` + `sekeco_submission` |

Modul dimuat oleh `sekeco_load_modules()`; file modul yang belum ada dilewati,
sehingga pengembangan bisa bertahap.

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
