# Sekeco plugin

Content models, component blocks, forms and motion for the Sekeco block theme.

## Blocks

| Category | Blocks |
| --- | --- |
| Sekeco: Layout | `sekeco/section`, `sekeco/section-header`, `sekeco/hero`, `sekeco/cta`, `sekeco/marquee` |
| Sekeco: Content | `sekeco/statement`, `sekeco/capabilities` (+ `capability`), `sekeco/process` (+ `process-step`), `sekeco/faq` (+ `faq-item`), `sekeco/testimonials` (+ `testimonial`; layouts slider, cards, marquee; hides until a quote exists), `sekeco/stats` (+ `stat`), `sekeco/project-grid` |
| Sekeco: Utility | `sekeco/form`, `sekeco/theme-toggle`, `sekeco/search`, `sekeco/scroll-top` |

Width: Section, Hero and CTA have a Content width control with four options:
Narrow (theme.json `settings.layout.contentSize`, 760px), Default (the block's
own 1240px column), Wide (`wideSize`, 1320px) and Full (the whole screen; on a CTA the background runs edge to edge and the copy keeps the default column). The
choice is the `width` attribute and the `sk-w--narrow|wide|full` class; the
sizes reach CSS as `--sk-narrow`, `--sk-max` and `--sk-wide`, and
`src/shared/_widths.scss` holds the mixins. Blocks inside a section break out
to Wide or Full with the editor's native alignment.

Background: Hero and CTA have a Background option (None, Satin, Dusk, Aurora, Nebula). The
picture is a WebGL shader painted on a canvas (`src/shared/backgrounds`), timed
by the GSAP ticker at half resolution and 30fps, paused off screen and drawn
once for reduced motion. It reads the block's text colour, so it follows the
site's light and dark mode and the block's tone, and each picture keeps a calm
zone behind the copy. Without WebGL the block keeps its normal ground.

Buttons: the theme registers six gradient styles for `core/button` next to
Fill and Outline (`assets/css/buttons.css`); the plugin adds a Size control
(Small, Medium, Large) that sets `is-size-sm` or `is-size-lg` on the button.

Child blocks are only insertable inside their parent. All blocks are dynamic and
registered from `build/blocks/<name>` by `includes/blocks/components.php`.

## Development

```sh
bun install
bun run start   # watch
bun run build   # production build (commit build/)
bun run icons   # regenerate Solar icon assets
bun run lint:js && bun run lint:css
```
