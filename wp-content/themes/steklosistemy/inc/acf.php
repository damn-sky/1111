<?php
/**
 * ACF: поля для ручного редактирования контактов и главной.
 * Работает, когда активен Advanced Custom Fields PRO.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function steklo_acf_ready() {
	return function_exists( 'acf_add_local_field_group' ) && function_exists( 'get_field' );
}

function steklo_acf_options_pages() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => 'Настройки сайта',
			'menu_title' => 'Настройки сайта',
			'menu_slug'  => 'steklo-site-settings',
			'capability' => 'edit_theme_options',
			'redirect'   => false,
			'icon_url'   => 'dashicons-admin-generic',
			'position'   => 59,
		)
	);

	// Отдельный экран для текстов/медиа главной — работает даже без «статической главной» в Чтении.
	if ( function_exists( 'acf_add_options_sub_page' ) ) {
		acf_add_options_sub_page(
			array(
				'page_title'  => 'Главная страница',
				'menu_title'  => 'Главная',
				'menu_slug'   => 'steklo-home-settings',
				'parent_slug' => 'steklo-site-settings',
				'capability'  => 'edit_theme_options',
			)
		);
	}
}
add_action( 'acf/init', 'steklo_acf_options_pages' );

function steklo_acf_register_fields() {
	if ( ! steklo_acf_ready() ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'                   => 'group_steklo_contacts',
			'title'                 => 'Контакты и соцсети',
			'fields'                => array(
				array(
					'key'   => 'field_steklo_tab_contacts',
					'label' => 'Контакты',
					'type'  => 'tab',
				),
				array(
					'key'   => 'field_steklo_phone',
					'label' => 'Телефон 1',
					'name'  => 'phone',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_steklo_phone_second',
					'label' => 'Телефон 2',
					'name'  => 'phone_second',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_steklo_address',
					'label' => 'Адрес на карточке',
					'name'  => 'address',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_steklo_hours',
					'label' => 'Часы работы',
					'name'  => 'hours',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_steklo_hours_weekend',
					'label' => 'Выходные',
					'name'  => 'hours_weekend',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_steklo_legal_address',
					'label' => 'Юридический адрес',
					'name'  => 'legal_address',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_steklo_copyright',
					'label' => 'Копирайт',
					'name'  => 'copyright',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_steklo_requisites',
					'label' => 'Реквизиты',
					'name'  => 'requisites',
					'type'  => 'text',
				),
				array(
					'key'          => 'field_steklo_map_embed',
					'label'        => 'Яндекс.Карта (ссылка iframe)',
					'name'         => 'map_embed',
					'type'         => 'url',
					'instructions' => 'Ссылка из «Поделиться → HTML-код» виджета Яндекс.Карт.',
				),
				array(
					'key'   => 'field_steklo_max_url',
					'label' => 'Ссылка «Написать в Max»',
					'name'  => 'max_url',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_steklo_tab_social',
					'label' => 'Соцсети',
					'type'  => 'tab',
				),
				array(
					'key'   => 'field_steklo_telegram',
					'label' => 'Telegram',
					'name'  => 'social_telegram',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_steklo_vk',
					'label' => 'ВКонтакте',
					'name'  => 'social_vk',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_steklo_instagram',
					'label' => 'Instagram',
					'name'  => 'social_instagram',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_steklo_youtube',
					'label' => 'YouTube',
					'name'  => 'social_youtube',
					'type'  => 'url',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'steklo-site-settings',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
		)
	);

	acf_add_local_field_group(
		array(
			'key'                   => 'group_steklo_home',
			'title'                 => 'Главная страница',
			'fields'                => array(
				array(
					'key'   => 'field_steklo_home_tab_hero',
					'label' => 'Первый экран',
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_steklo_hero_intro',
					'label'        => '',
					'name'         => '',
					'type'         => 'message',
					'message'      => 'Тексты и кнопки первого экрана. После правок нажмите «Сохранить» вверху страницы. Поля также доступны при редактировании страницы «Главная» в разделе «Страницы».',
				),
				array(
					'key'          => 'field_steklo_hero_title',
					'label'        => 'Заголовок H1',
					'name'         => 'hero_title',
					'type'         => 'textarea',
					'rows'         => 4,
					'instructions' => 'Каждая строка — отдельная строка заголовка на сайте (как в макете).',
				),
				array(
					'key'          => 'field_steklo_hero_text',
					'label'        => 'Подзаголовок',
					'name'         => 'hero_text',
					'type'         => 'textarea',
					'rows'         => 3,
					'instructions' => 'Текст под заголовком.',
				),
				array(
					'key'          => 'field_steklo_hero_btn_1_text',
					'label'        => 'Кнопка 1 — текст',
					'name'         => 'hero_btn_1_text',
					'type'         => 'text',
					'instructions' => 'Левая кнопка (обычно «Рассчитать стоимость»).',
				),
				array(
					'key'          => 'field_steklo_hero_btn_1_url',
					'label'        => 'Кнопка 1 — ссылка',
					'name'         => 'hero_btn_1_url',
					'type'         => 'text',
					'instructions' => 'Например #estimate (форма на этой странице) или полный URL.',
				),
				array(
					'key'          => 'field_steklo_hero_btn_2_text',
					'label'        => 'Кнопка 2 — текст',
					'name'         => 'hero_btn_2_text',
					'type'         => 'text',
					'instructions' => 'Правая кнопка (обычно «Написать в МАХ»).',
				),
				array(
					'key'          => 'field_steklo_hero_btn_2_url',
					'label'        => 'Кнопка 2 — ссылка',
					'name'         => 'hero_btn_2_url',
					'type'         => 'url',
					'instructions' => 'Куда ведёт вторая кнопка (мессенджер, tel:, страница).',
				),
				array(
					'key'     => 'field_steklo_hero_media_msg',
					'label'   => '',
					'name'    => '',
					'type'    => 'message',
					'message' => 'Фон справа на первом экране: загрузите видео MP4 и/или картинку. Если видео не задано или отключено в браузере — показывается картинка.',
				),
				array(
					'key'           => 'field_steklo_hero_video',
					'label'         => 'Видео (MP4)',
					'name'          => 'hero_video',
					'type'          => 'file',
					'return_format' => 'url',
					'mime_types'    => 'mp4',
				),
				array(
					'key'           => 'field_steklo_hero_image',
					'label'         => 'Картинка / постер',
					'name'          => 'hero_image',
					'type'          => 'image',
					'return_format' => 'url',
					'preview_size'  => 'medium',
					'instructions'  => 'Статичный кадр и запасной вариант без видео.',
				),
				array(
					'key'           => 'field_steklo_hero_mobile_glass',
					'label'         => 'Мобильное фото стекла',
					'name'          => 'hero_mobile_glass',
					'type'          => 'image',
					'return_format' => 'url',
					'preview_size'  => 'medium',
				),
				array(
					'key'   => 'field_steklo_home_tab_catalog',
					'label' => 'Каталог',
					'type'  => 'tab',
				),
				array(
					'key'   => 'field_steklo_catalog_intro',
					'label' => 'Текст справа от «Каталог»',
					'name'  => 'catalog_intro',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'          => 'field_steklo_catalog',
					'label'        => 'Карточки каталога',
					'name'         => 'catalog',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Добавить карточку',
					'sub_fields'   => array(
						array(
							'key'   => 'field_steklo_catalog_title',
							'label' => 'Название',
							'name'  => 'title',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_steklo_catalog_slug',
							'label' => 'Slug страницы',
							'name'  => 'slug',
							'type'  => 'text',
						),
						array(
							'key'          => 'field_steklo_catalog_tags',
							'label'        => 'Теги',
							'name'         => 'tags',
							'type'         => 'text',
							'instructions' => 'Через запятую: Распашные, Сдвижные',
						),
						array(
							'key'           => 'field_steklo_catalog_class',
							'label'         => 'Класс сетки',
							'name'          => 'class',
							'type'          => 'select',
							'choices'       => array(
								'is-shower'    => 'Душевые (высокая)',
								'is-rail'      => 'Ограждения (широкая)',
								'is-room'      => 'Межкомнатные',
								'is-doors'     => 'Двери',
								'is-constr'    => 'Конструкции',
								'is-visor'     => 'Козырьки',
								'is-pergola'   => 'Пергола',
								'is-frameless' => 'Безрамное',
							),
							'default_value' => 'is-shower',
							'return_format' => 'value',
						),
						array(
							'key'           => 'field_steklo_catalog_image',
							'label'         => 'Картинка',
							'name'          => 'image',
							'type'          => 'image',
							'return_format' => 'url',
							'preview_size'  => 'medium',
						),
						array(
							'key'           => 'field_steklo_catalog_mobile_image',
							'label'         => 'Картинка (мобильная, опционально)',
							'name'          => 'mobile_image',
							'type'          => 'image',
							'return_format' => 'url',
							'preview_size'  => 'medium',
						),
						array(
							'key'           => 'field_steklo_catalog_video',
							'label'         => 'Видео при наведении (mp4)',
							'name'          => 'video',
							'type'          => 'file',
							'return_format' => 'url',
							'mime_types'    => 'mp4',
							'instructions'  => 'Для душевых / дверей / безрамного. Пусто — дефолт темы или без видео.',
						),
					),
				),
				array(
					'key'   => 'field_steklo_home_tab_stats',
					'label' => 'Цифры',
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_steklo_stats',
					'label'        => 'Цифры',
					'name'         => 'stats',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Добавить',
					'sub_fields'   => array(
						array(
							'key'   => 'field_steklo_stats_value',
							'label' => 'Число',
							'name'  => 'value',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_steklo_stats_label',
							'label' => 'Подпись',
							'name'  => 'label',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'   => 'field_steklo_home_tab_why',
					'label' => 'Почему мы',
					'type'  => 'tab',
				),
				array(
					'key'   => 'field_steklo_benefits_title',
					'label' => 'Заголовок блока',
					'name'  => 'benefits_title',
					'type'  => 'text',
				),
				array(
					'key'           => 'field_steklo_benefits_background',
					'label'         => 'Фон / лестница (десктоп)',
					'name'          => 'benefits_background',
					'type'          => 'image',
					'return_format' => 'url',
					'preview_size'  => 'medium',
					'instructions'  => 'Большая картинка со лестницей и подсветкой.',
				),
				array(
					'key'          => 'field_steklo_why',
					'label'        => 'Карточки преимуществ',
					'name'         => 'why',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Добавить карточку',
					'sub_fields'   => array(
						array(
							'key'   => 'field_steklo_why_title',
							'label' => 'Заголовок',
							'name'  => 'title',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_steklo_why_text',
							'label' => 'Текст',
							'name'  => 'text',
							'type'  => 'textarea',
							'rows'  => 3,
						),
					),
				),
				array(
					'key'   => 'field_steklo_home_tab_works',
					'label' => 'Работы и процесс',
					'type'  => 'tab',
				),
				array(
					'key'   => 'field_steklo_works_intro',
					'label' => 'Текст у блока «Наши работы»',
					'name'  => 'works_intro',
					'type'  => 'text',
				),
				array(
					'key'          => 'field_steklo_works_link',
					'label'        => 'Ссылка «по ссылке здесь»',
					'name'         => 'works_link',
					'type'         => 'url',
				),
				array(
					'key'          => 'field_steklo_home_works',
					'label'        => 'Карточки работ на главной',
					'name'         => 'home_works',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Добавить работу',
					'instructions' => 'Фото и подписи в блоке «Наши работы». Если пусто — берутся записи типа «Работы» или дефолты темы.',
					'sub_fields'   => array(
						array(
							'key'   => 'field_steklo_home_work_title',
							'label' => 'Название',
							'name'  => 'title',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_steklo_home_work_date',
							'label' => 'Дата',
							'name'  => 'date',
							'type'  => 'text',
						),
						array(
							'key'           => 'field_steklo_home_work_image',
							'label'         => 'Фото',
							'name'          => 'image',
							'type'          => 'image',
							'return_format' => 'url',
							'preview_size'  => 'medium',
						),
					),
				),
				array(
					'key'   => 'field_steklo_home_tab_estimate',
					'label' => 'Форма расчёта',
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_steklo_estimate_title',
					'label'        => 'Заголовок формы',
					'name'         => 'estimate_title',
					'type'         => 'textarea',
					'rows'         => 4,
					'instructions' => 'Каждая строка — отдельная строка заголовка.',
				),
				array(
					'key'           => 'field_steklo_estimate_glass',
					'label'         => 'Фото слева (стекло / телефон)',
					'name'          => 'estimate_glass',
					'type'          => 'image',
					'return_format' => 'url',
					'preview_size'  => 'medium',
				),
				array(
					'key'           => 'field_steklo_estimate_hover',
					'label'         => 'Фото при наведении',
					'name'          => 'estimate_hover',
					'type'          => 'image',
					'return_format' => 'url',
					'preview_size'  => 'medium',
				),
				array(
					'key'   => 'field_steklo_home_tab_process',
					'label' => 'Как мы работаем',
					'type'  => 'tab',
				),
				array(
					'key'   => 'field_steklo_process_lead',
					'label' => 'Текст «Как мы работаем»',
					'name'  => 'process_lead',
					'type'  => 'textarea',
					'rows'  => 4,
				),
				array(
					'key'          => 'field_steklo_gifts',
					'label'        => 'Бесплатные услуги',
					'name'         => 'gifts',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Добавить',
					'sub_fields'   => array(
						array(
							'key'   => 'field_steklo_gift_item',
							'label' => 'Услуга',
							'name'  => 'item',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'          => 'field_steklo_steps',
					'label'        => 'Шаги работы',
					'name'         => 'steps',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Добавить шаг',
					'sub_fields'   => array(
						array(
							'key'   => 'field_steklo_step_title',
							'label' => 'Заголовок',
							'name'  => 'title',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_steklo_step_text',
							'label' => 'Текст',
							'name'  => 'text',
							'type'  => 'textarea',
							'rows'  => 4,
						),
						array(
							'key'           => 'field_steklo_step_image',
							'label'         => 'Фото',
							'name'          => 'image',
							'type'          => 'image',
							'return_format' => 'url',
							'preview_size'  => 'medium',
						),
					),
				),
				array(
					'key'   => 'field_steklo_home_tab_partners',
					'label' => 'С кем мы работаем',
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_steklo_partners_title',
					'label'        => 'Заголовок блока',
					'name'         => 'partners_title',
					'type'         => 'textarea',
					'rows'         => 2,
					'instructions' => 'Две строки, например: «С кем» и «мы работаем».',
				),
				array(
					'key'          => 'field_steklo_partners',
					'label'        => 'Категории (плашки)',
					'name'         => 'partners',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => 'Добавить',
					'sub_fields'   => array(
						array(
							'key'   => 'field_steklo_partner_label',
							'label' => 'Название',
							'name'  => 'label',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'   => 'field_steklo_partners_cta_text',
					'label' => 'Кнопка — текст',
					'name'  => 'partners_cta_text',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_steklo_partners_cta_url',
					'label' => 'Кнопка — ссылка',
					'name'  => 'partners_cta_url',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_steklo_home_tab_faq',
					'label' => 'FAQ / Отзывы',
					'type'  => 'tab',
				),
				array(
					'key'   => 'field_steklo_faq_lead',
					'label' => 'Текст у «Вопрос — ответ»',
					'name'  => 'faq_lead',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'          => 'field_steklo_faq',
					'label'        => 'Вопросы и ответы',
					'name'         => 'faq',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => 'Добавить вопрос',
					'sub_fields'   => array(
						array(
							'key'   => 'field_steklo_faq_q',
							'label' => 'Вопрос',
							'name'  => 'q',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_steklo_faq_a',
							'label' => 'Ответ',
							'name'  => 'a',
							'type'  => 'textarea',
							'rows'  => 3,
						),
					),
				),
				array(
					'key'   => 'field_steklo_reviews_lead',
					'label' => 'Текст у отзывов',
					'name'  => 'reviews_lead',
					'type'  => 'textarea',
					'rows'  => 3,
				),
				array(
					'key'   => 'field_steklo_reviews_score',
					'label' => 'Оценка',
					'name'  => 'reviews_score',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_steklo_reviews_meta',
					'label' => 'Подпись к оценке',
					'name'  => 'reviews_meta',
					'type'  => 'text',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'steklo-home-settings',
					),
				),
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'active'                => true,
			'hide_on_screen'        => array( 'the_content', 'excerpt', 'discussion', 'comments', 'slug', 'author', 'format', 'page_attributes', 'featured_image', 'categories', 'tags', 'send-trackbacks' ),
		)
	);
}
add_action( 'acf/init', 'steklo_acf_register_fields' );

/**
 * Берёт значение ACF, иначе дефолт из темы.
 *
 * @param string $key Field name.
 * @param mixed  $default Default.
 * @param mixed  $post_id Post ID or 'option'.
 * @return mixed
 */
