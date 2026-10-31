<?php
/**
 * Данные главной для React-лендинга (window.STEKLO) из ACF + дефолты темы.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function steklo_landing_asset( $file ) {
	return get_template_directory_uri() . '/landing/assets/' . ltrim( $file, '/' );
}

function steklo_media_url( $value, $fallback = '' ) {
	if ( empty( $value ) ) {
		return $fallback;
	}
	if ( is_array( $value ) ) {
		if ( ! empty( $value['url'] ) ) {
			return $value['url'];
		}
		if ( ! empty( $value['ID'] ) ) {
			$url = wp_get_attachment_url( (int) $value['ID'] );
			return $url ? $url : $fallback;
		}
		return $fallback;
	}
	if ( is_numeric( $value ) ) {
		$url = wp_get_attachment_url( (int) $value );
		return $url ? $url : $fallback;
	}
	return is_string( $value ) ? $value : $fallback;
}

function steklo_catalog_id_from_class( $class ) {
	$map = array(
		'is-shower'    => 'shower',
		'is-rail'      => 'railing',
		'is-constr'    => 'construction',
		'is-visor'     => 'canopy',
		'is-room'      => 'partition',
		'is-doors'     => 'door',
		'is-pergola'   => 'pergola',
		'is-frameless' => 'frameless',
		'shower'       => 'shower',
		'railing'      => 'railing',
		'construction' => 'construction',
		'canopy'       => 'canopy',
		'partition'    => 'partition',
		'door'         => 'door',
		'pergola'      => 'pergola',
		'frameless'    => 'frameless',
	);
	$class = (string) $class;
	return $map[ $class ] ?? sanitize_title( $class );
}

function steklo_split_stat_label( $label ) {
	$label = trim( (string) $label );
	$parts = preg_split( '/\s+/u', $label, 2 );
	return array(
		'a' => $parts[0] ?? '',
		'b' => $parts[1] ?? '',
	);
}

/**
 * @return array
 */
