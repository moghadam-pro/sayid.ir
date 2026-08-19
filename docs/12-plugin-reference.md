# Plugin Reference — `sayid-site-core`

Field-by-field reference for every content type, taxonomy and setting the
plugin registers. Source of truth for the code is
`plugins/sayid-site-core/includes/class-meta-fields.php` (field definitions)
and `class-content-types.php` / `class-taxonomies.php` (registration) — this
document explains *why* each decision was made and how the fields map to
what a visitor sees.

## Content types

| Post type | Slug | Archive | Why a custom post type (or not) |
|---|---|---|---|
| Note | `sayid_note` | `/notes/` | Fast, lightweight, high-frequency. Kept separate from `post` because Notes need a much smaller editing surface and a distinct archive/IA position (`فکرها → یادداشت‌ها`). |
| Article | `post` (relabeled) | `/articles/` via topic/date archives, permalink base is the standard `post` base | **Not** a custom post type. Articles are exactly what WordPress's built-in blog post already models — long-form, dated, taxonomy-able, RSS-able. Re-labelling `post` as "نوشته‌ها" avoids reimplementing RSS, sitemaps and every SEO plugin's assumptions about what `post` means. |
| Lab | `sayid_lab` | `/lab/` | A closed content type with its own vocabulary (`status`) and metadata (repo/live URLs, tools) that doesn't belong on generic posts. |
| Project | `sayid_project` | `/work/` | Highest-fidelity content type; richest field set, lowest publishing frequency. |

All four participate in the shared `sayid_topic` taxonomy (see below), so a
visitor browsing a topic sees Articles, Notes, Lab items and Projects mixed
into one chronological stream.

## Shared taxonomy — `sayid_topic`

Registered on `post`, `sayid_note`, `sayid_lab`, `sayid_project`.
Non-hierarchical (tag-like). Seeded once on first activation with the 13
topics from brief §40 (`طراحی محصول`, `تجربه کاربری`, … `نمونه‌سازی`); editors
are free to add, rename or remove terms afterwards from the normal
Notes/Articles/Lab/Projects → Topics screen — the seed only runs once
(guarded by the `sayid_topics_seeded` option).

## Fields by content type

Every field below is a native WordPress postmeta value (`register_post_meta`,
REST-exposed), edited through a plain `add_meta_box()` panel — no ACF, no
third-party fields plugin (brief §57).

### Note (`sayid_note`)

Uses core Title, Editor (body) and Featured Image (cover) — those need no
custom field. Custom fields:

| Field | Meta key | Notes |
|---|---|---|
| Source URL | `sayid_source_url` | Optional external reference the note responds to. |
| Related Notes/Articles/Lab/Projects | `sayid_related_notes` / `_articles` / `_lab` / `_projects` | Multi-select of published posts, manually curated. |

### Article (`post`)

Uses core Title, Editor, Excerpt (short-form fallback), Featured Image
(cover). Custom fields:

| Field | Meta key | Notes |
|---|---|---|
| Subtitle / short excerpt | `sayid_subtitle` | Shown in the homepage Featured Article card; distinct from the native excerpt so editors can write a punchier homepage-specific line without touching the archive excerpt. |
| Featured on homepage | `sayid_featured_homepage` (bool) | Manual override for §22's "featured article" slot. If unset, the homepage falls back to the newest Article automatically — see `Sayid_Core_Queries::featured_article()`. |
| Related content | `sayid_related_*` | Same pattern as Notes. |

Reading time is **not** a stored field — it's computed on render from
`post_content` (`sayid_reading_time_label()`), so it never goes stale.

### Lab (`sayid_lab`)

