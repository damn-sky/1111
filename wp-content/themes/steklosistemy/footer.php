<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$c     = steklo_content();
$phone = preg_replace( '/[^\d+]/', '', $c['phone'] );
$phone2 = preg_replace( '/[^\d+]/', '', $c['phone_second'] );
?>
<footer class="site-footer" id="contacts">
	<div class="container footer-wrap">
		<div class="footer-left">
			<div class="footer-cols">
				<?php foreach ( $c['menus'] as $label => $items ) : ?>
					<div class="footer-col">
						<p class="footer-label"><?php echo esc_html( $label ); ?></p>
						<ul>
							<?php foreach ( $items as $title => $slug ) : ?>
								<li><a href="<?php echo esc_url( steklo_page_url( $slug ) ); ?>"><?php echo esc_html( $title ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="footer-legal">
				<div class="footer-legal-top">
					<div class="footer-legal-left">
						<p><?php echo esc_html( $c['legal_address'] ); ?></p>
						<p><?php echo esc_html( $c['copyright'] ); ?></p>
					</div>
					<p class="footer-requisites"><?php echo esc_html( $c['requisites'] ); ?></p>
				</div>
				<nav class="footer-legal-links" aria-label="Правовая информация">
					<?php
					$legal_i = 0;
					$legal_n = count( $c['legal'] );
					foreach ( $c['legal'] as $title => $slug ) :
						$legal_i++;
						?>
						<a href="<?php echo esc_url( steklo_page_url( $slug ) ); ?>"><?php echo esc_html( $title ); ?></a><?php echo $legal_i < $legal_n ? ' <span aria-hidden="true">|</span> ' : ''; ?>
					<?php endforeach; ?>
				</nav>
			</div>
		</div>

		<div class="footer-card">
			<div class="footer-card-top">
				<a class="footer-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo esc_url( steklo_img( 'logo-dark.png' ) ); ?>" alt="Стеклосистемы">
				</a>
				<p class="footer-address"><?php echo esc_html( $c['address'] ); ?></p>
				<p class="footer-hours"><?php echo esc_html( $c['hours'] ); ?><br><?php echo esc_html( $c['hours_weekend'] ); ?></p>
			</div>

			<iframe
				class="footer-map"
				title="Яндекс Карта: <?php echo esc_attr( $c['address'] ); ?>"
				src="<?php echo esc_url( $c['map_embed'] ); ?>"
				loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"
				allowfullscreen
			></iframe>

			<div class="footer-card-bottom">
				<div class="footer-phones">
					<a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_html( $c['phone'] ); ?></a>
					<span aria-hidden="true">|</span>
					<a href="tel:<?php echo esc_attr( $phone2 ); ?>"><?php echo esc_html( $c['phone_second'] ); ?></a>
				</div>
				<?php
				$social_class = 'socials socials-dark';
				include get_template_directory() . '/template-parts/socials.php';
				?>
			</div>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
