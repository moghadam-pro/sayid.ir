<?php
/**
 * Shared nav content (logo mark + role label + theme switch + primary
 * menu), used both inside the plain `.site-header` (every page except the
 * homepage) and inside the Hero's own `.home-hero__nav` row (homepage
 * only) — see header.php and template-parts/hero.php. One markup source,
 * two visual contexts handled entirely by CSS parent selectors, so the
 * menu never has to be maintained twice.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="site-nav">
	<nav class="site-nav__menu" aria-label="<?php esc_attr_e( 'منوی اصلی', 'sayid' ); ?>">
		<?php sayid_primary_nav(); ?>
	</nav>
	<?php
	/* translators: accessible name for the home link — includes the role
	   label so a screen reader hears one clear sentence instead of the
	   logo and the (visually adjacent, same-link) role text run together. */
	$mark_label = __( 'سعید مقدم، طراح ارشد محصول — بازگشت به صفحه اصلی', 'sayid' );
	?>
	<div class="site-nav__mark-group">
		<a class="site-nav__mark" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $mark_label ); ?>">
			<?php if ( has_custom_logo() ) : ?>
				<?php
				// `the_custom_logo()` wraps its <img> in its own <a> — since
				// this whole block already sits inside .site-nav__mark, that
				// would nest an anchor inside an anchor (invalid HTML; the
				// browser auto-closes the outer one, silently detaching the
				// role label and theme switch from the home link). Rendering
				// just the image avoids that.
				echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'custom-logo' ) );
				?>
			<?php else : ?>
				<span class="site-nav__mark-label"><?php bloginfo( 'name' ); ?></span>
				<span class="site-nav__mark-glyph" aria-hidden="true">s.</span>
			<?php endif; ?>
			<span class="site-nav__mark-role"><?php esc_html_e( 'طراح ارشد محصول', 'sayid' ); ?></span>
		</a>
		<?php echo sayid_render_theme_switch(); // phpcs:ignore ?>
	</div>
</div>
