# Sekeco plugin

Content models, component blocks, forms and motion for the Sekeco block theme.

## Blocks

| Category | Blocks |
| --- | --- |
| Sekeco: Layout | `sekeco/section`, `sekeco/section-header`, `sekeco/hero`, `sekeco/cta`, `sekeco/marquee` |
| Sekeco: Content | `sekeco/statement`, `sekeco/capabilities` (+ `capability`), `sekeco/process` (+ `process-step`), `sekeco/faq` (+ `faq-item`), `sekeco/stats` (+ `stat`), `sekeco/project-grid` |
| Sekeco: Utility | `sekeco/form`, `sekeco/theme-toggle` |

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
