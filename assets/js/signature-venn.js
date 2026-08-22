/**
 * signature-venn.js — the Design × Code × AI Venn diagram.
 *
 * Interaction: four real, labeled buttons (data-zone-trigger) — one per
 * pairwise overlap plus the center where all three meet — each activate
 * the matching SVG overlap region (data-zone) and update the aria-live
 * caption with that zone's real sentence. Deliberately not driven by
 * pointer-position math over the diagram itself: a labeled button is
 * simpler, fully keyboard-operable, and unambiguous about which zone is
 * being activated, versus guessing which invisible region a cursor is
 * hovering.
 */
(function () {
	'use strict';

	var sections = document.querySelectorAll('[data-signature-venn]');
	if (!sections.length) {
		return;
	}

	sections.forEach(function (venn) {
		var section = venn.closest('[data-sayid-signature]') || venn;
		var dataScript = section.querySelector('[data-signature-zones]');
		var relationshipEl = section.querySelector('[data-signature-relationship]');
		var chips = venn.querySelectorAll('[data-zone-trigger]');
		var overlaps = venn.querySelectorAll('[data-zone]');

		var data = { default: '', zones: {} };
		try {
			data = dataScript ? JSON.parse(dataScript.textContent) : data;
		} catch (e) { /* keep defaults */ }

		function activate(zone) {
			overlaps.forEach(function (el) {
				el.classList.toggle('is-active', el.getAttribute('data-zone') === zone);
			});
			chips.forEach(function (chip) {
				var isActive = chip.getAttribute('data-zone-trigger') === zone;
				chip.classList.toggle('is-active', isActive);
				chip.setAttribute('aria-pressed', isActive ? 'true' : 'false');
			});
			if (relationshipEl && data.zones[zone]) {
				relationshipEl.textContent = data.zones[zone];
			}
		}

		function reset() {
			overlaps.forEach(function (el) { el.classList.remove('is-active'); });
			chips.forEach(function (chip) {
				chip.classList.remove('is-active');
				chip.setAttribute('aria-pressed', 'false');
			});
			if (relationshipEl) {
				relationshipEl.textContent = data.default;
			}
		}

		chips.forEach(function (chip) {
			var zone = chip.getAttribute('data-zone-trigger');
			chip.setAttribute('aria-pressed', 'false');
			chip.addEventListener('pointerenter', function (event) {
				if (event.pointerType === 'mouse' || event.pointerType === 'pen') {
					activate(zone);
				}
			});
			chip.addEventListener('focus', function () { activate(zone); });
			chip.addEventListener('click', function () { activate(zone); });
		});

		venn.addEventListener('pointerleave', reset);
		venn.addEventListener('focusout', function (event) {
			if (!venn.contains(event.relatedTarget)) {
				reset();
			}
		});
	});
})();
