# Sayid Site Core

The plugin that powers `sayid.ir` v2 underneath WordPress + Elementor.

WordPress owns content, taxonomies, settings and relationships. Elementor
owns page composition and presentation. This plugin owns everything in
between: the content model (Notes, Articles, Lab, Projects, Now), the shared
topic taxonomy, relationship resolution, design tokens, the theme system,
the Hero entry / deferred homepage mount, the Lab pointer interaction, the
Design × Code × AI network, and a handful of native Elementor widgets (plus
matching shortcodes) that expose all of it to the page builder.

See `/docs` at the repository root for the full documentation set:

- `docs/12-plugin-reference.md` — field-by-field reference for every content type and setting
- `docs/13-elementor-build-guide.md` — how to assemble the homepage and pages in Elementor using this plugin's widgets/shortcodes and CSS hooks
- `docs/14-editorial-workflow.md` — how Sayid actually publishes day to day
- `docs/15-deployment-and-qa.md` — install steps, responsive/accessibility/performance QA checklist, troubleshooting
- `docs/16-final-implementation-report.md` — what was built, what was migrated, what remains configurable, and every assumption made

## Requirements

- WordPress 6.0+
- PHP 7.4+
- Elementor (Elementor Pro recommended but not required — every dynamic
  section also works as a plain shortcode without Elementor Pro)
- Hello Elementor theme (or any theme that calls `get_header()`/`get_footer()`
  the standard way)

## Install

1. Copy (or symlink) `plugins/sayid-site-core` into `wp-content/plugins/`.
2. Activate **Sayid Site Core** from Plugins → Installed Plugins.
3. Activation registers the content types/taxonomy and flushes permalinks
   automatically. If archive pages 404 immediately after activation, visit
   Settings → Permalinks and click Save once (a known WordPress quirk with
   some hosts' object caches, not specific to this plugin).
4. Add Estedad's variable woff2 file to `assets/fonts/Estedad-Variable.woff2`
   (not bundled in this repository — see "Fonts" below).

## Fonts

This plugin does not bundle the Estedad font files. `assets/css/base.css`
declares the `@font-face` rule pointing at
`assets/fonts/Estedad-Variable.woff2`; until that file exists, the site
falls back to `Vazirmatn, IRANSans, Tahoma, system-ui, sans-serif`, which
keeps Persian text fully legible. Download the variable woff2 from
Estedad's official distribution and place it at that path — no code changes
needed.

## What this plugin does NOT do

- It does not install or configure Elementor/Elementor Pro.
- It does not create Elementor Theme Builder templates in the database —
  this build environment has no live WordPress/Elementor database to create
  them in. `docs/13-elementor-build-guide.md` documents the exact,
  reproducible steps to build the homepage and page templates in Elementor
  using this plugin's widgets. Coded PHP templates
  (`templates/single-sayid_note.php` etc.) cover single/archive views today
  so the site is fully functional without that step; they can be turned off
  per post type later via the `sayid_disabled_templates` filter once an
  Elementor Pro Theme Builder replacement exists.
- It does not touch the existing Hero — the Hero stays exactly as Elementor
  already renders it. This plugin only ships the CSS contract
  (`.home-hero`, `.home-hero__inner`, `.home-hero__media`) that constrains
  it responsively once those classes are added to the relevant Elementor
  elements (see the build guide).
