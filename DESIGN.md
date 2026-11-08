---
name: Sekeco
description: A techy agency system in the logo's gradient. Deep ink or lavender paper grounds, blue to purple to red light, Bricolage Grotesque set tight, pointer-lit cards.
colors:
  brand-blue: "#4D68E4"
  brand-purple: "#A33FBD"
  brand-red: "#FD065A"
  brand-cyan: "#09C4F7"
  brand-pink: "#FF6FAE"
  accent-on-paper: "#3F59D6"
  accent-on-ink: "#8FA2FF"
  ink: "#07070D"
  surface-dark: "#10101A"
  text-dark: "#F3F2FA"
  muted-dark: "#A3A2BA"
  border-dark: "#23233A"
  paper: "#F6F5FB"
  surface-light: "#FFFFFF"
  text-light: "#0B0B16"
  muted-light: "#5A5972"
  border-light: "#E1DFEE"
  brand-field-base: "#14102E"
typography:
  display:
    fontFamily: "Bricolage Grotesque, DM Sans, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "4.5rem"
    fontWeight: 600
    lineHeight: 1
    letterSpacing: "-0.045em"
  headline:
    fontFamily: "Bricolage Grotesque, DM Sans, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "2.75rem"
    fontWeight: 600
    lineHeight: 1.05
    letterSpacing: "-0.04em"
  title:
    fontFamily: "Bricolage Grotesque, DM Sans, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "1.875rem"
    fontWeight: 600
    lineHeight: 1.1
    letterSpacing: "-0.035em"
  body:
    fontFamily: "DM Sans, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: "0"
  label:
    fontFamily: "DM Sans, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "0.8125rem"
    fontWeight: 400
    lineHeight: 1.4
    letterSpacing: "0.04em"
rounded:
  sm: "10px"
  md: "20px"
  lg: "40px"
  full: "999px"
spacing:
  tiny: "8px"
  x-small: "16px"
  small: "24px"
  regular: "48px"
  large: "88px"
  x-large: "152px"
  xx-large: "216px"
components:
  button-primary:
    backgroundColor: "{colors.brand-blue}"
    textColor: "#FFFFFF"
    rounded: "{rounded.full}"
    padding: "14px 24px"
  button-outline:
    backgroundColor: "transparent"
    textColor: "{colors.text-dark}"
    rounded: "{rounded.full}"
    padding: "14px 24px"
  pill-link:
    backgroundColor: "{colors.brand-purple}"
    textColor: "#FFFFFF"
    rounded: "{rounded.full}"
    height: "52px"
    padding: "0 24px 0 10px"
  card-capability:
    backgroundColor: "{colors.surface-dark}"
    textColor: "{colors.text-dark}"
    rounded: "{rounded.md}"
    padding: "28px"
  card-cover:
    backgroundColor: "{colors.brand-blue}"
    textColor: "#FFFFFF"
    rounded: "{rounded.md}"
    padding: "32px"
  cta-field:
    backgroundColor: "{colors.brand-field-base}"
    textColor: "#FFFFFF"
    rounded: "{rounded.lg}"
    padding: "112px 72px"
  header-pill:
    backgroundColor: "{colors.surface-dark}"
    rounded: "{rounded.full}"
    height: "72px"
---

# Design System: Sekeco

## Overview

**Creative North Star: "The Logo, Lit"**

The whole system is the Sekeco logo's gradient (blue, purple, red-pink, with cyan as a spark) treated as light on a quiet ground. The ground is deep ink by default and lavender paper in light mode; the colour lives in the brand gradient, in cover cards, in the hero's blurred ambient light and in one closing field. Everything else is neutral and typographic: tightly tracked Bricolage Grotesque headings over DM Sans text, outlined cards, generous space.

The feel is techy and eclectic rather than corporate. The page is a calm dark surface that wakes up under the pointer: cards grow a neon ring and inner glow that follow the cursor, pill buttons slide their gradient, and the hero breathes with blurred brand light. Structure is stable: a floating pill header, a side rail, a mosaic of projects, outlined capability and process cards, a marquee. Motion is GSAP reveal and scroll work with a reduced-motion fallback; hidden pre-reveal states exist only when JavaScript is running.

**Key Characteristics:**
- Dark default, light mode swaps grounds only; the brand colours stay put.
- Gradient is a fill and a light source, never a text colour (one logo-derived exception).
- Headings 600 weight, -0.035em to -0.045em tracking; text DM Sans at 1.6 line height.
- Large radii (20px cards, 40px closing field, full pills) and 1px hairlines.
- Photographs on Works and Portfolio cards sit under a dark scrim; Solar Line Duotone is the only icon set.

