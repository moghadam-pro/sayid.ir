# Theme Pivot — from `sayid-site-core` (plugin + Elementor) to `sayid` (standalone theme)

This document records why the implementation approach changed mid-project,
what changed, and what stayed the same. It supersedes the "Custom Plugin"
direction in the original build brief (§47) and in
`docs/11-implementation-architecture.md` for anything the two disagree on.

## Why

The plugin + Elementor build (`plugins/sayid-site-core/`, still in this
repo) was implemented, deployed, and debugged live on the real
`sayid.ir` WordPress install. Real deployment surfaced friction the
brief's plugin-on-top-of-Elementor model didn't anticipate:

- Wiring the Hero's responsive contract required manually adding three CSS
  classes to specific Elementor elements — straightforward in principle,
  error-prone in practice without live access to verify each step, and
  fragile to re-verify every time the Hero is edited in Elementor.
- Site-wide typography (Estedad) had to fight Hello Elementor's own Global
  Fonts cascade — fixed with `!important` and container scoping, but a
  real, avoidable cost.
- Diagnosing "nothing changed" issues (page cache, missing CSS requests)
  took several round trips specifically because Elementor's own asset
  pipeline and the plugin's assets were two independent systems layered on
  the same page, doubling the surface area to debug.

Given this, the site owner asked for a full pivot: a standalone theme that
owns everything end to end, content model through to the Hero pixels,
with **no Elementor dependency at all**. This is a legitimate, reasoned
override of the original brief's §51 "keep the existing practical stack"
guidance — that guidance optimized for the *initial* build; live usage is
better evidence than the initial assumption, and a page builder whose own
cascade and caching model actively works against the design system is no
longer the faster path.

## What changed

| | Plugin + Elementor (retired) | Theme (current) |
|---|---|---|
| Location | `plugins/sayid-site-core/` | `themes/sayid/` |
| Hero | Existing Elementor Hero, tagged with CSS classes | Authored directly in `template-parts/hero.php`, matching the real Hero screenshot |
| Homepage assembly | `[sayid_homepage_deferred]` shortcode/widget placed in Elementor | `front-page.php` calls render functions directly |
| Page composition | Elementor page builder | Plain PHP templates |
| Typography enforcement | `!important` + container scoping to beat Elementor's Global Fonts | Plain `body { font-family }` — nothing to fight |
| Now editing | Settings → این‌روزها admin page | این‌روزها **Dashboard widget** (first thing seen on login) |
| Contact form | Elementor Pro Form widget (assumed) | Native `wp_mail()` form with nonce + honeypot |
| Content seeding | None (no live DB access at build time) | `demo-content.xml` (WXR) — one sample per content type, imported in one step |

## What did NOT change

- The content model: same post types (`sayid_note`, `sayid_lab`,
  `sayid_project`, `post`-as-Article), the same `sayid_topic` taxonomy, the
  same postmeta field names, the same relationship pattern. None of it was
  ever Elementor-specific, so it moved into `inc/*.php` as plain functions
  with zero semantic changes — `docs/12-plugin-reference.md` is still
  accurate.
- The design tokens (`assets/css/tokens.css`): colors, spacing, radius,
  motion, typography scale — copied verbatim.
- The homepage section order, copy, and query rules (brief §13, §42).
- The Lab pointer interaction, the Design × Code × AI network, and the
  deferred-homepage-reveal mechanism (`hidden` attribute, not `<template>`
  — see the plugin-era decision recorded in
  `docs/16-final-implementation-report.md`, which still applies here
  unchanged).
- The editorial workflow and QA checklists (`docs/14`, `docs/15`) — field
  names and query rules are identical, so those documents needed no edits.

## New in this pivot

- **The Hero is now real, specific content** matching a screenshot of the
  actual current sayid.ir Hero, rather than "whatever already exists in
  Elementor, constrained by CSS classes." Copy, composition, the two
  opposite-direction background marquee lines, and the vertical social
  rail are all hard-coded in `template-parts/hero.php` — brief §14's
  instruction not to redesign the Hero is honored by reproducing it
  faithfully rather than reinterpreting it.
- **A "Your IP: …" line in the Hero.** Not part of the original brief;
  confirmed as intentional, existing design during this pivot (not a
  screenshot artifact). Implemented via `sayid_get_visitor_ip()` — display
  only, checks CDN/proxy headers before `REMOTE_ADDR`, never used for
  access control.
- **The Hero photo is genuinely swappable** without a code deploy, via one
  Customizer image control (`inc/customizer.php`) — everything else about
  the Hero is intentionally static per the site owner's explicit
  "some sections dynamic, some fixed" direction.
- **A native contact form.** No page-builder form widget exists to lean on
  anymore; `inc/contact-form.php` is a small, real, working replacement
  (nonce + honeypot + `wp_mail()`).
- **Now moved from a settings page to a Dashboard widget** (explicit
  request) — same storage (`sayid_now` option), same render function on
  the front end, only the admin editing surface changed.

## Known limitation carried into this pivot

The Hero **portrait photo itself** was not available as a file during this
build — only a rendered screenshot in conversation, which cannot be
extracted as a usable image asset. `template-parts/hero.php` renders a
correctly-sized, correctly-masked placeholder block in the photo's exact
position until it's uploaded via Customize → هیرو صفحه‌ی اصلی. This is the
one piece of the Hero that cannot be verified pixel-for-pixel until that
upload happens.
