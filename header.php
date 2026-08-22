<?php
/**
 * On the homepage, the nav lives inside the Hero itself (see
 * template-parts/hero.php) so it can be part of the same single-viewport
 * first-screen composition — this file skips `.site-header` there to avoid
 * a duplicate nav. Every other page gets the plain top bar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( ! is_front_page() ) : ?>
	<header class="site-header">
		<div class="site-container">
			<?php get_template_part( 'template-parts/site-nav' ); ?>
		</div>
	</header>
<?php endif; ?>
