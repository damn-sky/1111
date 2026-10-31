<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<?php $search_id = function_exists( 'wp_unique_id' ) ? wp_unique_id( 'steklo-search-' ) : 'steklo-search'; ?>
	<label class="screen-reader" for="<?php echo esc_attr( $search_id ); ?>">Поиск по сайту</label>
	<input id="<?php echo esc_attr( $search_id ); ?>" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Поиск по сайту" required>
	<button type="submit">Найти</button>
</form>
