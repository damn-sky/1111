export type PartnerItem = {
  id: string;
  label: string;
  lines: string[];
  icon: string;
  x: number;
  y: number;
  revealOrder: number;
};

/** Раскладка и порядок появления — как в Figma (Default → Variant9). */
export const PARTNER_ITEMS: Omit<PartnerItem, 'icon'>[] = [
  {
    id: 'private',
    label: 'Частные / физические лица',
    lines: ['Частные /', 'физические лица'],
    x: 3,
    y: 34,
    revealOrder: 1,
  },
  {
    id: 'retail',
    label: 'Торговые центры и магазины',
    lines: ['Торговые центры', 'и магазины'],
    x: 52,
    y: 62,
    revealOrder: 2,
  },
  {
    id: 'designers',
    label: 'Дизайнеры, архитекторы',
    lines: ['Дизайнеры,', 'архитекторы'],
    x: 58,
    y: 8,
    revealOrder: 3,
  },
  {
    id: 'developers',
    label: 'Застройщики и строительные компании',
    lines: ['Застройщики и', 'строительные компании'],
    x: 1,
    y: 58,
    revealOrder: 4,
  },
  {
    id: 'government',
    label: 'Государственные учреждения и организации',
    lines: ['Государственные', 'учреждения и организации'],
    x: 54,
    y: 38,
    revealOrder: 5,
  },
  {
    id: 'crews',
    label: 'Строительные бригады',
    lines: ['Строительные', 'бригады'],
    x: 36,
    y: 17,
    revealOrder: 6,
  },
  {
    id: 'hotels',
    label: 'Гостиницы, отели, базы отдыха',
    lines: ['Гостиницы, отели,', 'базы отдыха'],
    x: 22,
    y: 72,
    revealOrder: 7,
  },
  {
    id: 'restaurants',
    label: 'Рестораны и кафе',
    lines: ['Рестораны', 'и кафе'],
    x: 8,
    y: 9,
    revealOrder: 8,
  },
];

const ICONS: Record<string, string> = {
  private: 'partners-tag-private.svg',
  retail: 'partners-tag-retail.svg',
  designers: 'partners-tag-designers.svg',
  developers: 'partners-tag-developers.svg',
  government: 'partners-tag-government.svg',
  crews: 'partners-tag-crews.svg',
  hotels: 'partners-tag-hotels.svg',
  restaurants: 'partners-tag-restaurants.svg',
};

export function buildPartnerItems(assetBase: string): PartnerItem[] {
  return PARTNER_ITEMS.map((item) => ({
    ...item,
    icon: `${assetBase}${ICONS[item.id]}`,
  }));
}
