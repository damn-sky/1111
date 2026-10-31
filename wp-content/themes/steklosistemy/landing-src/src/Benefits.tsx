import {useRef} from 'react';
import {motion, useReducedMotion, useScroll, useTransform, type MotionValue} from 'motion/react';
import {getContent, type BenefitItem} from './content';
import './benefits.css';

function BenefitText({benefit}: {benefit: BenefitItem}) {
  return (
    <>
      <h3>{benefit.title}</h3>
      <p>{benefit.body}</p>
    </>
  );
}

function ScrollBenefit({benefit, progress, reduced}: {benefit: BenefitItem; progress: MotionValue<number>; reduced: boolean}) {
  const transform = useTransform(progress, (value) => (value >= 1 ? 'none' : `translateY(calc(${(benefit.from - benefit.y) * (1 - value)} * var(--benefit-u)))`));
  return (
    <motion.article
      className={`benefit-card ${benefit.light ? 'benefit-card--light' : ''}`}
      style={{
        left: `calc(${benefit.x} * var(--benefit-u))`,
        top: `calc(${benefit.y} * var(--benefit-u))`,
        height: `calc(${benefit.height} * var(--benefit-u))`,
        transform: reduced ? 'none' : transform,
      }}
    >
      <BenefitText benefit={benefit} />
    </motion.article>
  );
}

export function Benefits() {
  const {benefits} = getContent();
  const sectionRef = useRef<HTMLElement>(null);
  const reduced = !!useReducedMotion();
  const {scrollYProgress} = useScroll({target: sectionRef, offset: ['start start', 'end end']});

  return (
    <section ref={sectionRef} id="benefits" data-header-theme="dark" className={`benefits ${reduced ? 'benefits--reduced' : ''}`} aria-labelledby="benefits-title">
      <div className="benefits-scene">
        <div className="benefits-stage">
          <img className="benefits-background" src={benefits.background} alt="" loading="lazy" />
          <div className="benefits-heading">
            <h2 id="benefits-title">{benefits.title}</h2>
          </div>
          <div className="benefits-desktop-cards">
            {benefits.items.map((benefit) => (
              <ScrollBenefit key={benefit.id} benefit={benefit} progress={scrollYProgress} reduced={reduced} />
            ))}
          </div>
        </div>
      </div>
      <div className="benefits-mobile">
        <img className="benefits-mobile-stairs" src={benefits.mobileStairs} alt="" />
        <img className="benefits-mobile-stars" src={benefits.mobileStars} alt="" />
        <h2>{benefits.title}</h2>
        <div className="benefits-mobile-cards">
          {benefits.items
            .filter((benefit) => benefit.id !== 'warranty')
            .map((benefit) => (
              <article className={`benefit-card ${benefit.light ? 'benefit-card--light' : ''}`} key={benefit.id}>
                <BenefitText benefit={benefit} />
              </article>
            ))}
        </div>
      </div>
    </section>
  );
}
