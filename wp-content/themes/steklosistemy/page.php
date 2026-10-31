<?php
get_header();
?>
<main class="section container page-wrap">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<p class="pill">Стеклосистемы</p>
		<h1><?php the_title(); ?></h1>
		<div class="page-body">
			<?php
			if ( trim( get_the_content() ) === '' ) {
				echo '<p class="muted">Страница создана и готова к наполнению. Текст добавляется в админке: Страницы → эта запись.</p>';
			} else {
				the_content();
			}
			?>
		</div>
	<?php endwhile; ?>
</main>
<?php
get_footer();
