<?php
get_header();
if ( is_home() || is_front_page() ) {
	get_template_part( 'template-parts/landing' );
} else {
	echo '<main class="section container">';
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			echo '<article><h1>' . esc_html( get_the_title() ) . '</h1>';
			the_content();
			echo '</article>';
		}
	}
	echo '</main>';
}
get_footer();
