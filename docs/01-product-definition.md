# sayid.ir — Product Definition & Strategy

_Last updated: 2026-08-17_

## 1. Product idea

sayid.ir should not be rebuilt as a Persian copy of `moghadam.pro`.

The two sites should have clearly different jobs:

- **moghadam.pro** → professional international portfolio, case studies, career positioning, recruiter and client evaluation.
- **sayid.ir** → a living Persian personal/professional hub for thinking, building, experimenting, documenting, and sharing.

A useful working definition is:

> **sayid.ir is Sayid Moghadam’s living Persian space for product design, building, experiments, learning, and writing.**

It is not only a portfolio and not only a blog. It should behave more like a **personal product / digital garden / living studio**.

---

## 2. Primary goals

### Goal 1 — Build a clear Persian professional identity

A visitor should quickly understand that Sayid is a Product Designer who does more than create interfaces: he frames problems, builds systems, experiments with technology, understands implementation, and documents what he learns.

The desired perception is:

> Product Designer + Builder + Systems Thinker + Curious Experimenter

The homepage should communicate this identity before presenting a long list of tools or capabilities.

### Goal 2 — Turn experience into durable assets

Short-lived experiences should become reusable, searchable knowledge.

Instead of allowing an idea to disappear inside a social post, the website should support a progression such as:

```text
Experience → Note → Article → Project / Lab → Knowledge
```

A small observation can start as a Note. If it becomes more valuable, it can grow into an Article or be connected to a Project or Lab experiment.

### Goal 3 — Create opportunities

The site should naturally move visitors through this journey:

```text
Discover → Understand → Trust → Explore work → Contact
```

The site should not feel like a static funnel built only around `Home → Portfolio → Contact`.

---

## 3. Audience

### Priority 1 — Persian-speaking designers and product people

They are interested in:

- product design practice
- design systems
- UX decisions
- tools and workflows
- AI-assisted product building
- real project lessons
- experiments and opinions

### Priority 2 — Founders, PMs, and developers

They need to understand:

- how Sayid thinks
- how he approaches product problems
- whether he understands engineering constraints
- how he works across design and implementation
- how he handles systems and complexity

### Priority 3 — Persian-speaking clients and employers

They need evidence of:

- credibility
- experience
- selected work
- quality of thinking
- ability to collaborate
- contact and availability

### Priority 4 — Junior designers and learners

They may follow the site for practical learning, inspiration, methods, and real-world experience.

### Priority 5 — General technology audience

They may arrive through topics such as AI, tools, WordPress, Figma, plugins, personal experiments, and product building.

---

## 4. Positioning principles

### Do not make the portfolio consume the whole site

Portfolio content is important, but it is only one part of the product.

The strongest version of sayid.ir combines three dimensions:

```text
THINK        BUILD        WORK
Notes        Lab          Projects
Articles     Experiments  Case Studies
```

All three connect to the same personal identity.

### Show thinking, not only outcomes

A polished project is useful, but the reasoning behind it creates deeper trust.

The site should expose:

- decisions
- trade-offs
- lessons
- experiments
- failures and iterations where appropriate
- connections between design and implementation

### Prefer a few strong signals over a long capability list

The homepage should avoid becoming a directory of every tool or discipline Sayid has ever worked with.

Skills and tools can exist as supporting metadata, but the main narrative should remain clear.

---

## 5. Recommended information architecture

```text
sayid.ir
│
├── Home
│
├── Think
│   ├── Notes
│   └── Articles
│
├── Build
│   └── Lab / Experiments
│
├── Work
│   └── Projects / Case Studies
│
├── About
│
└── Contact
```

The exact labels can change during UI design, but the conceptual separation should remain.

---

## 6. Homepage role

The homepage should be a **live window into the system**, not a manually maintained landing page.

Recommended sections:

### Hero

A stable, identity-first introduction.

It should explain who Sayid is and what kind of work/thinking visitors will find here.

### Now

A very lightweight block showing what Sayid is currently exploring, learning, or building.

Example:

> Currently exploring AI-assisted product building, Figma-to-code workflows, and product engineering.

This should take less than a minute to update.

### Latest Notes

Automatically query the newest short-form notes.

### Selected Work

A small curated set of high-value projects. This section changes slowly.

### From the Lab

Recent experiments and things being built.

Possible status labels:

- Exploring
- Building
- Beta
- Shipped
- Archived

### Latest Article

The newest long-form piece.

### Currently Learning / Exploring

Small dynamic signals about areas of curiosity.

### Footer

Contact, social links, RSS, and navigation.

---

## 7. Relationship between sayid.ir and moghadam.pro

The two sites should strengthen each other instead of duplicating each other.

### moghadam.pro

Optimized for evaluation:

- international recruiters
- hiring managers
- international clients
- polished case studies
- resume and professional background
- English-first professional positioning

### sayid.ir

Optimized for continuity and personality:

- Persian-speaking community
- notes and thinking
- experiments
- articles
- build logs
- selected projects
- personal professional voice

Cross-linking between them is useful, but duplicated pages and duplicated maintenance should be minimized.

---

## 8. Fastest rebuild strategy

The objective is to **publish faster, not engineer harder**.

For the first rebuild phase, keep the existing practical stack rather than introducing migration complexity.

Recommended implementation direction:

```text
WordPress
  ↓
Custom Post Types
  ↓
Custom Fields / Taxonomies / Relationships
  ↓
Elementor Dynamic Templates
  ↓
Dynamic Queries / Loops
  ↓
Homepage + Archives + Related Content
```

Avoid rebuilding the platform in a new framework merely because a new stack is technically attractive.

A new stack should only be considered later if it solves a demonstrated product or maintenance problem.

---

## 9. Backend and management-panel principle

The most important backend decision is **not how beautiful the admin panel looks**.

The most important decision is the **content model**.

A well-designed content model makes publishing fast and enables the same item to automatically appear in multiple relevant locations.

The CMS should optimize for:

- very fast publishing
- minimal duplicated entry
- consistent metadata
- relationships between content
- automatic homepage updates
- reusable templates
- easy filtering and discovery

The management experience matters when it removes friction from publishing.

---

## 10. Product success criteria

The rebuild is successful when:

1. A first-time visitor understands the site’s identity within seconds.
2. Publishing a Note takes only a few minutes.
3. Publishing new content automatically updates all relevant views.
4. The homepage feels different after new content is published without manual redesign.
5. Long-form case studies are no longer required to make the website feel active.
6. Content created for social media can be preserved and expanded on the site.
7. sayid.ir and moghadam.pro have complementary, not duplicated, responsibilities.
8. The system remains easy enough that Sayid actually wants to keep using it.

---

## 11. Guiding principle

> **A living website is not a website with more animation. It is a website whose knowledge, work, and context visibly evolve over time.**

The architecture should therefore optimize for continuity of publishing rather than periodic redesigns.