<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$copyright = sayid_theme_text( 'sayid_footer_copyright', __( 'هرگونه کپی‌برداری از این وبسایت آزاد می‌باشد. ساخته شده و بعضی وقت‌ها هم خراب شده با ❤️', 'sayid' ) );

// Order is set from Appearance → Customize → فوتر — .footer-links-group
// is already a flex container with these two as direct children.
$order_links  = (int) get_theme_mod( 'sayid_footer_order_links', 1 );
$order_social = (int) get_theme_mod( 'sayid_footer_order_social', 2 );
?>
<footer class="site-footer">
	<div class="site-container footer-row">
		<p class="footer-signature"><?php echo esc_html( $copyright ); ?></p>

		<div class="footer-links-group">
			<nav class="footer-links" aria-label="<?php esc_attr_e( 'ناوبری فوتر', 'sayid' ); ?>" style="order: <?php echo esc_attr( $order_links ); ?>">
				<?php sayid_footer_nav(); ?>
			</nav>

			<ul class="footer-icons__social" style="order: <?php echo esc_attr( $order_social ); ?>">
				<?php foreach ( sayid_social_links() as $key => $link ) : ?>
					<li>
						<a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $link['label'] ); ?>" title="<?php echo esc_attr( $link['label'] ); ?>">
							<?php echo sayid_icon_social( $key ); // phpcs:ignore ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