## Colors

A four-colour logo gradient over two paired neutral grounds: deep ink and lavender paper.

### Primary
- **Brand Blue** (#4D68E4): start of the gradient, the button and the first cover tone. Also the theme's `accent` preset and the browser accent colour.
- **Brand Purple** (#A33FBD): the middle of the gradient, link selection background, the glow shadow under hovered buttons.
- **Brand Red-Pink** (#FD065A): the end of the gradient and of the cover tones 2 and 3 (pink tint #FF6FAE appears in glows).

### Secondary
- **Brand Cyan** (#09C4F7): the spark. Only at the head of the `brand-soft` gradient (button hover, footer wordmark) and the pointer ring.

### Neutral
- **Deep Ink** (#07070D): dark page ground, footer, menu overlay.
- **Ink Surface** (#10101A): dark cards, header pill and surface blocks.
- **Moonlit Text** (#F3F2FA), **Dusk Muted** (#A3A2BA), **Night Border** (#23233A): dark text, secondary text, hairlines.
- **Lavender Paper** (#F6F5FB): light ground. **White** (#FFFFFF) surface; **Ink Text** (#0B0B16); **Slate Muted** (#5A5972); **Mist Border** (#E1DFEE).
- **Accent on Paper** (#3F59D6) and **Accent on Ink** (#8FA2FF): the legible text-and-focus accent in each mode (icons, focus rings, marquee separators, hover links).
- **Brand Field Base** (#14102E): the deep violet under the closing field mesh.

### Named Rules
**The Light, Not Paint Rule.** The brand gradient fills buttons, pill links, cover cards, the closing field and ambient hero light. Body copy, headings and icons never take it. The single exception is the footer wordmark, set in `brand-soft` like the logo itself; it is not a pattern for other type.

**The Tone Scope Rule.** A section fixes its own ground through a tone class (ink, paper, accent field, surface) that remaps text, muted, line and surface together. Never hand-colour text inside a section; read the scope variables.

**The Text Accent Rule.** Brand blue is a fill. Where blue must read as text or a focus ring, use the mode's accent (#3F59D6 on paper, #8FA2FF on ink).

## Typography

**Display Font:** Bricolage Grotesque (with DM Sans, system-ui)
**Body Font:** DM Sans (with system-ui, Segoe UI)

**Character:** Bricolage brings a slightly quirky, tight headline voice; DM Sans stays neutral and readable underneath. Both are bundled variable woff2 files.

### Hierarchy
- **Display** (600, clamp 2.5rem to 4.75rem, 1.02, -0.04em): h1 and hero title. Hero title tops out at 4.5rem in a 17ch measure; CTA titles reach 5.5rem at 0.98.
- **Headline** (600, clamp 2rem to 3.25rem, 1.05, -0.04em): h2 and section titles.
- **Title** (600, clamp 1.5rem to 2.25rem, 1.1, -0.035em to -0.04em): h3, card, capability and process titles.
- **Body** (400, 1rem to 1.0625rem fluid, 1.6): text sits at 82% of the heading colour. Lead paragraphs 52ch or less.
- **Label** (400, 0.8125rem, 0.04em, uppercase): small section labels; see Don'ts.
- **Menu** (500, clamp 2.5rem to 5.5rem, 1.06, -0.04em): overlay navigation in Bricolage.

### Named Rules
**The Tight Display Rule.** Every Bricolage heading carries negative tracking from -0.035em down to -0.045em; larger means tighter. Body and labels are never tracked negatively.

## Layout

Content width 720px, wide 1320px, component max 1240px with a fluid gutter of clamp(16px, 4vw, 48px). The spacing scale is 8, 16, then fluid clamps up to 216px; components own their internal rhythm and sections stack seamlessly (stacked sections overlap by the 40px radius). The home page reads as a floating pill header over a hero, then a project mosaic (repeating one 8-column card and 4-column cards on a 12-column grid at 1000px and above), capabilities in four columns at 1100px, process cards in auto-fit columns of at least 240px, a marquee, and a closing field. Two-column project lists become a 7/5 rhythm. On narrow screens everything is a single column, the header CTA hides under 600px, and the side rail only exists from 1100px.

A fixed side rail (theme switch, vertical scroll-to-top, search button) sits at the left from 1100px.

## Elevation & Depth

Flat by default; depth comes from tonal surfaces, hairlines and light. Shadows are glows that appear only on interaction, tinted with brand purple. The header floats with a blurred translucent pseudo-element rather than a shadow.

### Shadow Vocabulary
- **Button glow** (`box-shadow: 0 10px 30px -10px rgb(163 63 189 / 0.75)`): hovered buttons, with a 2px lift.
- **Pill glow** (`box-shadow: 0 8px 28px -8px rgb(163 63 189 / 0.7)`): hovered pill links.
- **Card glow** (`box-shadow: 0 0 44px -14px rgb(163 63 189 / 0.55)`): hovered pointer-lit cards.
- **Header glass** (`backdrop-filter: blur(18px) saturate(140%)` on a surface at 82% opacity): the pill header only.

### Named Rules
**The Hover-Only Light Rule.** The neon ring and inner glow exist on hover and focus-within, follow the pointer through `--mx` and `--my`, and are hidden entirely on touch devices. Nothing depends on them.

**The Blur Boundary Rule.** Never put `transform`, `filter` or `backdrop-filter` on an ancestor of the fixed menu overlay; blur lives on a pseudo-element.

## Shapes

Soft and round. Cards and surfaces 20px, the closing field and stacked sections 40px, small elements 10px, buttons, pills and the header fully rounded (999px), icon buttons and bullets circular. Hairline 1px borders define outlined cards; cover cards have no border and clip their photographs. Photographs scale slowly (1.1 to 1.18) on hover inside the clip.

## Components

### Buttons
- **Shape:** full pill (999px), 14px 24px padding (header 0.95rem 1.5rem), weight 500.
- **Primary:** `brand` gradient fill, white text, 160% background size.
- **Hover / Focus:** gradient slides to the far end, lift 2px, purple glow; outline button inverts to contrast fill.
- **Outline:** 1px currentcolor border, transparent, inverts on hover.

### Pill Link
`brand` gradient, 52px tall, circled outline icon on the leading side; on hover the gradient slides and the gap widens from 12px to 16px.

### Cards / Containers
- **Capability and process cards:** outlined, 20px radius, 1px line border, surface fill, 28px padding. Capabilities hold a 48px circular icon and a 56px circular go button; process cards carry a two-digit counter because order is information.
- **Cover cards (Works, Portfolio):** 20px radius, clipped, 320 to 440px tall. Tones: blue to violet, violet to red, red to pink, deep glass. Photographs sit under a bottom scrim (ink 78% to transparent) with title bottom-left and a white circular arrow appearing on hover. Typographic covers set the name large at the top.
- **Team cards:** portrait 4/5, greyscale until hover, name below, no overlay.
- **Pointer-lit:** cards may add the glow treatment described in Elevation.

### Navigation
Floating pill header (72px, glass, mark plus lowercase site title, centred links at 1000px and above, actions on the right). Hides on scroll down. The menu overlay is a full-screen ink panel with Bricolage links at display size that indent and turn #8FA2FF on hover.

### Marquee
Full-width band tinted 5% of the foreground, Bricolage words at up to 3.75rem in muted text, separated by an accent asterisk; pauses on hover, static when reduced motion is requested.

### Closing Field
The CTA block: 40px radius, the `--sk-field-bg` mesh of blue, red and purple radial light over #14102E, white text, left aligned, title up to 5.5rem. The footer below is deep ink with the oversized lowercase gradient wordmark.

### Hero
Centred title and lead over blurred brand light (three radial blobs at 30px blur, half strength in light mode), with a horizontal reel of cards below.

## Do's and Don'ts

### Do:
- **Do** use the `brand` gradient (110deg, #4D68E4, #A33FBD, #FD065A) for fills and `brand-soft` (adds #09C4F7 at the start) for hover states.
- **Do** read colours from tone-scope variables so a section works in both modes.
- **Do** use Solar Line Duotone icons from the generated set only.
- **Do** keep hover-only effects meaningful without them, and keep reduced-motion collapsing animations to near zero.
- **Do** run photographs under the ink scrim so overlaid white text keeps contrast.

### Don't:
- **Don't** set body text, headings or icons in the gradient; the footer wordmark is the lone logo-derived exception.
- **Don't** add hard offset shadows, glyph icons, dashicons or glyph arrows.
- **Don't** use backdrop-filter, filter or transform on ancestors of the fixed menu overlay.
- **Don't** track Bricolage headings looser than -0.035em, or use it for body text.
- **Don't** introduce colours outside the logo palette and the paired neutrals.
