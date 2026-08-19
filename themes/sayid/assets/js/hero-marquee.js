/**
 * hero-marquee.js — the two background marquee lines are pure CSS
 * animation (see .home-hero__marquee-row--a/--b in hero.css); this script
 * only pauses them while the Hero is scrolled out of view, so the
 * animation doesn't keep costing a compositor layer once the visitor has
 * moved on down the page. `prefers-reduced-motion` is already handled
 * entirely in CSS, so this script does nothing at all in that case.
 */
(function () {
	'use strict';

	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var marquee = document.querySelector('[data-hero-marquee]');
	if (!marquee || !('IntersectionObserver' in window)) {
		return;
	}

	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			marquee.style.animationPlayState = entry.isIntersecting ? 'running' : 'paused';
			var rows = marquee.querySelectorAll('.home-hero__marquee-row');
			rows.forEach(function (row) {
				row.style.animationPlayState = entry.isIntersecting ? 'running' : 'paused';
			});
		});
	}, { threshold: 0 });

	io.observe(marquee);
})();
