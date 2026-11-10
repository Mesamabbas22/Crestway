/* Crestway theme scripts — no dependencies. */
(function () {
	'use strict';

	var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
	var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };
	var currency = (window.DH && window.DH.currency) || '$';
	var money = function (n) { return currency + Math.round(n).toLocaleString(); };

	/* ---------- Header on scroll + back to top ---------- */
	var header = $('#site-header');
	var toTop = $('#back-to-top');
	var onScroll = function () {
		var y = window.scrollY;
		if (header) header.classList.toggle('is-scrolled', y > 20);
		if (toTop) toTop.classList.toggle('is-visible', y > 600);
	};
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();
	if (toTop) toTop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });

	/* ---------- Mobile nav ---------- */
	var toggle = $('#nav-toggle');
	var nav = $('#main-nav');
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var open = nav.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (open && header) header.classList.add('is-scrolled');
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && nav.classList.contains('is-open')) {
				nav.classList.remove('is-open');
				toggle.setAttribute('aria-expanded', 'false');
				toggle.focus();
			}
		});
	}

	/* ---------- Fleet filters ---------- */
	var filterToggle = $('#filter-toggle');
	var filterCard = $('#filter-card');
	if (filterToggle && filterCard) {
		filterToggle.addEventListener('click', function () {
			var open = filterCard.classList.toggle('is-open');
			filterToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}
	var range = $('#max_price');
	var out = $('#price-out');
	if (range && out) {
		range.addEventListener('input', function () { out.textContent = currency + range.value; });
	}
	if (filterCard && range) {
		// Slider at its maximum means "no price limit".
		filterCard.addEventListener('submit', function () {
			if (range.value === range.max) range.disabled = true;
			$$('input[type=radio]:checked', filterCard).forEach(function (r) { if (r.value === '') r.disabled = true; });
		});
	}
	var sort = $('[data-sort]');
	if (sort) {
		sort.addEventListener('change', function () {
			var url = new URL(window.location.href);
			url.searchParams.delete('paged');
			url.pathname = url.pathname.replace(/\/page\/\d+\/?$/, '/');
			if (sort.value) url.searchParams.set('sort', sort.value); else url.searchParams.delete('sort');
			window.location.href = url.toString();
		});
	}

	/* ---------- Dates ---------- */
	var ymd = function (d) {
		var m = String(d.getMonth() + 1).padStart(2, '0');
		var day = String(d.getDate()).padStart(2, '0');
		return d.getFullYear() + '-' + m + '-' + day;
	};
	var addDays = function (str, n) {
		var p = str.split('-');
		var d = new Date(+p[0], +p[1] - 1, +p[2]);
		d.setDate(d.getDate() + n);
		return ymd(d);
	};
	var today = ymd(new Date());

	$$('form').forEach(function (form) {
		var pickup = $('[data-date="pickup"]', form);
		var ret = $('[data-date="return"]', form);
		if (!pickup || !ret) return;

		pickup.min = today;
		var sync = function () {
			if (pickup.value) {
				ret.min = addDays(pickup.value, 1);
				if (!ret.value || ret.value <= pickup.value) ret.value = addDays(pickup.value, 1);
			}
			form.dispatchEvent(new Event('dh:dates'));
		};
		pickup.addEventListener('change', sync);
		ret.addEventListener('change', function () { form.dispatchEvent(new Event('dh:dates')); });

		// Booking form gets sensible defaults so the price summary is meaningful.
		if (form.hasAttribute('data-booking') && !pickup.value) {
			pickup.value = addDays(today, 1);
			ret.value = addDays(today, 4);
		}
		if (pickup.value) sync();
	});

	/* ---------- Booking price calculator ---------- */
	var booking = $('[data-booking]');
	if (booking) {
		var rate = parseFloat(booking.getAttribute('data-rate')) || 0;
		var calc = function () {
			var p = $('[data-date="pickup"]', booking).value;
			var r = $('[data-date="return"]', booking).value;
			var days = 1;
			if (p && r) {
				var diff = Math.round((new Date(r) - new Date(p)) / 86400000);
				days = Math.max(1, diff);
			}
			var extrasDay = $$('[data-extra]:checked', booking).reduce(function (sum, el) {
				return sum + (parseFloat(el.getAttribute('data-extra')) || 0);
			}, 0);
			$('[data-days]', booking).textContent = days;
			$('[data-base]', booking).textContent = money(rate * days);
			$('[data-extras]', booking).textContent = money(extrasDay * days);
			$('[data-total]', booking).textContent = money((rate + extrasDay) * days);
		};
		booking.addEventListener('change', calc);
		booking.addEventListener('dh:dates', calc);
		calc();

		var btn = $('button[type=submit]', booking);
		booking.addEventListener('submit', function () {
			if (btn) { btn.disabled = true; btn.style.opacity = '.7'; }
		});
	}

	/* ---------- Reveal on scroll + counters ---------- */
	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var countUp = function (el) {
		var target = parseInt(el.getAttribute('data-count'), 10) || 0;
		if (reduce) { el.textContent = target; return; }
		var start = null;
		var step = function (ts) {
			if (!start) start = ts;
			var t = Math.min(1, (ts - start) / 1400);
			el.textContent = Math.round(target * (1 - Math.pow(1 - t, 3)));
			if (t < 1) requestAnimationFrame(step);
		};
		requestAnimationFrame(step);
	};

	if ('IntersectionObserver' in window) {
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				var el = entry.target;
				el.classList.add('is-in');
				$$('[data-count]', el).forEach(countUp);
				io.unobserve(el);
			});
		}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

		$$('.reveal').forEach(function (el, i) {
			el.style.transitionDelay = (i % 3) * 80 + 'ms';
			io.observe(el);
		});
	} else {
		$$('.reveal').forEach(function (el) { el.classList.add('is-in'); });
		$$('[data-count]').forEach(function (el) { el.textContent = el.getAttribute('data-count'); });
	}
})();
