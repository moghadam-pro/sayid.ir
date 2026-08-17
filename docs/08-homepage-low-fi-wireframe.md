# Homepage Low-Fidelity Wireframe — v1

This document turns the homepage narrative into a first layout-level wireframe.

The goal is to define proportion, rhythm, hierarchy, and responsive behavior before visual styling.

---

## 1. Global layout principles

### Desktop content width

Use a wide outer canvas with a controlled content container.

Suggested starting values:

```text
Viewport: fluid
Outer horizontal padding: 32–64px depending on viewport
Primary content max-width: ~1680px
Editorial reading width: ~720–820px
```

The exact numbers will be tuned after testing the existing Hero.

### Vertical rhythm

Sections should not all use the same spacing.

Recommended rhythm:

```text
Hero             full viewport
Now              compact / medium
Selected Work    large
Lab              large
Signature        immersive / very large
Notes            medium
Featured Article large
Connect          large but sparse
Footer           compact
```

The page should feel composed, not mechanically stacked.

---

## 2. Desktop wireframe

```text
┌────────────────────────────────────────────────────────────────────┐
│ HEADER                                                             │
│                                                                    │
│                                                                    │
│                         HERO                                       │
│                                                                    │
│                 Existing hero composition                          │
│                                                                    │
│                                                                    │
│                               ●                                    │
│                               │                                    │
│                               ↓                                    │
└────────────────────────────────────────────────────────────────────┘

                   [first scroll intent / mount]

┌────────────────────────────────────────────────────────────────────┐
│ NOW                                                                │
│                                                                    │
│  Large current-thought statement        Building                   │
│                                         Exploring                  │
│                                         Learning                   │
│                                                                    │
│  last updated                                                     │
└────────────────────────────────────────────────────────────────────┘

          generous transition / breathing space

┌────────────────────────────────────────────────────────────────────┐
│ SELECTED WORK                                                      │
│                                                                    │
│  intro                                                             │
│                                                                    │
│  ┌──────────────────────────────────────────────────────────────┐  │
│  │                                                              │  │
│  │                 FEATURED PROJECT                             │  │
│  │                                                              │  │
│  └──────────────────────────────────────────────────────────────┘  │
│                                                                    │
│  ┌────────────────────────────┐ ┌────────────────────────────┐     │
│  │ PROJECT 02                 │ │ PROJECT 03                 │     │
│  └────────────────────────────┘ └────────────────────────────┘     │
│                                                                    │
│                                             all projects →         │
└────────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────────┐
│ LAB                                                                │
│                                                                    │
│  title + short statement                                           │
│                                                                    │
│  ┌───────────────────────────────┬──────────────────────────────┐  │
│  │                               │                              │  │
│  │ LARGE LAB ITEM                │ EXPERIMENT                   │  │
│  │                               │                              │  │
│  ├──────────────────┬────────────┴──────────────────────────────┤  │
│  │ SMALL ITEM       │ WIDE LAB ITEM                            │  │
│  │                  │                                           │  │
│  └──────────────────┴───────────────────────────────────────────┘  │
│                                                                    │
│  pointer-reactive internal visuals                                 │
└────────────────────────────────────────────────────────────────────┘

██████████████████████████████████████████████████████████████████████
█                                                                    █
█  DESIGN × CODE × AI                                                █
█                                                                    █
█  thesis statement                                                  █
█                                                                    █
█                   interactive relationship network                 █
█                                                                    █
█                 ●──────●                                           █
█                /        \                                          █
█           ●───●          ●───●                                     █
█                \        /                                          █
█                 ●──────●                                           █
█                                                                    █
█  active relationship explanation                                  █
█                                                                    █
██████████████████████████████████████████████████████████████████████

┌────────────────────────────────────────────────────────────────────┐
│ LATEST NOTES                                                       │
│                                                                    │
│  date       note title                                      tag    │
│  date       note title                                      tag    │
│  date       note title                                      tag    │
│  date       note title                                      tag    │
│                                                                    │
│                                                   all notes →      │
└────────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────────┐
│ FEATURED ARTICLE                                                   │
│                                                                    │
│  ┌──────────────────────────────────────────────────────────────┐  │
│  │                                                              │  │
│  │                  EDITORIAL VISUAL                            │  │
│  │                                                              │  │
│  └──────────────────────────────────────────────────────────────┘  │
│                                                                    │
│  Featured Article                                                  │
│  Large title                                                       │
│  Short introduction                                                │
│  Read →                                                            │
└────────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────────┐
│                                                                    │
│                                                                    │
│                       CONNECT                                      │
│                                                                    │
│             Large conversational invitation                        │
│                                                                    │
│                  Primary CTA    Secondary link                      │
│                                                                    │
│                                                                    │
└────────────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────────────┐
│ FOOTER                                                             │
│                                                                    │
│ Identity        Navigation        Elsewhere        Utility          │
│                                                                    │
│ English portfolio → moghadam.pro                                   │
└────────────────────────────────────────────────────────────────────┘
```

