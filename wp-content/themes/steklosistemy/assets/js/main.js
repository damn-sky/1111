(function () {
	var header = document.getElementById('top');
	var drawer = document.getElementById('drawer');

	function setState(state) {
		if (!header) return;
		header.setAttribute('data-state', state);
		if (state === 'search') {
			var input = header.querySelector('#header-search');
			if (input) input.focus();
		}
	}

	document.querySelectorAll('[data-header-search]').forEach(function (btn) {
		btn.addEventListener('click', function () { setState('search'); });
	});
	document.querySelectorAll('[data-header-contact]').forEach(function (btn) {
		btn.addEventListener('click', function () { setState('contact'); });
	});
	document.querySelectorAll('[data-header-close]').forEach(function (btn) {
		btn.addEventListener('click', function () { setState('menu'); });
	});
	document.querySelectorAll('[data-open-menu]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			if (!drawer) return;
			drawer.hidden = !drawer.hidden;
		});
	});
	document.querySelectorAll('[data-close-menu]').forEach(function (btn) {
		btn.addEventListener('click', function () { if (drawer) drawer.hidden = true; });
	});

	var hero = document.querySelector('.hero');
	if (header && hero) {
		var onScroll = function () {
			var light = window.scrollY > hero.offsetHeight - 40;
			header.classList.toggle('is-light', light);
			header.classList.toggle('is-dark', !light);
		};
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	document.querySelectorAll('[data-filter]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var cat = btn.getAttribute('data-filter');
			document.querySelectorAll('[data-filter]').forEach(function (item) {
				item.classList.toggle('is-active', item === btn);
			});
			document.querySelectorAll('.work-card').forEach(function (card) {
				var own = card.getAttribute('data-cat');
				card.classList.toggle('is-hidden', own && own !== cat);
			});
		});
	});

	if ('IntersectionObserver' in window) {
		var reveal = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-in');
					reveal.unobserve(entry.target);
				}
			});
		}, { threshold: 0.16, rootMargin: '0px 0px -8% 0px' });
		document.querySelectorAll('[data-reveal]').forEach(function (el) {
			reveal.observe(el);
		});
	} else {
		document.querySelectorAll('[data-reveal]').forEach(function (el) {
			el.classList.add('is-in');
		});
	}
})();
