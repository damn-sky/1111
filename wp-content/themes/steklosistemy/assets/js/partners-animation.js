/**
 * Анимация блока #partners при доскролле.
 * События: partners:enter (на секции), partners:tag (на каждой плашке).
 *
 * Пример:
 * document.getElementById('partners').addEventListener('partners:enter', () => {
 *   console.log('Блок партнёров в зоне видимости');
 * });
 */
(function () {
  var STAGGER_MS = 420;

  function initPartnersAnimation(section) {
    if (!section || section.dataset.partnersAnimReady === '1') return;
    section.dataset.partnersAnimReady = '1';

    var tags = Array.prototype.slice
      .call(section.querySelectorAll('[data-reveal-order]'))
      .sort(function (a, b) {
        return Number(a.getAttribute('data-reveal-order')) - Number(b.getAttribute('data-reveal-order'));
      });

    var reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function revealAll() {
      section.classList.add('partners--active');
      tags.forEach(function (node) {
        node.classList.add('is-visible');
      });
      section.dispatchEvent(new CustomEvent('partners:enter', {bubbles: true, detail: {section: section}}));
    }

    if (reduced || !('IntersectionObserver' in window)) {
      revealAll();
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (!entry.isIntersecting) return;

          section.classList.add('partners--active');
          section.dispatchEvent(new CustomEvent('partners:enter', {bubbles: true, detail: {section: section}}));

          tags.forEach(function (node, index) {
            window.setTimeout(function () {
              node.classList.add('is-visible');
              var order = Number(node.getAttribute('data-reveal-order'));
              node.dispatchEvent(
                new CustomEvent('partners:tag', {bubbles: true, detail: {order: order, node: node}}),
              );
            }, index * STAGGER_MS);
          });

          observer.disconnect();
        });
      },
      {threshold: 0.22, rootMargin: '0px 0px -8% 0px'},
    );

    observer.observe(section);
  }

  function boot() {
    document.querySelectorAll('#partners, [data-partners-animate]').forEach(initPartnersAnimation);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }

  window.initPartnersAnimation = initPartnersAnimation;
})();
