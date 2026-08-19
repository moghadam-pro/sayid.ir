/**
 * homepage-entry.js — Hero-only entry state + deferred homepage reveal
 * (brief §15-16, docs/04).
 *
 * Deliberate deviation from the brief's literal `<template>` + `cloneNode`
 * suggestion: `#homepage-deferred-root` is normal, always-present, fully
 * server-rendered DOM from the very first response (see the docblock on
 * `Sayid_Core_Render::deferred_homepage()` for the full rationale — in
 * short, an inert `<template>` risks never being indexed and cannot satisfy
 * "no-JS users get a normal scrollable page" at the same time). This script
 * is the *only* thing that ever hides that content, and only while it is
 * actually running:
 *
 *   qualifying first visit  -> root gets `hidden` + document scroll lock
 *   first continuation gesture -> both are removed, scroll continues
 *   no JS at all              -> `hidden` is never applied; normal page
 *   bypassed visit (see below) -> `hidden` is never applied; normal page
 *
 * The `hidden` attribute both visually hides the subtree (like
 * `display:none`) and removes it from the accessibility tree, which is what
 * docs/04 §9 requires ("must not be exposed to the accessibility tree
 * before it is mounted or intentionally revealed") — without the SEO/no-JS
 * cost of `<template>`.
 */
(function () {
	'use strict';

	var root = document.documentElement;
	var hero = document.querySelector('.home-hero');
	var deferredRoot = document.getElementById('homepage-deferred-root');
	var scrollCue = document.querySelector('[data-scroll-cue]');

	// Not the homepage, or the Hero/deferred-content widgets weren't placed
	// — nothing to lock, leave the page exactly as rendered.
	if (!deferredRoot || !hero) {
		return;
	}

	var prefersReducedMotion = window.matchMedia &&
		window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function isBypassed() {
		// Anchor / deep link, e.g. sayid.ir/#lab — let the browser show and
		// scroll to the target section normally.
		if (window.location.hash) {
			return true;
		}
		// Browser back/forward restoring a previous scroll position.
		var nav = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
		if (nav && nav.type === 'back_forward') {
			return true;
		}
		// Repeat homepage visit in the same tab session.
		try {
			if (sessionStorage.getItem('sayid-home-entered') === '1') {
				return true;
			}
		} catch (e) { /* private mode: fall through, treat as first visit */ }

		return false;
	}

	function markSessionEntered() {
		try {
			sessionStorage.setItem('sayid-home-entered', '1');
		} catch (e) { /* ignore */ }
	}

	if (isBypassed()) {
		markSessionEntered();
		return; // deferredRoot is already visible normal DOM — nothing to do
	}

	// ---- Locked entry path. ----
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
		if (delta > 24) { // swipe up = continue downward
			continueIntoContent();
		}
	}

	function onKeydown(event) {
		// Only react when focus is on the page itself, not on a control
		// inside the Hero that has its own meaning for these keys.
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
