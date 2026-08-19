<?php
/**
 * Homepage. Hero is always-visible, always-present; everything below it is
 * also always-present real DOM (#homepage-deferred-root) — homepage-entry.js
 * is the only thing that ever hides it, and only while it's running. See
 * inc/render.php and assets/js/homepage-entry.js for the full contract.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

sayid_enqueue_homepage_entry();

get_template_part( 'template-parts/hero' );

echo '<div id="homepage-deferred-root">' . sayid_render_homepage_sections() . '</div>'; // phpcs:ignore

get_footer();
