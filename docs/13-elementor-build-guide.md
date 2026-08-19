# Elementor Build Guide

This document exists because of a real constraint: this build was produced
in a session with **no access to the live `sayid.ir` WordPress database or
Elementor installation** (outbound network access to `sayid.ir` is blocked
in this environment, and no WordPress credentials were provided). Rather
than fabricate an Elementor Theme Builder export that doesn't actually
exist, `sayid-site-core` ships as installable code plus this precise,
reproducible set of steps for wiring it into Elementor by hand — this is
the honest deliverable per brief §62.D.

Once installed on the real site, follow this guide once; it should take
well under an hour end to end.

## 0. Prerequisites

- `sayid-site-core` installed and activated (see plugin README).
- Hello Elementor theme active, Elementor (Pro recommended) active.
- At least one Note, one Lab item (status ≠ Archived), one Project with
  "نمایش در صفحه‌ی اصلی" checked, and one Article exist and are published —
  otherwise the corresponding homepage section renders nothing (each
  section's render function returns an empty string when its query is
  empty, so the page never shows a broken/empty box, but you also won't see
  anything to check your work against).

## 1. Homepage — Hero (existing, do not rebuild)

**Do not recreate the Hero.** Open the existing Home page in Elementor and
locate the Hero Section.

1. Select the **Hero Section** itself → Advanced tab → CSS Classes → add
   `home-hero`.
2. Select the Section's **inner Container/Column** that wraps the Hero's
   text + media → Advanced → CSS Classes → add `home-hero__inner`.
3. Select the Hero's **image/media widget** (whatever widget currently
   renders the portrait/illustration) → Advanced → CSS Classes → add
   `home-hero__media`.
4. Save. This alone activates the responsive contract in
   `components.css` (`min-height: 100dvh`, capped media size, the
   `max-height: 760px` short-viewport safety rule) without touching a single
   pixel of the Hero's existing content, copy, or composition.
5. As the **last widget inside the Hero Section**, add an **HTML** or
   **Shortcode** widget containing:
   ```
   [sayid_scroll_cue]
   ```
   (or drag the **Sayid — نشانگر اسکرول** widget from the *Sayid — sayid.ir*
   category if Elementor Pro is active). The cue is positioned via
   `position: absolute` relative to `.home-hero`, so it must live inside the
   Hero section, not after it.

## 2. Homepage — everything below the Hero

Immediately **after** the Hero Section (same page, next element), add one
**Shortcode** widget (or the **Sayid — بدنه‌ی معلق صفحه اصلی** Elementor
widget) containing:

```
[sayid_homepage_deferred]
```

That single widget renders Now, Selected Work, Lab, the Design × Code × AI
network, Latest Notes, Featured Article and Connect as normal,
always-present, server-rendered HTML inside `#homepage-deferred-root`.
`homepage-entry.js` (enqueued automatically on the front page) finds the
Hero and that root; on a qualifying first visit it applies the native
`hidden` attribute to the root plus a document scroll lock, producing the
single-viewport entry state, then removes both on the visitor's first
continuation gesture so scrolling continues straight into `Now`.

This intentionally departs from the brief's literal `<template>` +
`cloneNode` suggestion — see the docblock on
`Sayid_Core_Render::deferred_homepage()` and
`docs/16-final-implementation-report.md` for why: an inert `<template>`
risks never being indexed by crawlers that don't dispatch a scroll gesture,
and cannot satisfy "no-JS users get a normal scrollable page" at the same
time. The `hidden`-attribute approach gets full SEO, a genuinely normal
no-JS homepage, and the same entry-lock UX for JS-enabled visitors.

**Do not** add Now/Work/Lab/etc. as separate Elementor sections on the
homepage — they only exist inside that one deferred payload. If you want to
preview an individual section's design in the Elementor editor without the
entry-lock behavior (e.g. to art-direct spacing), temporarily drop that
section's own shortcode (`[sayid_now]`, `[sayid_lab]`, …) on a scratch page,
adjust surrounding Elementor spacing there, then remove it — the actual
production copy lives only inside `[sayid_homepage_deferred]`.

### Section-by-section shortcode/widget reference

| Section | Shortcode | Elementor widget |
|---|---|---|
| Now | `[sayid_now]` | Sayid — این روزها |
| Selected Work | `[sayid_selected_work]` | Sayid — کارهای منتخب |
| Lab | `[sayid_lab]` | Sayid — آزمایشگاه |
| Design × Code × AI | `[sayid_signature]` | Sayid — طراحی × کد × هوش مصنوعی |
| Latest Notes | `[sayid_notes count="5"]` | Sayid — یادداشت‌های تازه (has a "تعداد" control) |
| Featured Article | `[sayid_featured_article]` | Sayid — نوشته منتخب |
| Connect | `[sayid_connect]` | Sayid — حرف بزنیم |
| Deferred wrapper (homepage only) | `[sayid_homepage_deferred]` | Sayid — بدنه‌ی معلق صفحه اصلی |
| Related content (single templates) | `[sayid_related]` | Sayid — مطالب مرتبط |
| Scroll cue | `[sayid_scroll_cue]` | Sayid — نشانگر اسکرول |
| Theme switch | `[sayid_theme_switch]` | Sayid — سوییچ تم |

