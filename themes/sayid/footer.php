<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="site-footer">
	<div class="site-container footer-grid">
		<div class="footer-col footer-col--identity">
			<p class="footer-identity__name"><?php bloginfo( 'name' ); ?></p>
			<p class="footer-identity__role"><?php esc_html_e( 'طراح ارشد محصول', 'sayid' ); ?></p>
		</div>

		<div class="footer-col">
			<p class="footer-col__title"><?php esc_html_e( 'ناوبری', 'sayid' ); ?></p>
			<?php sayid_footer_nav(); ?>
		</div>

		<div class="footer-col">
			<p class="footer-col__title"><?php esc_html_e( 'دیگر جاها', 'sayid' ); ?></p>
			<ul class="footer-social__list">
				<?php foreach ( sayid_social_links() as $key => $link ) : ?>
					<li>
						<a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener">
							<?php echo esc_html( $link['label'] ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="footer-col">
			<p class="footer-col__title"><?php esc_html_e( 'نسخه انگلیسی', 'sayid' ); ?></p>
			<a class="footer-english-link" href="https://moghadam.pro" target="_blank" rel="noopener">moghadam.pro</a>
		</div>
	</div>

	<div class="site-container footer-bottom">
		<p class="footer-signature"><?php esc_html_e( 'ساخته شده، تغییر کرده و بعضی وقت‌ها هم خراب شده توسط سید.', 'sayid' ); ?></p>
		<?php echo sayid_render_theme_switch(); // phpcs:ignore ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
