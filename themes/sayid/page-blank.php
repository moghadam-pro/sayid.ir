<?php
/**
 * Template Name: قالب خام (بدون هدر و فوتر)
 *
 * Equivalent to Elementor's "Canvas" template: no site header/footer, no
 * `.prose`/editorial-width wrapper — just this page's own content. Only
 * `wpautop` is unhooked from `the_content` (not the whole filter chain),
 * so do_blocks()/do_shortcode() still run — a Query Loop block or a
 * [shortcode] still renders — while hand-written <style>/<script> no
 * longer gets stray <p> tags wrapped around it. WordPress admins have
 * unfiltered_html by default on a single-site install, so raw HTML/CSS/JS
 * typed into this page (e.g. via the Custom HTML block, or the Code
 * editor) is preserved as-is on save — no extra sanitization bypass
 * needed here.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
remove_filter( 'the_content', 'wpautop' );
while ( have_posts() ) : the_post();
	?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'sayid-blank-page' ); ?>>
<?php wp_body_open(); ?>
<?php the_content(); ?>
<?php wp_footer(); ?>
</body>
</html>
	<?php
endwhile;
