# Selected Work & Lab — Homepage Design Direction

This document defines the visual and interaction direction for two key homepage sections of the sayid.ir rebuild: **Selected Work** and **Lab**.

The goal is to keep these sections clearly differentiated:

- **Selected Work** establishes professional credibility.
- **Lab** expresses curiosity, experimentation, building, and motion.

They should feel related, but never interchangeable.

---

## 1. Selected Work

### Purpose

Selected Work is not a portfolio archive. It is a deliberately small, high-signal selection of projects that communicates range, maturity, and impact without turning the homepage into a gallery.

Recommended homepage count: **3 projects maximum**.

### Recommended composition

Desktop direction:

```text
[ Section intro ]

┌──────────────────────────────────────────────┐
│                                              │
│                Featured project              │
│                                              │
│        Large editorial / product visual      │
│                                              │
│        Project title                         │
│        Role / short context                  │
└──────────────────────────────────────────────┘

┌──────────────────────┐  ┌──────────────────────┐
│                      │  │                      │
│  Secondary project   │  │  Secondary project   │
│                      │  │                      │
└──────────────────────┘  └──────────────────────┘

Explore all projects →
```

### Content hierarchy

Each card should avoid excessive metadata. The homepage version should include only:

- Project title
- One short context line
- Optional role/category
- Strong visual
- Optional compact proof point

Avoid duration, tool lists, collaborator lists, long descriptions, or case-study summaries here.

### Featured project

The first project should visually dominate the section.

It may use:

- full-width or near-full-width layout
- large screenshot or editorial visual
- subtle motion on hover
- optional one-line outcome or product scale indicator

The project selection should prioritize the strongest available evidence of product thinking, not simply the newest work.

### Secondary projects

Two smaller cards should provide contrast in domain, product type, or design challenge.

The pair should demonstrate range without looking like a generic portfolio grid.

### Interaction

Interaction should remain restrained:

- media gently scales or shifts
- title/arrow moves slightly
- card border or background responds subtly
- no aggressive tilt
- no pointer-heavy special effects

The section should feel stable and confident.

### Responsive behavior

On tablet and mobile:

```text
Featured project
↓
Project 02
↓
Project 03
↓
Explore all
```

The featured card remains visually stronger, but the difference in physical size should be reduced to avoid excessive scrolling.

### Dynamic content model

Projects should be selected through structured WordPress fields rather than manually rebuilt inside Elementor.

Suggested fields:

```text
show_on_homepage
homepage_priority
homepage_visual
homepage_short_description
homepage_proof_point
```

The homepage query returns the top three selected projects in priority order.

---

## 2. Transition: Selected Work → Lab

The transition should deliberately change the personality of the page.

Selected Work is:

```text
structured
calm
professional
visual
```

Lab becomes:

```text
playful
interactive
system-like
experimental
```

A small transitional statement can introduce the shift, for example conceptually:

> Not everything I make starts as a project.

The exact public copy will be written later in Persian.

---

## 3. Lab / Things I Build

### Purpose

Lab is where unfinished, experimental, technical, or self-initiated work is allowed to exist without needing to become a full case study.

Examples may include:

- WordPress plugins
- Figma experiments
- RTL/LTR tooling
- AI-assisted products
- small utilities
- prototypes
- design-system experiments
- code experiments
- unfinished ideas worth sharing

This section is one of the primary mechanisms for keeping sayid.ir alive between major portfolio releases.

### Bento layout

Recommended desktop direction:

```text
┌──────────────────────────┬───────────────────┐
│                          │                   │
│  Primary Lab item        │  Experiment       │
│                          │                   │
│                          │                   │
├───────────────┬──────────┴───────────────────┤
│               │                              │
│  Small item   │  Wide Lab item               │
│               │                              │
└───────────────┴──────────────────────────────┘
```

The Bento system should feel modular, but the composition should not be perfectly uniform.

