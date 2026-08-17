# sayid.ir — Content Model & Living Site Principles

_Last updated: 2026-08-17_

## 1. Why the content model matters

The rebuild should not begin by drawing pages one by one.

It should begin by defining reusable content objects and the relationships between them.

The goal is to make the website easy to keep alive after launch.

A good publishing system should allow one action to update multiple surfaces automatically.

Example:

```text
Publish a new Note
       ↓
Homepage latest notes
Notes archive
Tag pages
Related content
Search
RSS
```

No manual homepage card creation should be required.

---

## 2. Core content types

### 2.1 Notes

**Purpose:** fast, lightweight publishing.

Notes are for ideas that are valuable enough to preserve but do not justify a full article.

Typical examples:

- a Figma observation
- a UX decision
- an Elementor fix
- a useful AI workflow
- a design-system lesson
- a short product opinion
- a learning from a current build

Recommended characteristics:

- short body
- minimal publishing friction
- optional cover image
- tags
- optional related Project / Lab item
- automatic publish date
- optional source / external reference

Expected publishing frequency: **high**.

A Note should be publishable in minutes.

---

### 2.2 Articles

**Purpose:** deeper thinking and durable long-form content.

Articles can grow from Notes, social posts, project experiences, or independent ideas.

Typical examples:

- product design essays
- detailed lessons
- workflow explanations
- AI and design reflections
- technical/product crossover topics

Recommended fields:

- title
- subtitle / excerpt
- cover
- body
- published date
- updated date
- tags
- reading time
- related Notes
- related Projects / Lab items

Expected publishing frequency: **medium to low**.

---

### 2.3 Lab / Builds

**Purpose:** show active experimentation and building.

This is one of the most important differentiators for sayid.ir.

A Lab item does not need to be a polished portfolio case study. It can represent something small, unfinished, experimental, or technical.

Examples:

- WordPress plugins
- Figma experiments
- AI-assisted tools
- prototypes
- automation experiments
- small open-source projects
- design-to-code explorations

Recommended fields:

- title
- short description
- status
- start date
- optional shipped date
- technologies / tools
- cover or preview
- repository URL
- live URL
- body / build log
- related Notes
- related Articles
- related Projects

Recommended statuses:

```text
Idea
Exploring
Building
Beta
Shipped
Paused
Archived
```

Expected publishing frequency: **regular**.

---

### 2.4 Projects / Case Studies

**Purpose:** demonstrate important professional work and outcomes.

Projects should have the highest editorial quality but the lowest publishing pressure.

Recommended fields:

- title
- short description
- role
- organization / client
- project type
- date range
- duration
- tools
- collaborators
- cover
- challenge
- context
- process
- key decisions
- outcome
- metrics where available
- gallery / media
- related Notes
- related Articles
- related Lab items
- external portfolio link when appropriate

Expected publishing frequency: **low**.

The website should still feel active even when no new case study is published for months.

---

### 2.5 Pages

Static or slow-changing structural pages:

- About
- Contact
- Uses / Tools, only if useful
- Privacy / Legal where needed

These should not be used for content that naturally belongs in a reusable content type.

---

## 3. Shared taxonomies

Avoid creating a separate taxonomy for every content type unless necessary.

A shared topic/tag system makes cross-content discovery possible.

Example topics:

- Product Design
- UX
- UI
- Design Systems
- Figma
- AI
- Product Engineering
- WordPress
- Front-end
- RTL
- Accessibility
- Research
- Prototyping

A visitor interested in `AI` should be able to discover Notes, Articles, Lab items, and Projects connected to AI.

---

## 4. Relationships

Relationships are more valuable than isolated archives.

Example:

```text
Lab: MPRO Portfolio Plugin
│
├── Note: Why I started building it
├── Note: A CPT architecture mistake I found
├── Article: Building small products with AI
├── Repository: GitHub
└── Related Project: sayid.ir rebuild
```

This turns the website into a knowledge graph instead of a set of disconnected pages.

Recommended relation types:

