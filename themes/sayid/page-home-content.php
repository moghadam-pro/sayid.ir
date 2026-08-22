<?php
/**
 * Template Name: محتوای صفحه‌ی اصلی (بدون نمایش)
 *
 * A utility page, not a real destination: assign this template to any one
 * page, then fill in its "محتوای صفحه‌ی اصلی (هیرو)" meta box (added in
 * inc/meta-fields.php via add_meta_boxes_page) — front-page.php reads those
 * fields through sayid_home_field() (inc/template-tags.php) to fill in the
 * Hero's copy, falling back to the hardcoded defaults for anything left
 * blank. Visiting the page's own URL directly serves no purpose, so it
 * just sends visitors to the homepage instead of rendering empty chrome.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_safe_redirect( home_url( '/' ) );
exit;
