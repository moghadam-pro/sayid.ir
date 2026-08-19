# Deployment & QA

## Deployment

1. `git pull` (or deploy via your normal pipeline) so
   `plugins/sayid-site-core` exists in the repository checkout.
2. Copy/symlink `plugins/sayid-site-core` into the live site's
   `wp-content/plugins/sayid-site-core`.
3. Activate the plugin. This registers post types/taxonomy and flushes
   rewrite rules automatically (`register_activation_hook`).
4. Add the Estedad variable font file at
   `assets/fonts/Estedad-Variable.woff2` (see plugin README "Fonts" — not
   bundled in this repo).
5. Follow `docs/13-elementor-build-guide.md` once, in full, to wire the Hero
   CSS classes and the homepage/footer widgets.
6. Publish at least one item of each content type so every homepage section
   has something to render (an empty query renders nothing — the page
   won't break, but you also can't verify a section you haven't fed).
7. Run through the QA checklist below before calling the rebuild live.

Nothing in this plugin writes to the database beyond standard WordPress
options/postmeta — there is no separate migration script to run, and no
secrets, credentials or dumps are stored in this repository (brief §63).

## Responsive QA

Check every item below at each of these viewports (brief §32 / docs/10 §16):

```
Mobile:   360×800   390×844   430×932
Tablet:   768×1024  1024×768  1180×820
Desktop:  1280×800  1366×768  1440×900  1600×900  1728×1117  1920×1080
Large:    2560×1440 3440×1440 3840×2160
```

Plus: at least one unusually **short** desktop height (e.g. 1440×720) to
exercise the Hero's `max-height: 760px` safety rule.

For each viewport, on the homepage:

- [ ] Hero fills the first viewport with no vertical scrollbar, and the
      Hero media never exceeds `min(78dvh, 900px)` height / `min(52vw,
      920px)` width — large displays should show more whitespace around the
      Hero, never a larger portrait.
- [ ] Scroll cue is visible, small, and does not overlap Hero content.
- [ ] Scrolling (wheel/touch/keyboard) on first visit reveals the rest of
      the homepage in one continuous motion, no second gesture required.
- [ ] Selected Work: 1 featured + 2 secondary on desktop/tablet stack
      correctly; mobile shows featured, then 02, then 03, then the CTA.
- [ ] Lab Bento: asymmetric on desktop (1200px+), simplifies to 2 columns
      on tablet, stacks to 1 column on mobile — no card becomes illegibly
      small.
- [ ] Design × Code × AI: network is legible at every width; on mobile the
      node labels don't overlap and tapping a node updates the relationship
      caption.
- [ ] Notes rows don't produce cramped horizontal metadata on mobile (date/
      tag should stack above the title, not compress inline).
- [ ] Featured Article stacks media above body below 1200px.
- [ ] Connect stays centered with a controlled line length at ultra-wide.
- [ ] Footer nav groups reflow sensibly; theme switch stays reachable and
      usable at 360px width.
- [ ] Long Persian titles (test with a genuinely long project/article
      title) do not break card layouts or overflow their container.

## Accessibility QA

- [ ] Full keyboard pass: Tab through header nav → Hero → scroll cue →
      (after continuing) Now → project cards → Lab cards → signature nodes
      → note rows → featured article → connect CTAs → footer → theme
      switch. Every interactive element has a visible focus ring
      (`:focus-visible`, brand-purple/yellow ring per theme).
- [ ] `ArrowDown` / `PageDown` / `Space` on the homepage (focus on `<body>`,
      not inside a Hero control) triggers the same continuation as scroll.
- [ ] Screen reader spot-check (VoiceOver/NVDA): Hero and Now content is
      announced normally; the deferred homepage content is *not* announced
      while locked (verify the `hidden` attribute is present on
      `#homepage-deferred-root` before the first gesture, absent after).
- [ ] Signature network: tabbing through node buttons updates the
      `aria-live="polite"` relationship caption; the interaction is fully
      operable without a pointer.
