<?php
/**
 * Shared nav content (logo mark + primary menu), used both inside the
 * plain `.site-header` (every page except the homepage) and inside the
 * Hero's own `.home-hero__nav` row (homepage only) — see header.php and
 * template-parts/hero.php. One markup source, two visual contexts handled
 * entirely by CSS parent selectors, so the menu never has to be maintained
 * twice.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="site-nav">
	<nav class="site-nav__menu" aria-label="<?php esc_attr_e( 'منوی اصلی', 'sayid' ); ?>">
		<?php sayid_primary_nav(); ?>
	</nav>
	<a class="site-nav__mark" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php if ( has_custom_logo() ) : ?>
			<?php the_custom_logo(); ?>
		<?php else : ?>
			<span class="site-nav__mark-label"><?php bloginfo( 'name' ); ?></span>
			<span class="site-nav__mark-glyph" aria-hidden="true">s.</span>
		<?php endif; ?>
	</a>
</div>
