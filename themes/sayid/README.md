# Sayid — sayid.ir v2 theme

A standalone WordPress theme for sayid.ir. No Elementor, no parent theme,
no page-builder dependency of any kind — content model, design system,
Hero, and every template are owned end to end by this theme.

This supersedes the earlier `sayid-site-core` **plugin** + Elementor
approach (see `docs/17-theme-pivot.md` for why). If that plugin is still
active on the site, **deactivate it** before or right after activating this
theme — its content model (CPTs, taxonomy, fields) is functionally
identical to what this theme registers itself, so running both is
redundant, not additive.

## Install

1. Copy (or symlink) `themes/sayid` into `wp-content/themes/sayid`.
2. Appearance → Themes → activate **Sayid**. This registers the content
   types/taxonomy and flushes permalinks automatically.
3. If archive pages 404 right after activation, visit Settings →
   Permalinks and click Save once (host-level page caching can hold onto
   the old rewrite rules).
4. Settings → General → make sure **Site Language** is set to فارسی — the
   theme relies on WordPress's own `is_rtl()`/`language_attributes()` to
   set `dir="rtl"` correctly rather than hardcoding it.
5. Add the Estedad variable font at
   `assets/fonts/Estedad-Variable.woff2` (not bundled — see "Fonts" below).
6. Appearance → Customize → **هیرو صفحه‌ی اصلی** → upload the Hero portrait
   photo. Until this is set, the Hero shows a neutral placeholder block in
   the photo's exact position/size, so the layout is fully visible and
   testable before the real photo exists.
7. Appearance → Menus → create and assign a **منوی اصلی** (primary) menu.
   Until it's assigned, the header nav falls back to a small set of
   built-in links so it never renders empty. The footer is a fixed compact
   row (کارها / آزمایشگاه / یادداشت‌ها + icons) and isn't menu-driven, so
   it needs no separate menu.
8. Optionally: Tools → Import → WordPress → import `demo-content.xml` for
   one sample of each content type plus empty About/Contact page shells.
   Every imported item is saved as a **draft**, clearly marked as a sample
   in its own body text — review, edit, and publish (or delete) each one;
   nothing in it is real content and nothing publishes automatically.
9. Create/assign the **تماس** page template (`page-contact.php`) to a
   Contact page if you didn't import it from the demo content — Page
   Attributes → Template → تماس. The form posts to itself and emails
   `i@moghadam.pro` via `wp_mail()`; no third-party form plugin is used.
10. Dashboard (wp-admin's main screen) → fill in the **این روزها** widget
    (top-left box). This is the fastest-changing content on the site —
    see inc/now-dashboard-widget.php.

## Fonts

Same as the retired plugin: no binary font files are bundled. `base.css`
declares `@font-face` pointing at
`assets/fonts/Estedad-Variable.woff2`; until that file exists, the
fallback stack (`Vazirmatn, IRANSans, Tahoma, system-ui, sans-serif`) keeps
the site fully legible.

## Structure

```
sayid/
├── style.css              theme header (required by WordPress)
├── functions.php           bootstrap
├── inc/
│   ├── helpers.php         date/reading-time/IP/misc helpers
│   ├── setup.php           theme supports, nav menu locations, image sizes
│   ├── enqueue.php         CSS/JS registration + conditional interaction-script enqueue
│   ├── taxonomies.php      sayid_topic (shared across post/note/lab/project)
│   ├── content-types.php   sayid_note / sayid_lab / sayid_project CPTs + `post` relabel
│   ├── meta-fields.php     postmeta registration + native meta boxes
│   ├── relationships.php   related-content read API
│   ├── now-dashboard-widget.php   "این روزها" as a WP Dashboard widget
│   ├── queries.php         homepage section queries
│   ├── render.php          render functions for every homepage section
│   ├── admin-columns.php   status/featured columns on admin list screens
│   ├── template-tags.php   nav menus, social links, Contact page lookup
│   ├── customizer.php      Hero photo control, Contact phone number
│   ├── contact-form.php    native contact form handler (no form plugin)
│   └── icons.php           inline SVGs: theme-switch icons, social badges
├── template-parts/
│   ├── site-nav.php        shared logo + primary menu markup
│   └── hero.php            the Hero itself
├── assets/
│   ├── css/                tokens, base, layout, components, hero, interactions
│   ├── js/                 theme, nav, homepage-entry, lab-pointer, signature-venn, hero-marquee, hero-rotator
│   └── fonts/               (Estedad woff2 goes here — not bundled)
├── header.php / footer.php
├── front-page.php           homepage: Hero + deferred sections
├── home.php                 Articles index (WP's "Posts page")
├── single.php                single Article
├── single-sayid_note.php / archive-sayid_note.php
├── single-sayid_lab.php / archive-sayid_lab.php
├── single-sayid_project.php / archive-sayid_project.php
├── taxonomy-sayid_topic.php
├── page.php                  generic page (About uses this, unmodified)
├── page-contact.php          Contact page template
├── 404.php / index.php
└── demo-content.xml           one-click sample content (WXR)
```

## What's genuinely dynamic vs. what's intentionally static

Per the explicit direction for this rebuild:

- **Static** (hard-coded in the template, edited by changing code): the
  Hero's copy and composition (`template-parts/hero.php`) — everything
  except the portrait photo itself, which has its own Customizer control.
- **Dynamic** (query-driven from WordPress content, per `inc/render.php`):
  Now, Selected Work, Lab, Latest Notes, Featured Article. None of these
  are ever manually duplicated in a template — publishing content is the
  only thing that changes what renders.

## Everything else

Field reference, editorial workflow, and responsive/accessibility/
performance QA checklists from the plugin-era build still apply almost
unchanged — see `docs/12-plugin-reference.md`, `docs/14-editorial-workflow.md`
and `docs/15-deployment-and-qa.md` at the repository root (field names,
post types, and query rules are identical; only the delivery mechanism
changed from "plugin + Elementor" to "theme"). `docs/17-theme-pivot.md`
covers what's specific to this rebuild.
