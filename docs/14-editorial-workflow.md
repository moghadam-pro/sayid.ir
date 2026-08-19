# Editorial Workflow

How Sayid actually publishes, day to day. This is the operational answer to
brief §64's "Content" definition-of-done checklist.

## Publishing a Note (target: under a minute)

1. یادداشت‌ها → یادداشت جدید.
2. Title + body. That's the minimum.
3. Optional: pick one or two Topics in the side panel, add a Source URL if
   the note responds to something external, add a cover image, link a
   related Lab item/Article/Project if one obviously applies.
4. Publish.

That single action updates, automatically, with no further steps:

- the homepage Latest Notes list (next page load — no cache to bust in the
  default setup)
- the `/notes/` archive
- any Topic archive the note was tagged with
- the "related content" rail on anything that references it back

## Publishing a Lab item

1. آزمایشگاه → آیتم جدید.
2. Title + short description + Status (پیش‌فرض: در حال ساخت اگر تازه شروع
   کردی).
3. Optional: repo/live URLs, tools, started/shipped dates, a cover image, a
   longer build-log in the body.
4. Publish.

The homepage Lab section re-queries automatically; nothing needs to be
re-arranged in Elementor. If you want a specific item to visually lead the
Bento grid, lower its "اولویت نمایش در صفحه‌ی اصلی" number (lower = earlier).
Setting Status to "آرشیو شده" removes it from the homepage automatically
without deleting or unpublishing it — it stays visible on `/lab/`.

## Publishing an Article

Articles are WordPress's ordinary Posts, re-labelled "نوشته‌ها" in the admin
menu — everything you already know about publishing a WordPress post
applies (categories/tags UI is replaced by the shared "موضوع‌ها" taxonomy,
same behavior).

1. نوشته‌ها → افزودن نوشته جدید.
2. Title, body, a subtitle in "جزئیات نوشته" (used on the homepage card —
   keep it to one punchy sentence), a cover image.
3. To make this the homepage's featured article, check "نمایش در صفحه‌ی
   اصلی". Leave every article unchecked and the homepage automatically shows
   the newest one instead — there's no wrong state to be in.
4. Publish.

Reading time is computed automatically from the body; never enter it by
hand.

## Publishing a Project

The only content type worth spending real time on — it should still take
under 20 minutes for a project you already have material for.

1. پروژه‌ها → پروژه جدید.
2. Title, short description, cover image (used on the homepage card and the
   project archive).
3. Fill in as many of Role / Organization / Type / Dates / Tools /
   Collaborators as apply — all optional, all skippable.
4. Fill in whichever of Challenge / Context / Process / Decisions / Outcome
   / Metrics you actually have content for. Leave the rest blank — the
   single-project template only prints sections that have content, so a
   partially documented project never looks broken.
5. Check "نمایش در صفحه‌ی اصلی" only for the 2–3 strongest projects at any
   given time (brief §18: "exactly three projects on the homepage in V1").
   Use "اولویت نمایش" to control which one is the large featured card
   (lowest number = featured position).
6. Publish.

## Updating Now (target: seconds)

Settings screen "این روزها" in the left admin menu (near the top, clock
icon). Four short text fields (statement / building / exploring / learning)
plus an optional link. Save — the homepage's `Now` section and its
"به‌روزرسانی" timestamp update immediately; there is no separate publish
step and no post to manage.

## What never needs manual homepage editing

Per brief §42 and the Definition of Done (§64 "no duplicated manual
homepage content"): Now, Selected Work, Lab, Latest Notes and Featured
Article are 100% query-driven. The only Elementor-level editing that ever
touches the homepage is the one-time setup in
`docs/13-elementor-build-guide.md` — after that, publishing content is the
only thing that changes what visitors see there.
