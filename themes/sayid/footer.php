<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="site-footer">
	<div class="site-container footer-row">
		<nav class="footer-links" aria-label="<?php esc_attr_e( 'ناوبری فوتر', 'sayid' ); ?>">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'sayid_project' ) ); ?>"><?php esc_html_e( 'کارها', 'sayid' ); ?></a>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'sayid_lab' ) ); ?>"><?php esc_html_e( 'آزمایشگاه', 'sayid' ); ?></a>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'sayid_note' ) ); ?>"><?php esc_html_e( 'یادداشت‌ها', 'sayid' ); ?></a>
		</nav>

		<div class="footer-icons">
			<ul class="footer-icons__social">
				<?php foreach ( sayid_social_links() as $key => $link ) : ?>
					<li>
						<a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $link['label'] ); ?>" title="<?php echo esc_attr( $link['label'] ); ?>">
							<?php echo sayid_icon_social( $key ); // phpcs:ignore ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php echo sayid_render_theme_switch(); // phpcs:ignore ?>
		</div>
	</div>

	<div class="site-container footer-bottom">
		<p class="footer-signature"><?php esc_html_e( 'هرگونه کپی‌برداری از این وبسایت آزاد می‌باشد. ساخته شده و بعضی وقت‌ها هم خراب شده با ❤️', 'sayid' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
