=== Sekeco ===
Contributors: sekeco
Tags: blocks, gutenberg, portfolio, product, team, block theme
Requires at least: 6.7
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.13.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Native content models and block enhancements for the Sekeco theme.

== Description ==

Sekeco is the companion plugin for the Sekeco block theme. It is built
core-first:

* Content models use native WordPress APIs (CPT, taxonomy, post meta).
* Display uses core Query Loop and block bindings.
* Motion is applied to core Group/Cover blocks.
* Custom blocks are reserved for genuine gaps: filterable queries and sliders.

== Current status ==

Phase 0:

* Plugin bootstrap and module loader.
* Hero is no longer a custom block; hero layouts now live in the Sekeco theme
  as core Group/Columns patterns.

Phase 1:

* Motion extension for core Group and Cover blocks.
* Motion controls: animation, trigger, duration, and delay.
* Motion is applied at render time, so saved content remains native core markup.
* Frontend reveal uses IntersectionObserver + CSS transitions and respects
  reduced motion.

Phase 2:

* Portfolio post type with category and service taxonomies.
* Works and Portfolio share the core Category taxonomy with Posts, plus a Team post type.
* Registered meta for portfolio (client/year/role/URL), product (URL/status/stack/year/repo), and team (role/email/socials).
* Native document settings panels for editing the meta fields.
* Block binding source for registered meta, including singular templates.
* Portfolio, Product, and Team Query Loop patterns.
* Archive and single templates in the Sekeco theme.

Phase 3:

* Generic Slider block (`sekeco/slider`).
* Manual slides mode using InnerBlocks.
* Query mode for portfolio items, works, and posts.
* Responsive slides-per-view, gap, arrow controls, keyboard navigation, and
  a scroll-snap fallback without JavaScript.
* Variations: Manual slides, Portfolio slider, Works slider.

Phase 4:

* Generic Filterable Query block (`sekeco/filterable-query`).
* Wraps a native Query Loop and adds taxonomy filter buttons.
* Client-side filtering with native buttons, aria-pressed state, and a
  no-JavaScript fallback that shows all items.
* Portfolio filter variation with a prebuilt Query Loop.
* Theme pattern `sekeco/portfolio-filter-grid` used by the Portfolio archive.

== Changelog ==

= 0.13.0 =
* Front-end form submissions from a logged-in session no longer fail with
  "Your session expired". The REST request now sends `X-WP-Nonce` when the
  visitor is signed in, so WordPress authenticates the cookie before the form
  nonce is checked.
* Form builder field cards now lay their controls out in a wrapping row instead
  of a table, and every field can be collapsed to a single header line.
* Removed the underlined text decoration from the field action buttons.

= 0.12.0 =
* The form builder now uses native WordPress admin components: form-table,
  standard inputs, buttons, and dashicons. The custom Google Forms skin was
  removed.

= 0.11.0 =
* Form editing no longer uses the block editor. The classic screen now has a
  Google Forms-style builder: draggable cards, option rows, required switch,
  type picker, preview button, and form description at the top.

= 0.10.0 =
* Forms now have public URLs at /form/{slug}, a single form template, and a
  "View Form" row action in the admin list.
* Enriched the form builder: checkbox group and time fields, placeholder, help
  text, half/full width, reorder and duplicate actions, and one-option-per-line
  options.
* Added form description, redirect-after-submit, a [sekeco_form] shortcode,
  CSV export, and an entries column on the forms list.

= 0.9.0 =
* Renamed the client work post type to Works and the internal product post type
  to Portfolio, across admin labels, frontend copy, and URLs (/works/ and
  /portfolio/). Old product and work URLs redirect.
* Removed the duplicate Categories admin submenus under Works and Portfolio;
  categories are managed once under Posts.

= 0.8.0 =
* Portfolio and Product now use the core Category taxonomy, so one set of
  categories covers Work, Products, and the Journal.
* Category archives include custom post types, and filterable queries only list
  categories that actually contain items of the queried post type.
* One-page surveys now step through questions with transitions, a counter, and
  Next/Back navigation.
* Added the `page-canvas` custom template for full-screen pages without header
  or footer.

= 0.7.0 =
* Added native Forms and one-page Surveys: form CPT, submission CPT, field
  repeater, REST submission endpoint, no-JS fallback, honeypot and rate limit,
  email notifications, and the `sekeco/form` embed block.
* The contact page now uses the native form instead of an embedded iframe.

= 0.6.0 =
* Replaced the Testimonial content model with Products: Sekeco's own
  internal platforms, tools, and experiments.
* Added the Team content model with role, email, and social profile meta.
* Slider variations now cover Products instead of Testimonials.

= 0.5.2 =
* Slider slides now use border-box sizing, so a three-per-view track fits its
  container instead of clipping the last slide.

= 0.5.1 =
* Slider slides now set their own text color, so they stay readable when
  placed inside dark sections such as a testimonial band.
* Bumped the Slider block version to refresh cached styles.

= 0.5.0 =
* Added the generic Filterable Query block and Portfolio filter pattern.

= 0.4.0 =
* Added the generic Slider block with manual and query modes.

= 0.3.0 =
* Added Portfolio and Testimonial content models, meta panels, block bindings, patterns, and templates.

= 0.2.0 =
* Added the motion extension for core Group and Cover blocks.

= 0.1.0 =
* Initial scaffold for the core-first Sekeco plugin.
