# Homepage Architecture & Interaction Model

> Status: Draft — intended to evolve through design review and implementation.

## 1. Goal

The homepage should feel like a living product rather than a static portfolio index.

The first viewport remains intentionally focused: the existing Hero is preserved as the entry experience, then the rest of the homepage is revealed only after the visitor shows an intent to continue.

The page should communicate this sequence:

```text
Who is Sayid?
    ↓
What is he focused on now?
    ↓
What has he shipped?
    ↓
What is he building and experimenting with?
    ↓
How does he think and connect disciplines?
    ↓
What is he writing / learning?
    ↓
How can we connect?
```

## 2. Hero — preserve, optimize, constrain

The current Hero remains the first section.

### Required improvements

- Keep the section viewport-filling on initial entry.
- Prevent the Hero image from scaling uncontrollably on very large displays.
- Separate the Hero section height from the artwork/image dimensions.
- Cap the content width and visual size independently.
- Use fluid typography with explicit upper limits.
- Ensure the composition stays intentional on ultrawide and tall desktop displays.

### Recommended sizing model

```text
Hero shell: 100dvh
Content container: max-width around 1600–1760px
Hero artwork: viewport-relative but capped by an explicit max-height/max-width
Text: clamp() based scaling, not unrestricted vw scaling
```

The image should ideally be a positioned visual element rather than relying on an unconstrained `background-size: cover` implementation when the composition needs precise control.

## 3. Scroll Discovery Cue

At the bottom of the Hero, show a very small animated vector cue that communicates that more content exists.

Possible visual language:

- minimal vertical line
- moving dot / small arrow
- subtle mouse-wheel or trackpad metaphor
- short Persian microcopy such as `برای ادامه اسکرول کن`

The cue should be low-noise and secondary to the Hero.

It disappears immediately after the first continuation intent.

## 4. Deferred Homepage Loading

### Desired experience

On the initial page load:

```text
DOM / visible page
├── Header
├── Hero
├── Scroll discovery cue
└── Deferred content root (empty)
```

The remaining homepage sections are not inserted into the live DOM yet.

The browser scrollbar should not be visible during this entry state.

On the first downward continuation intent:

```text
wheel
trackpad
swipe
ArrowDown
PageDown
Space
```

The website should:

1. Detect the intent.
2. Resolve or fetch the deferred homepage payload.
3. Insert the remaining sections into the DOM.
4. Enable normal document scrolling.
5. Preserve the visitor's original intent by continuing the movement into the next section.
6. Remove the discovery cue.
7. Never repeat this initialization during the same page lifecycle.

### Recommended performance model

Do not wait until the scroll event to begin all network work.

Use a two-stage strategy:

```text
Initial render
    ↓
Hero becomes interactive
    ↓
requestIdleCallback / post-load
    ↓
Prefetch deferred homepage HTML/data into memory
    ↓
First scroll intent
    ↓
Insert into DOM immediately
```

If the visitor scrolls before the idle prefetch starts or completes, the same action should trigger the request immediately.

This preserves the lightweight initial DOM while avoiding a visible wait on first interaction.

### Important implementation constraints

- Enhancement must not trap keyboard users.
- `ArrowDown`, `PageDown`, `Space`, touch and wheel input should all work.
- Respect `prefers-reduced-motion`.
- The page must fail safely if JavaScript fails.
- Images below the fold should remain lazy-loaded even after the sections are inserted.
- Avoid layout jumps when deferred content is mounted.
- Archives and individual content pages remain indexable independently; homepage deferral should not be the only path to important content.

## 5. Proposed Homepage Sections

### 0 — Hero / Entry

**Purpose:** Identity and immediate positioning.

Preserve the current visual direction and improve responsive constraints only.

---

### 1 — Now / Current Focus

**Purpose:** Immediately make the website feel alive.

A compact section describing what Sayid is currently building, exploring or learning.

Example content model:

```text
NOW
Exploring AI-assisted product building
Building sayid.ir v2
Learning / experimenting with Figma-to-code workflows
```

This should be extremely easy to update from WordPress.

It can optionally display a `Last updated` date.

---

### 2 — Selected Work

**Purpose:** Establish professional credibility without turning the homepage into a conventional portfolio.

Show only 2–4 strong projects.

Each card should focus on:

- problem / context
- role
- one meaningful outcome or characteristic
- strong visual

The section links to the complete Projects archive.

---

### 3 — Lab / Things I Build

**Purpose:** Make experimentation and builder identity visible.

This is the best home for the interactive Bento-card pattern inspired by Stripe's modular solution cards.

Possible entries:

```text
MPRO Portfolio Plugin      Shipped
AI-assisted tools          Building
RTL / LTR experiments      Exploring
Design-system experiments  Active
```

### Interaction concept

