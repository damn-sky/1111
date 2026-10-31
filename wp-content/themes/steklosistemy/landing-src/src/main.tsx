import React from 'react';
import {Header} from './Header';
import {Catalog} from './Catalog';
import {Benefits} from './Benefits';
import {Portfolio} from './Portfolio';
import {Estimate} from './Estimate';
import {WorkSteps} from './WorkSteps';
import {Partners} from './Partners';
import {createRoot} from 'react-dom/client';
import {motion, MotionConfig, useReducedMotion} from 'motion/react';
import {getContent, iconUrl} from './content';
import './styles.css';

function Icon({name}: {name: string}) {
  return <img className="icon" src={iconUrl(name)} alt="" />;
}

function ActionButton({secondary = false}: {secondary?: boolean}) {
  const {hero} = getContent();
  if (secondary) {
    const href = hero.ctaSecondaryUrl?.trim() ?? '';
    if (href) {
      const external = /^https?:\/\//i.test(href);
      return (
        <a className="action secondary" href={href} target={external ? '_blank' : undefined} rel={external ? 'noreferrer' : undefined}>
          <Icon name="max" />
          {hero.ctaSecondary}
        </a>
      );
    }
    return (
      <motion.button type="button" className="action secondary" aria-disabled="true" title="Укажите ссылку для кнопки 2 в админке">
        <Icon name="max" />
        {hero.ctaSecondary}
      </motion.button>
    );
  }
  return (
    <motion.a href={hero.ctaPrimaryUrl || '#estimate'} className="action">
      {hero.ctaPrimary}
      <Icon name="arrow" />
    </motion.a>
  );
}

function HeroBackground() {
  const {hero} = getContent();
  const reducedMotion = useReducedMotion();
  return (
    <div className="hero-media" aria-hidden="true">
      <img className="hero-art" src={hero.image} alt="" />
      {!reducedMotion && hero.video && (
        <video className="hero-video" autoPlay muted loop playsInline preload="auto">
          <source src={hero.video} type="video/mp4" />
        </video>
      )}
    </div>
  );
}

function App() {
  const {hero} = getContent();
  return (
    <MotionConfig reducedMotion="user">
      <main>
        <section className="hero" data-header-theme="dark" aria-labelledby="hero-title">
          <HeroBackground />
          <div className="top-shade" />
          <picture className="stars">
            <source media="(max-width: 600px)" srcSet={hero.mobileStars} />
            <img src={hero.stars} alt="" />
          </picture>
          <Header />
          <img className="mobile-glass" src={hero.mobileGlass} alt="Стеклянный знак Стеклосистемы" />
          <div className="hero-content">
            <motion.div className="intro" initial={{opacity: 0}} animate={{opacity: 1}} transition={{duration: 0.45}}>
              <h1 id="hero-title">
                {hero.titleLines.map((line, i) => (
                  <span key={i}>{line}</span>
                ))}
              </h1>
              <p>{hero.text}</p>
            </motion.div>
            <div className="actions">
              <ActionButton />
              <ActionButton secondary />
            </div>
          </div>
        </section>
        <Catalog />
        <Benefits />
        <Portfolio />
        <Estimate />
        <WorkSteps />
        <Partners />
      </main>
    </MotionConfig>
  );
}

createRoot(document.getElementById('root')!).render(
  <React.StrictMode>
    <App />
  </React.StrictMode>,
);
