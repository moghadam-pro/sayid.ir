# Sayid

A standalone WordPress theme built for sayid.ir. No Elementor, no parent
theme, no page-builder dependency of any kind — the content model, design
system, Hero, and every template are owned end to end by this theme.

This theme is free. There are no restrictions on installing or using it.

## Overview

- RTL-first, personal-site theme with its own content model: Articles
  (the native `post` type), Notes (a `post` tagged with a dedicated
  category), Lab items, and Projects, sharing one taxonomy across all
  three.
- A single-viewport Hero with an animated background, a rotating
  headline, and copy — plus the Lab, Signature, and Connect sections'
  text, item counts, and per-section visibility — all editable from
  Appearance → Customize instead of being hardcoded.
- A homepage assembled entirely from dynamic sections — a "Now" panel, a
  Selected Work grid, a Lab grid, a Signature (Venn-diagram) section, a
  Latest Notes list, and a compact Articles grid. Every section is
  query-driven; nothing is manually duplicated between the homepage and
  the content itself.
- A "Now" panel editable directly from the WordPress Dashboard, meant to
  be the fastest thing on the site to update.
- Three selectable Page templates beyond the default: a reusable
  form page (configurable recipient email), an archive/list page
  (optionally scoped to one category), and a blank page template with no
  header or footer that renders its content completely raw — for a
  self-contained HTML/CSS/JS page.
- A native contact form and a native reusable form template — no
  third-party form plugin.
- Light/dark/system theme switching, with the choice remembered per
  visitor.
- Date output goes through plain `date_i18n()`, so a Jalali calendar
  plugin that filters that function's output converts dates automatically
  without any extra work in the theme.
- A demo content file (WXR) with one sample of each content type, useful
  for a quick first look at the homepage fully populated.

## Content model

- **Articles** use WordPress's native `post` type, relabeled in the admin.
- **Notes** are also `post`s — tagged with a dedicated category that the
  theme creates automatically on activation — rather than a separate post
  type, so both share the same editing screen, fields, and REST
  behavior.
- **Lab** and **Projects** are custom post types with their own fields
  (status, tools, dates, related links, and — for Projects — a full case
  study layout).
- A shared taxonomy connects Articles/Notes, Lab items, and Projects by
  topic.
- Cross-content relationships (related items shown on a single page) are
  editable per post, resolved through one shared read API.

## Page templates

- The default template renders a page the same way a single Article
  renders — title, then content.
- A form template renders page content followed by a name/email/message
  form; the recipient address is configurable per page, falling back to
  the site admin email.
- An archive template renders page content as an intro followed by a
  paginated list of posts, optionally scoped to one category.
- A blank template renders with no site header or footer — the page's
  content is dumped into `<body>` exactly as written in the block
  editor's Code view, with no WordPress content filtering applied at all,
  for a fully self-contained page (its own `<style>`/`<script>`).

## Customizer

Appearance → Customize exposes homepage copy and section visibility
directly, alongside WordPress's own site-identity controls:

- **Hero** — photo, greeting, name, name suffix, role, lede, CTA button
  label, and the rotating headline's phrases (one per line).
- **Header** — the role label next to the logo, and a numeric order field
  for each of the three header elements (primary menu, logo mark, theme
  switch), so their left-to-right position can be rearranged without
  touching code.
- **Footer** — the copyright line, and a numeric order field for each of
  the footer's two groups (footer links, social icons). The footer links
  themselves come from a dedicated "منوی فوتر" nav menu location
  (Appearance → Menus), the same way the primary menu already works.
- **Lab section** — title, description, and how many items to show.
- **Signature section** — a visibility toggle, plus the eyebrow, title,
  and main sentence.
- **Notes section** — a visibility toggle and how many notes to show.
- **Articles section** — a visibility toggle.
- **Connect section** — title, subtitle, description, and both buttons'
  label and link.
- **Contact page** — an optional phone number (leaving it blank hides the
  "you can call" option on the Contact page).

Every field falls back to the theme's original copy when left blank, and
the "Now" panel is intentionally not here — it has its own dedicated
Dashboard widget instead (see above).

## SEO

The theme emits its own meta description, Open Graph, Twitter Card, and
JSON-LD structured data (`Person` on the homepage, `Article` on single
posts) only when no dedicated SEO plugin is active. If RankMath, Yoast, or
All in One SEO is detected (by its version constant), the theme steps
aside entirely and lets the plugin own that output, avoiding duplicate or
conflicting tags — this also means the theme is compatible with RankMath
out of the box, with no extra setup required. `add_theme_support(
'title-tag' )` already lets any SEO plugin fully own the `<title>` tag.
None of this runs on the blank page template ("قالب خام"), which is a
deliberately untouched raw canvas. RankMath's own on-page analysis,
sitemaps, redirects, and schema all run in local PHP with no outbound
requests, so they work normally even on a server with no outbound
internet access — only features that need the *server itself* to call
out (e.g. Search Console/Analytics auto-sync) would be affected by that.

## Design system

Colors, spacing, typography, and motion are all defined as CSS custom
properties in one tokens file, consumed by every component. Dark mode
follows the OS by default and can be overridden per visitor, with the
choice persisted in local storage. No binary font file is bundled — the
primary typeface is expected at a fixed path, with a full system-font
fallback stack in place until it exists, so the site stays fully legible
either way.

## Structure

