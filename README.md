# Sekeco — WordPress

Monorepo untuk situs **Sekeco**: satu **block theme** (`Sekeco`) dan satu
**plugin pendamping** (`Sekeco`). Semua tampilan dibangun dengan blok native
Gutenberg — tanpa page builder pihak ketiga.

| Bagian | Lokasi | Versi |
| --- | --- | --- |
| Tema | `wp-content/themes/sekeco/` | 1.6.1 |
| Plugin | `wp-content/plugins/sekeco/` | 0.13.0 |
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
    theme.json            design token: warna, tipografi, spacing, shadow
    style.css             header tema + CSS progressive enhancement
    functions.php         hook tema (section kosong, tombol tanpa URL)
    templates/            template block (front-page, archive-*, single-*, …)
    parts/                header, footer
    patterns/             pola siap pakai (hero, stats, grid, cta, …)
    styles/               style variation: midnight, sunrise
    assets/fonts/         Geist + Bricolage Grotesque (woff2)
  plugins/sekeco/
    sekeco.php            header plugin + module loader
    includes/             PHP: content, meta, bindings, motion, blocks, forms
    src/                  sumber blok & panel editor
    build/                hasil build (di-commit)
    assets/admin/         CSS/JS form builder klasik
```

## 5. Tema

- **theme.json v3** — palette (`base`, `contrast`, `surface`, `accent`,
  `accent-2`, `muted`, `border`), gradient, spacing `20`–`80`, radius, shadow
  `soft`/`glow`, font family `sans`/`display`/`mono`.
- **Template** — `front-page`, `home`, `index`, `page`, `page-no-title`,
  `page-canvas`, `single`, `single-portfolio`, `single-product`,
  `single-team`, `single-sekeco_form`, `form-canvas`, `archive`,
  `archive-portfolio`, `archive-product`, `archive-team`, `search`, `404`.
- **Pattern** — semua memakai namespace `sekeco/*`, jadi tema dan plugin saling
  bergantung dan harus di-commit bersama.
- `customTemplates` di `theme.json` hanya mendaftarkan template yang boleh
  dipilih user (`page-no-title`, `page-canvas`, `form-canvas`). File di
  `templates/` otomatis terdaftar.

## 6. Plugin

| Kebutuhan | Implementasi |
| --- | --- |
| Konten Works | CPT `portfolio`, archive `/works/` |
| Konten Portfolio | CPT `product`, archive `/portfolio/` |
| Team | CPT `team`, archive `/team/` |
| Kategori | core `category` dipakai bersama ketiga CPT |
| Binding | `sekeco/meta` untuk field kustom di template |
| Animasi | motion (transisi + delay) pada `core/group` & `core/cover` |
| Slider | blok `sekeco/slider` (manual & query mode) |
| Filter | blok `sekeco/filterable-query` (membungkus Query Loop) |
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
