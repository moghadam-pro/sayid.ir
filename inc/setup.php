<?php
/**
 * Core theme setup: supports, nav menus, image sizes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', function () {
	load_theme_textdomain( 'sayid', SAYID_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo', array(
		'height'      => 40,
		'width'       => 120,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style',
	) );
	add_theme_support( 'responsive-embeds' );

	// This theme draws its own palette from design tokens (brief §27);
	// the block editor's default color picker is not part of the system.
	add_theme_support( 'disable-custom-colors' );

	register_nav_menus( array(
		'primary' => __( 'منوی اصلی', 'sayid' ),
		'footer'  => __( 'منوی فوتر', 'sayid' ),
	) );

	// Editorial-width reading column (brief §26), used as the default
	// content width for embeds/oEmbeds.
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 768;
	}
} );

/**
 * Image sizes used by the design system's card/media components.
 */
add_action( 'after_setup_theme', function () {
	add_image_size( 'sayid-card', 800, 500, true );
	add_image_size( 'sayid-featured', 1200, 750, true );
} );

/**
 * Themes have no register_activation_hook() equivalent to a plugin's — the
 * closest is `after_switch_theme`, which fires once the CPTs/taxonomy are
 * already registered (since `init` has already run for this request), so a
 * flush here reliably picks up the new permalink structures. If archive
 * pages still 404 after activating, Settings → Permalinks → Save once more
 * clears any host-level page-cache of the old rewrite rules.
 */
add_action( 'after_switch_theme', function () {
	flush_rewrite_rules();
} );
