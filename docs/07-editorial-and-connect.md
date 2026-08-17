# Editorial Homepage Sections — Notes, Article, Connect & Footer

This document defines the quieter editorial half of the sayid.ir homepage.

These sections follow the interactive dark signature section and intentionally reduce visual intensity.

The sequence is:

```text
Design × Code × AI
↓
Latest Notes
↓
Featured Article
↓
Connect
↓
Footer
```

---

## 1. Latest Notes

### Purpose

Latest Notes is the clearest signal that sayid.ir is alive.

Notes are lightweight observations, discoveries, lessons, experiments, and small ideas that do not need the production effort of a full article.

The homepage should show a small recent stream rather than a card-heavy blog grid.

### Visual direction

Preferred desktop direction:

```text
Latest Notes

17 Aug     A short note title                              AI
14 Aug     Another observation                             Design
11 Aug     Something learned while building                Build
08 Aug     A Figma experiment                              Figma

All notes →
```

The section should feel closer to an editorial index than a news website.

### Recommended item content

- date
- title
- one category or tag
- optional reading time only if useful

Avoid thumbnails by default.

The title itself should carry the section.

### Interaction

On hover/focus:

- row becomes slightly more emphasized
- arrow or directional cue may appear
- category gains emphasis
- optional small contextual preview can appear on large screens later

Do not turn each note into a large card.

### Quantity

Homepage recommendation: **4–5 latest notes**.

This is enough to show activity without extending the page unnecessarily.

---

## 2. Notes publishing model

Notes should have the lowest publishing friction on the site.

Possible required fields:

```text
title
body
date
tags
```

Optional fields:

```text
related_project
related_lab_item
related_article
external_reference
```

Publishing a note should automatically update:

- Latest Notes on homepage
- Notes archive
- related topic/tag pages
- related content blocks where relevant

No manual Elementor editing should be required.

---

## 3. Featured Article

### Purpose

Articles represent deeper thinking and should feel intentionally different from Notes.

Notes communicate frequency.

Articles communicate depth.

The homepage should feature **one article**, not a full article grid.

### Visual direction

```text
┌──────────────────────────────────────────────────────┐
│                                                      │
│                Editorial / conceptual visual          │
│                                                      │
├──────────────────────────────────────────────────────┤
│ FEATURED ARTICLE                                     │
│                                                      │
│ Large article title                                  │
│ Short editorial introduction                         │
│                                                      │
│ Read article →                                       │
└──────────────────────────────────────────────────────┘
```

The section can use more expressive typography than Notes.

### Featured selection

Possible rules:

1. manually selected featured article, if set
2. otherwise latest long-form article

This allows intentional curation without creating maintenance work.

### Image direction

The article visual should not rely on generic stock photography.

Preferred options:

- custom editorial artwork
- diagrams
- generated conceptual visuals with a consistent system
- photography when directly relevant
- product/interface details where appropriate

---

## 4. Transition: Article → Connect

The page should begin reducing content density after the Featured Article.

The user has already seen:

- identity
- current activity
- professional work
- experiments
- worldview
- recent thinking
- deeper writing

At this point the next action should be simple.

No additional proof-heavy section is needed.

---

## 5. Connect

### Purpose

Connect is an invitation, not a traditional contact form embedded into the homepage.

The section should feel personal and low-friction.

Conceptual copy direction:

```text
یه چیز جالب توی ذهنت داری؟

حرف بزنیم.
```

Exact copy will be refined later.

### Preferred actions

Primary:

```text
Email / Start a conversation
```

Secondary:

```text
LinkedIn
```

Optional additional links may exist, but the section should not become a social-link directory.

### Visual behavior

Connect should have generous empty space.

The CTA may use a subtle pointer-reactive treatment from the broader visual system, but should not compete with Lab or the signature section.

---

## 6. Contact page relationship

The homepage Connect section is not a replacement for the dedicated Contact page.

Homepage:

```text
quick invitation
```

Contact page:

```text
more context
contact options
optional form
availability / collaboration details
```

The homepage CTA can lead to the Contact page or directly to email depending on final interaction decisions.

---

## 7. Footer

The footer should stay compact.

Recommended content groups:

```text
Identity
Sayid Moghadam
Product Designer / Builder

Navigation
Work
Lab
Notes
Articles
About

Elsewhere
LinkedIn
GitHub
Figma
Dribbble

Utility
RSS
English website
Theme preference if needed
```

Do not repeat the entire sitemap.

### English-site link

The relationship with moghadam.pro should be explicit but quiet.

Possible treatment:

```text
English portfolio → moghadam.pro
```

This reinforces the intended separation between the two sites.

---

## 8. End-of-page detail

A small final line can reinforce the living-site idea.

Concept examples:

```text
Built, changed, and occasionally broken by Sayid.
```

or a last-updated indicator if it remains meaningful.

The final public language should stay conversational.

---

## 9. Responsive behavior

### Notes

Mobile rows simplify to:

```text
date / tag
Title
```

Avoid horizontal metadata layouts that become compressed.

### Featured Article

Visual and text stack vertically.

### Connect

Large typography remains, but line length is controlled.

### Footer

Navigation groups stack or form a simple two-column layout depending on width.

---

## 10. Performance and DOM strategy

These sections are part of the deferred homepage content introduced after first scroll intent.

Notes and article data can be prefetched while the browser is idle after initial Hero load.

Images below the fold should remain lazy-loaded even when section markup has been mounted.

The first-load optimization should distinguish between:

```text
fetching data
mounting DOM
loading heavy media
```

These do not need to happen simultaneously.

---

## 11. Homepage structure — first complete draft

```text
00 Hero
   ↓
01 Now
   ↓
02 Selected Work
   ↓
03 Lab
   ↓
04 Design × Code × AI
   ↓
05 Latest Notes
   ↓
06 Featured Article
   ↓
07 Connect
   ↓
08 Footer
```

This is now the first complete homepage narrative draft.

The next phase should move from section definition to **low-fidelity homepage wireframing**, followed by the shared visual system: layout grid, spacing, typography, color roles, surfaces, borders, radius, motion, and responsive rules.
