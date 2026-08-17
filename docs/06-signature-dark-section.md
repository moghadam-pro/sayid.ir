# Signature Dark Section — Design × Code × AI

This document defines the narrative, visual, and interaction direction for the dark signature section of the sayid.ir homepage.

The section appears after **Lab** and before **Latest Notes**.

Its purpose is not to list skills. It should communicate the relationship between disciplines and make that relationship feel interactive.

---

## 1. Core idea

Working title:

```text
Design × Code × AI
```

The central idea:

> The most interesting work happens in the connections between disciplines.

The exact public Persian copy will be refined later.

This section should make the visitor understand that the site owner works across product design, systems, implementation, experimentation, and AI-assisted building — without presenting a traditional skill matrix.

---

## 2. Role in homepage rhythm

Before this section:

```text
Selected Work → credibility
Lab → experimentation
```

This section becomes:

```text
Signature section → worldview / operating model
```

After it:

```text
Latest Notes → thinking in public
```

It therefore acts as a bridge from **things made** to **ideas written**.

---

## 3. Atmosphere

The section should be visually distinct from the surrounding homepage.

Direction:

- dark background
- high depth but low visual noise
- subtle grid or spatial reference system
- fine paths and nodes
- local gradients, not giant decorative blobs
- soft luminous accents
- strong typography
- generous vertical space

It should feel immersive, technical, and calm rather than futuristic for its own sake.

Avoid:

- neon cyberpunk styling
- excessive glow
- random particle fields
- generic AI imagery
- 3D floating glass cards
- skill-progress bars

---

## 4. Narrative structure

Recommended desktop structure:

```text
[ Small eyebrow / context ]

Design × Code × AI

A short thesis statement explaining that the valuable part is the connection between disciplines.


             Research
                ●
               / \
              /   \
             /     \
     Design ●───────● Systems
            \       /
             \     /
              \   /
               ●
               AI
               │
               │
               ●
              Code

[ Optional supporting statement / interaction hint ]
```

The diagram is conceptual. Final geometry should be asymmetrical and more organic than a perfect network chart.

---

## 5. Interaction model

### Default state

The network is calm.

- nodes have low emphasis
- only a subset of connections are clearly visible
- background geometry is subtle
- motion is nearly imperceptible

### Pointer proximity

When the pointer approaches a node:

1. the nearest node gains emphasis
2. its direct connections brighten
3. related labels become more legible
4. secondary nodes shift by a very small amount toward the active relationship
5. unrelated connections reduce emphasis

This should create **focus**, not chaos.

### Meaningful relationships

Each connection should correspond to an actual concept.

Examples:

```text
Design ↔ Systems     → scalable product language
Design ↔ Research    → problem framing
Design ↔ AI          → accelerated exploration
Code ↔ Design        → implementation awareness
Code ↔ AI            → product building
Systems ↔ Code       → tokens / reusable foundations
AI ↔ Research        → synthesis / exploration
```

The network should not contain decorative meaningless edges.

---

## 6. Micro-copy behavior

An optional small supporting line can change when a relationship becomes active.

Example conceptual states:

```text
Design + Systems
Creating consistency without removing flexibility.
```

```text
Design + Code
Understanding the material changes the design.
```

```text
AI + Building
Moving from idea to working experiment faster.
```

This gives the visual interaction semantic value.

The final copy will be written in Persian and should remain conversational rather than academic.

---

## 7. Scroll behavior

The section should not hijack scrolling.

No forced scroll-jacking or mandatory pinned sequence is required for the first version.

Preferred behavior:

- section enters naturally
- network gradually becomes visible as it enters viewport
- pointer interaction becomes active after visibility threshold
- text remains readable without interaction

A small amount of scroll-linked parallax may be considered later, but only if it adds depth without affecting navigation control.

---

## 8. Mobile behavior

Pointer interactions cannot be the only interaction model.

Mobile direction:

- simplified node layout
- tap a node or relationship to focus
- alternatively rotate through relationships automatically with long intervals
- no dense particle motion
- no hover-dependent content

The section must remain meaningful as a static composition.

Recommended mobile hierarchy:

```text
Title
Thesis
Interactive/static network
Selected relationship explanation
```

---

## 9. Accessibility

The network is enhancement, not primary content.

Requirements:

- thesis and concepts exist as real text in DOM
- keyboard focus can activate meaningful relationships if interactive controls are used
- sufficient text contrast
- reduced-motion mode disables node movement and animated convergence
- semantic content remains understandable when JavaScript is unavailable

---

## 10. Performance direction

The first implementation should prefer SVG + CSS/JS over canvas/WebGL unless testing proves a stronger need.

Why:

- easier responsive control
- easier accessibility
- easier Elementor integration
- lower implementation overhead
- direct control of paths and nodes

Possible architecture:

```text
SVG network
↓
pointer / focus event
↓
nearest relationship calculation
↓
CSS class / CSS variable update
↓
opacity + transform + stroke changes
```

Use `requestAnimationFrame` for pointer-driven calculations.

Canvas/WebGL should only be introduced if the final visual concept genuinely requires it.

---

## 11. Design token relationship

Even though the section is dark, it should not become a separate theme.

It must use the shared system for:

- typography
- spacing
- radii
- motion duration
- easing
- accent colors

A dark-mode token layer can override surfaces and text colors while preserving the same semantic tokens.

---

## 12. Transition into Latest Notes

The exit from this dark immersive section should become intentionally quiet.

Concept:

```text
Dark network
↓
connections fade
↓
one line continues downward
↓
light background returns
↓
Latest Notes
```

This creates a visual transition from connected disciplines into written thoughts.

---

## 13. Implementation ownership

WordPress:

- optional relationship descriptions
- editable thesis copy if needed

Elementor:

- layout
- title
- surrounding content
- section sizing

Version-controlled custom interaction layer:

- SVG markup/component
- pointer/focus behavior
- relationship state
- animation
- reduced-motion handling

---

## 14. Current homepage sequence

```text
Hero
↓
Now
↓
Selected Work
↓
Lab
↓
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

The next definition step is the editorial half of the homepage: **Latest Notes, Featured Article, Connect, and Footer**, followed by the first low-fidelity homepage wireframe and visual-system definition.
