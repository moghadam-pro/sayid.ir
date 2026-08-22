/**
 * homepage-entry.js — Hero-only entry state + deferred homepage reveal.
 *
 * #homepage-deferred-root is normal, always-present, server-rendered DOM
 * (front-page.php renders it directly, not behind a shortcode/widget) —
 * this script is the only thing that ever hides it, and only while it is
 * actually running:
 *
 *   qualifying first visit     -> root gets `hidden` + document scroll lock
 *   first continuation gesture -> both are removed, scroll continues
 *   no JS at all               -> `hidden` is never applied; normal page
 *   bypassed visit (see below) -> `hidden` is never applied; normal page
 *
 * The `hidden` attribute both visually hides the subtree and removes it
 * from the accessibility tree until intentionally revealed, without an
 * inert `<template>`'s SEO/no-JS cost (a `<template>` never becomes real
 * DOM without a script reacting to a gesture crawlers don't perform).
 */
(function () {
	'use strict';

	var root = document.documentElement;
	var hero = document.querySelector('.home-hero');
	var deferredRoot = document.getElementById('homepage-deferred-root');
	var scrollCue = document.querySelector('[data-scroll-cue]');

	if (!deferredRoot || !hero) {
		return;
	}

	var prefersReducedMotion = window.matchMedia &&
		window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function isBypassed() {
		if (window.location.hash) {
			return true;
		}
		var nav = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
		if (nav && nav.type === 'back_forward') {
			return true;
		}
		try {
			if (sessionStorage.getItem('sayid-home-entered') === '1') {
				return true;
			}
		} catch (e) { /* private mode: treat as first visit */ }
		return false;
	}

	function markSessionEntered() {
		try {
			sessionStorage.setItem('sayid-home-entered', '1');
		} catch (e) { /* ignore */ }
	}

	if (isBypassed()) {
		markSessionEntered();
		return;
	}

	deferredRoot.hidden = true;
	root.setAttribute('data-home-entry', 'locked');
	markSessionEntered();

	var triggered = false;

	function continueIntoContent() {
		if (triggered) {
			return;
		}
		triggered = true;

		root.setAttribute('data-home-entry', 'transitioning');
		if (scrollCue) {
			scrollCue.classList.add('is-hidden');
		}

		deferredRoot.hidden = false;
		deferredRoot.classList.add('is-revealing');

		requestAnimationFrame(function () {
			root.removeAttribute('data-home-entry');
			deferredRoot.scrollIntoView({
				behavior: prefersReducedMotion ? 'auto' : 'smooth',
				block: 'start',
			});
		});

		detachListeners();
	}

	function onWheel(event) {
		if (event.deltaY > 0) {
			continueIntoContent();
		}
	}

	var touchStartY = null;
	function onTouchStart(event) {
		touchStartY = event.touches[0].clientY;
	}
	function onTouchMove(event) {
		if (touchStartY === null) {
			return;
		}
		var delta = touchStartY - event.touches[0].clientY;
		if (delta > 24) {
			continueIntoContent();
		}
	}

	function onKeydown(event) {
		if (event.target !== document.body && event.target !== root) {
			return;
		}
		if (event.key === 'ArrowDown' || event.key === 'PageDown') {
			event.preventDefault();
			continueIntoContent();
		} else if (event.key === ' ' || event.code === 'Space') {
			event.preventDefault();
			continueIntoContent();
		}
	}

	function onCueClick() {
		continueIntoContent();
	}

	function detachListeners() {
		window.removeEventListener('wheel', onWheel);
		window.removeEventListener('touchstart', onTouchStart);
		window.removeEventListener('touchmove', onTouchMove);
		document.removeEventListener('keydown', onKeydown);
		if (scrollCue) {
			scrollCue.removeEventListener('click', onCueClick);
		}
	}

	window.addEventListener('wheel', onWheel, { passive: true });
	window.addEventListener('touchstart', onTouchStart, { passive: true });
	window.addEventListener('touchmove', onTouchMove, { passive: true });
	document.addEventListener('keydown', onKeydown);
	if (scrollCue) {
		scrollCue.addEventListener('click', onCueClick);
	}
})();
