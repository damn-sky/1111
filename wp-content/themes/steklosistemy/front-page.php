<?php
/**
 * Главная: React-лендинг + данные из ACF (window.STEKLO).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$landing_uri = get_template_directory_uri() . '/landing';
$landing_dir = get_template_directory() . '/landing';

$steklo_landing_href = static function ( $filename ) use ( $landing_dir, $landing_uri ) {
	$path = $landing_dir . '/assets/' . $filename;
	if ( ! is_readable( $path ) ) {
		return '';
	}
	$url = $landing_uri . '/assets/' . $filename;
	return add_query_arg( 'ver', (string) filemtime( $path ), $url );
};

$css_href = $steklo_landing_href( 'landing-app.css' );
$js_href  = $steklo_landing_href( 'landing-app.js' );

// Запасной вариант, если на сервере ещё старая hashed-сборка.
if ( ! $js_href ) {
	$files = glob( $landing_dir . '/assets/index-*.js' ) ?: array();
	if ( $files ) {
		usort(
			$files,
			static function ( $a, $b ) {
				return filemtime( $b ) <=> filemtime( $a );
			}
		);
		$js_href = add_query_arg( 'ver', (string) filemtime( $files[0] ), $landing_uri . '/assets/' . basename( $files[0] ) );
	}
}
if ( ! $css_href ) {
	$files = glob( $landing_dir . '/assets/index-*.css' ) ?: array();
	if ( $files ) {
		usort(
			$files,
			static function ( $a, $b ) {
				return filemtime( $b ) <=> filemtime( $a );
			}
		);
		$css_href = add_query_arg( 'ver', (string) filemtime( $files[0] ), $landing_uri . '/assets/' . basename( $files[0] ) );
	}
}

$payload = function_exists( 'steklo_landing_payload' ) ? steklo_landing_payload() : array();
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#152f50">
	<?php wp_head(); ?>
	<?php if ( $css_href ) : ?>
		<link rel="stylesheet" href="<?php echo esc_url( $css_href ); ?>" crossorigin>
	<?php endif; ?>
</head>
<body <?php body_class( 'steklo-landing-page' ); ?>>
<?php wp_body_open(); ?>
<script>
window.STEKLO = <?php echo wp_json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>;
</script>
<div id="root"></div>
<?php if ( $js_href ) : ?>
	<script type="module" src="<?php echo esc_url( $js_href ); ?>" crossorigin></script>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
