/**
 * signature-network.js — Design × Code × AI relationship network
 * (brief §20 / docs/06).
 *
 * Interaction model: activating a node (pointer hover, keyboard focus, or
 * tap) highlights that node, brightens its direct edges, lightly emphasizes
 * the nodes on the other end of those edges, and dims everything unrelated.
 * A live-region caption is updated with the relationship's real Persian
 * sentence (from the JSON payload server-rendered into the section — see
 * Sayid_Core_Render::signature()), so the interaction has semantic value
 * and works identically for pointer, keyboard and screen-reader users.
 *
 * No scroll-jacking, no forced pinned sequence, no distance-based pointer
 * math: activation is per-node (hover/focus/tap on that specific button),
 * which keeps the interaction simple, robust, and fully keyboard/touch
 * accessible without continuous rAF sampling of pointer position.
 */
(function () {
	'use strict';

	var sections = document.querySelectorAll('[data-signature-network]');
	if (!sections.length) {
		return;
	}

	var prefersReducedMotion = window.matchMedia &&
		window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var isCoarsePointer = window.matchMedia &&
		window.matchMedia('(pointer: coarse)').matches;

	sections.forEach(setUpNetwork);

	function setUpNetwork(container) {
		var section = container.closest('[data-sayid-signature]') || container;
		var dataScript = section.querySelector('[data-signature-edges]');
		var relationshipEl = section.querySelector('[data-signature-relationship]');
		var nodes = container.querySelectorAll('[data-node]');
		var edges = container.querySelectorAll('[data-edge]');
		var defaultText = relationshipEl ? relationshipEl.textContent : '';

		var edgeData = [];
		try {
			edgeData = dataScript ? JSON.parse(dataScript.textContent) : [];
		} catch (e) {
			edgeData = [];
		}

		function edgesForNode(key) {
			return edgeData.filter(function (edge) {
				return edge.from === key || edge.to === key;
			});
		}

		function activate(key) {
			var connected = edgesForNode(key);
			var connectedKeys = {};
			connected.forEach(function (edge) {
				connectedKeys[edge.from] = true;
				connectedKeys[edge.to] = true;
			});

			nodes.forEach(function (node) {
				var nodeKey = node.getAttribute('data-node');
				node.classList.remove('is-active', 'is-connected', 'is-dim');
				if (nodeKey === key) {
					node.classList.add('is-active');
				} else if (connectedKeys[nodeKey]) {
					node.classList.add('is-connected');
				} else {
					node.classList.add('is-dim');
				}
			});

			edges.forEach(function (edge) {
				var from = edge.getAttribute('data-from');
				var to = edge.getAttribute('data-to');
				var isConnected = from === key || to === key;
				edge.classList.toggle('is-active', isConnected);
				edge.classList.toggle('is-dim', !isConnected);
			});

			if (relationshipEl && connected.length) {
				relationshipEl.textContent = connected[0].message;
			}
		}

		function reset() {
			nodes.forEach(function (node) {
				node.classList.remove('is-active', 'is-connected', 'is-dim');
			});
			edges.forEach(function (edge) {
				edge.classList.remove('is-active', 'is-dim');
			});
			if (relationshipEl) {
				relationshipEl.textContent = defaultText;
			}
		}

		nodes.forEach(function (node) {
			var key = node.getAttribute('data-node');

			node.addEventListener('pointerenter', function (event) {
				if (event.pointerType === 'touch') {
					return; // handled by click below, to avoid a double trigger
				}
				stopAutoRotate();
				activate(key);
			});
			node.addEventListener('focus', function () {
				stopAutoRotate();
				activate(key);
			});
			node.addEventListener('click', function () {
				stopAutoRotate();
				activate(key);
				scheduleAutoRotateResume();
			});
		});

		container.addEventListener('pointerleave', function () {
			reset();
		});
		container.addEventListener('focusout', function (event) {
			// Only reset once focus has left the whole network, not when it
			// moves from one node button to the next.
			if (!container.contains(event.relatedTarget)) {
				reset();
			}
		});

		// ---- Mobile: gentle auto-rotate through relationships. ----
		var rotateTimer = null;
		var resumeTimer = null;
		var rotateIndex = 0;
		var nodeKeys = Array.prototype.map.call(nodes, function (n) {
			return n.getAttribute('data-node');
		});

		function stopAutoRotate() {
			if (rotateTimer) {
				clearInterval(rotateTimer);
				rotateTimer = null;
			}
		}

		function scheduleAutoRotateResume() {
			clearTimeout(resumeTimer);
			resumeTimer = setTimeout(startAutoRotate, 6000);
		}

		function startAutoRotate() {
			if (!isCoarsePointer || prefersReducedMotion || !nodeKeys.length) {
				return;
			}
			stopAutoRotate();
			rotateTimer = setInterval(function () {
				activate(nodeKeys[rotateIndex % nodeKeys.length]);
				rotateIndex++;
			}, 4000);
		}

		if (isCoarsePointer && !prefersReducedMotion) {
			var io = 'IntersectionObserver' in window
				? new IntersectionObserver(function (entries) {
					entries.forEach(function (entry) {
						if (entry.isIntersecting) {
							startAutoRotate();
						} else {
							stopAutoRotate();
						}
					});
				}, { threshold: 0.4 })
				: null;
			if (io) {
				io.observe(container);
			} else {
				startAutoRotate();
			}
		}
	}
})();