Every widget/shortcode pair calls the exact same PHP render function — there
is no drift between the two paths.

## 3. Footer

In the site's global footer (Elementor Pro Theme Builder footer, or Hello
Elementor's footer widget area — whichever the live site already uses):

1. Add navigation links per brief §24 (کارها، آزمایشگاه، یادداشت‌ها،
   نوشته‌ها، درباره من، تماس) using normal Elementor Nav Menu / icon list
   widgets — these are static links, no dynamic query needed.
2. Add the "English portfolio" link to `https://moghadam.pro` as its own
   quiet line.
3. Add an **HTML/Shortcode** widget with `[sayid_theme_switch]` (or the
   **Sayid — سوییچ تم** widget). `theme.js` (enqueued on every page) wires
   its three buttons automatically — no configuration needed, and it works
   even if multiple `[sayid_theme_switch]` instances exist on the page
   (e.g. one in a mobile-only footer variant).

## 4. About & Contact pages

These are ordinary Elementor pages — build them directly in Elementor
following the narrative direction in brief §44–45. No dynamic query is
required for About. For Contact, use Elementor Pro's Form widget (or
WPForms/Contento/any form plugin already in use) with the four fields from
brief §45 (name, email, topic, message) sent to `i@moghadam.pro`.

If you want the About/Contact pages to show a related-content rail at the
bottom (rarely necessary for static pages), drop `[sayid_related]` — it
resolves relationships for `get_the_ID()`, so it only does something
meaningful on content types that actually have related-content fields
(Notes/Articles/Lab/Projects), not on plain Pages.

## 5. Single/Archive templates — current state and upgrade path

`sayid-site-core` ships real, working coded PHP templates for:

```
single-sayid_note.php      archive-sayid_note.php
single-sayid_lab.php       archive-sayid_lab.php
single-sayid_project.php   archive-sayid_project.php
                            taxonomy-sayid_topic.php
```

These are **not** a placeholder — they use the same design tokens, CSS
classes and `Sayid_Core_Render::related()` helper as the rest of the site,
and are live the moment the plugin is activated (`class-templates.php`
hooks `template_include`). This is a deliberate, common, production-valid
WordPress pattern: pair Elementor for freeform pages (Home, About, Contact)
with coded templates for structured, repeating content types.

**If/when Elementor Pro Theme Builder access exists** and you'd rather
art-direct these visually instead:

1. Build a Theme Builder **Single** template, set its display condition to
   the relevant post type (e.g. "Lab" / `sayid_project`).
2. Pull fields via Elementor Pro's built-in **Dynamic Tags → Post Custom
   Field**, using the meta keys from `docs/12-plugin-reference.md` (e.g.
   `sayid_role`, `sayid_status`). Elementor's Custom Field dynamic tag reads
   any post meta by key natively — no custom Dynamic Tag class is required
   for raw fields.
3. For the computed values that aren't raw meta (status label, reading
   time, formatted date, the related-content rail), keep using the
   `[sayid_related]` shortcode widget and the *status*/*reading time*
   columns are visible as reference in the admin list screens
   (`class-admin.php`) — or add small custom Dynamic Tags later if the
   team wants those specific values available inside more Elementor
   controls.
4. Once a post type's Theme Builder template is published and verified,
   disable this plugin's coded template for it via:
   ```php
   add_filter( 'sayid_disabled_templates', function ( $disabled ) {
       $disabled[] = 'sayid_lab'; // or 'sayid_note', 'sayid_project', 'sayid_topic'
       return $disabled;
   } );
   ```
   in a small site-specific mu-plugin or the child theme's `functions.php`.
   Do this per post type, incrementally — there's no need to migrate all
   four at once.

## 6. Verifying the deferred-mount interaction

After steps 1–2 are live:

1. Load the homepage fresh (private/incognito window, to avoid the
   session-storage bypass described in `docs/04-hero-scroll-entry.md` §8).
2. Confirm only the Hero + scroll cue are visible, with no visible
   scrollbar.
3. Scroll down (wheel, trackpad, or press `ArrowDown`/`PageDown`/`Space`) —
   the rest of the homepage should mount and the page should continue
   scrolling into `Now` in the same gesture, not require a second one.
4. Reload and immediately visit `sayid.ir/#lab` — the full homepage should
   render immediately with no lock (anchor-bypass rule).
5. Disable JavaScript entirely and reload — the full homepage, including
   every deferred section, should render as a normal scrollable page with
   no lock and no missing content (this is the point of rendering
   `#homepage-deferred-root` as real DOM rather than a `<template>` — see
   §2 above).