function steklo_field( $key, $default = '', $post_id = 'option' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $key, $post_id );
	if ( $value === null || $value === false || $value === '' ) {
		return $default;
	}
	return $value;
}

function steklo_front_id() {
	$page_id = (int) get_option( 'page_on_front' );
	return $page_id > 0 ? $page_id : false;
}

/**
 * ID источника полей главной: сначала статическая главная, иначе options (Настройки → Главная).
 *
 * @return int|string
 */
function steklo_home_store() {
	$front = steklo_front_id();
	return $front ? $front : 'option';
}

/**
 * Поле главной: страница «Главная» (если назначена) или options.
 *
 * @param string $key Field name.
 * @param mixed  $default Default.
 * @return mixed
 */
function steklo_home_field( $key, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}

	$front = steklo_front_id();
	if ( $front ) {
		$value = get_field( $key, $front );
		if ( $value !== null && $value !== false && $value !== '' ) {
			return $value;
		}
	}

	$value = get_field( $key, 'option' );
	if ( $value === null || $value === false || $value === '' ) {
		return $default;
	}
	return $value;
}

function steklo_content_from_acf( $content ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $content;
	}

	$content['phone']         = steklo_field( 'phone', $content['phone'] );
	$content['phone_second']  = steklo_field( 'phone_second', $content['phone_second'] );
	$content['address']       = steklo_field( 'address', $content['address'] );
	$content['hours']         = steklo_field( 'hours', $content['hours'] );
	$content['hours_weekend'] = steklo_field( 'hours_weekend', $content['hours_weekend'] );
	$content['legal_address'] = steklo_field( 'legal_address', $content['legal_address'] );
	$content['copyright']     = steklo_field( 'copyright', $content['copyright'] );
	$content['requisites']    = steklo_field( 'requisites', $content['requisites'] );
	$content['map_embed']     = steklo_field( 'map_embed', $content['map_embed'] );
	$content['max_url']       = steklo_field( 'max_url', $content['max_url'] );

	$content['social']['telegram']  = steklo_field( 'social_telegram', $content['social']['telegram'] );
	$content['social']['vk']        = steklo_field( 'social_vk', $content['social']['vk'] );
	$content['social']['instagram'] = steklo_field( 'social_instagram', $content['social']['instagram'] );
	$content['social']['youtube']   = steklo_field( 'social_youtube', $content['social']['youtube'] );

	$hero_title = steklo_home_field( 'hero_title', $content['hero']['title'] );
	$hero_text  = steklo_home_field( 'hero_text', $content['hero']['text'] );
	$content['hero']['title'] = is_string( $hero_title ) ? $hero_title : $content['hero']['title'];
	$content['hero']['text']  = is_string( $hero_text ) ? $hero_text : $content['hero']['text'];

	$content['catalog_intro'] = steklo_home_field( 'catalog_intro', $content['catalog_intro'] );
	$content['works_intro']   = steklo_home_field( 'works_intro', $content['works_intro'] );
	$content['process_lead']  = steklo_home_field( 'process_lead', $content['process_lead'] );
	$content['faq_lead']      = steklo_home_field( 'faq_lead', $content['faq_lead'] );
	$content['reviews_lead']  = steklo_home_field( 'reviews_lead', $content['reviews_lead'] );
	$content['reviews_score'] = steklo_home_field( 'reviews_score', $content['reviews_score'] );
	$content['reviews_meta']  = steklo_home_field( 'reviews_meta', $content['reviews_meta'] );

	$catalog = steklo_home_field( 'catalog', array() );
	if ( is_array( $catalog ) && $catalog ) {
		$mapped = array();
		foreach ( $catalog as $row ) {
			$tags = array();
			if ( ! empty( $row['tags'] ) ) {
				$tags = array_values(
					array_filter(
						array_map( 'trim', explode( ',', (string) $row['tags'] ) )
					)
				);
			}
			$image = '';
			if ( ! empty( $row['image'] ) ) {
				$image = is_array( $row['image'] ) ? ( $row['image']['url'] ?? '' ) : $row['image'];
			}
			$mobile_image = '';
			if ( ! empty( $row['mobile_image'] ) ) {
				$mobile_image = is_array( $row['mobile_image'] ) ? ( $row['mobile_image']['url'] ?? '' ) : $row['mobile_image'];
			}
			$video = '';
			if ( ! empty( $row['video'] ) ) {
				$video = is_array( $row['video'] ) ? ( $row['video']['url'] ?? '' ) : $row['video'];
			}
			$mapped[] = array(
				'title'        => $row['title'] ?? '',
				'slug'         => $row['slug'] ?? '',
				'tags'         => $tags,
				'class'        => $row['class'] ?? 'is-shower',
				'image'        => $image,
				'mobile_image' => $mobile_image,
				'video'        => $video,
			);
		}
		if ( $mapped ) {
			$content['catalog'] = $mapped;
		}
	}

	$stats = steklo_home_field( 'stats', array() );
	if ( is_array( $stats ) && $stats ) {
		$mapped = array();
		foreach ( $stats as $row ) {
			$mapped[] = array(
				'value' => $row['value'] ?? '',
				'label' => $row['label'] ?? '',
			);
		}
		if ( $mapped ) {
			$content['stats'] = $mapped;
		}
	}

	$why = steklo_home_field( 'why', array() );
	if ( is_array( $why ) && $why ) {
		$mapped = array();
		foreach ( $why as $row ) {
			$mapped[] = array(
				'title' => $row['title'] ?? '',
				'text'  => $row['text'] ?? '',
			);
		}
		if ( $mapped ) {
			$content['why'] = $mapped;
		}
	}

	$gifts = steklo_home_field( 'gifts', array() );
	if ( is_array( $gifts ) && $gifts ) {
		$mapped = array();
		foreach ( $gifts as $row ) {
			if ( ! empty( $row['item'] ) ) {
				$mapped[] = $row['item'];
			}
		}
		if ( $mapped ) {
			$content['gifts'] = $mapped;
		}
	}

	$steps = steklo_home_field( 'steps', array() );
	if ( is_array( $steps ) && $steps ) {
		$mapped = array();
		foreach ( $steps as $row ) {
			$image = '';
			if ( ! empty( $row['image'] ) ) {
				$image = is_array( $row['image'] ) ? ( $row['image']['url'] ?? '' ) : $row['image'];
			}
			$mapped[] = array(
				'title' => $row['title'] ?? '',
				'text'  => $row['text'] ?? '',
				'image' => $image,
			);
		}
		if ( $mapped ) {
			$content['steps'] = $mapped;
		}
	}

	$partners = steklo_home_field( 'partners', array() );
	if ( is_array( $partners ) && $partners ) {
		$mapped = array();
		foreach ( $partners as $row ) {
			if ( ! empty( $row['label'] ) ) {
				$mapped[] = $row['label'];
			}
		}
		if ( $mapped ) {
			$content['partners'] = $mapped;
		}
	}

	$faq = steklo_home_field( 'faq', array() );
	if ( is_array( $faq ) && $faq ) {
		$mapped = array();
		foreach ( $faq as $row ) {
			$mapped[] = array(
				'q' => $row['q'] ?? '',
				'a' => $row['a'] ?? '',
			);
		}
		if ( $mapped ) {
			$content['faq'] = $mapped;
		}
	}

	$home_works = steklo_home_field( 'home_works', array() );
	if ( is_array( $home_works ) && $home_works ) {
		$mapped = array();
		foreach ( $home_works as $row ) {
			$image = '';
			if ( ! empty( $row['image'] ) ) {
				$image = is_array( $row['image'] ) ? ( $row['image']['url'] ?? '' ) : $row['image'];
			}
			$mapped[] = array(
				'title' => $row['title'] ?? '',
				'date'  => $row['date'] ?? '',
				'image' => $image,
			);
		}
		if ( $mapped ) {
			$content['works'] = $mapped;
		}
	}

	return $content;
}
add_filter( 'steklo_content', 'steklo_content_from_acf' );