---

## 3. Approximate section-height targets

These are starting proportions, not fixed CSS values.

```text
Hero             100dvh
Now              ~55–70vh desktop
Selected Work    content-driven, roughly 120–160vh
Lab              roughly 100–130vh
Signature        roughly 90–120vh
Notes            ~60–80vh
Featured Article ~90–110vh
Connect          ~70–90vh
Footer           auto / compact
```

The goal is to prevent every section from feeling like another full-screen slide.

---

## 4. Hero and first-scroll boundary

Initial DOM-visible experience:

```text
Header
Hero
Scroll Cue
Deferred mount root
```

The rest of the homepage is prefetched but not mounted when possible.

On first valid scroll intent:

```text
scroll cue fades
↓
remaining sections mount
↓
overflow unlocks
↓
scroll continues naturally
```

If entering through history restoration, anchor navigation, or a deep-link state, bypass the Hero lock.

---

## 5. Tablet wireframe changes

At medium widths:

- reduce outer gutters
- keep Featured Work full-width
- keep secondary work cards side by side where space allows
- simplify Lab Bento from asymmetric 3–4-column logic to 2 columns
- simplify the signature network
- avoid excessive section heights

Concept:

```text
Hero
Now: 60/40 split or stacked
Featured Work
Project 02 | Project 03
Lab 2-column
Signature
Notes
Article
Connect
Footer
```

---

## 6. Mobile wireframe

```text
┌─────────────────────┐
│ HERO                │
│                     │
│ existing content    │
│                     │
│         ●           │
│         ↓           │
└─────────────────────┘

┌─────────────────────┐
│ NOW                 │
│ current thought     │
│                     │
│ Building            │
│ Exploring           │
│ Learning            │
└─────────────────────┘

┌─────────────────────┐
│ FEATURED WORK       │
└─────────────────────┘

┌─────────────────────┐
│ PROJECT 02          │
└─────────────────────┘

┌─────────────────────┐
│ PROJECT 03          │
└─────────────────────┘

┌─────────────────────┐
│ LAB ITEM            │
└─────────────────────┘
┌─────────────────────┐
│ LAB ITEM            │
└─────────────────────┘
┌─────────────────────┐
│ LAB ITEM            │
└─────────────────────┘

███████████████████████
█ DESIGN × CODE × AI █
█                   █
█ simplified network █
█                   █
█ relationship text █
███████████████████████

┌─────────────────────┐
│ NOTES               │
│ date/tag            │
│ title               │
│                     │
│ date/tag            │
│ title               │
└─────────────────────┘

┌─────────────────────┐
│ ARTICLE VISUAL      │
│                     │
│ Article title       │
│ intro               │
└─────────────────────┘

┌─────────────────────┐
│ CONNECT             │
│                     │
│ CTA                 │
└─────────────────────┘

┌─────────────────────┐
│ FOOTER              │
└─────────────────────┘
```

On mobile the emphasis shifts from complex composition to strong sequencing and typography.

---

## 7. Grid direction

Starting recommendation for desktop:

```text
12-column grid
```

Rather than forcing every component onto equal columns, use the grid as an alignment system.

Possible alignments:

```text
Now statement        7 cols
Now status           4 cols
Gap                  1 col

Featured Work        12 cols
Secondary Work       6 + 6

Lab                  asymmetric spans
Editorial reading    ~6 cols centered or offset
```

Tablet can move to 8 columns, mobile to 4.

---

## 8. Spacing direction

Use a small shared spacing scale rather than arbitrary values.

Initial semantic levels:

```text
space-1  micro
space-2  inline
space-3  component
space-4  card
space-5  section internal
space-6  section separation
space-7  major narrative break
```

Actual pixel/rem tokens will be defined in the visual-system phase.

---

## 9. Alignment personality

Avoid centering every section.

Preferred balance:

- Hero follows existing composition
- Now is asymmetric
- Selected Work is strong and grid-aligned
- Lab is modular/asymmetric
- Signature section can use centered visual gravity but offset text
- Notes is editorial and left/right aligned according to RTL layout
- Connect can become more centered and spacious

This creates rhythm through alignment, not only color changes.

---

## 10. RTL considerations

The Persian site is RTL-first.

This affects more than text direction:

- primary reading entry begins from the right
- metadata hierarchy follows RTL scanning
- directional arrows should be context-aware
- asymmetric compositions should be visually mirrored only when the hierarchy benefits from it
- diagrams and abstract networks do not need mechanical mirroring if direction has no semantic meaning

The layout should be designed natively in RTL rather than designed LTR and flipped later.

---

## 11. Next phase

With the first low-fidelity homepage wireframe defined, the next work is the shared **Visual System v1**:

```text
Typography
Color roles
Background / surface system
Grid and container values
Spacing tokens
Border system
Radius system
Shadow / glow rules
Motion tokens
Interactive states
RTL rules
Responsive breakpoints
```

After that, the Hero can be audited against the new system and the first high-fidelity homepage direction can be produced.
