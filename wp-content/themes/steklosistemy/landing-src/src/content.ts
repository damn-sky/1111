export type CatalogCard = {
  id: string;
  title: string;
  tags: string[];
  large?: boolean;
  mobile?: boolean;
  image: string;
  mobileImage?: string;
  video?: string;
};

export type BenefitItem = {
  id: string;
  title: string;
  body: string;
  x: number;
  y: number;
  from: number;
  height: number;
  light?: boolean;
};

export type WorkItem = {
  title: string;
  date: string;
  dateTime: string;
  image: string;
};

export type StepItem = {
  title: string;
  paragraphs: string[];
  image: string;
};

export type StekloContent = {
  assetBase: string;
  phones: Array<{label: string; href: string}>;
  addressHtml: string;
  social: {max: string; telegram: string};
  hero: {
    titleLines: string[];
    text: string;
    video: string;
    image: string;
    mobileGlass: string;
    stars: string;
    mobileStars: string;
    ctaPrimary: string;
    ctaPrimaryUrl: string;
    ctaSecondary: string;
    ctaSecondaryUrl: string;
  };
  catalog: {
    title: string;
    introLines: string[];
    cards: CatalogCard[];
    stats: Array<{number: string; a: string; b: string}>;
  };
  benefits: {
    title: string;
    background: string;
    mobileStairs: string;
    mobileStars: string;
    items: BenefitItem[];
  };
  portfolio: {
    title: string;
    introBefore: string;
    linkLabel: string;
    linkUrl: string;
    categories: string[];
    works: WorkItem[];
  };
  estimate: {
    titleLines: string[];
    glass: string;
    hover: string;
    submitLabel: string;
  };
  workSteps: {
    titleLines: string[];
    lead: string;
    freeTitle: string;
    free: string[];
    steps: StepItem[];
  };
  partners: {
    titleLines: string[];
    tags: string[];
    ctaText: string;
    ctaUrl: string;
    circle1: string;
    circle2: string;
    circle3: string;
    watermark: string;
  };
};

declare global {
  interface Window {
    STEKLO?: Partial<StekloContent> & {assetBase?: string};
  }
}

const asset = (base: string, name: string) => `${base}${name}`;

