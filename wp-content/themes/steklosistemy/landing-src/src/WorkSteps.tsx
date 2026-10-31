import {useRef, useState, type PointerEvent, type KeyboardEvent} from 'react';
import {getContent} from './content';
import './work-steps.css';

export function WorkSteps() {
  const {workSteps, assetBase} = getContent();
  const list = useRef<HTMLDivElement>(null);
  const [progress, setProgress] = useState(0);
  const dragOffset = useRef<number | null>(null);

  function moveScrollbar(event: PointerEvent<HTMLDivElement>) {
    if (dragOffset.current === null || !list.current) return;
    const bounds = event.currentTarget.getBoundingClientRect();
    const thumbHeight = (bounds.height * 100) / 640;
    const next = Math.max(0, Math.min(1, (event.clientY - bounds.top - dragOffset.current) / (bounds.height - thumbHeight)));
    list.current.scrollTop = next * (list.current.scrollHeight - list.current.clientHeight);
  }

  function keyboard(event: KeyboardEvent<HTMLDivElement>) {
    const el = list.current;
    if (!el) return;
    const amount = event.key === 'ArrowDown' ? 40 : event.key === 'ArrowUp' ? -40 : event.key === 'PageDown' ? el.clientHeight : event.key === 'PageUp' ? -el.clientHeight : 0;
    if (amount) {
      event.preventDefault();
      el.scrollTop += amount;
    } else if (event.key === 'Home' || event.key === 'End') {
      event.preventDefault();
      el.scrollTop = event.key === 'Home' ? 0 : el.scrollHeight;
    }
  }

  return (
    <section className="work-steps" id="work-steps" data-header-theme="light" aria-labelledby="work-steps-title">
      <div className="work-steps-intro">
        <div className="work-steps-heading">
          <h2 id="work-steps-title">
            {workSteps.titleLines.map((line, i) => (
              <span key={i}>
                {i > 0 && <br />}
                {line}
              </span>
            ))}
          </h2>
          <p>{workSteps.lead}</p>
        </div>
        <div className="work-steps-free">
          <h3>{workSteps.freeTitle}</h3>
          <div>
            {workSteps.free.map((service, i) => (
              <div className="work-steps-free-item" key={i}>
                {i > 0 && <img src={`${assetBase}steps-divider.svg`} alt="" />}
                <p>
                  {service.split('\n').map((line, li) => (
                    <span key={li}>
                      {li > 0 && <br />}
                      {line}
                    </span>
                  ))}
                </p>
              </div>
            ))}
          </div>
        </div>
      </div>
      <div className="work-steps-gallery">
        <div
          className="work-steps-list"
          ref={list}
          id="work-steps-list"
          tabIndex={0}
          role="region"
          aria-label="Четыре этапа работы. Прокручиваемый список"
          onScroll={(event) => {
            const el = event.currentTarget;
            setProgress(el.scrollTop / Math.max(1, el.scrollHeight - el.clientHeight));
          }}
        >
          {workSteps.steps.map((step, i) => (
            <article className={`work-step work-step--${i + 1}`} key={step.title}>
              <div className="work-step-copy">
                <span className="work-step-number">0{i + 1}</span>
                <h3>{step.title}</h3>
                <div className="work-step-description">
                  {step.paragraphs.map((p, pi) => (
                    <p key={pi} dangerouslySetInnerHTML={{__html: p}} />
                  ))}
                </div>
              </div>
              <div className="work-step-art">
                <img src={step.image} alt="" loading="lazy" />
              </div>
            </article>
          ))}
        </div>
        <div
          className="work-steps-scrollbar"
          role="scrollbar"
          aria-label="Прокрутка этапов работы"
          aria-orientation="vertical"
          aria-controls="work-steps-list"
          aria-valuemin={0}
          aria-valuemax={100}
          aria-valuenow={Math.round(progress * 100)}
          tabIndex={0}
          onKeyDown={keyboard}
          onPointerDown={(event) => {
            const bounds = event.currentTarget.getBoundingClientRect();
            const thumbHeight = (bounds.height * 100) / 640;
            const thumbTop = progress * (bounds.height - thumbHeight);
            const y = event.clientY - bounds.top;
            dragOffset.current = y >= thumbTop && y <= thumbTop + thumbHeight ? y - thumbTop : thumbHeight / 2;
            event.currentTarget.setPointerCapture(event.pointerId);
            moveScrollbar(event);
          }}
          onPointerMove={moveScrollbar}
          onPointerUp={() => {
            dragOffset.current = null;
          }}
          onPointerCancel={() => {
            dragOffset.current = null;
          }}
        >
          <span style={{top: `${progress * 84.375}%`}} />
        </div>
      </div>
    </section>
  );
}