Cards are calm by default.

Pointer movement introduces local energy:

- a thin border becomes visible only near the cursor
- a radial mask follows pointer coordinates
- particles / nodes / visual points react toward the active region
- content remains readable without interaction
- interaction progressively reduces on touch devices

The effect should communicate curiosity and experimentation, not exist as decorative motion only.

---

### 4 — Design × Code × AI / Connected Practice

**Purpose:** Express the site's core personal thesis visually.

This is the proposed home for the interactive dark visual inspired by Stripe's dark statistics section.

Instead of copying a financial data visualization, adapt the underlying interaction idea into a map of connected disciplines.

Possible visual nodes:

```text
Product Design
Systems Thinking
Code
AI
Research
Prototyping
Writing
Building
```

The nodes can connect, converge and react subtly as the visitor moves through the lower visual area.

The message:

> The interesting work happens in the connections between disciplines.

This section can become a signature visual moment for sayid.ir.

---

### 5 — Latest Notes

**Purpose:** High-frequency freshness.

Display recent short-form thinking in a lightweight list rather than large article cards.

Example structure:

```text
Date        Title / thought                         Tag
17 Aug      ...                                     AI
15 Aug      ...                                     Product Design
12 Aug      ...                                     Figma
```

This section should update automatically from the Notes content type.

---

### 6 — Featured Article / Long-form Thinking

**Purpose:** Show depth after the visitor has already seen faster-moving content.

Feature one recent or manually selected long-form article with a large editorial treatment.

The Latest Notes and Featured Article sections should look intentionally different so the content hierarchy is obvious.

---

### 7 — Connect

**Purpose:** End with a human invitation rather than a generic contact form.

Possible direction:

```text
Have an idea?
Building something interesting?
Want to talk about product, design, AI or systems?

Let's talk.
```

Keep this visually simple and direct.

---

### 8 — Footer

Include the expected navigation, social channels, RSS / feed access if introduced, and the relationship to `moghadam.pro`.

## 6. Homepage Flow — First Draft

```text
┌────────────────────────────────────────────┐
│ HERO                                       │
│ existing identity + optimized visual       │
│                                      ↓     │
└────────────────────────────────────────────┘
                  first intent
                       ↓
┌────────────────────────────────────────────┐
│ NOW / CURRENT FOCUS                        │
└────────────────────────────────────────────┘
                       ↓
┌────────────────────────────────────────────┐
│ SELECTED WORK                              │
└────────────────────────────────────────────┘
                       ↓
┌────────────────────────────────────────────┐
│ LAB / THINGS I BUILD                       │
│ Interactive Bento cards                    │
└────────────────────────────────────────────┘
                       ↓
┌────────────────────────────────────────────┐
│ DESIGN × CODE × AI                         │
│ Dark interactive connected visual          │
└────────────────────────────────────────────┘
                       ↓
┌────────────────────────────────────────────┐
│ LATEST NOTES                               │
└────────────────────────────────────────────┘
                       ↓
┌────────────────────────────────────────────┐
│ FEATURED ARTICLE                           │
└────────────────────────────────────────────┘
                       ↓
┌────────────────────────────────────────────┐
│ CONNECT                                    │
└────────────────────────────────────────────┘
                       ↓
                     FOOTER
```

## 7. Visual Direction

Stripe is a reference for interaction quality and system thinking, not a template to reproduce.

Useful principles to adapt:

- high information density with strong hierarchy
- alternating calm light surfaces and immersive dark moments
- modular Bento composition
- fine line work and subtle data-like graphics
- pointer-aware local interactions
- restrained gradients
- motion that communicates system behavior
- responsive interactions that degrade gracefully on touch devices

sayid.ir should retain its own identity, Persian typography, personal tone and brand color system.

## 8. Implementation Direction — WordPress / Elementor

Recommended separation of responsibilities:

```text
WordPress
├── Content types / fields / taxonomies
├── Deferred-home endpoint
└── content queries

Custom site plugin / small front-end layer
├── deferred DOM loader
├── pointer interaction engine
├── motion preferences
└── reusable interactive components

Elementor
├── section composition
├── typography
├── responsive layout
└── presentation templates
```

Avoid putting complex interaction logic into multiple Elementor HTML widgets. Interactive behavior should live in version-controlled reusable assets where possible.

## 9. Next Design Decisions

Before implementation, review and refine:

1. Whether `Now` should be the first section after Hero.
2. Exact number and type of Selected Work cards.
3. What belongs in Lab versus Projects.
4. Content/message for the `Design × Code × AI` interactive dark section.
5. Whether Notes should appear before or after the dark interactive section.
6. Hero breakpoints and artwork constraints.
7. Exact scroll-discovery cue.
8. Deferred-load behavior on mobile, keyboard navigation and reduced-motion environments.