function defaults(base: string): StekloContent {
  return {
    assetBase: base,
    phones: [
      {label: '+7 (978) 111 25 50', href: '+79781112550'},
      {label: '+7 (978) 111 75 25', href: '+79781117525'},
    ],
    addressHtml: 'г. Симферополь,<br/>ул. Линейная, 2В',
    social: {max: '', telegram: ''},
    hero: {
      titleLines: ['Конструкции ', 'из безопасного ', 'закаленного стекла ', 'в Крыму'],
      text: 'Изготавливаем и монтируем душевые, ограждения, перегородки, двери и другие конструкции любой сложности',
      video: asset(base, 'hero.mp4'),
      image: asset(base, 'hero.png'),
      mobileGlass: asset(base, 'glass.png'),
      stars: asset(base, 'stars.svg'),
      mobileStars: asset(base, 'mobileStars.svg'),
      ctaPrimary: 'Рассчитать стоимость',
      ctaPrimaryUrl: '#estimate',
      ctaSecondary: 'Написать в МАХ',
      ctaSecondaryUrl: '',
    },
    catalog: {
      title: 'Каталог',
      introLines: [
        'Современные стеклянные конструкции на заказ.',
        'Индивидуальные решения из закаленного стекла',
        'для квартир, частных домов и коммерческих помещений',
      ],
      cards: [
        {id: 'shower', title: 'Душевые\nперегородки', tags: ['Распашные', 'Сдвижные', 'Глухие', 'Раздвижные', 'Гармошка', 'Двери'], large: true, mobile: true, image: asset(base, 'catalog-shower.png'), video: asset(base, 'catalog-shower.mp4')},
        {id: 'railing', title: 'Ограждения', tags: ['Лестницы', 'Балконы', 'Крыши', 'Уличные'], mobile: true, image: asset(base, 'catalog-railing.png')},
        {id: 'construction', title: 'Конструкции', tags: ['Столы', 'Полы', 'Скинали', 'Шкафы'], mobile: true, image: asset(base, 'catalog-construction.png'), mobileImage: asset(base, 'catalog-mobileTable.png')},
        {id: 'canopy', title: 'Козырьки', tags: ['На винтах', 'На шине'], mobile: true, image: asset(base, 'catalog-canopy.png')},
        {id: 'partition', title: 'Межкомнатные\nперегородки', tags: ['Глухие', 'Распашные', 'Раздвижные', 'Лофт'], image: asset(base, 'catalog-partition.png')},
        {id: 'door', title: 'Двери', tags: ['Распашные', 'Раздвижные', 'Входные группы', 'Лофт'], image: asset(base, 'catalog-door.png'), video: asset(base, 'catalog-door.mp4')},
        {id: 'pergola', title: 'Пергола', tags: ['Поворотно-сдвижная', 'Сдвижная'], image: asset(base, 'catalog-pergola.png')},
        {id: 'frameless', title: 'Безрамное\nостекление', tags: ['Слайд', 'Книжка', 'To-do-cristal', 'Гильотина', 'Глухое остекление'], large: true, image: asset(base, 'catalog-frameless.png'), video: asset(base, 'catalog-frameless.mp4')},
      ],
      stats: [
        {number: '1', a: 'год', b: 'сервиса'},
        {number: '2', a: 'года', b: 'гарантии'},
        {number: '15', a: 'лет', b: 'опыта'},
        {number: '3589', a: 'довольных', b: 'клиентов'},
      ],
    },
    benefits: {
      title: 'Почему мы?',
      background: asset(base, 'benefits-background.png'),
      mobileStairs: asset(base, 'benefits-mobile-stairs.svg'),
      mobileStars: asset(base, 'benefits-mobile-stars.svg'),
      items: [
        {id: 'glass', title: 'Безопасное стекло', body: 'Закалённое стекло в 4–5 раз прочнее обычного. Толщина конструкции  от 6 мм. Для гарантированной безопасности  - триплекс.', x: 909, y: 74, from: 814, height: 145, light: true},
        {id: 'project', title: 'Индивидуальный проект', body: 'Учитываем геометрию помещения, уклон, расположение сантехники, мебели и дизайн проект помещения. Предоставляем консультацию с примерами реализации. ', x: 129, y: 128, from: 868, height: 163},
        {id: 'team', title: 'Своя монтажная бригада', body: 'Специалисты с опытом работы со стеклом от 5 лет. Аккуратный монтаж и уборка. Фото и видео отчет о проделанной работе.', x: 813, y: 609, from: 1839, height: 145, light: true},
        {id: 'all', title: 'Всё в одном месте', body: 'Консультация, замер, проектирование, производство, доставка и монтаж одной командой. ', x: 197, y: 405, from: 1285, height: 127},
        {id: 'hardware', title: 'Проверенная фурнитура', body: 'Комплектующие из нержавеющей стали, алюминия и латунного сплава рассчитанные на ежедневную нагрузку. Мы работаем только с фурнитурой, которая показала свою работа на объектах.', x: 806, y: 333, from: 1213, height: 181},
        {id: 'warranty', title: 'Гарантия и сервис', body: 'Гарантия на фурнитуру 2 года и 1 год сервиса по договору.', x: 280, y: 657, from: 1887, height: 109, light: true},
      ],
    },
    portfolio: {
      title: 'Наши работы',
      introBefore: 'Посмотреть больше наших актуальных работ можно ',
      linkLabel: 'по ссылке здесь',
      linkUrl: '',
      categories: ['Душевые перегородки', 'Двери межкомнатные', 'Стеклянные перегородки', 'Ограждения', 'Козырьки', 'Конструкции', 'Безрамное остекление', 'Пергола'],
      works: Array.from({length: 8}, (_, i) => ({
        title: 'Угловая перегородка из прозрачного стекла',
        date: '05.10.2026',
        dateTime: '2026-10-05',
        image: asset(base, `portfolio-img${i + 1}.png`),
      })),
    },
    estimate: {
      titleLines: ['Покажите Ваше', 'пространство —', 'мы предложим', 'решение'],
      glass: asset(base, 'estimate-glass.png'),
      hover: asset(base, 'estimate-hover-imgImg.png'),
      submitLabel: 'Заказать рассчёт стоимости',
    },
    workSteps: {
      titleLines: ['Как мы', 'работаем'],
      lead: 'От первой консультации до готовой конструкции — всего четыре шага. Берём на себя всё: от предварительного расчёта и точных замеров до производства и профессионального монтажа. Вам остаётся только оценить результат.',
      freeTitle: 'Бесплатные услуги:',
      free: ['Выезд инженера\nна объект', 'Консультация\nспециалиста', 'Замеры\nи просчёты', '1 год\nсервиса'],
      steps: [
        {
          title: 'Предварительный расчет стоимости',
          paragraphs: [
            'Уточняем задачу, смотрим фото и называем предварительный диапазон стоимости.',
            '<strong>На стоимость в первую очередь влияют выбор цвета стекла и фурнитуры.</strong> В некоторых случаях - технические условия установки конструкции на Вашем объекте, которые обсуждаются индивидуально на контрольном замере.',
          ],
          image: asset(base, 'steps-1.png'),
        },
        {
          title: 'Контрольный замер и договор',
          paragraphs: [
            'Инженер приезжает на объект, проверяет геометрию и помогает выбрать решение. <strong>Замер и консультация 0 руб.</strong>',
            'После утверждения стоимости мы заключаем договор, оплата проходит в 2 этапа: <strong>80% на старте работ и 20% после принятия работ по монтажу.</strong>',
            'В договоре мы указываем длительность гарантии и срок гарантийного обслуживания конструкции.',
          ],
          image: asset(base, 'steps-2.png'),
        },
        {
          title: 'Производство',
          paragraphs: [
            'Инженер создает 3D-схему, где Вы сможете увидеть как будет выглядеть именно Ваша конструкция, ее размеры, цвет стекла и фурнитуры.',
            'После утверждения схемы с Вашей стороны, мы отдаем ее завод для изготовления стекла по Вашим размерам. Стекло изготавливается окло <strong>10 рабочих дней.</strong>',
            'Фурнитура подбирается индивидуально под Ваш запрос: стоимость, дизайнерские предпочтения и условия использования.',
          ],
          image: asset(base, 'steps-3.png'),
        },
        {
          title: 'Монтаж и принятие работ',
          paragraphs: [
            'После того как стекло и фурнитура поступили на наш склад, мы согласовываем с Вами удобные дату и время монтажа.',
            'Наши специалисты устанавливают конструкцию и предоставляю фото и видео о проделанной работе на объекте.',
            'После принятия работ по монтажу, <strong>Вы проводите последнюю часть оплаты в 20%.</strong>',
          ],
          image: asset(base, 'steps-4.png'),
        },
      ],
    },
    partners: {
      titleLines: ['С кем', 'мы работаем'],
      tags: [
        'Рестораны и кафе',
        'Строительные бригады',
        'Дизайнеры, архитекторы',
        'Частные / физические лица',
        'Государственные учреждения и организации',
        'Застройщики и строительные компании',
        'Торговые центры и магазины',
        'Гостиницы, отели, базы отдыха',
      ],
      ctaText: 'Стать партнером',
      ctaUrl: '',
      circle1: asset(base, 'partners-circle-1.png'),
      circle2: asset(base, 'partners-circle-2.png'),
      circle3: asset(base, 'partners-circle-3.png'),
      watermark: asset(base, 'partners-watermark.png'),
    },
  };
}

function deepMerge<T>(base: T, patch?: Partial<T>): T {
  if (!patch) return base;
  const out: any = Array.isArray(base) ? [...(base as any)] : {...(base as any)};
  for (const [key, value] of Object.entries(patch as object)) {
    if (value === undefined || value === null || value === '') continue;
    if (Array.isArray(value)) {
      if (value.length) out[key] = value;
      continue;
    }
    if (typeof value === 'object' && typeof out[key] === 'object' && !Array.isArray(out[key])) {
      out[key] = deepMerge(out[key], value as any);
      continue;
    }
    out[key] = value;
  }
  return out;
}

export function getContent(): StekloContent {
  const fromWp = typeof window !== 'undefined' ? window.STEKLO : undefined;
  const base = fromWp?.assetBase || `${import.meta.env.BASE_URL}assets/`;
  return deepMerge(defaults(base), fromWp as Partial<StekloContent>);
}

export function iconUrl(name: string): string {
  const base = getContent().assetBase;
  return `${base}${name}.svg`;
}
