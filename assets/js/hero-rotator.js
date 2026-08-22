/**
 * hero-rotator.js — the Hero's rotating headline chip. Cycles through the
 * `<li>` phrases in `.home-hero__rotator-track` with a slide-down
 * transition, styled to read as "selected text" rather than a button (it
 * is not a link — nothing to click, nothing to navigate to).
 *
 * Pure transform-based animation (translateY on the track), so it's cheap
 * and GPU-friendly. Respects `prefers-reduced-motion` by jumping between
 * phrases instantly instead of animating, and pauses entirely while the
 * tab is hidden.
 */
(function () {
	'use strict';

	var rotator = document.querySelector('[data-hero-rotator]');
	if (!rotator) {
		return;
	}
	var track = rotator.querySelector('.home-hero__rotator-track');
	var items = rotator.querySelectorAll('.home-hero__rotator-track > li');
	if (!track || items.length < 2) {
		return;
	}

	var prefersReducedMotion = window.matchMedia &&
		window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	var index = 0;
	var timer = null;
	var itemHeight = 0;

	function measure() {
		track.style.transform = 'none';
		itemHeight = items[0].offsetHeight;
		rotator.style.height = itemHeight + 'px';
		goTo(index);
	}

	function goTo(nextIndex) {
		items[index].setAttribute('aria-hidden', 'true');
		index = nextIndex;
		items[index].removeAttribute('aria-hidden');
		track.style.transform = 'translateY(-' + (index * itemHeight) + 'px)';
	}

	function tick() {
		goTo((index + 1) % items.length);
	}

	function start() {
		if (timer || prefersReducedMotion) {
			return;
		}
		timer = setInterval(tick, 2600);
	}

	function stop() {
		clearInterval(timer);
		timer = null;
	}

	window.addEventListener('resize', measure);

	document.addEventListener('visibilitychange', function () {
		if (document.hidden) {
			stop();
		} else {
			start();
		}
	});

	measure();

	// Estedad loads with font-display:swap, so the first measurement above
	// can land while the fallback font is still showing. Its line metrics
	// differ enough to clip the chip, so re-measure once the real font is
	// in — otherwise the wrong height sticks until the visitor resizes.
	if (document.fonts && document.fonts.ready) {
		document.fonts.ready.then(measure);
	}

	start();
})();
