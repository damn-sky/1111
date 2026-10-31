<?php
get_header();
?>
<main class="section container page-wrap">
	<p class="pill">Портфолио</p>
	<h1>Наши работы</h1>
	<div class="works-row" style="margin-top:24px">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article class="work-card">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large' ); } ?>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p class="muted">Работы с главной пока стоят в макете. Новые добавляются в админке: Работы → Добавить.</p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
