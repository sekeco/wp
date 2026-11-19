# Sekeco & Matholek — Aturan Kerja

## Repository

- Satu repositori (monorepo) di root, berisi dua situs:
  - **Sekeco** (situs agensi): tema + plugin `sekeco`. Pattern tema memakai blok
    `sekeco/*`.
  - **Matholek** (landing page Perguruan Islam Mathali'ul Falah): tema + plugin
    `matholek`. Pattern tema memakai blok `matholek/*`. Tidak memakai plugin
    Sekeco; aktifkan salah satu pasangan saja saat development.
- Branch utama `main`. Tidak ada tag atau release.
- Yang dilacak git hanya kode proyek:
  - `wp-content/themes/sekeco/`
  - `wp-content/plugins/sekeco/`
  - `wp-content/themes/matholek/`
  - `wp-content/plugins/matholek/`
  - `compose.yml`, `.gitignore`, `.gitattributes`, `AGENTS.md`, `README.md`
- WordPress core (`wp-admin/`, `wp-includes/`, `wp-*.php`, `license.txt`,
  `readme.html`) berasal dari image Docker — jangan di-commit.
- Jangan pernah commit: `wp-content/uploads/`, `wp-content/upgrade/`,
  `wp-content/debug.log`, `.db/`, `node_modules/`, plugin pihak ketiga.

## Format commit

`type(scope): ringkasan imperatif`

Scope:

- `theme` — tema Sekeco
- `plugin` — plugin Sekeco
- `matholek-theme` — tema Matholek
- `matholek-plugin` — plugin Matholek
- `repo` — Docker, gitignore, aturan, tooling
- `docs` — dokumentasi

Type: `feat`, `fix`, `refactor`, `style`, `docs`, `chore`, `perf`, `test`.

Contoh:

```
feat(plugin): add autoresponder to form submissions
fix(theme): keep works filter aligned on mobile
chore(repo): ignore local database volume
```

Aturan:

- Satu commit = satu concern. Jangan mencampur perubahan tema dan plugin dalam
  satu commit, juga jangan mencampur Sekeco dan Matholek.
- Pesan dalam bahasa Inggris, ringkas, tanpa emoji.
- Body opsional, jelaskan **kenapa**, bukan mengulang **apa**.
- Jangan membuat tag/release.

## Sebelum commit

- Jika menyentuh `wp-content/plugins/sekeco/src/` atau
  `wp-content/plugins/matholek/src/` (jalankan di folder plugin itu):
  `bun run lint:js && bun run lint:css && bun run build`, lalu ikutkan `build/`.
- Jika menyentuh PHP: `php -l` pada file yang berubah.
- Pastikan tidak ada `wp-content/debug.log` baru dan tidak ada data uji tertinggal.
- Jalankan `git status`, lalu review file yang di-stage sebelum commit.

## Yang dilarang

- `git add -A` atau `git add .` dari root tanpa memeriksa daftar file.
- `git commit -am` untuk perubahan campuran.
- Force push ke `main`.
- Menulis kredensial atau secret ke file yang dilacak. Kredensial lokal di
  `compose.yml` hanya untuk development.

## Versi

- Tema: `Version:` di `wp-content/themes/sekeco/style.css`.
- Plugin: header `Version:` dan `SEKECO_VERSION` di
  `wp-content/plugins/sekeco/sekeco.php`.
- Tema Matholek: `Version:` di `wp-content/themes/matholek/style.css`.
- Plugin Matholek: header `Version:` dan `MATHOLEK_VERSION` di
  `wp-content/plugins/matholek/matholek.php`.
- Naikkan versi setiap perubahan perilaku. Tanpa tag/release.
