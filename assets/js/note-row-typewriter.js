/**
 * note-row-typewriter.js — on hover, types a snippet of the Note's body
 * text into .note-row__excerpt, next to the title. Clamped to one line by
 * CSS (overflow:hidden; white-space:nowrap on .note-row__excerpt); this
 * also stops appending characters once the element starts to overflow its
 * own box, so it never keeps "typing" past what could ever be visible.
 */
(function () {
	'use strict';

	var links = document.querySelectorAll('.note-row__link');
	if (!links.length) {
		return;
	}

	var prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var TYPE_INTERVAL_MS = 18;

	links.forEach(function (link) {
		var excerptEl = link.querySelector('.note-row__excerpt');
		if (!excerptEl) {
			return;
		}
		var fullText = excerptEl.getAttribute('data-note-excerpt') || '';
		if (!fullText) {
			return;
		}

		var timer = null;
		var index = 0;

		function stop() {
			if (timer) {
				clearInterval(timer);
				timer = null;
			}
		}

		function typeStep() {
			index++;
			excerptEl.textContent = fullText.slice(0, index);
			if (index >= fullText.length || excerptEl.scrollWidth > excerptEl.clientWidth) {
				stop();
			}
		}

		link.addEventListener('mouseenter', function () {
			stop();
			if (prefersReducedMotion) {
				excerptEl.textContent = fullText;
				return;
			}
			index = 0;
			excerptEl.textContent = '';
			timer = setInterval(typeStep, TYPE_INTERVAL_MS);
		});

		link.addEventListener('mouseleave', function () {
			stop();
			excerptEl.textContent = '';
		});
	});
})();