| Field | Meta key | Notes |
|---|---|---|
| Short description | `sayid_short_description` | Shown on the Bento card. Keep it to one sentence. |
| Status | `sayid_status` | One of: `idea`, `reviewing`, `building`, `beta`, `shipped`, `paused`, `archived` (brief §11 vocabulary). `archived` items are excluded from the homepage query automatically. |
| Started / Shipped | `sayid_started_at` / `sayid_shipped_at` | Free-text (e.g. "تیر ۱۴۰۴") — deliberately not a date picker, so partial/approximate dates are fine. |
| Tools | `sayid_tools` | Comma-separated free text. |
| Repository / Live URL | `sayid_repo_url` / `sayid_live_url` | The homepage Bento card links to Live URL if set, else Repository, else the internal single page. |
| Featured on homepage | `sayid_featured_homepage` (bool) | Currently informational — the homepage Lab query shows everything not archived (brief's "exclude Archived" rule); this flag is reserved for a future stricter curation pass without a schema change. |
| Homepage priority | `sayid_homepage_priority` (int) | Lower = shown first/larger. Ties break by most-recently-modified. |
| Related content | `sayid_related_*` | Same pattern. |

### Project (`sayid_project`)

Richest field set; every field is optional so an early or lightly
documented project still renders a clean page (empty sections are simply
not printed — see `templates/single-sayid_project.php`).

| Field | Meta key |
|---|---|
| Short description | `sayid_short_description` |
| Role | `sayid_role` |
| Organization / client | `sayid_organization` |
| Project type | `sayid_project_type` |
| Date start / end | `sayid_date_start` / `sayid_date_end` |
| Tools | `sayid_tools` |
| Collaborators | `sayid_collaborators` |
| Challenge / Context / Process / Decisions / Outcome | `sayid_challenge`, `sayid_context`, `sayid_process`, `sayid_decisions`, `sayid_outcome` — each a small rich-text (`wp_editor`) field |
| Metrics | `sayid_metrics` — free text, handle with the accuracy caution from brief §10 (Badesaba's 3→15,000 clicks example) |
| Gallery | `sayid_gallery` — comma-separated attachment IDs |
| External portfolio URL | `sayid_external_url` — e.g. a deeper case study on moghadam.pro |
| Featured on homepage | `sayid_featured_homepage` (bool) — **required** to appear in Selected Work |
| Homepage priority | `sayid_homepage_priority` (int) |
| Related content | `sayid_related_*` |

## Now (`Settings → این روزها`)

A single options row (`sayid_now` option), not a post type, per brief §17 /
§39. Fields: `statement`, `building`, `exploring`, `learning`, `link_label`,
`link_url`, `updated_at` (set automatically on every save — never edited by
hand). This is intentionally the fastest-to-update thing on the whole site.

## Relationships

Storage/UI: the `sayid_related_*` postmeta arrays above, edited as a
multi-select of published post titles per related type. Manual curation, as
brief §41 specifies — no auto-recommendation engine.

Read-side API: `Sayid_Core_Relationships::get_related( $post_id )` returns
`['notes' => WP_Post[], 'articles' => …, 'lab' => …, 'projects' => …]`,
resolved to published posts only. `get_related_flat()` gives a single capped
list for a simple "related content" rail — this is what
`Sayid_Core_Render::related()` uses on every single template and the
`[sayid_related]` shortcode / *Sayid — مطالب مرتبط* Elementor widget.

## Homepage queries

All defined in `includes/class-queries.php`, matching brief §42 exactly:

| Section | Query |
|---|---|
| Selected Work | `sayid_project`, `sayid_featured_homepage = 1`, ordered by `sayid_homepage_priority` asc then newest, limit 3 |
| Lab | `sayid_lab`, `sayid_status != archived`, ordered by priority then most-recently-modified, limit 4 |
| Latest Notes | `sayid_note`, newest first, limit 5 (3 on the mobile-simplified count if you choose to lower the shortcode's `count` attribute) |
| Featured Article | `post` with `sayid_featured_homepage = 1` if any exist, else newest `post`, limit 1 |

No homepage content is ever hand-duplicated in Elementor — every section
above is a query result rendered through `Sayid_Core_Render`.
