/**
 * nav.js — makes the header's dropdown submenus reachable on touch.
 *
 * The dropdown itself is pure CSS (:hover / :focus-within), which covers
 * mouse and keyboard. Touch has neither: a tap on a parent item fires
 * navigation immediately, so the nested items could never be opened. Here
 * the first tap opens the submenu and the second follows the parent's own
 * link, which is the behaviour people expect from a mobile nav.
 *
 * Only runs where hover genuinely doesn't work — on a normal desktop the
 * CSS is left entirely alone.
 */
(function () {
	'use strict';

	var hasHover = window.matchMedia && window.matchMedia('(hover: hover)').matches;
	if (hasHover) {
		return;
	}

	var parents = document.querySelectorAll('.site-nav__list .menu-item-has-children');
	if (!parents.length) {
		return;
	}

	function closeAll(except) {
		Array.prototype.forEach.call(parents, function (item) {
			if (item !== except) {
				item.classList.remove('is-open');
			}
		});
	}

	Array.prototype.forEach.call(parents, function (item) {
		var link = item.querySelector(':scope > a');
		if (!link) {
			return;
		}
		link.addEventListener('click', function (event) {
			if (item.classList.contains('is-open')) {
				return; // Second tap — let the link navigate.
			}
			event.preventDefault();
			closeAll(item);
			item.classList.add('is-open');
		});
	});

	document.addEventListener('click', function (event) {
		if (!event.target.closest('.site-nav__list .menu-item-has-children')) {
			closeAll(null);
		}
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeAll(null);
		}
	});
})();
