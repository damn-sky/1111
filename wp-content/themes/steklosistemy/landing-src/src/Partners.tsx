import type {CSSProperties} from 'react';
import {getContent} from './content';
import {buildPartnerItems} from './partners-config';
import {usePartnersScrollAnimation} from './usePartnersScrollAnimation';
import './partners.css';

export function Partners() {
  const {partners, assetBase} = getContent();
  const titleLines = partners.titleLines?.length ? partners.titleLines : ['С кем', 'мы работаем'];
  const items = buildPartnerItems(assetBase);
  const cta = partners.ctaUrl?.trim();
  const {sectionRef, active} = usePartnersScrollAnimation();

  return (
    <section
      ref={sectionRef}
      className={`partners${active ? ' partners--active' : ''}`}
      id="partners"
      data-header-theme="dark"
      data-partners-animate
      aria-labelledby="partners-title"
    >
      <div className="partners-bg" aria-hidden="true">
        <img className="partners-circle partners-circle--1" src={partners.circle1} alt="" loading="lazy" />
        <img className="partners-circle partners-circle--2" src={partners.circle2} alt="" loading="lazy" />
        <img className="partners-circle partners-circle--3" src={partners.circle3} alt="" loading="lazy" />
        <img className="partners-watermark" src={partners.watermark} alt="" loading="lazy" />
        <span className="partners-plus partners-plus--a" />
        <span className="partners-plus partners-plus--b" />
        <span className="partners-plus partners-plus--c" />
        <span className="partners-plus partners-plus--d" />
      </div>

      <div className="partners-inner">
        <h2 id="partners-title">
          {titleLines.map((line, i) => (
            <span key={i}>
              {i > 0 && <br />}
              {line}
            </span>
          ))}
        </h2>

        <ul className="partners-tags">
          {items.map((item) => (
            <li
              key={item.id}
              data-partner-tag
              data-reveal-order={item.revealOrder}
              style={
                {
                  '--x': `${item.x}%`,
                  '--y': `${item.y}%`,
                } as CSSProperties
              }
            >
              <span className="partners-tag">
                <img className="partners-tag-icon" src={item.icon} alt="" loading="lazy" />
                <span className="partners-tag-text">
                  {item.lines.map((line, i) => (
                    <span key={i}>
                      {i > 0 && <br />}
                      {line}
                    </span>
                  ))}
                </span>
              </span>
            </li>
          ))}
        </ul>

        {cta ? (
          <a className="partners-cta" href={cta}>
            <span>{partners.ctaText}</span>
            <img src={`${assetBase}arrow.svg`} alt="" />
          </a>
        ) : (
          <button type="button" className="partners-cta" aria-disabled="true" title="Укажите ссылку кнопки в админке">
            <span>{partners.ctaText}</span>
            <img src={`${assetBase}arrow.svg`} alt="" />
          </button>
        )}
      </div>
    </section>
  );
}
