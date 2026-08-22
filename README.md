# Sayid — sayid.ir v2 theme

A standalone WordPress theme for sayid.ir. No Elementor, no parent theme,
no page-builder dependency of any kind — content model, design system,
Hero, and every template are owned end to end by this theme.

This supersedes the earlier `sayid-site-core` **plugin** + Elementor
approach. If that plugin is still active on the site, **deactivate it**
before or right after activating this theme — its content model (CPTs,
taxonomy, fields) is functionally identical to what this theme registers
itself, so running both is redundant, not additive. (That plugin, plus
the design/architecture docs from before the theme rebuild, live on the
`project-archive` branch — this `master` branch is the theme only, kept
flat at the repo root specifically so **Code → Download ZIP** on this
branch produces a zip installable as-is via Appearance → Themes → Add New
→ Upload Theme, no repackaging needed.)

## Install

1. **Code → Download ZIP** on this branch, then Appearance → Themes →
   Add New Theme → Upload Theme in wp-admin, and upload that zip directly
   — GitHub's zip already wraps everything in one folder, which is what
   WordPress's uploader expects. (Working from a local clone instead? Copy
   the repo's contents into `wp-content/themes/sayid/`.)
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
   one sample of each content type plus empty About/Contact/Home-content
   page shells. Every imported item is saved as a **draft**, clearly
   marked as a sample in its own body text — review, edit, and publish (or
   delete) each one; nothing in it is real content and nothing publishes
   automatically.
9. Create/assign the **تماس** page template (`page-contact.php`) to a
   Contact page if you didn't import it from the demo content — Page
   Attributes → Template → تماس. The form posts to itself and emails
   `i@moghadam.pro` via `wp_mail()`; no third-party form plugin is used.
10. Optional but recommended: create/assign the **محتوای صفحه‌ی اصلی**
    template (`page-home-content.php`) to one page (or import it from the
    demo content) — its meta box is the editable source for the Hero's
    text (name, role, lede, rotator phrases, CTA label). Skip this
    entirely and the Hero just keeps using its original hardcoded copy —
    see "Page templates" below.
11. Dashboard (wp-admin's main screen) → fill in the **این روزها** widget
    (top-left box). This is the fastest-changing content on the site —
    see inc/now-dashboard-widget.php.

## Dates

`sayid_format_date()`/`sayid_format_date_short()` (inc/helpers.php) call
plain `date_i18n( 'j F Y' )`/`date_i18n( 'j F' )` rather than building the
string themselves, so a Jalali calendar plugin (e.g. "Parsi Date"), which
works by filtering `date_i18n`'s output, converts these to a real Shamsi
day-month-year automatically once active. Without one, dates still show as
Gregorian with Persian digits — not a Jalali conversion on its own.

## Notes are a category, not a post type

Notes used to be their own CPT (`sayid_note`); they're now just `post`s
(the same type Articles use) tagged with the **یادداشت** category, which
the theme seeds automatically on activation with the fixed slug `note` —
`sayid_notes_category_id()` (inc/content-types.php) is the one place that
slug is hardcoded, everything else (the homepage's "یادداشت‌های تازه"
query, `category-note.php`, footer/nav links) looks it up through that.
Nothing to configure: tag a post "یادداشت" from the normal category picker
and it shows up everywhere a Note is expected to.

## Page templates

Beyond the default (`page.php`, "قالب پست سینگل" — title + content, same
frame as a single Article), Page Attributes → Template offers:

- **قالب فرم** (`page-form.php`) — page title/content, then a
  name/email/message form. Emails whatever address is set in that page's
  "تنظیمات فرم" meta box, or the site admin email if left blank. Separate
  from — and simpler than — the dedicated Contact page
  (`page-contact.php`), which keeps its own fixed copy and recipient.
- **قالب آرشیو** (`page-archive.php`) — page title/content as an intro,
  followed by a paginated list of posts, optionally narrowed to one
  دسته‌بندی via the "تنظیمات آرشیو" meta box (blank = every post).
- **قالب خام (بدون هدر و فوتر)** (`page-blank.php`) — no site header or
  footer, the page's own content dumped into `<body>` byte-for-byte (via
  `get_the_content()`, bypassing the whole `the_content` filter chain —
  no `wpautop`, no `wptexturize`, no `do_blocks`/`do_shortcode`), the same
  role Elementor's "Canvas" template used to serve. Meant for a complete,
  self-contained HTML/CSS/JS page written in the Code editor — trade-off
  is that a Gutenberg dynamic block (Query Loop, a `[shortcode]` block)
  won't render here, since producing that output *is* what the bypassed
  filter chain does.
