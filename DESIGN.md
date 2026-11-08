---
name: Sekeco
description: A warm, type-led agency system. Ink and cream grounds, one sage field colour, four cover tones, DM Sans set tight.
colors:
  ink: "#111013"
  cream: "#F2EFE4"
  surface-light: "#FAF8F2"
  surface-dark: "#1A191C"
  muted-light: "#5E5C54"
  muted-dark: "#ADAA9F"
  border-light: "#D8D3C3"
  border-dark: "#2C2B2E"
  sage: "#4A4F49"
  sage-lifted: "#B9C1B1"
  sage-light: "#C8CCBD"
  salmon: "#F4B6A4"
  signal-red: "#D91842"
typography:
  display:
    fontFamily: "DM Sans, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "4.5rem"
    fontWeight: 500
    lineHeight: 1
    letterSpacing: "-0.045em"
  headline:
    fontFamily: "DM Sans, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "4rem"
    fontWeight: 500
    lineHeight: 1.02
    letterSpacing: "-0.045em"
  title:
    fontFamily: "DM Sans, system-ui, -apple-system, Segoe UI, sans-serif"
    fontSize: "2rem"
    fontWeight: 500
    lineHeight: 1.1
    letterSpacing: "-0.04em"
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
  gutter: "48px"
components:
  button-primary:
    backgroundColor: "{colors.sage}"
    textColor: "{colors.cream}"
    rounded: "{rounded.full}"
    padding: "0.9rem 1.5rem"
  button-primary-hover:
    backgroundColor: "{colors.ink}"
    textColor: "{colors.cream}"
  pill-link:
    backgroundColor: "{colors.sage}"
    textColor: "{colors.cream}"
    rounded: "{rounded.full}"
    height: "52px"
    padding: "0 24px 0 10px"
  capability-card:
    textColor: "{colors.ink}"
    rounded: "{rounded.md}"
    padding: "28px"
  capability-card-hover:
    backgroundColor: "{colors.sage}"
    textColor: "{colors.cream}"
  cover-card:
    backgroundColor: "{colors.salmon}"
    textColor: "{colors.ink}"
    rounded: "{rounded.md}"
  closing-card:
    backgroundColor: "{colors.sage}"
    textColor: "{colors.cream}"
    rounded: "{rounded.lg}"
    padding: "112px 72px"
  header-pill:
    backgroundColor: "{colors.surface-dark}"
    textColor: "{colors.cream}"
    rounded: "{rounded.full}"
    height: "72px"
---

# Design System: Sekeco

## Overview

**Creative North Star: "The Warm Studio Wall"**

Sekeco reads like a studio wall in lamplight: a warm near-black ground (or cream, by toggle), big tightly-tracked type, and a handful of flat coloured covers pinned to it. Dark is the default; light is a switch. Everything is one typeface at one weight, so hierarchy comes from size, tracking and dimming, not from a second voice.

Colour is rationed. Sage is the single working colour (buttons, pills, hover fills, the closing card). Salmon, signal red, sage light and sage appear together only as cover tones for Works, where flat colour stands in for imagery. Surfaces are flat and tonal; depth comes from overlap (rounded stacked sections, a floating header) rather than shadow.

**Key Characteristics:**
- Dark default, cream light mode, both warm; never pure black or white.
- One face, DM Sans 500, headline tracking -0.045em, secondary words dimmed not italicised.
- 20px cards, 40px closing and stacked-section corners, 999px pills and buttons.
- Outlined cards that fill sage on hover; flat cover tones; no shadows in shipped components.
- Small uppercase kicker labels name each section.
- Motion is GSAP reveals plus one expo ease; reduced motion collapses everything.

## Colors

Warm ink and cream grounds, one muted sage field colour, four cover tones that appear on Works covers.

### Primary
- **Sage Field** (`{colors.sage}`): the single action colour: buttons, pill links, capability hover fill, closing card, selection, form accent. Text on it is cream.
- **Lifted Sage** (`{colors.sage-lifted}`): sage's dark-mode stand-in for text-weight uses (focus ring, caret, menu link hover, footer link hover) where #4A4F49 would vanish on ink.

### Secondary (cover tones)
- **Salmon** (`{colors.salmon}`): cover tone 1, ink text.
- **Signal Red** (`{colors.signal-red}`): cover tone 2, cream text.
- **Sage Light** (`{colors.sage-light}`): cover tone 3, ink text.
- **Sage** (`{colors.sage}`): cover tone 4, cream text.

### Neutral
- **Ink** (`{colors.ink}`): dark ground, light-mode text, footer, menu overlay, ink tone.
- **Cream** (`{colors.cream}`): light ground, dark-mode text, text on sage.
- **Surface Light / Dark** (`{colors.surface-light}`, `{colors.surface-dark}`): header pill, surface group style, floating search button.
- **Muted Light / Dark** (`{colors.muted-light}`, `{colors.muted-dark}`): leads, descriptions, tags, dimmed headline words.
- **Border Light / Dark** (`{colors.border-light}`, `{colors.border-dark}`): hairlines; card outlines use a 14% text-colour line.

### Named Rules
**The One Field Rule.** Sage is the only colour that acts. Salmon, red and sage light are cover tones for Works and never fill buttons, links or section grounds.

**The Tone Scope Rule.** A section fixes its own ground through a tone (ink, paper, accent, surface) and remaps the preset colours inside it. Do not hard-code colours inside a tone; read the scope variables.

## Typography

**Display Font:** DM Sans (variable 100-1000, with system-ui fallbacks)
**Body Font:** DM Sans
**Label Font:** DM Sans

**Character:** One geometric sans, medium weight, squeezed tight at large sizes. Body copy sits at 82% of the text colour so headings read forward.

