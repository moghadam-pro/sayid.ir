# Hero Entry, Scroll Cue & Deferred Homepage Mount

This document defines the first interaction of the new `sayid.ir` homepage: the initial Hero, the scroll affordance, and the transition into the dynamic content below.

## Goal

The first viewport should feel intentional, calm, and complete on its own.

At first load, the visitor sees only:

- Header
- Hero
- A small scroll cue near the bottom edge

The rest of the homepage should not be mounted into the DOM immediately. It can be prefetched during browser idle time, then mounted when the user shows clear intent to continue.

The interaction should feel instant. The user should not experience the first scroll as blocked or wasted.

---

## 1. Hero behavior

The current Hero direction should be preserved. The main work is responsive control, especially on very large displays.

### Hero shell

Recommended behavior:

- Use `100dvh` for the first viewport.
- Keep the visual composition vertically centered.
- Apply a maximum width to the inner composition.
- Cap hero media dimensions independently from the viewport size.
- Use fluid typography with hard minimum and maximum values.

Conceptual rules:

```css
.hero {
  min-height: 100vh;
  min-height: 100dvh;
  overflow: clip;
}

.hero__inner {
  width: min(100% - 48px, 1720px);
  min-height: 100dvh;
  margin-inline: auto;
}

.hero__visual {
  max-width: var(--hero-visual-max-width);
  max-height: min(78dvh, 880px);
}
```

Exact values should be tuned against the current Hero rather than treated as final tokens.

### Large-screen rule

A larger viewport must create more breathing room, not infinitely enlarge the Hero artwork.

The composition should remain controlled on common large-display ranges such as:

- 2560×1440
- 3440×1440 ultrawide
- 3840×2160

The Hero media, copy width, and CTA group should each have independent maximum sizes.

---

## 2. Initial page state

On a normal first visit to the homepage:

```text
DOM
├── Header
├── Hero
├── Scroll Cue
└── Deferred Homepage Root (empty)
```

The page begins in an entry state.

Suggested state attribute:

```html
<html data-home-entry="locked">
```

The browser scrollbar should not be visible yet.

This state is temporary and exists only until the visitor expresses intent to continue.

---

## 3. Scroll cue

The scroll cue should be a micro-interaction, not a button competing with the Hero.

### Visual character

- Small
- Low contrast
- Positioned close to the bottom safe area
- Uses a simple vector / line-based graphic
- Gentle repeating motion
- Persian helper copy can be used if needed, but the visual should communicate the interaction even without text

Possible motion:

```text
●
│
↓
```

The dot can travel a short distance downward, fade, and reset.

Recommended cycle:

- 1.8–2.4 seconds
- subtle easing
- no aggressive bouncing

### Reduced motion

With `prefers-reduced-motion: reduce`, the cue stays static or uses only a simple opacity change.

---

## 4. What counts as "continue" intent

Do not listen only for mouse-wheel events.

The transition should support:

- Mouse wheel downward
- Trackpad downward gesture
- Touch swipe upward
- `ArrowDown`
- `PageDown`
- `Space` when appropriate
- Clicking/tapping the scroll cue

Upward wheel movement should not trigger the transition.

---

## 5. Prefetch first, mount later

The content below the Hero should ideally be fetched before the user asks for it, without mounting it immediately.

Preferred sequence:

```text
Hero becomes interactive
        ↓
Browser becomes idle
        ↓
Prefetch remaining homepage payload
        ↓
Keep payload/template ready
        ↓
User shows scroll intent
        ↓
Mount remaining sections
        ↓
Unlock page scrolling
        ↓
Continue the user's original movement
```

This keeps the initial DOM light while avoiding a network delay on the first scroll.

Possible browser mechanisms:

- `requestIdleCallback()` with a fallback timer
- a lightweight REST endpoint
- a WordPress AJAX/REST response
- a pre-rendered template payload

The final transport can be chosen during implementation.

---

## 6. First-scroll transition

The transition should be short enough to feel like normal scrolling.

Target sequence:

