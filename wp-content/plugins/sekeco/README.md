# Sekeco plugin

Companion plugin for the Sekeco block theme.

## Architecture

- `includes/content/` — CPT and taxonomy registration
- `includes/meta/` — post meta registration
- `includes/bindings/` — block bindings sources
- `includes/motion/` — motion extension for core Group/Cover
- `includes/blocks/` — PHP for gap blocks
- `src/` — block editor sources and build entry points

Modules are loaded by `sekeco_load_modules()` in `sekeco.php`. A module file
that does not exist yet is ignored, so phases can be implemented incrementally.

## Phase 0

- No custom Hero block.
- Hero layouts live in the theme as core Group/Columns patterns.
- Plugin contains only the bootstrap and module loader.

## Phase 1

- Motion extension for `core/group` and `core/cover`.
- Editor panel: Animation, Trigger, Duration, Delay.
- Saved content stays native core markup; motion classes/data/style are added
  by `render_block`.
- Frontend runtime uses IntersectionObserver + CSS transitions.

## Phase 2

- Works CPT: `portfolio` (admin label "Works"), using the core `category` taxonomy; archive at `/works/`.
- Portfolio CPT: `product` (admin label "Portfolio"), also using the core `category` taxonomy; archive at `/portfolio/`.
- Team CPT: `team` with role and social meta.
- Registered meta and native document settings panels.
- `sekeco/meta` block bindings source, with a singular-template fallback.
- Theme patterns: `sekeco/portfolio-grid` (Works), `sekeco/product-grid` (Portfolio), `sekeco/team-grid`.
- Theme templates: `archive-portfolio`, `single-portfolio`, `archive-product`, `single-product`, `archive-team`, `single-team`.

## Phase 3

- Generic `sekeco/slider` block.
- Manual InnerBlocks mode and query mode (product, portfolio, post).
- Responsive per-view, gap, arrow controls, keyboard navigation, and scroll-snap fallback.
- Variations: Manual slides, Product slider, Portfolio slider.

## Phase 4

- Generic `sekeco/filterable-query` block.
- Wraps a native Query Loop and adds taxonomy filter buttons.
- Client-side filtering with aria-pressed state; all items remain visible without JS.
- Portfolio filter variation and `sekeco/portfolio-filter-grid` theme pattern.
- Portfolio archive uses the filterable pattern.

## Phase 5 (forms)

- `sekeco_form` CPT with a field repeater (text, email, tel, url, number,
  date, textarea, select, radio, checkbox).
- `sekeco_submission` CPT stores label/value pairs plus IP, agent, and page URL.
- `sekeco/form` dynamic block embeds a form or a one-page survey.
- Forms are public at `/form/{slug}` and also available via `[sekeco_form id="…"]`.
- `form-canvas` custom template renders a form without header/footer (used by the survey).
- Fields support placeholder, help text, and half/full width; fields can be
  reordered and duplicated in the builder.
- Submissions can be exported as CSV from the form settings box.
- REST submission endpoint (`/sekeco/v1/forms/{id}/submit`) plus an
  `admin-post.php` fallback for visitors without JavaScript.
- Spam controls: honeypot, submit-time window, and per-IP rate limiting.
- Survey mode steps through numbered questions with transitions, a counter,
  and Next/Back navigation; without JS all questions render on one page.
- `page-canvas` custom template renders a page without header or footer.

## Build

```sh
bun install
bun run build
bun run lint:js
bun run lint:css
```
