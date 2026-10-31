<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$c = steklo_content();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header <?php echo is_front_page() ? 'is-dark' : 'is-light'; ?>" id="top" data-state="menu">
	<div class="header-bar">
		<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img class="logo-for-dark" src="<?php echo esc_url( steklo_img( 'logo-light.png' ) ); ?>" alt="Стеклосистемы">
			<img class="logo-for-light" src="<?php echo esc_url( steklo_img( 'logo-dark.png' ) ); ?>" alt="">
		</a>

		<div class="header-center">
			<nav class="main-nav" data-view="menu" aria-label="Основное меню">
				<?php foreach ( $c['menus'] as $label => $items ) : ?>
					<div class="nav-drop">
						<button type="button" class="nav-link"><?php echo esc_html( $label ); ?> <span aria-hidden="true">▾</span></button>
						<div class="nav-panel">
							<?php foreach ( $items as $title => $slug ) : ?>
								<a href="<?php echo esc_url( steklo_page_url( $slug ) ); ?>"><?php echo esc_html( $title ); ?></a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
				<a class="nav-link" href="<?php echo esc_url( steklo_page_url( 'kontakty' ) ); ?>">Контакты</a>
			</nav>

			<div class="header-contact" data-view="contact" hidden>
				<a class="contact-phone" href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $c['phone'] ) ); ?>">
					<?php echo esc_html( $c['phone'] ); ?>
					<span class="mini" aria-hidden="true">☎</span>
					<span class="mini" aria-hidden="true">✈</span>
				</a>
				<a class="contact-phone" href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $c['phone_second'] ) ); ?>">
					<?php echo esc_html( $c['phone_second'] ); ?>
					<span class="mini" aria-hidden="true">☎</span>
					<span class="mini" aria-hidden="true">✈</span>
				</a>
				<span class="contact-address"><?php echo esc_html( $c['address'] ); ?></span>
			</div>

			<form class="header-search" data-view="search" hidden role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader" for="header-search">Поиск</label>
				<input id="header-search" type="search" name="s" placeholder="Поиск">
				<button type="submit" aria-label="Найти">
					<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16 16l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
				</button>
			</form>
		</div>

		<div class="header-tools">
			<button type="button" class="icon-btn" data-header-search aria-label="Поиск">
				<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16 16l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			</button>
			<button type="button" class="icon-btn" data-header-contact aria-label="Контакты">
				<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><circle cx="12" cy="8" r="3.2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M5 19c1.4-3 3.8-4.5 7-4.5S17.6 16 19 19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			</button>
			<button type="button" class="icon-btn icon-close" data-header-close hidden aria-label="Закрыть">
				<svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			</button>
			<button type="button" class="icon-btn burger" data-open-menu aria-label="Меню">
				<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M5 7h14M5 12h14M5 17h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
			</button>
		</div>
	</div>
</header>

<div class="drawer" id="drawer" hidden>
	<button type="button" class="search-close" data-close-menu>Закрыть</button>
	<?php foreach ( $c['menus'] as $label => $items ) : ?>
		<p class="drawer-label"><?php echo esc_html( $label ); ?></p>
		<?php foreach ( $items as $title => $slug ) : ?>
			<a href="<?php echo esc_url( steklo_page_url( $slug ) ); ?>"><?php echo esc_html( $title ); ?></a>
		<?php endforeach; ?>
	<?php endforeach; ?>
	<a href="<?php echo esc_url( steklo_page_url( 'kontakty' ) ); ?>">Контакты</a>
</div>
