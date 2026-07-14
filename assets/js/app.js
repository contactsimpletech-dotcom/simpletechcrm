/**
 * Perfex CRM WordPress Theme — Main JS
 * Runs after DOM ready. All sidebar/header interactions are in footer.php
 * to keep them inline. This file handles additional non-critical features.
 */

(function ($) {
	'use strict';

	/* ── Auto-hide alerts after 5s ─────────────────────────── */
	setTimeout(function () {
		document.querySelectorAll('.alert[data-auto-dismiss]').forEach(function (el) {
			el.style.transition = 'opacity .4s';
			el.style.opacity    = '0';
			setTimeout(function () { el.remove(); }, 400);
		});
	}, 5000);

	/* ── Confirm delete buttons ────────────────────────────── */
	document.querySelectorAll('[data-confirm]').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			var msg = btn.getAttribute('data-confirm') || 'Are you sure?';
			if (!window.confirm(msg)) {
				e.preventDefault();
				e.stopPropagation();
			}
		});
	});

	/* ── Tooltip titles ────────────────────────────────────── */
	document.querySelectorAll('[title]').forEach(function (el) {
		// Basic aria-label fallback
		if (!el.getAttribute('aria-label')) {
			el.setAttribute('aria-label', el.getAttribute('title'));
		}
	});

	/* ── Collapsible card body ─────────────────────────────── */
	document.querySelectorAll('.card-toggle').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var card = btn.closest('.card');
			var body = card && card.querySelector('.card-body');
			if (body) {
				var collapsed = body.style.display === 'none';
				body.style.display = collapsed ? '' : 'none';
				btn.querySelector('i') && btn.querySelector('i').classList.toggle('fa-chevron-up', collapsed);
				btn.querySelector('i') && btn.querySelector('i').classList.toggle('fa-chevron-down', !collapsed);
			}
		});
	});

	/* ── Table row click → navigate ────────────────────────── */
	document.querySelectorAll('table.perfex-table[data-row-click] tbody tr').forEach(function (row) {
		var link = row.querySelector('a[href]');
		if (link) {
			row.style.cursor = 'pointer';
			row.addEventListener('click', function (e) {
				if (e.target.tagName !== 'A' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'INPUT') {
					window.location = link.href;
				}
			});
		}
	});

	/* ── Progress bars: trigger on scroll-into-view ─────────── */
	if ('IntersectionObserver' in window) {
		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					var bar = entry.target;
					var pct = bar.getAttribute('data-percent') || bar.getAttribute('data-width') || 0;
					bar.style.width = Math.min(parseFloat(pct), 100) + '%';
					observer.unobserve(bar);
				}
			});
		}, { threshold: 0.2 });

		document.querySelectorAll('[data-percent], [data-width]').forEach(function (bar) {
			bar.style.width = '0%';
			observer.observe(bar);
		});
	}

	/* ── Sticky table header ────────────────────────────────── */
	// Already handled via CSS thead th — nothing extra needed.

	/* ── Form validation helper ─────────────────────────────── */
	document.querySelectorAll('form[data-validate]').forEach(function (form) {
		form.addEventListener('submit', function (e) {
			var valid  = true;
			var fields = form.querySelectorAll('[required]');
			fields.forEach(function (field) {
				field.style.borderColor = '';
				if (!field.value.trim()) {
					field.style.borderColor = '#ef4444';
					field.focus();
					valid = false;
				}
			});
			if (!valid) {
				e.preventDefault();
			}
		});
	});

	/* ── Keyboard shortcut: Ctrl+K → focus search ──────────── */
	document.addEventListener('keydown', function (e) {
		if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
			e.preventDefault();
			var inp = document.getElementById('perfexSearchInput');
			if (inp) {
				inp.focus();
				inp.select();
			}
		}
	});

	/* ── Print-friendly invoice/proposal ────────────────────── */
	document.querySelectorAll('[data-print]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			window.print();
		});
	});

	/* ── Toggle password visibility ─────────────────────────── */
	document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
		var targetId = btn.getAttribute('data-toggle-password');
		var input    = document.getElementById(targetId);
		if (!input) return;
		btn.addEventListener('click', function () {
			var isText = input.type === 'text';
			input.type = isText ? 'password' : 'text';
			var icon   = btn.querySelector('i');
			if (icon) {
				icon.className = isText ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash';
			}
		});
	});

	/* ── Row select helper ───────────────────────────────────── */
	var selectAllCbs = document.querySelectorAll('input[id^="selectAll"]');
	selectAllCbs.forEach(function (cb) {
		cb.addEventListener('change', function () {
			var table = cb.closest('table');
			if (table) {
				table.querySelectorAll('.row-check').forEach(function (rc) {
					rc.checked = cb.checked;
				});
			}
		});
	});

	/* ── Responsive table: add data-label attrs for mobile ──── */
	document.querySelectorAll('.table-container').forEach(function (container) {
		var table   = container.querySelector('table');
		if (!table) return;
		var headers = Array.from(table.querySelectorAll('thead th')).map(function (th) { return th.textContent.trim(); });
		table.querySelectorAll('tbody tr').forEach(function (row) {
			row.querySelectorAll('td').forEach(function (td, i) {
				if (headers[i]) td.setAttribute('data-label', headers[i]);
			});
		});
	});

	/* ── Date relative time ──────────────────────────────────── */
	function relativeTime(dateStr) {
		var diff  = Math.floor((Date.now() - new Date(dateStr)) / 1000);
		if (diff < 60)   return diff + 's ago';
		if (diff < 3600) return Math.floor(diff / 60)   + 'm ago';
		if (diff < 86400)return Math.floor(diff / 3600) + 'h ago';
		return Math.floor(diff / 86400) + 'd ago';
	}

	document.querySelectorAll('[data-relative-time]').forEach(function (el) {
		var date = el.getAttribute('data-relative-time') || el.textContent;
		try { el.textContent = relativeTime(date); el.title = date; } catch(e) {}
	});

})(window.jQuery || { fn: {} });
