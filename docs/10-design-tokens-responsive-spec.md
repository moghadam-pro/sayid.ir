# Design Tokens & Responsive Specification

This document turns the visual direction into implementation-ready rules for CSS, Elementor templates and custom interaction code.

## 1. Root tokens

```css
:root {
  /* Brand */
  --color-yellow: #ffbf01;
  --color-purple: #6050a8;

  /* Layout */
  --content-max: 1760px;
  --gutter: clamp(20px, 4vw, 72px);

  /* Radius */
  --radius-sm: 8px;
  --radius-md: 16px;
  --radius-lg: 24px;
  --radius-pill: 999px;

  /* Motion */
  --motion-fast: 160ms;
  --motion-normal: 280ms;
  --motion-slow: 600ms;
  --motion-ambient: 1800ms;
  --ease-out: cubic-bezier(.22, 1, .36, 1);

  /* Spacing */
  --space-1: 4px;
  --space-2: 8px;
  --space-3: 12px;
  --space-4: 16px;
  --space-6: 24px;
  --space-8: 32px;
  --space-12: 48px;
  --space-16: 64px;
  --space-24: 96px;
  --space-32: 128px;
  --space-40: 160px;
}
```

## 2. Semantic colors

Light mode:

```css
:root {
  --color-bg: #f7f8fb;
  --color-surface: #ffffff;
  --color-surface-subtle: #f1f3f7;
  --color-text: #13151a;
  --color-text-muted: #667085;
  --color-border: rgba(19, 21, 26, .10);
  --color-border-strong: rgba(19, 21, 26, .18);
}
```

Dark mode:

```css
[data-theme='dark'] {
  --color-bg: #0b0c11;
  --color-surface: #12141b;
  --color-surface-subtle: #171a22;
  --color-text: #f6f7fa;
  --color-text-muted: #a7adbb;
  --color-border: rgba(255, 255, 255, .10);
  --color-border-strong: rgba(255, 255, 255, .18);
}
```

When no explicit `data-theme` exists, system preference should populate the dark variables through `prefers-color-scheme`.

## 3. Theme control

The footer includes a compact three-state control:

```text
System  ·  Light  ·  Dark
```

It may be represented primarily with icons, but every control must retain an accessible label.

Storage model:

```text
no local value  → system
light           → force light
dark            → force dark
```

The theme script should run as early as possible in the document head to avoid a visible theme flash.

## 4. Breakpoint contract

Only four product-level responsive regimes are used:

```css
/* Mobile */
0–767px

/* Tablet / compact */
768–1199px

/* Standard desktop */
1200–1920px

/* Large display */
1921px+
```

Implementation media-query boundaries:

```css
@media (min-width: 768px) { }
@media (min-width: 1200px) { }
@media (min-width: 1921px) { }
```

Avoid adding one-off breakpoints unless a real content collision requires one.

## 5. Grid contract

### Mobile

```text
4 columns
20–24px outer gutter
12–16px grid gap
```

### Tablet / compact

```text
8 columns
32–48px outer gutter
16–24px grid gap
```

### Standard desktop

```text
12 columns
48–72px outer gutter
24–32px grid gap
```

### Large display

```text
12 columns
content width remains capped
outer whitespace expands naturally
24–32px grid gap
```

Recommended grid primitive:

```css
.layout-grid {
  display: grid;
  grid-template-columns: repeat(12, minmax(0, 1fr));
  gap: clamp(16px, 1.6vw, 32px);
}
```

Change the column count at product breakpoints rather than rebuilding every component manually.

## 6. Section rhythm

Recommended general section padding:

```css
.section {
  padding-block: clamp(88px, 9vw, 160px);
}
```

Tighter sections such as Now or Notes may use a lower spacing tier.

Immersive sections such as the Signature Dark Section can exceed the normal rhythm when their composition needs more breathing room.

## 7. Typography implementation

```css
html {
  font-family: 'Estedad', sans-serif;
}

body {
  font-size: clamp(16px, .95vw, 18px);
  line-height: 1.8;
}

.display {
  font-size: clamp(56px, 5vw, 96px);
  line-height: 1.12;
  font-weight: 700;
}

.h1 {
  font-size: clamp(44px, 4vw, 72px);
  line-height: 1.18;
  font-weight: 700;
}

.h2 {
  font-size: clamp(34px, 3vw, 56px);
  line-height: 1.25;
  font-weight: 600;
}

.h3 {
  font-size: clamp(28px, 2.1vw, 40px);
  line-height: 1.3;
  font-weight: 600;
}
```