### Hierarchy
- **Display** (500, clamp 2.5rem to 4.5rem, 1.0): hero title, centred, max 17ch; closing card title runs larger (to 5.5rem, 0.98); menu links to 5.5rem.
- **Headline** (500, clamp 2.25rem to 4rem, 1.02, -0.045em): section titles.
- **Title** (500, clamp 1.625rem to 2rem, 1.1, -0.04em): capability, process step and card titles.
- **Body** (400, 1rem fluid to 1.0625rem, 1.6): running text; leads 1.0625rem to 1.25rem, 1.55, muted, max 44-52ch.
- **Label** (400, 0.8125rem, +0.04em, uppercase): the kicker above section titles and the hero label; process numerals use 500 at 0.875rem tabular.

### Named Rules
**The Dimmed Word Rule.** Emphasis inside a headline is `em`/`i` rendered upright in the muted colour, never italic or a second colour.

**The Single Weight Rule.** Headings are 500 everywhere; only the footer wordmark (600) and site title (700) differ.

## Layout

Content column 720px, wide 1320px; components cap at 1240px inside a fluid gutter `clamp(16px, 4vw, 48px)`. Sections pad `clamp(64px, 8.5vw, 128px)` vertically; neutral sections in a row share one gap. The hero is a centred stack (label, title, lead, action) above a Works mosaic. The project mosaic is a 12-column grid, rows alternating 8/4 then 4/4/4; it collapses to 2 columns then 1. Capabilities run 4/2/1 columns and process steps auto-fit from 240px, both with a 12-20px gap. Section headers split 7/4 with the kicker spanning above. Stacked sections overlap the previous by 40px with rounded top corners.

A left side rail (from 1100px) holds the vertical Dark/Light switch and a "Scroll to top" link; a round floating search sits bottom-left. Below 1100px the rail disappears and the menu overlay carries navigation. Spacing scale is fluid (8, 16, 24-ish, 48-ish, 88-ish, 152-ish).

## Elevation & Depth

Flat and tonal. No shipped component carries a box-shadow. Depth comes from the floating header (blurred surface pill on a pseudo-element), rounded stacked sections overlapping their predecessor, and fills that appear on hover. A `lift` shadow preset exists in theme.json for editor use only. Photographic cards use a bottom scrim gradient (ink 70% to transparent) for text legibility only.

### Named Rules
**The Flat Rule.** Surfaces never cast shadows; separate with tone, outline or overlap.

## Shapes

Soft and round, in three steps. 20px (`{rounded.md}`) for cards, images, covers and surface groups; 40px (`{rounded.lg}`) for the closing card and the top corners of stacked sections; 999px for buttons, pill links, header, switches and round icon buttons (48-56px circles). 10px is available for small elements. Icons sit in 1px-outlined circles. Outlines are 1px in the scope line colour.

## Components

### Buttons
- **Shape:** full pill (999px).
- **Primary:** sage fill, cream text, 500 weight, padding 0.9rem 1.5rem.
- **Hover / Focus:** fill flips to the contrast colour with a 2px lift (0.5s expo); focus is a 2px lifted-sage outline offset 3px.
- **Outline style:** 1px currentcolor border, transparent; fills with the contrast colour on hover.

### Pill link
Sage pill, 52px high, with a circled Solar arrow at the leading edge (1px cream-50% ring); gap widens 12px to 16px and the fill lightens on hover.

### Cover cards (Works)
Flat tone fill (salmon, red, sage light, sage) or a photograph with scrim, 20px, min 320-440px tall. Title bottom-left; typographic covers set the name large (to 4.5rem) at top with tags at the foot. On hover the image scales 1.1 to 1.18, the title rises 4px and a cream round arrow button appears top-right. Team cards show a grayscale 4:5 portrait that colours on hover.

### Capability and process cards
Outlined, 20px, 28px padding. Capability cards fill sage on hover or focus-within with cream text, and the circled icon (48px) and 56px go-button shift; whole card is the link. Process steps are outlined only, with a tabular two-digit numeral.

### Marquee
Tinted band (text colour at 5%), words in display 500 up to 3.75rem, muted, separated by a small asterisk; pauses on hover, becomes a wrapped static list under reduced motion.

### Navigation
Floating full-width pill header (72px, blurred surface, 14px from top, hides on scroll down) with brand left, centred inline menu at 1000px+ (1rem, 86% opacity rising to 100% on hover/current), action right. A round 56px menu button opens an ink overlay with display-size links that indent and lift to sage on hover.

### Closing card
40px sage card with cream text, 14ch display title, muted lead, actions below; inset by the viewport margin and stackable over the previous section. The footer is ink with an oversized wordmark (600, -0.045em).

### Theme switch
Vertical Dark/Light in the side rail; the active option is a filled pill. An icon variant swaps sun and moon.

## Do's and Don'ts

### Do:
- **Do** use sage `#4A4F49` as the only action fill, with cream text.
- **Do** set headlines at 500, tracking -0.045em, and dim secondary words instead of italicising.
- **Do** use 20px for cards, 40px for closing and stacked edges, 999px for anything pressable.
- **Do** use small uppercase kicker labels (0.8125rem, +0.04em, muted) to name sections.
- **Do** use Solar Line Duotone icons only (generated set), inside outlined circles.
- **Do** honour reduced motion: reveals, marquee and transitions collapse.

### Don't:
- **Don't** use salmon, red or sage light as button, link or section ground; they are cover tones.
- **Don't** add box-shadows to components; the system is flat.
- **Don't** introduce a second typeface or weights other than 500 for headings.
- **Don't** use pure black, pure white or cool greys.
- **Don't** wrap fixed descendants (header menu overlay) in transform, filter or backdrop-filter.

*Not canonized, carried by the build:* none beyond the kicker, which the user explicitly adopted into the system.
