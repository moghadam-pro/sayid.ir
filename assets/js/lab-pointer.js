/**
 * lab-pointer.js — local pointer-reveal border for Lab Bento cards
 * (brief §19 / docs/05 §4).
 *
 * Fine-pointer devices only. Touch/coarse pointers get the complete static
 * card defined in components.css — this script never attaches its
 * listeners there, so there is nothing to clean up or feature-detect twice.
 *
 * Performance: pointer coordinates are written to CSS custom properties
 * only, batched through requestAnimationFrame, never triggering layout
 * reads on `pointermove` itself.
 */
(function () {
	'use strict';

	if (!window.matchMedia || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
		return;
	}

	var cards = document.querySelectorAll('[data-lab-card]');
	if (!cards.length) {
		return;
	}

	var pending = null; // { card, x, y }
	var rafId = null;

	function flush() {
		rafId = null;
		if (!pending) {
			return;
		}
		pending.card.style.setProperty('--pointer-x', pending.x + '%');
		pending.card.style.setProperty('--pointer-y', pending.y + '%');
		pending = null;
	}

	function schedule(card, x, y) {
		pending = { card: card, x: x, y: y };
		if (rafId === null) {
			rafId = requestAnimationFrame(flush);
		}
	}

	cards.forEach(function (card) {
		card.addEventListener('pointerenter', function (event) {
			if (event.pointerType !== 'mouse' && event.pointerType !== 'pen') {
				return;
			}
			card.classList.add('is-pointer-active');
		});

		card.addEventListener('pointermove', function (event) {
			if (event.pointerType !== 'mouse' && event.pointerType !== 'pen') {
				return;
			}
			var rect = card.getBoundingClientRect();
			var x = ((event.clientX - rect.left) / rect.width) * 100;
			var y = ((event.clientY - rect.top) / rect.height) * 100;
			schedule(card, x, y);
		});

		card.addEventListener('pointerleave', function () {
			card.classList.remove('is-pointer-active');
		});

		// Keyboard focus gets the same local-glow treatment, centered,
		// so the effect communicates state without requiring a pointer.
		card.addEventListener('focusin', function () {
			card.style.setProperty('--pointer-x', '50%');
			card.style.setProperty('--pointer-y', '50%');
			card.classList.add('is-pointer-active');
		});
		card.addEventListener('focusout', function () {
			card.classList.remove('is-pointer-active');
		});
	});
})();