function steklo_landing_payload() {
	$content = steklo_content();
	$base    = trailingslashit( get_template_directory_uri() . '/landing/assets' );

	$hero_title = (string) ( $content['hero']['title'] ?? '' );
	$title_lines = preg_split( "/\r\n|\n|\r/", $hero_title ) ?: array();
	$title_lines = array_values( array_filter( array_map( 'strval', $title_lines ), 'strlen' ) );
	if ( ! $title_lines ) {
		$title_lines = array( 'Конструкции ', 'из безопасного ', 'закаленного стекла ', 'в Крыму' );
	} else {
		// Сохраняем пробел в конце строк как в макете, кроме последней.
		$last = count( $title_lines ) - 1;
		foreach ( $title_lines as $i => $line ) {
			$title_lines[ $i ] = $i === $last ? $line : rtrim( $line ) . ' ';
		}
	}

	$phone  = (string) $content['phone'];
	$phone2 = (string) $content['phone_second'];
	$phones = array(
		array(
			'label' => $phone,
			'href'  => preg_replace( '/[^\d+]/', '', $phone ),
		),
		array(
			'label' => $phone2,
			'href'  => preg_replace( '/[^\d+]/', '', $phone2 ),
		),
	);

	$address = (string) $content['address'];
	$address_html = esc_html( $address );
	if ( str_contains( $address, ',' ) ) {
		$parts = array_map( 'trim', explode( ',', $address, 2 ) );
		$address_html = esc_html( $parts[0] ) . ',<br/>' . esc_html( $parts[1] ?? '' );
	}

	$hero_video = steklo_media_url( steklo_home_field( 'hero_video', '' ), steklo_landing_asset( 'hero.mp4' ) );
	$hero_image = steklo_media_url( steklo_home_field( 'hero_image', '' ), steklo_landing_asset( 'hero.png' ) );
	$hero_glass = steklo_media_url( steklo_home_field( 'hero_mobile_glass', '' ), steklo_landing_asset( 'glass.png' ) );

	$btn1_text = trim( (string) steklo_home_field( 'hero_btn_1_text', 'Рассчитать стоимость' ) );
	$btn1_url  = trim( (string) steklo_home_field( 'hero_btn_1_url', '#estimate' ) );
	if ( $btn1_url === '' ) {
		$btn1_url = '#estimate';
	}
	$btn2_text = trim( (string) steklo_home_field( 'hero_btn_2_text', 'Написать в МАХ' ) );
	$btn2_url  = trim( (string) steklo_home_field( 'hero_btn_2_url', '' ) );
	if ( $btn2_url === '' && ! empty( $content['max_url'] ) ) {
		$btn2_url = (string) $content['max_url'];
	}

	$default_cards = array(
		'shower'       => array( 'large' => true, 'mobile' => true, 'image' => 'catalog-shower.png', 'video' => 'catalog-shower.mp4' ),
		'railing'      => array( 'mobile' => true, 'image' => 'catalog-railing.png' ),
		'construction' => array( 'mobile' => true, 'image' => 'catalog-construction.png', 'mobileImage' => 'catalog-mobileTable.png' ),
		'canopy'       => array( 'mobile' => true, 'image' => 'catalog-canopy.png' ),
		'partition'    => array( 'image' => 'catalog-partition.png' ),
		'door'         => array( 'image' => 'catalog-door.png', 'video' => 'catalog-door.mp4' ),
		'pergola'      => array( 'image' => 'catalog-pergola.png' ),
		'frameless'    => array( 'large' => true, 'image' => 'catalog-frameless.png', 'video' => 'catalog-frameless.mp4' ),
	);

	$cards = array();
	foreach ( $content['catalog'] as $row ) {
		$id   = steklo_catalog_id_from_class( $row['class'] ?? ( $row['id'] ?? 'shower' ) );
		$meta = $default_cards[ $id ] ?? array( 'image' => 'catalog-shower.png' );
		$title = (string) ( $row['title'] ?? '' );
		// В макете у части карточек перенос строки.
		if ( in_array( $id, array( 'shower', 'partition', 'frameless' ), true ) && ! str_contains( $title, "\n" ) ) {
			$title = preg_replace( '/\s+/u', "\n", $title, 1 ) ?: $title;
		}
		$image = steklo_media_url( $row['image'] ?? '', steklo_landing_asset( $meta['image'] ) );
		if ( $image && ! preg_match( '#^https?://#i', $image ) && ! str_starts_with( $image, '/' ) ) {
			$image = steklo_landing_asset( $image );
		}
		$video = '';
		if ( ! empty( $row['video'] ) ) {
			$video = steklo_media_url( $row['video'], '' );
		} elseif ( ! empty( $meta['video'] ) ) {
			$video = steklo_landing_asset( $meta['video'] );
		}
		$mobile_image = '';
		if ( ! empty( $row['mobile_image'] ) ) {
			$mobile_image = steklo_media_url( $row['mobile_image'], '' );
		} elseif ( ! empty( $meta['mobileImage'] ) ) {
			$mobile_image = steklo_landing_asset( $meta['mobileImage'] );
		}
		$cards[] = array(
			'id'          => $id,
			'title'       => $title,
			'tags'        => array_values( $row['tags'] ?? array() ),
			'large'       => ! empty( $meta['large'] ),
			'mobile'      => ! empty( $meta['mobile'] ),
			'image'       => $image,
			'mobileImage' => $mobile_image,
			'video'       => $video,
		);
	}

	$stats = array();
	foreach ( $content['stats'] as $row ) {
		$split = steklo_split_stat_label( $row['label'] ?? '' );
		$stats[] = array(
			'number' => (string) ( $row['value'] ?? '' ),
			'a'      => $split['a'],
			'b'      => $split['b'],
		);
	}

	$intro = (string) $content['catalog_intro'];
	$intro_lines = preg_split( "/\r\n|\n|\r/", $intro ) ?: array();
	$intro_lines = array_values( array_filter( array_map( 'trim', $intro_lines ), 'strlen' ) );
	if ( ! $intro_lines ) {
		$intro_lines = array(
			'Современные стеклянные конструкции на заказ.',
			'Индивидуальные решения из закаленного стекла',
			'для квартир, частных домов и коммерческих помещений',
		);
	}

	$why_defaults = array(
		'glass'    => array( 'x' => 909, 'y' => 74, 'from' => 814, 'height' => 145, 'light' => true ),
		'project'  => array( 'x' => 129, 'y' => 128, 'from' => 868, 'height' => 163 ),
		'team'     => array( 'x' => 813, 'y' => 609, 'from' => 1839, 'height' => 145, 'light' => true ),
		'all'      => array( 'x' => 197, 'y' => 405, 'from' => 1285, 'height' => 127 ),
		'hardware' => array( 'x' => 806, 'y' => 333, 'from' => 1213, 'height' => 181 ),
		'warranty' => array( 'x' => 280, 'y' => 657, 'from' => 1887, 'height' => 109, 'light' => true ),
	);
	$why_ids = array_keys( $why_defaults );
	$benefits_items = array();
	foreach ( array_values( $content['why'] ) as $i => $row ) {
		$id = $why_ids[ $i ] ?? ( 'why-' . $i );
		$geo = $why_defaults[ $id ] ?? array( 'x' => 100, 'y' => 100, 'from' => 200, 'height' => 140 );
		$benefits_items[] = array(
			'id'     => $id,
			'title'  => (string) ( $row['title'] ?? '' ),
			'body'   => (string) ( $row['text'] ?? '' ),
			'x'      => $geo['x'],
			'y'      => $geo['y'],
			'from'   => $geo['from'],
			'height' => $geo['height'],
			'light'  => ! empty( $geo['light'] ),
		);
	}

	$benefits_bg = steklo_media_url( steklo_home_field( 'benefits_background', '' ), steklo_landing_asset( 'benefits-background.png' ) );

	$works = array();
	$works_src = $content['works'] ?? array();
	foreach ( array_values( $works_src ) as $i => $row ) {
		$img = steklo_media_url( $row['image'] ?? '', steklo_landing_asset( 'portfolio-img' . ( $i + 1 ) . '.png' ) );
		if ( $img && ! preg_match( '#^https?://#i', $img ) && ! str_starts_with( $img, '/' ) ) {
			$img = steklo_landing_asset( $img );
		}
		$date = (string) ( $row['date'] ?? '' );
		$date_time = '';
		if ( preg_match( '/^(\d{2})\.(\d{2})\.(\d{4})$/', $date, $m ) ) {
			$date_time = $m[3] . '-' . $m[2] . '-' . $m[1];
		}
		$works[] = array(
			'title'    => (string) ( $row['title'] ?? '' ),
			'date'     => $date,
			'dateTime' => $date_time,
			'image'    => $img,
		);
	}
	if ( ! $works ) {
		for ( $i = 1; $i <= 8; $i++ ) {
			$works[] = array(
				'title'    => 'Угловая перегородка из прозрачного стекла',
				'date'     => '05.10.2026',
				'dateTime' => '2026-10-05',
				'image'    => steklo_landing_asset( 'portfolio-img' . $i . '.png' ),
			);
		}
	}

	$estimate_glass = steklo_media_url( steklo_home_field( 'estimate_glass', '' ), steklo_landing_asset( 'estimate-glass.png' ) );
	$estimate_hover = steklo_media_url( steklo_home_field( 'estimate_hover', '' ), steklo_landing_asset( 'estimate-hover-imgImg.png' ) );
	$estimate_title = array( 'Покажите Ваше', 'пространство —', 'мы предложим', 'решение' );
	$et = steklo_home_field( 'estimate_title', '' );
	if ( is_string( $et ) && trim( $et ) !== '' ) {
		$estimate_title = array_values( array_filter( array_map( 'trim', preg_split( "/\r\n|\n|\r/", $et ) ?: array() ) ) );
	}

	$steps = array();
	foreach ( array_values( $content['steps'] ?? array() ) as $i => $row ) {
		$img = steklo_media_url( $row['image'] ?? '', steklo_landing_asset( 'steps-' . ( $i + 1 ) . '.png' ) );
		if ( $img && ! preg_match( '#^https?://#i', $img ) && ! str_starts_with( $img, '/' ) ) {
			$img = steklo_landing_asset( $img );
		}
		$text = (string) ( $row['text'] ?? '' );
		$paragraphs = preg_split( "/\r\n\r\n|\n\n/", $text ) ?: array( $text );
		$paragraphs = array_values( array_filter( array_map( 'trim', $paragraphs ), 'strlen' ) );
		$steps[] = array(
			'title'      => (string) ( $row['title'] ?? '' ),
			'paragraphs' => $paragraphs ?: array( $text ),
			'image'      => $img,
		);
	}

	$free = array();
	foreach ( $content['gifts'] ?? array() as $row ) {
		$item = is_array( $row ) ? (string) ( $row['item'] ?? '' ) : (string) $row;
		$free[] = str_replace( array( ' / ', ' | ' ), "\n", $item );
	}

	$works_link = (string) steklo_home_field( 'works_link', '' );

	$partner_tags = array_values( $content['partners'] ?? array() );
	$partners_raw = steklo_home_field( 'partners', array() );
	if ( is_array( $partners_raw ) && $partners_raw ) {
		$partner_tags = array();
		foreach ( $partners_raw as $row ) {
			$label = is_array( $row ) ? (string) ( $row['label'] ?? '' ) : (string) $row;
			$label = trim( $label );
			if ( $label !== '' ) {
				$partner_tags[] = $label;
			}
		}
	}

	$partners_title = trim( (string) steklo_home_field( 'partners_title', "С кем\nмы работаем" ) );
	$partners_title_lines = preg_split( "/\r\n|\n|\r/", $partners_title ) ?: array( 'С кем', 'мы работаем' );
	$partners_title_lines = array_values( array_filter( array_map( 'trim', $partners_title_lines ), 'strlen' ) );
	if ( ! $partners_title_lines ) {
		$partners_title_lines = array( 'С кем', 'мы работаем' );
	}

	$partners_cta_text = trim( (string) steklo_home_field( 'partners_cta_text', 'Стать партнером' ) );
	$partners_cta_url  = trim( (string) steklo_home_field( 'partners_cta_url', '' ) );

	$payload = array(
		'assetBase'   => $base,
		'phones'      => $phones,
		'addressHtml' => $address_html,
		'social'      => array(
			'max'      => (string) ( $content['max_url'] ?? '' ),
			'telegram' => (string) ( $content['social']['telegram'] ?? '' ),
		),
		'hero'        => array(
			'titleLines'   => $title_lines,
			'text'         => (string) ( $content['hero']['text'] ?? '' ),
			'video'        => $hero_video,
			'image'        => $hero_image,
			'mobileGlass'  => $hero_glass,
			'stars'            => steklo_landing_asset( 'stars.svg' ),
			'mobileStars'      => steklo_landing_asset( 'mobileStars.svg' ),
			'ctaPrimary'       => $btn1_text,
			'ctaPrimaryUrl'    => $btn1_url,
			'ctaSecondary'     => $btn2_text,
			'ctaSecondaryUrl'  => $btn2_url,
		),
		'catalog'     => array(
			'title'      => 'Каталог',
			'introLines' => $intro_lines,
			'cards'      => $cards,
			'stats'      => $stats,
		),
		'benefits'    => array(
			'title'        => (string) steklo_home_field( 'benefits_title', 'Почему мы?' ),
			'background'   => $benefits_bg,
			'mobileStairs' => steklo_landing_asset( 'benefits-mobile-stairs.svg' ),
			'mobileStars'  => steklo_landing_asset( 'benefits-mobile-stars.svg' ),
			'items'        => $benefits_items,
		),
		'portfolio'   => array(
			'title'       => 'Наши работы',
			'introBefore' => rtrim( (string) ( $content['works_intro'] ?? 'Посмотреть больше наших актуальных работ можно' ) ) . ' ',
			'linkLabel'   => 'по ссылке здесь',
			'linkUrl'     => $works_link,
			'categories'  => array_values( $content['works_filters'] ?? array() ),
			'works'       => $works,
		),
		'estimate'    => array(
			'titleLines'  => $estimate_title,
			'glass'       => $estimate_glass,
			'hover'       => $estimate_hover,
			'submitLabel' => 'Заказать рассчёт стоимости',
		),
		'workSteps'   => array(
			'titleLines' => array( 'Как мы', 'работаем' ),
			'lead'       => (string) ( $content['process_lead'] ?? '' ),
			'freeTitle'  => 'Бесплатные услуги:',
			'free'       => $free,
			'steps'      => $steps,
		),
		'partners'    => array(
			'titleLines' => $partners_title_lines,
			'tags'       => $partner_tags,
			'ctaText'    => $partners_cta_text,
			'ctaUrl'     => $partners_cta_url,
			'circle1'    => steklo_landing_asset( 'partners-circle-1.png' ),
			'circle2'    => steklo_landing_asset( 'partners-circle-2.png' ),
			'circle3'    => steklo_landing_asset( 'partners-circle-3.png' ),
			'watermark'  => steklo_landing_asset( 'partners-watermark.png' ),
		),
	);

	/**
	 * Позволяет дополнить/переопределить payload главной.
	 *
	 * @param array $payload Payload.
	 */
	return apply_filters( 'steklo_landing_payload', $payload );
}
