import {useRef, useState} from 'react';
import {motion, useReducedMotion, useScroll, useTransform} from 'motion/react';
import {getContent} from './content';
import './portfolio.css';

function WorkCard({title, date, dateTime, image, index}: {title: string; date: string; dateTime: string; image: string; index: number}) {
  return (
    <article className="work-card" tabIndex={0} aria-label={`${title}, работа ${index}`}>
      <img className="work-photo" src={image} alt={title} loading="lazy" />
      <div className="work-description">
        <h3>{title}</h3>
        <div className="work-bottom">
          <time dateTime={dateTime}>{date}</time>
          <span className="work-arrow" aria-hidden="true">
            <img src={`${getContent().assetBase}portfolio-imgIconsArrowDownRight.svg`} alt="" />
          </span>
        </div>
      </div>
    </article>
  );
}

export function Portfolio() {
  const {portfolio, assetBase} = getContent();
  const section = useRef<HTMLElement>(null);
  const reduced = !!useReducedMotion();
  const [mobileProgress, setMobileProgress] = useState(0);
  const {scrollYProgress} = useScroll({target: section, offset: ['start start', 'end end']});
  const transform = useTransform(scrollYProgress, (p) => `translateY(calc(${-1020 * p} * var(--portfolio-u)))`);

  return (
    <section id="portfolio" ref={section} data-header-theme="light" className={`portfolio ${reduced ? 'portfolio--reduced' : ''}`} aria-labelledby="portfolio-title">
      <div className="portfolio-scene">
        <div className="portfolio-stage">
          <div className="portfolio-heading">
            <h2 id="portfolio-title">{portfolio.title}</h2>
            <p>
              {portfolio.introBefore}
              {portfolio.linkUrl ? (
                <a className="portfolio-link" href={portfolio.linkUrl}>
                  {portfolio.linkLabel}
                </a>
              ) : (
                <span className="portfolio-link" aria-disabled="true" title="Ссылка не указана">
                  {portfolio.linkLabel}
                </span>
              )}
            </p>
          </div>
          <div className="portfolio-body">
            <nav className="portfolio-categories" aria-label="Категории работ">
              {portfolio.categories.map((category, i) => (
                <button key={category} className={i === 0 ? 'is-active' : ''} type="button" aria-disabled="true" title={i ? 'Работы этой категории не предоставлены в макете' : undefined}>
                  <span>{category}</span>
                </button>
              ))}
            </nav>
            <div className="portfolio-mobile-category">
              {portfolio.categories[0]}
              <span aria-hidden="true">⌄</span>
            </div>
            <div
              className="portfolio-window"
              tabIndex={0}
              aria-label="Выполненные работы. Прокручивайте страницу для просмотра."
              onScroll={(e) => {
                const el = e.currentTarget;
                setMobileProgress(el.scrollLeft / Math.max(1, el.scrollWidth - el.clientWidth));
              }}
            >
              <motion.div className="portfolio-grid" style={{transform: reduced ? 'none' : transform}}>
                {portfolio.works.map((work, i) => (
                  <WorkCard key={work.image + i} index={i + 1} {...work} />
                ))}
              </motion.div>
            </div>
          </div>
          <div className="portfolio-mobile-footer">
            <div className="portfolio-progress">
              <span style={{left: `${mobileProgress * 87.5}%`}} />
            </div>
            <button type="button" aria-disabled="true" title="Страница всех работ пока не предоставлена">
              Смотреть все <img src={`${assetBase}portfolio-imgIconsArrowDownRight.svg`} alt="" />
            </button>
          </div>
        </div>
      </div>
    </section>
  );
}
