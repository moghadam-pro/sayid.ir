/**
 * signature-prompt.js — «پرامپت‌نویس» mini-game.
 *
 * Fully client-side and simulated: each chip group is single-choice; the
 * chosen options become CSS custom properties on the preview and phrases
 * appended to the displayed prompt. No AI/API call — the point is that the
 * designer's choices, not the model, decide the result.
 */
(function () {
	'use strict';

	var STYLES = {
		color: {
			warm: { '--pg-bg': '#fff4e8', '--pg-text': '#3b2a1a', '--pg-accent': '#e8833a', '--pg-accent-text': '#fff' },
			bold: { '--pg-bg': '#1b1b2b', '--pg-text': '#f3f3fa', '--pg-accent': '#ffba20', '--pg-accent-text': '#1b1b2b' },
			natural: { '--pg-bg': '#eaf7ef', '--pg-text': '#17382a', '--pg-accent': '#2f9e77', '--pg-accent-text': '#fff' }
		},
		space: {
			tight: { '--pg-pad': '10px', '--pg-gap': '4px' },
			airy: { '--pg-pad': '24px', '--pg-gap': '12px' },
			wide: { '--pg-pad': '40px', '--pg-gap': '20px' }
		},
		feel: {
			kind: { '--pg-radius': '22px', '--pg-weight': '500', '--pg-tilt': '0deg' },
			serious: { '--pg-radius': '4px', '--pg-weight': '700', '--pg-tilt': '0deg' },
			playful: { '--pg-radius': '36px', '--pg-weight': '600', '--pg-tilt': '-1.5deg' }
		},
		ref: {
			minimal: { '--pg-shadow': 'none', '--pg-border': '1px solid rgba(128,128,128,.35)' },
			soft: { '--pg-shadow': '0 12px 30px rgba(0,0,0,.18)', '--pg-border': '1px solid transparent' },
			loud: { '--pg-shadow': '6px 6px 0 rgba(0,0,0,.85)', '--pg-border': '2px solid rgba(0,0,0,.85)' }
		}
	};

	var VERDICTS = [
		'خروجی اولیه‌ی هوش مصنوعی: سریع، ولی بی‌هویت.',
		'یه قدم بهتر شد. هنوز چیزهای زیادی نگفتی.',
		'داره شکل می‌گیره.',
		'تقریباً رسیدی؛ فقط یه تصمیم مونده.',
		'همین! مدل فقط ساخت؛ تصمیم‌ها از چشم تو اومد.'
	];

	document.querySelectorAll('[data-prompt-game]').forEach(function (game) {
		var chips = game.querySelectorAll('[data-pg-group]');
		var preview = game.querySelector('[data-pg-preview]');
		var promptEl = game.querySelector('[data-pg-prompt]');
		var bar = game.querySelector('[data-pg-bar]');
		var verdict = game.querySelector('[data-pg-verdict]');
		var base = promptEl.getAttribute('data-base') || '';
		var order = ['color', 'space', 'feel', 'ref'];
		var state = {};
		var allVars = [];

		Object.keys(STYLES).forEach(function (g) {
			Object.keys(STYLES[g]).forEach(function (o) {
				Object.keys(STYLES[g][o]).forEach(function (v) {
					if (allVars.indexOf(v) === -1) { allVars.push(v); }
				});
			});
		});

		function render() {
			allVars.forEach(function (v) { preview.style.removeProperty(v); });
			var phrases = [];
			var count = 0;
			order.forEach(function (g) {
				var val = state[g];
				if (!val) { return; }
				count++;
				var vars = STYLES[g][val];
				Object.keys(vars).forEach(function (v) { preview.style.setProperty(v, vars[v]); });
				var chip = game.querySelector('[data-pg-group="' + g + '"][data-pg-value="' + val + '"]');
				if (chip) { phrases.push(chip.getAttribute('data-pg-phrase')); }
			});
			chips.forEach(function (chip) {
				var on = state[chip.getAttribute('data-pg-group')] === chip.getAttribute('data-pg-value');
				chip.classList.toggle('is-active', on);
				chip.setAttribute('aria-pressed', on ? 'true' : 'false');
			});
			promptEl.textContent = '\u00AB' + base + (phrases.length ? '\u060C ' + phrases.join('\u060C ') : '') + '\u00BB';
			bar.style.width = (count / order.length * 100) + '%';
			verdict.textContent = VERDICTS[count];
			game.classList.toggle('is-complete', count === order.length);
			preview.classList.toggle('is-bad', count === 0);
		}

		chips.forEach(function (chip) {
			chip.addEventListener('click', function () {
				var g = chip.getAttribute('data-pg-group');
				var v = chip.getAttribute('data-pg-value');
				if (state[g] === v) { delete state[g]; } else { state[g] = v; }
				render();
			});
		});

		var reset = game.querySelector('[data-pg-reset]');
		if (reset) {
			reset.addEventListener('click', function () { state = {}; render(); });
		}

		render();
	});
})();
