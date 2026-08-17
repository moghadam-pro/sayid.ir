# WordPress / Elementor Implementation Architecture

This document defines how the sayid.ir visual and interaction system should be implemented without turning Elementor templates into the owner of application behavior.

## Goal

Keep the current WordPress + Elementor workflow for speed, while moving reusable behavior and system rules into version-controlled code.

## Responsibility split

```text
WordPress
  → content, taxonomies, settings, editorial state

Elementor
  → page composition, content placement, template presentation

sayid.ir custom frontend layer
  → design tokens
  → responsive primitives
  → theme control
  → deferred homepage mounting
  → Hero behavior
  → Lab pointer interactions
  → Signature network interaction
  → accessibility helpers
```

## Recommended custom plugin

Working name:

```text
sayid-site-core
```

Suggested structure:

```text
sayid-site-core/
├── sayid-site-core.php
├── includes/
│   ├── assets.php
│   ├── content-types.php
│   ├── settings.php
│   └── rest.php
├── assets/
│   ├── css/
│   │   ├── tokens.css
│   │   ├── base.css
│   │   ├── layout.css
│   │   ├── components.css
│   │   └── interactions.css
│   └── js/
│       ├── theme.js
│       ├── homepage-entry.js
│       ├── lab-pointer.js
│       └── signature-network.js
└── README.md
```

This plugin does not replace Elementor. It provides the system underneath Elementor.

## CSS ownership

### `tokens.css`

Owns:

- colors
- typography scale
- spacing
- radius
- motion
- container widths
- z-index layers

### `base.css`

Owns:

- global RTL defaults
- body/background behavior
- typography defaults
- focus styles
- reduced-motion defaults

### `layout.css`

Owns reusable primitives:

```text
.site-container
.section
.layout-grid
.editorial-width
.full-bleed
```

### `components.css`

Owns reusable site-level components such as:

```text
status indicator
note row
project card
Lab card
footer theme control
scroll cue
```

### `interactions.css`

Owns CSS portions of JS-driven interactions:

```text
pointer reveal mask
node active states
transition states
deferred mount states
```

## JavaScript ownership

### `theme.js`

Responsibilities:

- read system preference
- read stored override
- apply `data-theme`
- update footer theme control
- react to system changes when mode is System

Theme initialization should run before normal page scripts to minimise flash of the wrong theme.

### `homepage-entry.js`

Responsibilities:

- initial Hero-only viewport state
- scroll cue state
- prefetch deferred homepage content when idle
- mount deferred content on first meaningful scroll intent
- unlock document scrolling without swallowing the original interaction
- support wheel, touch, keyboard and assistive navigation cases
- bypass the Hero lock for anchors, history restoration and direct deep links

### `lab-pointer.js`

Responsibilities:

- fine-pointer detection
- per-card pointer coordinates
- local border reveal
- node/particle proximity values
- cleanup when card leaves interaction state

### `signature-network.js`

Responsibilities:

- SVG node state
- relationship highlighting
- pointer/keyboard focus behavior
- reduced-motion fallback

## Elementor contract

Elementor should output stable semantic hooks rather than custom behavior.

Example:

```html
<section class="home-lab" data-sayid-lab>
  <article class="lab-card" data-lab-card>
    ...
  </article>
</section>
```

The JS layer enhances those hooks.

Avoid inline scripts inside Elementor HTML widgets whenever the behavior is reusable or critical.

## Homepage deferred-content architecture

The preferred architecture is progressive and resilient.

Initial HTML contains:

```text
Header
Hero
Scroll cue
Deferred mount root
```

Remaining homepage content is made available through one of two strategies:

### Preferred first implementation: hidden template payload

Server renders the remaining homepage into a `<template>` element after the Hero.

Benefits:

- no second network request
- content is not active DOM until mounted
- simpler caching
- SEO/server output can still contain structured content if required by implementation strategy
- immediate first-scroll mount

Concept:

```html
<template id="homepage-deferred-content">
  ...remaining sections...
</template>
<div id="homepage-deferred-root"></div>
```

On first scroll intent, clone the template into the mount root.

### Optional later strategy: REST fetch

Use a cached REST/HTML fragment endpoint only if payload size proves large enough to justify a second request.

Do not introduce network dependency merely for architectural novelty.

## Important SEO note

The deferred interaction must not make the site's primary discoverable content invisible to search engines or unavailable without JavaScript.

Where practical, server-rendered/template-based content is preferred over client-only data fetching.

## Hero implementation contract

Hero shell:

```css
.home-hero {
  min-height: 100svh;
  min-height: 100dvh;
  overflow: clip;
}
```

Inner frame:

```css
.home-hero__inner {
  width: min(calc(100% - 2 * var(--gutter)), var(--content-max));
  min-height: inherit;
  margin-inline: auto;
}
```

Visual media should be constrained independently from the Hero shell.

Example principle:

```css
.home-hero__media {
  max-height: min(78dvh, 900px);
  max-width: min(52vw, 920px);
}
```

Exact values must be tuned against the existing Hero composition.

## Height-aware Hero behavior

Width breakpoints alone are not enough for Hero layouts.

Use vertical constraints when necessary, for example:

```css
@media (max-height: 760px) {
  /* reduce visual scale and vertical spacing */
}
```

This is not a new product breakpoint; it is a viewport-height safety rule for a full-screen composition.

The Hero must be tested especially on:

```text
1366×768
1440×900
1920×1080
3440×1440
3840×2160
```

## DOM scroll lock

Avoid globally setting permanent `overflow: hidden` through static CSS before JavaScript is known to be available.

Safer model:

1. JS adds a temporary class such as `.is-home-entry-locked`
2. the class locks document scrolling
3. JS removes it immediately when content mounts
4. no-JS visitors keep a normal scrollable document

This preserves progressive enhancement.

## Theme flash prevention

The initial theme decision should run in a tiny inline bootstrap in the document `<head>` before the main CSS paints when possible.

Pseudo-flow:

```text
read local theme override
  ↓
if light/dark → set data-theme
  ↓
otherwise allow system media query
```

The full footer-control behavior can load later.

## Accessibility rules

All custom interactions must support:

- keyboard navigation
- visible focus
- `prefers-reduced-motion`
- touch without hover dependency
- semantic links/buttons
- sufficient color contrast in both themes

Decorative SVG networks should be hidden from screen readers unless they communicate information that has no text equivalent.

## Performance rules

- avoid loading interaction code on pages that do not use it
- load Lab and Signature scripts only when their hooks exist
- use passive pointer/scroll listeners where appropriate
- batch pointer visuals through `requestAnimationFrame`
- do not create hundreds of DOM particles
- prefer SVG/CSS over WebGL for v1
- keep Hero assets properly sized and responsive

## Elementor workflow

A practical editing model:

1. build page/section layout in Elementor
2. assign documented semantic classes/data attributes
3. use global Elementor values only where they map cleanly to tokens
4. keep canonical tokens in code
5. let custom CSS/JS enhance the markup

This keeps day-to-day editing easy without making Elementor the only source of truth for the site's behavior.

## Deployment model

For the rebuild, the repository should progressively become the source of truth for:

```text
documentation
custom plugin source
design tokens
custom CSS
custom JavaScript
interaction assets
```

Elementor database content remains managed in WordPress, while exported templates or configuration snapshots may later be added where useful.
