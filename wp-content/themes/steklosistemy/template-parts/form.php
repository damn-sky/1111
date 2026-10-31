<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$topic = isset( $form_topic ) ? $form_topic : 'Расчёт стоимости';
?>
<form class="lead-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
	<input type="hidden" name="action" value="steklo_lead">
	<input type="hidden" name="topic" value="<?php echo esc_attr( $topic ); ?>">
	<?php wp_nonce_field( 'steklo_lead', 'steklo_nonce' ); ?>
	<div class="form-row">
		<input type="text" name="name" placeholder="Ваше имя *" required>
		<input type="tel" name="phone" placeholder="+7 000 000 00 00 *" required>
	</div>
	<textarea name="message" placeholder="Placeholder *" rows="3"></textarea>
	<label class="file-field">
		<input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
		<span>Файл · JPG, PNG, PDF, DOC (до 10 МБ)</span>
	</label>
	<label class="consent">
		<input type="checkbox" name="consent" value="1" required>
		<span>Даю согласие на обработку персональных данных для рассмотрения заявки и обратной связи в соответствии с <a href="<?php echo esc_url( steklo_page_url( 'politika' ) ); ?>">политикой конфиденциальности</a></span>
	</label>
	<button class="btn btn-dark" type="submit">Заказать расчёт стоимости ↘</button>
</form>
