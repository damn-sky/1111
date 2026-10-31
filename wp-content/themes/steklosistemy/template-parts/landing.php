<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$c      = steklo_content();
$works  = steklo_works();
$reviews = steklo_reviews();
$max    = $c['max_url'] ? $c['max_url'] : '#calc';
?>
<main>
	<?php if ( isset( $_GET['lead'] ) && $_GET['lead'] === 'ok' ) : ?>
		<p class="notice">Заявка сохранена. Мы свяжемся с вами.</p>
	<?php elseif ( isset( $_GET['lead'] ) && $_GET['lead'] === 'error' ) : ?>
		<p class="notice is-error">Проверьте имя, телефон и согласие на обработку данных.</p>
	<?php endif; ?>

	<section class="hero">
		<div class="hero-media" aria-hidden="true">
			<video autoplay muted loop playsinline poster="<?php echo esc_url( steklo_img( 'hero-poster.jpg' ) ); ?>">
				<source src="<?php echo esc_url( get_template_directory_uri() . '/assets/video/hero.mp4' ); ?>" type="video/mp4">
			</video>
		</div>
		<div class="container hero-copy">
			<h1><?php echo nl2br( esc_html( $c['hero']['title'] ) ); ?></h1>
			<p class="hero-lead"><?php echo esc_html( $c['hero']['text'] ); ?></p>
			<div class="hero-actions">
				<a class="btn btn-calc" href="#calc">
					Рассчитать стоимость
					<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M4 6.2 8 10.2 12 6.2" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
				<a class="btn btn-max" href="<?php echo esc_url( $max ); ?>">
					<svg viewBox="0 0 16 16" aria-hidden="true"><circle cx="8" cy="8" r="5.2" fill="none" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="8" r="1.5" fill="currentColor"/></svg>
					Написать в Max
				</a>
			</div>
		</div>
		<?php
		$social_class = 'hero-social';
		include get_template_directory() . '/template-parts/socials.php';
		?>
	</section>

	<section class="section catalog" id="catalog" data-reveal>
		<div class="container split-head">
			<h2>Каталог</h2>
			<p class="muted"><?php echo esc_html( $c['catalog_intro'] ); ?></p>
		</div>
		<div class="container catalog-grid">
			<?php foreach ( $c['catalog'] as $item ) : ?>
				<a class="cat-card <?php echo esc_attr( $item['class'] ); ?>" href="<?php echo esc_url( steklo_page_url( $item['slug'] ) ); ?>">
					<?php if ( ! empty( $item['image'] ) ) : ?>
						<img class="cat-visual" src="<?php echo esc_url( steklo_img( $item['image'] ) ); ?>" alt="">
					<?php endif; ?>
					<span class="cat-title"><?php echo esc_html( $item['title'] ); ?></span>
					<span class="cat-tags"><?php echo esc_html( implode( ' · ', $item['tags'] ) ); ?></span>
					<span class="cat-go" aria-hidden="true">↗</span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="stats" data-reveal>
		<div class="container stats-row">
			<?php foreach ( $c['stats'] as $stat ) : ?>
				<div>
					<strong><?php echo esc_html( $stat['value'] ); ?></strong>
					<span><?php echo esc_html( $stat['label'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="why" id="why" data-reveal>
		<div class="why-stage">
			<img class="why-photo" src="<?php echo esc_url( steklo_img( 'why-stairs.jpg' ) ); ?>" alt="Стеклянная лестница">
			<div class="why-head">
				<p class="pill pill-light">Преимущества</p>
				<h2>Почему мы?</h2>
			</div>
			<div class="why-cards">
				<?php foreach ( $c['why'] as $i => $card ) : ?>
					<article class="why-card" style="--i: <?php echo (int) $i; ?>">
						<h3><?php echo esc_html( $card['title'] ); ?></h3>
						<p><?php echo esc_html( $card['text'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section works" id="works" data-reveal>
		<div class="container split-head">
			<h2>Наши работы</h2>
			<p class="muted"><?php echo esc_html( $c['works_intro'] ); ?> <a href="<?php echo esc_url( get_post_type_archive_link( 'steklo_work' ) ); ?>">по ссылке здесь</a></p>
		</div>
		<div class="container works-layout">
			<ul class="filters">
				<?php foreach ( $c['works_filters'] as $i => $filter ) : ?>
					<li><button type="button" class="<?php echo $i === 0 ? 'is-active' : ''; ?>" data-filter="<?php echo esc_attr( $filter ); ?>"><?php echo esc_html( $filter ); ?></button></li>
				<?php endforeach; ?>
			</ul>
			<div class="works-row">
				<?php foreach ( $works as $work ) : ?>
					<?php
					$img = $work['image'];
					if ( $img && strpos( $img, 'http' ) !== 0 ) {
						$img = steklo_img( $img );
					}
					?>
					<article class="work-card" data-cat="<?php echo esc_attr( $work['cat'] ); ?>">
						<?php if ( $img ) : ?><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $work['title'] ); ?>"><?php endif; ?>
						<div class="work-meta">
							<h3><?php echo esc_html( $work['title'] ); ?></h3>
							<p><?php echo esc_html( $work['date'] ); ?></p>
						</div>
						<span class="work-go" aria-hidden="true">↘</span>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section consult" id="calc" data-reveal>
		<div class="container consult-grid">
			<div class="consult-visual">
				<img src="<?php echo esc_url( steklo_img( 'form-phone.jpg' ) ); ?>" alt="">
			</div>
			<div class="consult-form">
				<h2>Покажите ваше пространство — мы предложим решение</h2>
				<?php include get_template_directory() . '/template-parts/form.php'; ?>
			</div>
		</div>
	</section>

	<section class="section process" id="process" data-reveal>
		<div class="container process-grid">
			<div class="process-aside">
				<h2>Как мы работаем</h2>
				<p class="muted"><?php echo esc_html( $c['process_lead'] ); ?></p>
				<div class="gifts-inline">
					<p class="gifts-label">Бесплатные услуги:</p>
					<ul>
						<?php foreach ( $c['gifts'] as $gift ) : ?>
							<li><?php echo esc_html( $gift ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="steps">
				<?php foreach ( $c['steps'] as $n => $step ) : ?>
					<article class="step-card">
						<span class="step-num"><?php echo esc_html( sprintf( '%02d', $n + 1 ) ); ?></span>
						<div class="step-body">
							<h3><?php echo esc_html( $step['title'] ); ?></h3>
							<p><?php echo esc_html( $step['text'] ); ?></p>
						</div>
						<?php if ( ! empty( $step['image'] ) ) : ?>
							<img src="<?php echo esc_url( steklo_img( $step['image'] ) ); ?>" alt="">
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="partners" id="partners" data-reveal>
		<div class="partners-orbit" aria-hidden="true">
			<span class="orbit-ring ring-1"></span>
			<span class="orbit-ring ring-2"></span>
			<span class="orbit-ring ring-3"></span>
		</div>
		<div class="partners-center">
			<h2>С кем<br>мы работаем</h2>
		</div>
		<ul class="partners-tags">
			<?php foreach ( $c['partners'] as $i => $label ) : ?>
				<li style="--i: <?php echo (int) $i; ?>"><span><?php echo esc_html( $label ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class="section faq" id="faq" data-reveal>
		<div class="container faq-grid">
			<div>
				<h2>Вопрос — ответ</h2>
				<?php if ( $c['faq_lead'] ) : ?><p class="muted"><?php echo esc_html( $c['faq_lead'] ); ?></p><?php endif; ?>
			</div>
			<div class="faq-list">
				<?php foreach ( $c['faq'] as $item ) : ?>
					<details>
						<summary><?php echo esc_html( $item['q'] ); ?><span class="faq-plus" aria-hidden="true">+</span></summary>
						<?php if ( $item['a'] !== '' ) : ?><p><?php echo esc_html( $item['a'] ); ?></p><?php endif; ?>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section reviews" id="reviews" data-reveal>
		<div class="container reviews-layout">
			<div class="reviews-aside">
				<h2>Отзывы</h2>
				<p class="muted"><?php echo esc_html( $c['process_lead'] ); ?></p>
				<div class="score">
					<strong><?php echo esc_html( $c['reviews_score'] ); ?></strong>
					<span class="stars">★★★★★</span>
					<small><?php echo esc_html( $c['reviews_meta'] ); ?></small>
				</div>
				<a class="btn btn-calc" href="https://yandex.ru/maps/" target="_blank" rel="noopener">Больше отзывов на Яндекс Картах ↗</a>
			</div>
			<div class="reviews-col">
				<?php foreach ( $reviews as $review ) : ?>
					<article class="review-card">
						<div class="review-top">
							<div>
								<h3><?php echo esc_html( $review['name'] ); ?></h3>
								<p class="review-date"><?php echo esc_html( $review['date'] ); ?></p>
							</div>
							<p class="stars">★★★★★</p>
						</div>
						<p><?php echo esc_html( $review['text'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section consult consult-bottom" id="calc-2" data-reveal>
		<div class="container consult-grid">
			<div class="consult-visual">
				<img src="<?php echo esc_url( steklo_img( 'form-phone.jpg' ) ); ?>" alt="">
			</div>
			<div class="consult-form">
				<h2>Покажите ваше пространство — мы предложим решение</h2>
				<?php
				$form_topic = 'Расчёт стоимости — низ формы';
				include get_template_directory() . '/template-parts/form.php';
				?>
			</div>
		</div>
	</section>
</main>