Long Persian paragraphs should usually remain within roughly `40–55rem` rather than stretching across the whole page.

## 8. Hero responsive contract

The Hero remains a full viewport entry experience:

```css
.hero {
  min-height: 100dvh;
  overflow: clip;
}
```

Inner composition:

```css
.hero__inner {
  width: min(calc(100% - (2 * var(--gutter))), var(--content-max));
  margin-inline: auto;
}
```

The main Hero image must use both viewport-relative and absolute caps.

Conceptually:

```css
.hero__visual {
  max-height: min(78dvh, 900px);
  max-width: 100%;
}
```

Large displays should gain whitespace rather than a dramatically larger portrait or illustration.

## 9. Bento behavior by breakpoint

### Desktop

Allow asymmetric spans such as:

```text
8 + 4
4 + 8
```

or controlled 6/6 compositions.

### Tablet / compact

Reduce asymmetry. Prioritize reading order and keep key content large enough.

### Mobile

Stack all cards in one content column, even though the system grid remains four columns internally.

Do not preserve a desktop Bento shape at the cost of tiny mobile cards.

## 10. Interactive border primitive

Lab cards can use a masked pointer-reveal border.

Suggested architecture:

```text
.card
  ├── base surface
  ├── normal border
  └── ::before interactive border layer
```

Pointer coordinates become CSS custom properties:

```css
--pointer-x
--pointer-y
```

The interactive layer uses a radial mask around those coordinates.

The visible radius should stay local, around `160–260px` depending on card size.

The complete border should never light up at once.

## 11. Hover contract

Hover should be additive, not transformative.

Allowed:

```text
local glow
border reveal
node opacity increase
2–4px internal movement
small text/icon emphasis
subtle surface contrast increase
```

Avoid:

```text
large scale changes
card rotation
3D tilt
heavy shadows
fast gradient chasing
whole-card cursor following
```

For standard links and buttons, the interaction should stay under roughly `280ms`.

## 12. Touch behavior

Fine-pointer effects are enhancements.

```css
@media (hover: hover) and (pointer: fine) {
  /* pointer-proximity effects */
}
```

On touch devices:

- render the card in a visually complete static state
- keep ambient motion minimal
- use clear pressed/focus states
- never hide essential information behind hover

## 13. Reduced motion

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    scroll-behavior: auto !important;
    animation-duration: .01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: .01ms !important;
  }
}
```

Custom JS interactions must also detect reduced-motion preference and avoid running particle/node animations unnecessarily.

## 14. RTL technical contract

Default document declaration:

```html
<html lang="fa" dir="rtl">
```

Use logical CSS properties throughout the custom layer.

Preferred:

```css
margin-inline-start
padding-inline-end
inset-inline-start
border-inline-start
```

Avoid directional assumptions in JavaScript. Pointer coordinates are physical, while content alignment is logical; keep those concepts separate.

## 15. Large-display strategy

At widths above 1920px:

Do:

- maintain content max-width
- increase outer whitespace
- let immersive backgrounds extend full bleed
- keep type within the defined clamp maximum
- cap media and Hero artwork

Do not:

- proportionally scale the whole website
- turn 24px body copy into oversized typography
- enlarge buttons simply because more pixels are available
- stretch editorial text lines

The desired feeling is a larger canvas, not a zoomed interface.

## 16. QA viewport set

Every major homepage change should be visually checked at:

```text
360 × 800
390 × 844
430 × 932
768 × 1024
1024 × 768
1180 × 820
1280 × 800
1366 × 768
1440 × 900
1600 × 900
1728 × 1117
1920 × 1080
2560 × 1440
3440 × 1440
3840 × 2160
```

Also test at unusually short viewport heights, because the Hero composition is especially sensitive to height rather than width alone.

## 17. Implementation ownership

Recommended separation:

```text
WordPress
  → content and settings

Elementor
  → semantic page composition and editorial layout

Custom plugin / frontend layer
  → tokens, theme behavior, responsive utilities, pointer interactions, deferred homepage mounting
```

Do not distribute critical interaction code across unrelated Elementor HTML widgets.

## 18. Definition of responsive quality

A viewport is not considered supported merely because nothing overflows.

It is supported when:

- hierarchy still reads correctly
- visual weight remains balanced
- Persian copy has comfortable line lengths
- interactive targets are usable
- the Hero composition remains intentional
- decorative systems never compete with content
- the layout feels designed for the viewport rather than merely fitted into it
