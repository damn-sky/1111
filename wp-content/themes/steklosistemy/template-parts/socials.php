<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! isset( $c ) ) {
	$c = steklo_content();
}
$class = isset( $social_class ) ? $social_class : 'socials';
$phone = preg_replace( '/[^\d+]/', '', $c['phone'] );
?>
<nav class="<?php echo esc_attr( $class ); ?>" aria-label="Соцсети">
	<a href="tel:<?php echo esc_attr( $phone ); ?>" aria-label="Позвонить">
		<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8.2 4.8h2.1l1 2.4-1.3 1.1a12 12 0 0 0 5.7 5.7l1.1-1.3 2.4 1v2.1c0 .7-.6 1.3-1.3 1.3A14.2 14.2 0 0 1 6.9 6.1c0-.7.6-1.3 1.3-1.3Z" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>
	</a>
	<a href="<?php echo $c['social']['telegram'] ? esc_url( $c['social']['telegram'] ) : '#'; ?>" aria-label="Telegram">
		<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.8 6.3 16.4 17c-.2.8-.7 1-1.4.6l-3.9-2.9-1.9 1.8c-.2.2-.4.4-.8.4l.3-4 7.3-6.6c.3-.3-.1-.4-.5-.2L6.7 12.2 2.9 11c-.8-.2-.8-.8.2-1.2l15-5.8c.7-.2 1.2.2.7 1.3Z" fill="currentColor"/></svg>
	</a>
	<a href="<?php echo $c['social']['vk'] ? esc_url( $c['social']['vk'] ) : '#'; ?>" aria-label="ВКонтакте">
		<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8.2h2.1c.1 3.2 1.5 5.1 3.8 5.1h.2V8.2H13v3.4c2.2-.2 3.8-2 4.4-3.4H19.5c-.5 1.8-2 3.6-3.2 4.6 1.3.8 2.9 2.4 3.5 4.4h-2.2c-.5-1.4-1.7-2.5-3.3-2.6v2.6h-.2c-3.8 0-6.7-2.6-6.9-8.6Z" fill="currentColor"/></svg>
	</a>
	<a href="<?php echo $c['social']['instagram'] ? esc_url( $c['social']['instagram'] ) : '#'; ?>" aria-label="Instagram">
		<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5.5" y="5.5" width="13" height="13" rx="3.5" fill="none" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3.1" fill="none" stroke="currentColor" stroke-width="1.5"/><circle cx="16.3" cy="7.7" r=".8" fill="currentColor"/></svg>
	</a>
	<a href="<?php echo $c['social']['youtube'] ? esc_url( $c['social']['youtube'] ) : '#'; ?>" aria-label="YouTube">
		<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.2 8.4a2.2 2.2 0 0 0-1.6-1.6C16.2 6.5 12 6.5 12 6.5s-4.2 0-5.6.3a2.2 2.2 0 0 0-1.6 1.6A23 23 0 0 0 4.5 12a23 23 0 0 0 .3 3.6 2.2 2.2 0 0 0 1.6 1.6c1.4.3 5.6.3 5.6.3s4.2 0 5.6-.3a2.2 2.2 0 0 0 1.6-1.6 23 23 0 0 0 .3-3.6 23 23 0 0 0-.3-3.6ZM10.8 14.6V9.4L15 12l-4.2 2.6Z" fill="currentColor"/></svg>
	</a>
</nav>