Different card sizes can represent different levels of activity or importance.

### Lab status model

Each item should support a lightweight status:

```text
Exploring
Building
Beta
Shipped
Paused
Archived
```

The status should be visible but secondary.

This helps the section feel current and makes unfinished work intentional rather than incomplete.

---

## 4. Pointer interaction model

The Lab cards are the first homepage area where richer pointer interaction is encouraged.

The interaction should be inspired by proximity-based interfaces rather than card movement.

### Principle

The card itself stays physically stable.

The visual system **inside the card** reacts to the pointer.

Avoid generic 3D tilt.

### Border reveal

Default state:

```text
border: low contrast
```

Pointer enters card:

```text
pointer position
      ↓
CSS custom properties
      ↓
radial gradient / mask
      ↓
local border illumination
```

Only the border region near the pointer becomes clearly visible.

Conceptually:

```css
--pointer-x
--pointer-y

radial-gradient(
  220px circle at var(--pointer-x) var(--pointer-y),
  active,
  transparent
)
```

Exact colors and dimensions will be defined with the visual system.

### Nodes and particles

Selected cards may contain small nodes, dots, paths, or technical diagrams.

When the pointer moves:

- nearby nodes increase emphasis
- lines may brighten based on proximity
- particles can slightly converge toward a local focal point
- distant elements remain quiet

Movement should be subtle enough to remain readable and premium.

### Performance rule

Pointer interactions must use `requestAnimationFrame` or similarly efficient update patterns.

Avoid triggering React/Elementor-style layout updates on every pointer event.

Prefer:

```text
pointermove
↓
CSS variables / transform-only changes
↓
GPU-friendly rendering
```

### Reduced motion

When `prefers-reduced-motion: reduce` is enabled:

- disable particle movement
- disable convergence
- keep static border emphasis or simple hover state

The content must remain fully understandable without motion.

---

## 5. Content model for Lab

Lab should be a separate structured content type rather than hacked into portfolio projects.

Possible CPT:

```text
lab_item
```

Suggested fields:

```text
title
short_description
status
visual_type
cover_visual
external_url
internal_detail_page
repo_url
started_at
updated_at
featured
homepage_priority
tags
related_notes
related_articles
related_projects
```

Not every Lab item needs a detail page.

Some can open:

- GitHub repository
- Figma Community item
- external product
- article
- compact internal detail page

This keeps publishing friction low.

---

## 6. Homepage content freshness

Selected Work should change slowly.

Lab should change frequently.

Recommended expectation:

```text
Selected Work   → months
Lab             → days / weeks
Notes           → days
Articles        → weeks / months
```

This difference is intentional.

Professional proof remains stable while experiments keep the site visibly alive.

---

## 7. Visual relationship between the two sections

Selected Work should use larger editorial imagery and quieter backgrounds.

Lab may introduce:

- finer grids
- technical lines
- particles
- local gradients
- subtle glow
- interactive diagrams

However both must still use the same typography, spacing scale, radius system, and overall design tokens.

The goal is controlled contrast, not two separate websites.

---

## 8. Implementation boundary

Elementor should control:

- section layout
- responsive composition
- typography
- content placement
- basic card structure

A dedicated version-controlled interaction layer should control:

- pointer tracking
- local border reveal
- particle/node motion
- proximity calculations
- reduced-motion behavior

WordPress controls the content itself.

```text
WordPress → content
Elementor → presentation
Custom JS/CSS/plugin layer → behavior
```

This separation is a core implementation principle for the rebuild.

---

## 9. Current decision

Homepage sequence at this point:

```text
Hero
↓
Now
↓
Selected Work
↓
Lab
↓
Design × Code × AI (signature dark section)
↓
Latest Notes
↓
Featured Article
↓
Connect
↓
Footer
```

The next design-definition step is the **Design × Code × AI signature section**, including its interaction concept, narrative purpose, dark-mode visual system, and relationship to the Lab section.
