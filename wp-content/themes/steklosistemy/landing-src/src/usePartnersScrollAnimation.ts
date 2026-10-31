import {useEffect, useRef, useState, type RefObject} from 'react';

const STAGGER_MS = 420;

export type PartnersScrollState = {
  sectionRef: RefObject<HTMLElement | null>;
  active: boolean;
  visibleOrders: ReadonlySet<number>;
};

/**
 * Запускает анимацию блока «С кем мы работаем» при доскролле.
 * На секции срабатывает CustomEvent `partners:enter`, на каждой плашке — `partners:tag`.
 */
export function usePartnersScrollAnimation(): PartnersScrollState {
  const sectionRef = useRef<HTMLElement | null>(null);
  const [active, setActive] = useState(false);
  const [visibleOrders, setVisibleOrders] = useState<ReadonlySet<number>>(() => new Set());

  useEffect(() => {
    const section = sectionRef.current;
    if (!section) return;

    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const tagNodes = [...section.querySelectorAll<HTMLElement>('[data-reveal-order]')].sort(
      (a, b) => Number(a.dataset.revealOrder) - Number(b.dataset.revealOrder),
    );

    const revealAll = () => {
      setActive(true);
      section.classList.add('partners--active');
      const orders = tagNodes.map((node) => Number(node.dataset.revealOrder)).filter(Boolean);
      setVisibleOrders(new Set(orders));
      tagNodes.forEach((node) => node.classList.add('is-visible'));
      section.dispatchEvent(new CustomEvent('partners:enter', {bubbles: true, detail: {section}}));
    };

    if (reduced) {
      revealAll();
      return;
    }

    let timers: number[] = [];
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;

          setActive(true);
          section.classList.add('partners--active');
          section.dispatchEvent(new CustomEvent('partners:enter', {bubbles: true, detail: {section}}));

          tagNodes.forEach((node, index) => {
            const order = Number(node.dataset.revealOrder);
            const timer = window.setTimeout(() => {
              node.classList.add('is-visible');
              setVisibleOrders((prev) => new Set(prev).add(order));
              node.dispatchEvent(
                new CustomEvent('partners:tag', {bubbles: true, detail: {order, node}}),
              );
            }, index * STAGGER_MS);
            timers.push(timer);
          });

          observer.disconnect();
        });
      },
      {threshold: 0.22, rootMargin: '0px 0px -8% 0px'},
    );

    observer.observe(section);

    return () => {
      observer.disconnect();
      timers.forEach((id) => window.clearTimeout(id));
      timers = [];
    };
  }, []);

  return {sectionRef, active, visibleOrders};
}
