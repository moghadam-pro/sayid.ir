# Visual System v1

This document defines the initial visual system for the sayid.ir rebuild.

The site is Persian-first and fully RTL. The goal is to keep the existing brand identity while borrowing interaction and layout principles from Stripe rather than copying Stripe's visual identity.

## Core direction

- Language: Persian only for the product UI and website content.
- Direction: RTL by default.
- Typeface: Estedad across the entire website.
- Brand accents: existing yellow and purple.
- Theme: follows the visitor's system preference by default, with a compact footer control for manual override.
- Layout: fluid between breakpoints; breakpoints define layout regimes rather than fixed canvas sizes.
- Interaction style: subtle, local, responsive to pointer proximity, and never decorative for its own sake.

## Brand color roles

Primary brand colors remain:

- Yellow: `#FFBF01`
- Purple: `#6050A8`

They should not be used as large flat fills everywhere. Their main roles are:

- active states
- highlights
- small status indicators
- selected borders
- interactive glows
- gradients
- visual links between nodes and diagrams
- occasional emphasis in large editorial typography

### Suggested neutral system

Light theme:

```css
--bg: #f7f8fb;
--surface: #ffffff;
--surface-subtle: #f1f3f7;
--text-primary: #13151a;
--text-secondary: #667085;
--border: rgba(19, 21, 26, 0.10);
--border-strong: rgba(19, 21, 26, 0.18);
```

Dark theme:

```css
--bg: #0b0c11;
--surface: #12141b;
--surface-subtle: #171a22;
--text-primary: #f6f7fa;
--text-secondary: #a7adbb;
--border: rgba(255, 255, 255, 0.10);
--border-strong: rgba(255, 255, 255, 0.18);
```

Brand tokens:

```css
--brand-yellow: #ffbf01;
--brand-purple: #6050a8;
```

A restrained brand gradient may be used for signature moments:

```css
linear-gradient(120deg, var(--brand-yellow), var(--brand-purple));
```

Avoid turning the whole interface into a yellow-purple gradient theme. The accents should feel intentional and rare enough to remain meaningful.

## Typography

Use Estedad consistently. Do not introduce a secondary Latin or display font unless a future content requirement clearly justifies it.

Recommended hierarchy:

```css
--text-display: clamp(3.5rem, 5vw, 6rem);
--text-h1: clamp(2.75rem, 4vw, 4.5rem);
--text-h2: clamp(2.15rem, 3vw, 3.5rem);
--text-h3: clamp(1.75rem, 2.1vw, 2.5rem);
--text-large: clamp(1.125rem, 1.2vw, 1.5rem);
--text-body: clamp(1rem, 0.95vw, 1.125rem);
--text-small: 0.875rem;
```

Recommended weight usage:

- 400: body content
- 500: labels, metadata and navigation
- 600: section headings and card titles
- 700: large editorial emphasis only

Persian body copy needs generous line height. Target roughly `1.8` for body text and tighter values for headings.

## Surface system

The page should feel layered without looking like a dashboard full of cards.

Preferred hierarchy:

1. page background
2. subtle section background shifts
3. white/dark surfaces for cards
4. elevated or interactive surfaces only where necessary

Do not place every piece of content inside a bordered card.

### Recommended behavior

- Selected Work: large visual surfaces, low border emphasis.
- Lab: interactive card surfaces and Bento composition.
- Notes: mostly flat editorial rows.
- Featured Article: large editorial surface.
- Signature Dark Section: immersive full-width section rather than a card.

Shadows should be very soft and secondary to borders, surface contrast and gradients.

## Grid

The website uses a fluid grid system with four responsive regimes.

### Mobile

`0–767px`

- 4-column grid
- single-column content by default
- compact gutters
- all primary controls remain touch friendly

### Tablet and compact displays

`768–1199px`

- 8-column grid
- selected two-column compositions where useful
- reduced decorative density

### Standard desktop

`1200–1920px`

- 12-column grid
- primary design canvas
- full Bento and editorial layouts

### Large displays

`1921px+`

- retain the 12-column logic
- do not scale UI indefinitely
- increase outer whitespace rather than enlarging every element
- cap primary content width around `1760–1840px`

The Hero can still occupy `100dvh`, but its inner visual composition must stop scaling after its designed maximum size.

## Container and gutters

Use fluid gutters rather than one fixed value.

Conceptually:

```css
--page-gutter: clamp(20px, 4vw, 72px);
--content-max: 1760px;
```

For very large displays, keep the content centered and let the surrounding negative space grow.

## Spacing system

Use a 4px base but favour larger editorial jumps.

Core scale:

```text
4
8
12
16
24
32
48
64
96
128
160
```