1. Detect first downward intent.
2. Mark the entry state as transitioning.
3. Fade / retract the scroll cue.
4. Mount the deferred homepage content.
5. Unlock document scrolling.
6. Preserve or recreate the user's intended scroll delta.
7. Move naturally into the `Now` section.

Suggested states:

```text
locked → transitioning → unlocked
```

Do not create a cinematic transition that takes control away from the visitor.

The page should still feel like a website, not a presentation deck.

---

## 7. Transition into the Now section

The first section below the Hero is `Now`.

The handoff should feel like the Hero opens into the rest of the site rather than abruptly revealing a hidden block.

Possible composition:

```text
[ Hero viewport ]

             scroll cue
                  ↓
──────────────────────── subtle boundary / spacing

NOW
Currently building ...
Currently exploring ...
Currently learning ...
```

The `Now` section should be relatively compact and immediately communicate that the site is alive.

Its content should be extremely easy to edit in WordPress.

Suggested content fields:

- Primary current focus
- Secondary exploration
- Learning / curiosity item
- Optional status label
- Last updated date

Example structure:

```text
NOW

Building
sayid.ir v2

Exploring
AI-assisted product building

Learning
Figma → code workflows

Updated Aug 2026
```

This is content structure, not final copy.

---

## 8. Important exceptions

The Hero lock must not become an obstacle.

Skip the locked entry state when any of these apply:

### Anchor / deep link

If the visitor opens:

```text
sayid.ir/#lab
```

or another valid homepage anchor, render the full page and allow the browser to navigate normally.

### Browser history restoration

If the visitor presses Back and the browser restores a previous scroll position, do not force them back into the Hero lock.

### Internal navigation back to Home

If the homepage is revisited during the same session, we may choose to skip the lock and render the full page immediately.

Recommended default:

- first meaningful homepage visit in session → entry interaction enabled
- repeat homepage visit → full homepage available immediately

This should be tested rather than treated as a rigid rule.

### JavaScript failure

The full homepage must remain accessible when JavaScript fails.

Progressive enhancement is preferred:

- server-rendered / accessible fallback
- JavaScript enhances the initial experience
- JavaScript must not be required to discover core content

---

## 9. Accessibility

The interaction should never trap keyboard or assistive-technology users.

Requirements:

- no permanent focus trap
- semantic page structure remains valid
- scroll cue has an accessible label if interactive
- keyboard continuation works
- reduced-motion preference is respected
- the hidden/deferred content must not be exposed to the accessibility tree before it is mounted or intentionally revealed
- no essential information exists only inside animation

---

## 10. Performance principles

The Hero is the first impression, so visual quality matters, but the initial payload should stay controlled.

Priorities:

1. Hero copy and core layout render immediately.
2. Hero media is properly sized and optimized.
3. Below-the-fold imagery is not eagerly loaded.
4. Remaining homepage data can be prefetched during idle time.
5. Heavy Lab / interactive visual scripts load only before they are likely to be needed.
6. Pointer effects should use transforms, opacity, CSS masks, and animation-frame scheduling rather than frequent layout reads/writes.

The goal is not merely a good Lighthouse score; the entry interaction must remain responsive on normal laptops and phones.

---

## 11. Interaction architecture

Keep responsibilities separated:

### WordPress

Owns:

- `Now` content
- Projects
- Lab entries
- Notes
- Articles
- taxonomies and relationships

### Elementor

Owns:

- layout composition
- typography
- spacing
- presentation templates

### sayid.ir interaction layer

Owns:

- initial entry state
- scroll-intent detection
- deferred mount
- pointer tracking
- Bento border effects
- interactive node visualizations
- reduced-motion behavior

The interaction layer should live in version-controlled code instead of scattered Elementor HTML widgets.

---

## 12. Current decision

For the first implementation prototype:

- Preserve the current Hero visual direction.
- Fix large-screen media scaling and composition limits.
- Add a subtle scroll affordance.
- Prefetch below-the-fold homepage content after initial load/idle.
- Mount the remaining homepage on first downward intent.
- Transition directly into a compact dynamic `Now` section.
- Do not apply the Hero lock when it would interfere with deep links, history restoration, accessibility, or repeat navigation.

This interaction should be prototyped before implementing the rest of the homepage motion system.
