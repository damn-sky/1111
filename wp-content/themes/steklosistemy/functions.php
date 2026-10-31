<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require get_template_directory() . '/inc/content.php';
require get_template_directory() . '/inc/acf.php';
require get_template_directory() . '/inc/landing-data.php';

function steklo_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 64, 'width' => 180, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus(
		array(
			'primary' => 'Шапка',
			'footer'  => 'Подвал',
		)
	);
}
add_action( 'after_setup_theme', 'steklo_setup' );

function steklo_allow_mp4( $mimes ) {
	$mimes['mp4'] = 'video/mp4';
	return $mimes;
}
add_filter( 'upload_mimes', 'steklo_allow_mp4' );

function steklo_disable_blocks_on_front( $use, $post ) {
	if ( $post instanceof WP_Post && (int) $post->ID === (int) get_option( 'page_on_front' ) ) {
		return false;
	}
	return $use;
}
add_filter( 'use_block_editor_for_post', 'steklo_disable_blocks_on_front', 10, 2 );

function steklo_assets() {
	// Главная отдаёт новую React-вёрстку из landing/ — старые CSS/JS там не нужны.
	if ( is_front_page() ) {
		return;
	}
	wp_enqueue_style( 'steklo-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.5.2' );
	wp_enqueue_script( 'steklo-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.5.2', true );
}
add_action( 'wp_enqueue_scripts', 'steklo_assets' );

function steklo_register_types() {
	register_post_type(
		'steklo_work',
		array(
			'labels'       => array(
				'name'          => 'Работы',
				'singular_name' => 'Работа',
				'add_new_item'  => 'Добавить работу',
			),
			'public'       => true,
			'has_archive'  => true,
			'menu_icon'    => 'dashicons-format-gallery',
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'rewrite'      => array( 'slug' => 'raboty' ),
			'show_in_rest' => true,
		)
	);
	register_post_type(
		'steklo_review',
		array(
			'labels'       => array(
				'name'          => 'Отзывы',
				'singular_name' => 'Отзыв',
				'add_new_item'  => 'Добавить отзыв',
			),
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-star-filled',
			'supports'     => array( 'title', 'editor', 'excerpt' ),
			'rewrite'      => array( 'slug' => 'otzyvy' ),
			'show_in_rest' => true,
		)
	);
	register_post_type(
		'steklo_lead',
		array(
			'labels'       => array(
				'name'          => 'Заявки',
				'singular_name' => 'Заявка',
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-email',
			'supports'     => array( 'title', 'editor' ),
			'show_in_rest' => false,
		)
	);
}
add_action( 'init', 'steklo_register_types' );

function steklo_seed_pages() {
	if ( get_option( 'steklo_pages_ready' ) ) {
		return;
	}
	$content = steklo_content();
	$pages   = array( 'Главная' => 'glavnaya' );
	foreach ( $content['menus'] as $group ) {
		foreach ( $group as $title => $slug ) {
			$pages[ $title ] = $slug;
		}
	}
	foreach ( $content['legal'] as $title => $slug ) {
		$pages[ $title ] = $slug;
	}
	$pages['Контакты'] = 'kontakty';

	foreach ( $pages as $title => $slug ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post(
				array(
					'post_title'  => $title,
					'post_name'   => $slug,
					'post_status' => 'publish',
					'post_type'   => 'page',
					'post_content'=> '',
				)
			);
		}
	}

	$home = get_page_by_path( 'glavnaya' );
	if ( $home ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
	}
	update_option( 'steklo_pages_ready', 1 );
}
add_action( 'after_switch_theme', 'steklo_seed_pages' );

function steklo_handle_lead() {
	if ( ! isset( $_POST['steklo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['steklo_nonce'] ) ), 'steklo_lead' ) ) {
		wp_safe_redirect( home_url( '/?lead=error' ) );
		exit;
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$topic   = isset( $_POST['topic'] ) ? sanitize_text_field( wp_unslash( $_POST['topic'] ) ) : 'Расчёт';
	$consent = ! empty( $_POST['consent'] );

	if ( $name === '' || $phone === '' || ! $consent ) {
		wp_safe_redirect( home_url( '/?lead=error#calc' ) );
		exit;
	}

	$body = "Тема: {$topic}\nТелефон: {$phone}\n\n{$message}";
	$lead = wp_insert_post(
		array(
			'post_type'    => 'steklo_lead',
			'post_status'  => 'private',
			'post_title'   => $name . ' — ' . $phone,
			'post_content' => $body,
		)
	);

	if ( $lead && ! empty( $_FILES['file']['name'] ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		$uploaded = wp_handle_upload( $_FILES['file'], array( 'test_form' => false ) );
		if ( empty( $uploaded['error'] ) && ! empty( $uploaded['file'] ) ) {
			update_post_meta( $lead, 'steklo_file', $uploaded['url'] );
		}
	}

	wp_safe_redirect( home_url( '/?lead=ok#calc' ) );
	exit;
}
add_action( 'admin_post_nopriv_steklo_lead', 'steklo_handle_lead' );
add_action( 'admin_post_steklo_lead', 'steklo_handle_lead' );

function steklo_works() {
	$query = new WP_Query(
		array(
			'post_type'      => 'steklo_work',
			'posts_per_page' => 12,
			'post_status'    => 'publish',
		)
	);
	if ( $query->have_posts() ) {
		$items = array();
		while ( $query->have_posts() ) {
			$query->the_post();
			$items[] = array(
				'title' => get_the_title(),
				'date'  => get_the_date( 'd.m.Y' ),
				'cat'   => '',
				'image' => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
				'url'   => get_permalink(),
			);
		}
		wp_reset_postdata();
		return $items;
	}
	return steklo_content()['works'];
}

function steklo_reviews() {
	$query = new WP_Query(
		array(
			'post_type'      => 'steklo_review',
			'posts_per_page' => 12,
			'post_status'    => 'publish',
		)
	);
	if ( $query->have_posts() ) {
		$items = array();
		while ( $query->have_posts() ) {
			$query->the_post();
			$items[] = array(
				'name' => get_the_title(),
				'date' => get_the_date( 'j F Y' ),
				'text' => get_the_excerpt() ? get_the_excerpt() : wp_strip_all_tags( get_the_content() ),
			);
		}
		wp_reset_postdata();
		return $items;
	}
	return steklo_content()['reviews'];
}