Typical usage:

- component internal spacing: 12–32px
- card padding: 24–48px
- section internal groups: 32–64px
- section separation: 96–160px

Section spacing should use `clamp()` so mobile is not simply a compressed desktop layout.

## Borders

Borders should feel subtle until interaction requires attention.

Default:

```css
border: 1px solid var(--border);
```

Interactive cards may use a second pseudo-element whose border is revealed only near the pointer using a radial mask.

The border must never become a bright outline around the whole card simply because the card is hovered.

## Radius

Use a restrained radius system:

```css
--radius-sm: 8px;
--radius-md: 16px;
--radius-lg: 24px;
--radius-pill: 999px;
```

Recommended use:

- controls: 8–12px
- normal cards: 16px
- major Bento cards / large editorial surfaces: 20–24px
- pills and tags: full radius

Avoid excessive rounded-card styling. The page should still feel editorial and architectural.

## Motion

Motion should communicate state, proximity, relation or continuity.

Recommended timing tokens:

```css
--motion-fast: 160ms;
--motion-normal: 280ms;
--motion-slow: 600ms;
--motion-ambient: 1800ms;
--ease-out: cubic-bezier(.22, 1, .36, 1);
```

Use motion for:

- scroll cue
- local pointer proximity
- border reveal
- node activation
- subtle content entrance
- theme transition
- relationship diagrams

Avoid:

- aggressive parallax
- large card tilt
- continuous decorative motion everywhere
- scroll-jacking
- forced pinned storytelling

Respect `prefers-reduced-motion` across all custom interactions.

## Hover and pointer interaction

Pointer interactions should follow the local behavior pattern used in high-quality product sites such as Stripe: the response happens around the pointer rather than making the entire component perform a dramatic animation.

Recommended Lab card behavior:

1. read pointer position relative to the card
2. expose a local border glow around that point
3. increase opacity of nearby nodes or lines
4. slightly shift internal decorative particles toward the area of attention
5. keep the physical card itself stable

Typical movement should stay within a few pixels.

Touch devices must not depend on hover. They should use static visual states, tap/focus states and simplified ambient animation.

## Focus and keyboard states

Keyboard focus must be at least as clear as pointer hover.

Use a visible focus ring based on brand purple or yellow, with sufficient contrast in both themes.

Do not remove native focus without replacing it.

## Theme behavior

The initial theme follows the visitor's OS/browser setting:

```css
@media (prefers-color-scheme: dark) {
  /* dark default tokens */
}
```

A compact control in the footer provides three states:

- System
- Light
- Dark

This can visually behave like a tiny three-position switch rather than a large settings component.

Behavior:

1. first visit: no stored override; follow system
2. manual selection: store the user's choice locally
3. System selection: remove the override and resume following `prefers-color-scheme`

Implementation should use a root attribute such as:

```html
<html data-theme="light">
```

or

```html
<html data-theme="dark">
```

When no manual override exists, omit the attribute and allow system preference to decide.

## RTL implementation rules

The entire site is RTL-first.

Prefer CSS logical properties:

```css
padding-inline
margin-inline
inset-inline-start
border-inline-start
text-align: start
```

Avoid hardcoding `left` and `right` unless the value describes an intentionally physical direction rather than content direction.

Motion and arrows must also be reviewed in RTL context instead of only mirroring the layout mechanically.

## Responsive principles

The four breakpoints are guardrails, not four isolated compositions.

Between them:

- typography remains fluid
- gutters remain fluid
- media scales until a maximum
- card grids use intrinsic sizing where possible
- content uses `minmax()` and `clamp()` rather than long lists of breakpoint overrides

The implementation must be checked across at least these representative widths:

```text
360
390
430
768
1024
1180
1280
1366
1440
1600
1728
1920
2560
3440
3840
```

The goal is not merely to avoid broken layouts. Each size should preserve hierarchy, balance and intentional whitespace.

## Stripe-inspired principles being adopted

The current Stripe homepage uses modular solution groups followed by a strong dark section, creating clear changes in rhythm and hierarchy. The sayid.ir system adopts the underlying principles rather than copying the components directly.

Adopt:

- modular composition
- controlled surface hierarchy
- generous whitespace
- strong light/dark rhythm changes
- local pointer interaction
- data/network-like decorative systems
- restrained radius and border treatment
- dense information with strong hierarchy

Do not copy:

- Stripe-specific component layouts
- Stripe product illustrations
- Stripe color values
- Stripe branding or iconography

## Design-system rule of thumb

When unsure, prefer:

> fewer effects, better hierarchy, more space, and one meaningful interaction.

The site should feel alive because the content changes and the interface reacts intelligently—not because every element moves.
