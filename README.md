# sayid.ir

**Personal website, digital garden, and living studio of Sayid Moghadam.**

[sayid.ir](https://sayid.ir) is being rebuilt as a Persian-first space for product design, experiments, building, learning, and writing.

This repository originally started as the source repository for Sayid's personal website in 2013. It is now being repurposed for the next generation of the site while preserving its project history.

## Product direction

The new sayid.ir is intentionally **not** a Persian duplicate of [moghadam.pro](https://moghadam.pro).

- `moghadam.pro` focuses on professional portfolio, case studies, international opportunities, and career positioning.
- `sayid.ir` focuses on an evolving Persian personal/professional presence: thoughts, notes, articles, experiments, builds, and selected work.

A useful working definition:

> **A living Persian space for thinking, building, experimenting, documenting, and sharing.**

## Documentation

The rebuild is being defined from the product and content architecture first, before moving into UI implementation.

- [Product Definition & Strategy](docs/01-product-definition.md)
- [Content Model & Living Site Principles](docs/02-content-model.md)
- [Homepage Architecture & Interaction Model](docs/03-homepage-architecture.md)
- [Hero Entry, Scroll Cue & Deferred Homepage Mount](docs/04-hero-scroll-entry.md)
- [Selected Work & Lab](docs/05-selected-work-lab.md)
- [Signature Dark Section — Design × Code × AI](docs/06-signature-dark-section.md)
- [Editorial Homepage Sections — Notes, Article, Connect & Footer](docs/07-editorial-and-connect.md)
- [Homepage Low-Fidelity Wireframe — v1](docs/08-homepage-low-fi-wireframe.md)

## Current rebuild principles

- Treat the website as a living product, not a static portfolio.
- Optimize the publishing workflow for speed.
- Keep short-form Notes separate from long-form Articles and Case Studies.
- Make experiments and active builds visible through a dedicated Lab layer.
- Use structured content and relationships rather than manually maintained homepage sections.
- Let new content update relevant parts of the site automatically.
- Keep `sayid.ir` and `moghadam.pro` complementary rather than duplicated.

## Initial implementation direction

The first rebuild phase favors iteration speed over unnecessary platform migration:

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
Living Homepage + Archives + Related Content
```

A different stack should only be introduced later if real product or maintenance needs justify the migration cost.

## Status

**Homepage definition / low-fidelity architecture**

The first complete homepage narrative and low-fidelity layout are now documented. The next phase is Visual System v1, followed by Hero audit and high-fidelity homepage design.

---

© Sayid Moghadam