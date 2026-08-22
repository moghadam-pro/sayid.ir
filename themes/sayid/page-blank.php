<?php
/**
 * Template Name: قالب خام (بدون هدر و فوتر)
 *
 * Equivalent to Elementor's "Canvas" template: no site header/footer, no
 * `.prose`/editorial-width wrapper — just this page's own content.
 * `do_blocks()`/`do_shortcode()` stay hooked on `the_content` (a Query
 * Loop block or a [shortcode] still renders), but every *typographic*
 * filter is unhooked, since those are meant for prose and actively
 * corrupt code:
 *   - wpautop wraps stray <p>/<br> around hand-written HTML.
 *   - wptexturize rewrites plain ASCII into "smart" typography —
 *     critically, literal `&` becomes the entity `&#038;`, so `&&` in any
 *     inline <script> (e.g. `if (a &#038;&#038; b)`) turns into invalid
 *     JavaScript and throws "Invalid or unexpected token" in the console.
 *   - convert_smilies rewrites text emoticons like `:)` into <img> tags.
 * WordPress admins have unfiltered_html by default on a single-site
 * install, so raw HTML/CSS/JS typed into this page (e.g. via the Custom
 * HTML block, or the Code editor) is preserved as-is on save — no extra
 * sanitization bypass needed here.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_content', 'wptexturize' );
remove_filter( 'the_content', 'convert_smilies', 20 );
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
