/**
 * magic-name.js — hidden easter egg on the Hero's name (#magicalTag).
 *
 * 8 clicks within 2s of each other triggers a canvas firework burst, then
 * redirects to /magic. Not a real link (no href, no keyboard path) — the
 * only visible hint is the yellow hover color (hero.css), same as the
 * site's plain link hover.
 */
(function () {
	'use strict';

	var selector = '#magicalTag';
	var requiredClicks = 8;
	var maxIntervalMs = 2000;
	var redirectUrl = 'https://sayid.ir/magic';
	var prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	var count = 0;
	var lastClickTime = 0;
	var resetTimer = null;

	function resetCounter() {
		count = 0;
		lastClickTime = 0;
		if (resetTimer) {
			clearTimeout(resetTimer);
			resetTimer = null;
		}
	}

	// Canvas particle burst centered on the clicked element. Skipped under
	// prefers-reduced-motion — the redirect still happens either way, this
	// is just the celebratory flourish.
	function showFirework(el, callback) {
		var rect = el.getBoundingClientRect();
		var cx = rect.left + rect.width / 2;
		var cy = rect.top + rect.height / 2;

		var canvas = document.createElement('canvas');
		canvas.style.position = 'fixed';
		canvas.style.left = '0';
		canvas.style.top = '0';
		canvas.style.pointerEvents = 'none';
		canvas.width = window.innerWidth;
		canvas.height = window.innerHeight;
		document.body.appendChild(canvas);

		var ctx = canvas.getContext('2d');
		var particles = [];
		var colors = ['#ff0040', '#ff8000', '#ffff00', '#00ff80', '#00c0ff', '#8000ff'];

		for (var i = 0; i < 50; i++) {
			particles.push({
				x: cx,
				y: cy,
				angle: Math.random() * 2 * Math.PI,
				speed: 2 + Math.random() * 4,
				radius: 2 + Math.random() * 2,
				life: 40 + Math.random() * 20,
				color: colors[Math.floor(Math.random() * colors.length)]
			});
		}

		function animate() {
			ctx.clearRect(0, 0, canvas.width, canvas.height);
			var stillAlive = false;
			particles.forEach(function (p) {
				if (p.life > 0) {
					p.x += Math.cos(p.angle) * p.speed;
					p.y += Math.sin(p.angle) * p.speed;
					p.life--;
					stillAlive = stillAlive || p.life > 0;
					ctx.beginPath();
					ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI);
					ctx.fillStyle = p.color;
					ctx.fill();
				}
			});
			if (stillAlive) {
				requestAnimationFrame(animate);
			} else {
				document.body.removeChild(canvas);
				callback();
			}
		}
		animate();
	}

	function handleClick(e) {
		var target = e.target.closest(selector);
		if (!target) {
			return;
		}

		var now = Date.now();

		if (lastClickTime === 0 || (now - lastClickTime) <= maxIntervalMs) {
			count += 1;
		} else {
			count = 1;
		}

		lastClickTime = now;

		if (resetTimer) {
			clearTimeout(resetTimer);
		}
		resetTimer = setTimeout(resetCounter, maxIntervalMs + 50);

		if (count >= requiredClicks) {
			resetCounter();
			if (prefersReducedMotion) {
				window.location.href = redirectUrl;
			} else {
				showFirework(target, function () {
					window.location.href = redirectUrl;
				});
			}
		}
	}

	document.addEventListener('click', handleClick, false);
})();
