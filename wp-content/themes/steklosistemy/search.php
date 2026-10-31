<?php
get_header();
$query = get_search_query();
?>
<main class="section container page-wrap">
	<p class="pill">Поиск</p>
	<h1><?php echo $query ? 'Результаты: ' . esc_html( $query ) : 'Поиск по сайту'; ?></h1>
	<?php get_search_form(); ?>
	<div class="search-results">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt() ? get_the_excerpt() : wp_strip_all_tags( get_the_content() ), 28 ) ); ?></p>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p class="muted">По этому запросу пока ничего нет. Страницы разделов уже созданы — их можно наполнить в админке, и поиск начнёт их находить.</p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