- **محتوای صفحه‌ی اصلی (بدون نمایش)** (`page-home-content.php`) — not a
  real destination (visiting it just redirects to `/`); assigning it to
  one page exposes a meta box that becomes the Hero's editable copy
  source, read through `sayid_home_field()` (inc/template-tags.php). See
  install step 10 above.

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
│   ├── taxonomies.php      sayid_topic (shared across post/lab/project) + Works topic-tab filter
│   ├── content-types.php   sayid_lab / sayid_project CPTs + `post` relabel + Notes category seed
│   ├── meta-fields.php     postmeta registration + native meta boxes (posts, lab, projects, and per-template page fields)
│   ├── relationships.php   related-content read API
│   ├── now-dashboard-widget.php   "این روزها" as a WP Dashboard widget
│   ├── queries.php         homepage section queries
│   ├── render.php          render functions for every homepage section
│   ├── admin-columns.php   status/featured columns on admin list screens
│   ├── template-tags.php   nav menus, social links, Contact/Home-content page lookup
│   ├── customizer.php      Hero photo control, Contact phone number
│   ├── contact-form.php    native contact form handler for the Contact page
│   ├── form-template.php   native contact form handler for page-form.php (any page)
│   └── icons.php           inline SVGs: theme-switch icons, social icons
├── template-parts/
│   ├── site-nav.php        logo + role label + theme switch + primary menu
│   └── hero.php            the Hero itself (copy sourced via sayid_home_field())
├── assets/
│   ├── css/                tokens, base, layout, components, hero, interactions
│   ├── js/                 theme, nav, homepage-entry, lab-pointer, signature-venn, hero-marquee, hero-rotator, magic-name
│   └── fonts/               (Estedad woff2 goes here — not bundled)
├── header.php / footer.php
├── front-page.php           homepage: Hero + deferred sections
├── home.php                 Articles index (WP's "Posts page")
├── single.php                single Article or Note (same `post` type)
├── category-note.php         "یادداشت‌ها" archive
├── single-sayid_lab.php / archive-sayid_lab.php
├── single-sayid_project.php / archive-sayid_project.php (+ topic tabs)
├── taxonomy-sayid_topic.php
├── page.php                  "قالب پست سینگل" — default Page template (About uses this)
├── page-contact.php          "تماس" — fixed-copy Contact page
├── page-form.php              "قالب فرم" — reusable form page
├── page-archive.php           "قالب آرشیو" — post list page
├── page-blank.php              "قالب خام" — no header/footer, raw content
├── page-home-content.php       Hero content source (not a real destination)
├── 404.php / index.php
└── demo-content.xml           one-click sample content (WXR)
```

## What's genuinely dynamic vs. what's intentionally static

Per the explicit direction for this rebuild:

- **Static** (hard-coded in the template, edited by changing code): the
  Hero's *composition* — layout, marquee, nav placement
  (`template-parts/hero.php`).
- **Editable without a deploy, but not query-driven**: the Hero's *copy*
  (greeting/name/role/lede/CTA/rotator phrases) and the portrait photo —
  see "Page templates" above and the Customizer, respectively. Both fall
  back to the original confirmed copy/placeholder until touched.
- **Dynamic** (query-driven from WordPress content, per `inc/render.php`):
  Now, Selected Work, Lab, Latest Notes, Featured Article. None of these
  are ever manually duplicated in a template — publishing content is the
  only thing that changes what renders.

## Changelog

The theme's `Version:` header (style.css) and `SAYID_THEME_VERSION`
(functions.php) are bumped together with every shipped change, so
Appearance → Themes shows exactly which point in this history is
installed — compare it against the entries below to tell whether the
site is running the latest commit on git.

- **1.3.2** — "قالب خام" now renders the page's content via
  `get_the_content()`, bypassing the entire `the_content` filter chain
  (not just wpautop/wptexturize/convert_smilies one-by-one) — a fully
  raw, byte-for-byte dump of whatever's in the Code editor, on request.
  Trade-off: a Gutenberg dynamic block no longer renders on this template
  (nothing there processes it), unchanged from before for hand-written
  HTML/CSS/JS.
- **1.3.1** — Fixed a bug in "قالب خام": WordPress's `wptexturize`
  filter was still rewriting plain `&` into the `&#038;` entity inside
  page-blank.php's raw content, so any `&&` in an inline `<script>` broke
  with "Invalid or unexpected token". `wptexturize` and `convert_smilies`
  are now unhooked from `the_content` there too (alongside `wpautop`),
  while `do_blocks`/`do_shortcode` stay on so dynamic blocks keep working.
- **1.3.0** — This-Roozha widget: the three signal titles ("دارم
  می‌سازم" etc.) are now editable text, blank falls back to the original
  wording; their content is a textarea (multi-line). Notes are no longer a
  separate CPT — they're `post`s tagged "یادداشت" (auto-seeded category),
  excluded from the Articles rail/index so the two streams stay disjoint.
  "کارها" gained topic-filter tabs. The Hero's copy (name/role/lede/CTA/
  rotator phrases) is now editable through a WordPress Page's custom
  fields (see "Page templates"), instead of being hardcoded. Added four
  selectable Page templates: قالب پست سینگل (default), قالب فرم (reusable
  contact-style form), قالب آرشیو (post list, optional category filter),
  قالب خام (no header/footer, raw content — like Elementor's Canvas).
- **1.2.0** — Hero: restored the hidden click-8-times easter egg on the
  name ("سعید مقدم", `#magicalTag`) — a canvas firework burst then a
  redirect to `/magic`, ported over from the retired plugin build
  (`assets/js/magic-name.js`), plus its yellow hover hint.
- **1.1.0** — Header: role label ("طراح ارشد محصول") next to the logo,
  theme switch moved from the footer into the header; fixed a dropdown
  hover-gap bug in the primary nav. Footer: merged into a single row,
  real social-icon SVGs. Signature ("طرز فکر") section: fixed a bug
  where the Venn diagram rendered as a solid black blob, moved to a
  theme-aware (Hero-matched) background, reduced its height, switched to
  a 2-column layout. Global: white `::selection` text, site-wide yellow
  link hover, yellow/white `.btn` hover-active state. Dates now go
  through plain `date_i18n()` so a Jalali calendar plugin can convert
  them.
- **1.0.0** — Initial standalone theme, replacing the `sayid-site-core`
  plugin + Elementor build.

## Everything else

Field reference, editorial workflow, and responsive/accessibility/
performance QA checklists from the plugin-era build still apply almost
unchanged (field names, post types, and query rules are identical; only
the delivery mechanism changed from "plugin + Elementor" to "theme") —
see `docs/12-plugin-reference.md`, `docs/14-editorial-workflow.md`,
`docs/15-deployment-and-qa.md`, and `docs/17-theme-pivot.md` on the
`project-archive` branch, which is where the retired plugin and every
pre-rebuild design/architecture doc live now that this branch is the
theme only.