- [ ] `prefers-reduced-motion: reduce` (OS-level toggle): scroll cue dot
      stops animating, Now's status dot stops pulsing, Lab card border
      transitions become instant, signature network's idle "breathing"
      animation stops, and the deferred-content reveal fade is skipped.
- [ ] Color contrast: body text vs. background, muted text vs. background,
      and status dots/labels all meet WCAG AA in both light and dark theme.
- [ ] No information exists only inside a hover state — spot check the Lab
      card border reveal and the signature network by disabling `:hover`
      entirely (e.g. via devtools) and confirming everything is still
      readable/operable via focus/tap alone.

## Performance QA

- [ ] Homepage initial payload does not eagerly load below-fold images —
      confirm `loading="lazy"` is present on cover images rendered via
      `sayid_cover_html()`.
- [ ] `sayid-lab-pointer` and `sayid-signature-network` are registered
      site-wide but only *enqueued* by the section that actually renders
      them (`Sayid_Core_Render::lab()`/`signature()`/`deferred_homepage()`,
      and the Lab archive template) — check the Network tab on a page with
      neither section (e.g. Contact) and confirm neither script loads, then
      confirm both do load on a scratch page containing just
      `[sayid_lab]` or `[sayid_signature]`, without needing to be the
      homepage.
- [ ] No layout shift when the deferred homepage content reveals (the
      `hidden`→visible transition is opacity-only, not a height/size
      change, so there should be none).
- [ ] Lab card pointer interaction stays smooth (no dropped frames) while
      moving the pointer quickly across a card — it should only ever write
      CSS custom properties inside a single batched `requestAnimationFrame`
      callback (see `lab-pointer.js`).
- [ ] Signature network interaction does not run any per-frame JavaScript —
      it is purely event-driven (hover/focus/click), so there is nothing to
      profile here beyond normal event-handler cost.

## Troubleshooting

**Archive pages (`/notes/`, `/lab/`, `/work/`) 404 after activating the
plugin.** Visit Settings → Permalinks and click Save once. WordPress
sometimes needs an explicit permalink flush on hosts with aggressive
object/page caching even though the plugin calls `flush_rewrite_rules()` on
activation.

**A homepage section is missing entirely.** Every section's render function
returns an empty string (not a broken/placeholder box) when its query has
no results — this is intentional (brief §57: don't fabricate content to
fill a grid). Check that at least one qualifying item exists and is
`publish` status: a Project needs "نمایش در صفحه‌ی اصلی" checked; a Lab item
needs a Status other than "آرشیو شده"; Now needs its options page saved at
least once.

**Hero doesn't respect the responsive contract.** The three CSS classes
(`home-hero`, `home-hero__inner`, `home-hero__media`) are not applied
automatically — confirm they were added to the correct Elementor elements
per `docs/13-elementor-build-guide.md` §1. Inspect the Hero section in
devtools and confirm those exact class names are present on the section,
its inner container, and the media widget respectively.

**Theme flashes light before switching to dark on load.** Confirm the
inline bootstrap script (`Sayid_Core_Assets::inline_theme_bootstrap()`,
hooked at `wp_head` priority `0`) is actually the first `<script>` in
`<head>` — a caching/minification plugin that reorders or defers inline
`<head>` scripts can reintroduce the flash. Exclude this specific inline
script from any "move scripts to footer" optimization.

**Signature network nodes overlap on a very long custom node label.** Node
positions are percentage-based (`--nx`/`--ny` per node in
`Sayid_Core_Render::signature()`); if you edit the node graph, keep labels
short (2–3 Persian words) since the layout is not text-measurement-aware.

**A Lab item's pointer border doesn't appear.** By design: it's a
`(hover: hover) and (pointer: fine)`-gated enhancement (brief §19 "Touch
devices must render a complete static experience"). Test with an actual
mouse/trackpad, not touch emulation in devtools set to a touch device
profile.