- Note ↔ Article
- Note ↔ Lab
- Note ↔ Project
- Article ↔ Lab
- Article ↔ Project
- Lab ↔ Project

Relations can be manually curated at first. Full automation is unnecessary for the first version.

---

## 5. Content velocity model

Different content deserves different publishing effort.

```text
Notes          ██████████  Frequent
Lab / Builds   ██████      Regular
Articles       ███         Occasional
Case Studies   █           Rare / High quality
```

This model prevents the common personal-site problem where the site appears abandoned because the owner does not have time to publish polished case studies every week.

---

## 6. Homepage query logic

The homepage should mostly be driven by queries rather than manual editing.

Recommended logic:

### Latest Notes

```text
Post type: Note
Order: newest first
Limit: 3–5
```

### Selected Work

```text
Post type: Project
Filter: featured = true
Order: curated
Limit: 3
```

### From the Lab

```text
Post type: Lab
Exclude status: Archived
Order: updated date or curated priority
Limit: 3–4
```

### Latest Article

```text
Post type: Article
Order: newest first
Limit: 1
```

The exact implementation can use Elementor Loop Grid / Loop Carousel or custom WordPress queries depending on the final design.

---

## 7. The `Now` object

The `Now` section should be deliberately simple.

Do not turn it into a complex post type unless the need emerges.

A small Options Page / custom field group is enough for V1.

Possible fields:

- current headline
- current description
- last updated date
- optional link

Example:

```text
Currently
Exploring AI-assisted product building and Figma-to-code workflows.
Updated Aug 2026
```

The UX goal is to make updating it almost frictionless.

---

## 8. Publishing experience requirements

The admin experience should optimize for speed.

### Notes

Target workflow:

```text
New Note → title → body → tags → publish
```

Everything else should be optional.

### Lab

Target workflow:

```text
New Lab Item → title → summary → status → links → publish
```

### Project

Can have a richer editor because it is updated less frequently.

Avoid forcing the complexity of Projects onto Notes.

---

## 9. Dynamic site rules

### Rule 1 — Never duplicate content manually

If a piece of information already exists as structured content, query it.

### Rule 2 — Publishing should update the homepage automatically

A new article should never require a second manual homepage edit.

### Rule 3 — Archives should be meaningful

Do not create empty archive pages only because a post type exists.

### Rule 4 — Relationships should encourage exploration

Every detailed content page should offer a useful next step.

### Rule 5 — Dates communicate life

Show published and, where relevant, updated dates. A living site should make change visible.

### Rule 6 — RSS should be supported

RSS is a natural fit for Notes and Articles and reinforces the site as an owned publishing platform.

---

## 10. Recommended WordPress implementation for V1

Keep implementation intentionally simple.

### Core

- WordPress
- Elementor / Elementor Pro

### Structured content

Use either the existing custom plugin approach or a lightweight custom implementation for:

- custom post types
- custom fields
- taxonomies
- relationships

Do not install multiple overlapping plugins for the same content-model responsibility.

### Templates

Build reusable Elementor templates for:

- Note single
- Article single
- Lab single
- Project single
- taxonomy / archive views
- related-content blocks

### Queries

Use dynamic query loops wherever possible.

Introduce custom PHP query logic only where Elementor's native query capabilities become limiting.

---

## 11. What should update fastest?

From fastest to slowest:

1. **Now** — seconds to a minute
2. **Notes** — minutes
3. **Lab status / build log** — minutes
4. **Articles** — hours
5. **Projects / Case Studies** — days when necessary

The site's perceived freshness should depend mostly on the first three, not the last two.

---

## 12. MVP boundary

For the first rebuild, do not overbuild.

MVP should prove that:

- the new positioning is clear
- Notes are easy to publish
- Lab content is useful
- homepage sections update dynamically
- cross-content navigation works
- the site feels alive with low maintenance

Features such as advanced search, recommendation engines, complex filters, user accounts, or headless architecture can wait until real usage justifies them.

---

## 13. Core operating principle

> **The best CMS for sayid.ir is the one that makes publishing feel easier than postponing it.**

Every technical and design decision should be evaluated against that standard.