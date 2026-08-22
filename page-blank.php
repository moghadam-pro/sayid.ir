<?php
/**
 * Template Name: قالب خام (بدون هدر و فوتر)
 *
 * Equivalent to Elementor's "Canvas" template: no site header/footer, no
 * `.prose`/editorial-width wrapper. The body is a byte-for-byte dump of
 * whatever is in the page's Code editor — get_the_content() reads
 * post_content directly and completely bypasses the `the_content` filter
 * chain, so nothing (wpautop, wptexturize, convert_smilies, do_blocks,
 * do_shortcode, any plugin hooked there) touches it. That's a deliberate
 * trade-off: a Gutenberg dynamic block (Query Loop, a [shortcode] block)
 * would NOT render here, since rendering those *is* what that filter
 * chain does — but it's also what previously mangled hand-written
 * <script> (wptexturize turning `&&` into the `&#038;` entity broke a
 * game's JS with "Invalid or unexpected token"). This template is for a
 * complete, self-contained HTML/CSS/JS page, not for composing with
 * blocks, so raw passthrough is the correct default. WordPress admins
 * have unfiltered_html by default on a single-site install, so what's
 * typed into the Code editor is exactly what's stored and exactly what's
 * output here — no sanitization step in between.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
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
<?php echo get_the_content(); // phpcs:ignore -- raw passthrough is the point of this template, see docblock above ?>
<?php wp_footer(); ?>
</body>
</html>
	<?php
endwhile;
