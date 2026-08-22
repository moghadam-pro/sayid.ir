/**
 * theme.js — footer theme control (System / Light / Dark).
 *
 * The flash-prevention bootstrap that actually sets `data-theme` before
 * first paint lives inline in class-assets.php (`inline_theme_bootstrap`),
 * not here — this file only has to run after DOM is available, so it owns
 * the *interactive* half: wiring the control(s), persisting a manual
 * choice, and reacting live to OS changes while in System mode.
 *
 * Storage model (brief §25):
 *   no stored value  -> follow prefers-color-scheme
 *   'light' | 'dark' -> stored override, applied via [data-theme]
 *   System button    -> removes the override, resumes prefers-color-scheme
 */
(function () {
	'use strict';

	var STORAGE_KEY = 'sayid-theme';
	var root = document.documentElement;

	function getStored() {
		try {
			return localStorage.getItem(STORAGE_KEY);
		} catch (e) {
			return null;
		}
	}

	function setStored(value) {
		try {
			if (value) {
				localStorage.setItem(STORAGE_KEY, value);
			} else {
				localStorage.removeItem(STORAGE_KEY);
			}
		} catch (e) { /* ignore, e.g. private mode */ }
	}

	function apply(mode) {
		if (mode === 'light' || mode === 'dark') {
			root.setAttribute('data-theme', mode);
		} else {
			root.removeAttribute('data-theme');
		}
		syncControls(mode || 'system');
	}

	function syncControls(activeMode) {
		var switches = document.querySelectorAll('[data-theme-switch]');
		for (var i = 0; i < switches.length; i++) {
			var buttons = switches[i].querySelectorAll('[data-theme-option]');
			for (var j = 0; j < buttons.length; j++) {
				var isActive = buttons[j].getAttribute('data-theme-option') === activeMode;
				buttons[j].setAttribute('aria-pressed', isActive ? 'true' : 'false');
			}
		}
	}

	function init() {
		var stored = getStored();
		syncControls(stored || 'system');

		document.addEventListener('click', function (event) {
			var btn = event.target.closest('[data-theme-option]');
			if (!btn) {
				return;
			}
			var mode = btn.getAttribute('data-theme-option');
			if (mode === 'system') {
				setStored(null);
				apply(null);
			} else {
				setStored(mode);
				apply(mode);
			}
		});

		if (window.matchMedia) {
			var media = window.matchMedia('(prefers-color-scheme: dark)');
			var onChange = function () {
				// Only relevant in System mode — a manual override does not
				// react to OS changes until the visitor picks System again.
				if (!getStored()) {
					syncControls('system');
				}
			};
			if (media.addEventListener) {
				media.addEventListener('change', onChange);
			} else if (media.addListener) {
				media.addListener(onChange); // Safari <14
			}
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