```
style.css                theme header (required by WordPress)
functions.php             bootstrap
inc/
  helpers.php              date/reading-time/IP/misc helpers
  setup.php                theme supports, nav menu locations, image sizes
  enqueue.php              CSS/JS registration
  taxonomies.php           shared taxonomy + Works topic-tab filter
  content-types.php        custom post types + Notes category seeding
  meta-fields.php          postmeta registration + native meta boxes
  relationships.php        related-content read API
  now-dashboard-widget.php the dashboard "Now" panel
  queries.php              homepage section queries
  render.php               render functions for every homepage section
  admin-columns.php        admin list-screen columns
  template-tags.php        nav menus, social links, page lookups
  customizer.php           Homepage Customizer controls (see "Customizer" above)
  contact-form.php         the dedicated Contact page's form handler
  form-template.php        the reusable form template's form handler
  icons.php                inline SVG icons
  seo.php                  meta/OG/Twitter/JSON-LD fallbacks (see "SEO" above)
template-parts/
  site-nav.php             logo, role label, theme switch, primary menu
  hero.php                 the Hero itself
assets/
  css/                     tokens, base, layout, components, hero, interactions
  js/                      per-section interaction scripts (incl. the note-row hover typewriter)
  fonts/                   primary typeface goes here (not bundled)
header.php / footer.php
front-page.php              homepage
home.php                    Articles index
single.php                  single Article or Note
category-note.php           Notes archive
single-*.php / archive-*.php  Lab and Project single/archive templates
taxonomy-*.php               shared-topic archive
page.php                     default Page template
page-contact.php             the dedicated Contact page
page-form.php                 reusable form page template
page-archive.php              post-list page template
page-blank.php                 raw, no-header/footer page template
404.php / index.php
demo-content.xml               sample content (WXR)
```

## Changelog

The theme's version header (`style.css`) and the matching constant in
`functions.php` are bumped together with every shipped change.

- **1.5.0** — SEO: the theme now emits meta description, Open Graph,
  Twitter Card, and JSON-LD structured data on its own, stepping aside
  automatically when RankMath/Yoast/AIOSEO is active (see "SEO" above).
  Header/footer: Appearance → Customize gained "هدر" and "فوتر" sections
  with a numeric order field per element, plus a real "منوی فوتر" nav
  menu location so the footer's links are menu-driven like the primary
  menu. Notes: gained a Customizer visibility toggle and item-count
  field; hovering a note row now types out a one-line excerpt beside the
  title, and its "همه‌ی یادداشت‌ها" link moved up beside the section
  heading (matching the same move already made for Articles' "همه‌ی
  نوشته‌ها" link). Lab section: cards are smaller (a uniform 3×2 grid of
  6, up from 4) with correspondingly smaller card type. Hero: the lede
  text is smaller, the rotating-phrase chip lost its padding and corner
  radius, and hovering a social link now grows and highlights its
  underline mark in place instead of shifting the other links. Layout: a
  short/empty page's footer now always sticks to the bottom of the
  viewport instead of riding up under the content. Dashboard: the
  "پروژه‌ها" custom post type was relabeled "نمونه‌کارها" throughout the
  admin, matching the "کارها" wording already used on the front end —
  its add/edit screen, shared topic taxonomy, and single template were
  already in place.
- **1.4.0** — Moved homepage content editing to Appearance → Customize.
  The Hero's copy (already editable via a Page meta box) moved there, and
  gained company: the Lab section's title/description/item count, the
  Signature section's visibility toggle and eyebrow/title/main-sentence
  text, the Articles section's visibility toggle, and the Connect
  section's title/subtitle/description and both buttons' label and link.
  The Page-template-based mechanism this replaces for the Hero
  (page-home-content.php and its meta box) was removed entirely.
- **1.3.2** — The blank page template now renders content completely
  raw, bypassing WordPress's entire content filter pipeline, so what's
  written in the Code editor is output byte-for-byte with nothing
  rewritten. The trade-off is that a dynamic block (e.g. a Query Loop)
  won't render on this template, since producing that output is exactly
  what the bypassed pipeline does.
- **1.3.1** — Fixed a bug in the blank page template: a WordPress content
  filter was still rewriting plain `&` into an HTML entity inside raw
  page content, which broke any `&&` in an inline `<script>` with a
  JavaScript syntax error. That filter, along with a smiley-conversion
  filter, is now bypassed there too, while dynamic block/shortcode
  rendering is preserved.
- **1.3.0** — The dashboard "Now" panel's three signal titles became
  editable text (each falling back to its original label when left
  blank), and their content became multi-line. Notes are no longer a
  separate post type — they're `post`s tagged with an auto-created
  category, excluded from the Articles feed and index so the two streams
  stay disjoint. The Works archive gained topic-filter tabs. The Hero's
  copy became editable through a WordPress Page's custom fields instead
  of being hardcoded. Added the four selectable Page templates.
- **1.2.0** — Restored a hidden interaction on the Hero name: several
  clicks in quick succession trigger a small animation and a redirect,
  ported over from an earlier build.
- **1.1.0** — Header: added a role label next to the logo, moved the
  theme switch from the footer into the header, fixed a dropdown
  hover-gap bug in the primary navigation. Footer: merged into a single
  row with real social icons. Signature section: fixed a rendering bug,
  moved to a theme-aware background matching the Hero, reduced its
  height, switched to a two-column layout. Site-wide: white text
  selection, yellow link hover, yellow/white button hover state. Dates
  switched to plain `date_i18n()` for Jalali calendar plugin
  compatibility.
- **1.0.0** — Initial standalone theme release, replacing an earlier
  plugin-plus-page-builder build.
