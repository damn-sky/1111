import {useRef, useState} from 'react';
import {useReducedMotion} from 'motion/react';
import {getContent, iconUrl, type CatalogCard} from './content';
import './catalog.css';

function ServiceCard({card}: {card: CatalogCard}) {
  const videoRef = useRef<HTMLVideoElement>(null);
  const reducedMotion = useReducedMotion();
  const [videoReady, setVideoReady] = useState(false);
  const play = () => {
    if (!reducedMotion && card.video && matchMedia('(min-width:601px) and (hover:hover)').matches) {
      videoRef.current?.play().catch(() => setVideoReady(false));
    }
  };
  const pause = () => {
    videoRef.current?.pause();
    setVideoReady(false);
  };

  return (
    <article
      className={`service-card service-card--${card.id} ${card.large ? 'service-card--large' : ''} ${card.mobile ? 'service-card--mobile' : ''} ${videoReady ? 'service-card--playing' : ''}`}
      tabIndex={0}
      aria-label={card.title.replace('\n', ' ')}
      onMouseEnter={play}
      onMouseLeave={pause}
      onFocus={play}
      onBlur={pause}
    >
      <div className="service-art" aria-hidden="true">
        <picture>
          {card.mobileImage && <source media="(max-width:600px)" srcSet={card.mobileImage} />}
          <img src={card.image} alt="" loading="lazy" />
        </picture>
        <div className="service-art-blend" />
      </div>
      {card.video && !reducedMotion && (
        <video ref={videoRef} className="service-video" muted loop playsInline preload="none" aria-hidden="true" onPlaying={() => setVideoReady(true)} onError={() => setVideoReady(false)}>
          <source src={card.video} type="video/mp4" />
        </video>
      )}
      <div className="service-bottom-blend" />
      <h3>{card.title}</h3>
      <div className="service-tags">
        {card.tags.map((tag) => (
          <span key={tag}>{tag}</span>
        ))}
      </div>
      <span className="service-arrow" aria-hidden="true">
        <img src={`${getContent().assetBase}catalog-cardArrow.svg`} alt="" />
      </span>
    </article>
  );
}

export function Catalog() {
  const {catalog, assetBase} = getContent();
  const corner = (even: boolean) => `${assetBase}catalog-${even ? 'statCornerRight' : 'statCorner'}.svg`;

  return (
    <section className="catalog" id="catalog" data-header-theme="light" aria-labelledby="catalog-title">
      <div className="catalog-heading">
        <h2 id="catalog-title">{catalog.title}</h2>
        <p>
          {catalog.introLines.map((line, i) => (
            <span key={i}>
              {i > 0 && <br />}
              {line}
            </span>
          ))}
        </p>
      </div>
      <div className="catalog-grid">
        {catalog.cards.map((card) => (
          <ServiceCard key={card.id} card={card} />
        ))}
      </div>
      <div className="catalog-stats">
        <span className="stat-corners" aria-hidden="true">
          {['tl', 'tr', 'bl', 'br'].map((name, index) => (
            <img key={name} className={`stat-corner stat-corner--${name}`} src={corner(index % 2 === 1)} alt="" />
          ))}
        </span>
        {catalog.stats.map((stat) => (
          <div className="catalog-stat" key={stat.number + stat.a}>
            <strong>{stat.number}</strong>
            <span>
              {stat.a}
              <br />
              {stat.b}
            </span>
          </div>
        ))}
      </div>
    </section>
  );
}
