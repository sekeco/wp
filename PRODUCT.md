# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Decision makers who need a digital partner: small and mid-size businesses and institutions in Indonesia (schools, foundations, plantations, retail, recruitment) and startups or product teams abroad. They arrive to judge whether Sekeco can take a brand or an operational system from idea to something running, and to start a conversation. Audience is mixed local and international; all site copy is English.

## Product Purpose

Sekeco is an eclectic digital agency. The website presents its services, shows its works and products, introduces the team, and converts visitors into project enquiries through a built-in form. Success is a qualified enquiry submitted through the Sekeco form.

## Positioning

A small permanent core plus a network of hand-picked specialists, pulled in per project, covering the whole path from brand identity to a working system. One team holds brand, design, and engineering, so there is no hand-off between vendors, and the team still scales to the size of the job.

## Operating Context

Content is edited by non-developers in the WordPress block editor and Site Editor. Menus are managed through the core Navigation block; forms are built with the Sekeco form builder and stored as submissions. The site runs on WordPress with a Docker local stack; production has no Bun, so the plugin `build/` directory is committed.

## Capabilities and Constraints

- Services presented as primary capabilities: web and e-commerce, mobile apps (iOS and Android), ERP and business systems (including API integration), identity and UI/UX design.
- Content types: `portfolio` (admin label Works, `/works/`), `product` (admin label Portfolio, `/portfolio/`), `team` (`/team/`), all sharing core `category`. See CLAUDE.md for the naming trap.
- Theme is a pure block theme (theme.json v3); the Sekeco plugin owns custom blocks, CPTs, meta, block bindings, forms, and motion.
- Every UI string is English. Chat and docs with the owner are Indonesian.
- No testimonials, client quotes, or numeric claims are authored. Numbers come from real content (the Stat block counts published Works, Portfolio, and Team posts).
- Icons: Solar Line Duotone only, via the icon pipeline.

## Brand Commitments

- Name: Sekeco (lowercase wordmark acceptable).
- The owner wants the site to look and read brand new. The previous site (sekeco.github.io) is an anti-reference for look and copy; do not reuse its text, numbers, or testimonials.
- Brand colour is the logo's gradient: blue `#4D68E4`, purple `#A33FBD`, red-pink `#FD065A` with a cyan `#09C4F7` glint (from the ring mark, saved as `assets/images/sekeco-mark.svg`). The site should feel modern, techy and AI-capable, with cards that respond to the pointer (a soft spotlight, glare, tilt) taken from React Bits and Magic UI rather than a coloured neon glow, while keeping the layout and structure consistent.
- Headings are set in Bricolage Grotesque.
- Reference for the design system (not to be copied wholesale): Ohio Theme demo 8, https://ohio.clbthemes.com/demo8/homedemo8-elementor/. Binding traits to carry: modern, warm dark and light modes with a visible switcher, rounded cards, large type-led sections, project cards with overlaid titles, GSAP-driven motion and parallax, light transitions.

## Evidence on Hand

- Real works and products exist as posts in the local database; their featured images and meta are the proof. None of their content may be fabricated or imported from the old site unless the owner adds it as content.
- Absences future work must not fabricate: client logos, testimonials, awards, project metrics.

## Product Principles

1. The work is the proof: surface real Works and Portfolio posts before any claim.
2. One team, whole path: every section reinforces identity-to-system coverage rather than listing disconnected services.
3. Editable by design: every component is a block an editor can change in place; nothing important lives only in a pattern's raw markup.
4. Honest content: empty states hide themselves; no placeholder numbers or quotes ship.
5. Fast to enquire: a contact path is never more than one click away.

## Accessibility & Inclusion

WCAG 2.2 AA contrast in both light and dark modes, visible focus, full keyboard operation of the menu, theme toggle and filters, and `prefers-reduced-motion` honored by all motion. Mixed Indonesian and international audience, so avoid idioms and keep sentences plain.
