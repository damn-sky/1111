import {useEffect, useRef, useState} from 'react';
import {AnimatePresence, motion} from 'motion/react';
import {getContent, iconUrl} from './content';

const groups = [
  {label: 'Услуги', items: ['Душевые перегородки', 'Двери', 'Межкомнатные перегородки', 'Ограждения', 'Козырьки', 'Конструкции', 'Безрамное остекление', 'Пергола']},
  {label: 'О компании', items: ['Стеклосистемы', 'Шоу-рум', 'Благотворительность', 'Блог', 'Автопарк', 'Реквизиты', 'Отзывы']},
  {label: 'Покупателям', items: ['Наши работы', 'Доставка и оплата', 'Гарантия и сервисное обслуживание', 'Рекомендации по уходу']},
  {label: 'Партнерам', items: ['Дизайнерам', 'Строительным компаниям']},
];

function Icon({name}: {name: string}) {
  return <img className="icon" src={iconUrl(name)} alt="" />;
}

export function Header() {
  const content = getContent();
  const [theme, setTheme] = useState('dark');
  const [mode, setMode] = useState<'navigation' | 'search' | 'contacts' | 'mobile'>('navigation');
  const [open, setOpen] = useState<number | null>(null);
  const [query, setQuery] = useState('');
  const [searched, setSearched] = useState(false);
  const headerRef = useRef<HTMLElement>(null);
  const searchRef = useRef<HTMLInputElement>(null);
  const returnFocus = useRef<HTMLButtonElement | null>(null);
  const close = () => {
    setMode('navigation');
    setOpen(null);
    setSearched(false);
    returnFocus.current?.focus();
  };

  useEffect(() => {
    let frame = 0;
    const update = () => {
      frame = 0;
      const line = (headerRef.current?.getBoundingClientRect().bottom ?? 0) + 1;
      document.querySelectorAll<HTMLElement>('[data-header-theme]').forEach((section) => {
        const bounds = section.getBoundingClientRect();
        if (bounds.top <= line && bounds.bottom > line) setTheme(section.dataset.headerTheme ?? 'dark');
      });
    };
    const schedule = () => {
      if (!frame) frame = requestAnimationFrame(update);
    };
    const main = document.querySelector('main');
    const observer = main ? new MutationObserver(schedule) : null;
    if (main) observer!.observe(main, {childList: true, subtree: true});
    window.addEventListener('scroll', schedule, {passive: true});
    window.addEventListener('resize', schedule);
    update();
    return () => {
      window.removeEventListener('scroll', schedule);
      window.removeEventListener('resize', schedule);
      observer?.disconnect();
      cancelAnimationFrame(frame);
    };
  }, []);

  useEffect(() => {
    if (mode === 'search') searchRef.current?.focus();
  }, [mode]);

  useEffect(() => {
    const key = (event: KeyboardEvent) => {
      if (event.key === 'Escape') close();
    };
    const outside = (event: PointerEvent) => {
      if (!headerRef.current?.contains(event.target as Node)) {
        setOpen(null);
        setMode('navigation');
      }
    };
    document.addEventListener('keydown', key);
    document.addEventListener('pointerdown', outside);
    return () => {
      document.removeEventListener('keydown', key);
      document.removeEventListener('pointerdown', outside);
    };
  }, []);

  const toggle = (next: typeof mode, button: HTMLButtonElement) => {
    returnFocus.current = button;
    setOpen(null);
    setSearched(false);
    setMode(mode === next ? 'navigation' : next);
  };

  const logo = `${content.assetBase}${theme === 'light' ? 'logo-light' : 'logo'}.svg`;

  return (
    <>
      <header ref={headerRef} className={`header header--${theme} header-mode--${mode}`}>
        <a href="/" aria-label="Стеклосистемы — главная">
          <img className="logo" src={logo} alt="Стеклосистемы" />
        </a>
        {(mode === 'navigation' || mode === 'mobile') && (
          <nav aria-label="Основная навигация">
            {groups.map((group, index) => (
              <div
                className="nav-group"
                key={group.label}
                onMouseEnter={() => {
                  if (matchMedia('(hover:hover)').matches) setOpen(index);
                }}
                onMouseLeave={() => {
                  if (matchMedia('(hover:hover)').matches) setOpen(null);
                }}
                onBlur={(event) => {
                  if (!event.currentTarget.contains(event.relatedTarget)) setOpen(null);
                }}
              >
                <button
                  type="button"
                  className={open === index ? 'nav-open' : ''}
                  aria-expanded={open === index}
                  aria-controls={`submenu-${index}`}
                  onClick={() => setOpen(matchMedia('(hover:hover)').matches ? index : open === index ? null : index)}
                >
                  {group.label}
                  <span className={open === index ? 'arrow-expanded' : ''}>
                    <Icon name="down" />
                  </span>
                </button>
                <AnimatePresence>
                  {open === index && (
                    <motion.div
                      id={`submenu-${index}`}
                      className="submenu"
                      initial={{opacity: 0}}
                      animate={{opacity: 1}}
                      exit={{opacity: 0}}
                      transition={{duration: 0.3, ease: 'linear'}}
                    >
                      {group.items.map((item) => (
                        <button type="button" key={item} aria-disabled="true" title="Страница ещё не создана">
                          {item}
                        </button>
                      ))}
                    </motion.div>
                  )}
                </AnimatePresence>
              </div>
            ))}
            <button type="button" onClick={(event) => toggle('contacts', event.currentTarget)}>
              Контакты
            </button>
          </nav>
        )}
        {mode === 'search' && (
          <div className="header-search">
            <form
              role="search"
              onSubmit={(event) => {
                event.preventDefault();
                if (query.trim()) setSearched(true);
              }}
            >
              <input
                ref={searchRef}
                type="search"
                aria-label="Поиск по сайту"
                placeholder="Поиск"
                value={query}
                onChange={(event) => {
                  setQuery(event.target.value);
                  setSearched(false);
                }}
              />
              <button type="submit" aria-label="Найти">
                <Icon name="search" />
              </button>
            </form>
            {searched && <p role="status" className="search-status">Поиск будет доступен после подключения каталога.</p>}
          </div>
        )}
        {mode === 'contacts' && (
          <div className="header-contacts">
            {content.phones.map((phone) => (
              <div className="contact-number" key={phone.href}>
                <a href={`tel:${phone.href}`}>{phone.label}</a>
                <div className="contact-socials">
                  {(['max', 'telegram'] as const).map((name) => {
                    const url = content.social[name];
                    if (url) {
                      return (
                        <a key={name} href={url} target="_blank" rel="noreferrer" aria-label={name === 'max' ? 'MAX' : 'Telegram'}>
                          <Icon name={name} />
                        </a>
                      );
                    }
                    return (
                      <button type="button" key={name} aria-label={name === 'max' ? 'MAX' : 'Telegram'} aria-disabled="true" title="Ссылка не указана">
                        <Icon name={name} />
                      </button>
                    );
                  })}
                </div>
              </div>
            ))}
            <address dangerouslySetInnerHTML={{__html: content.addressHtml}} />
          </div>
        )}
        <div className="tools">
          <button type="button" className={mode === 'search' ? 'tool-active' : ''} aria-label={mode === 'search' ? 'Закрыть поиск' : 'Поиск'} aria-expanded={mode === 'search'} onClick={(event) => toggle('search', event.currentTarget)}>
            <Icon name={mode === 'search' ? 'close' : 'search'} />
          </button>
          <button type="button" className={mode === 'contacts' ? 'tool-active' : ''} aria-label={mode === 'contacts' ? 'Закрыть контакты' : 'Контакты'} aria-expanded={mode === 'contacts'} onClick={(event) => toggle('contacts', event.currentTarget)}>
            <Icon name={mode === 'contacts' ? 'close' : 'phone'} />
          </button>
          <button type="button" className={`mobile-menu ${mode === 'mobile' ? 'tool-active' : ''}`} aria-label={mode === 'mobile' ? 'Закрыть меню' : 'Меню'} aria-expanded={mode === 'mobile'} onClick={(event) => toggle('mobile', event.currentTarget)}>
            <Icon name={mode === 'mobile' ? 'close' : 'menu'} />
          </button>
        </div>
      </header>
      <div className="header-space" aria-hidden="true" />
    </>
  );
}
