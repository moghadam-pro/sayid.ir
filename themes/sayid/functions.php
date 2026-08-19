<?php
/**
 * Theme bootstrap.
 *
 * This theme intentionally has no Elementor dependency. It owns its own
 * content model (Notes, Articles-as-post, Lab, Projects, Now), design
 * tokens, Hero, homepage assembly, and templates end to end — the same
 * content model that previously lived in the `sayid-site-core` plugin,
 * carried over here because none of it was ever Elementor-specific.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SAYID_THEME_VERSION', '1.0.0' );
define( 'SAYID_THEME_DIR', get_template_directory() );
define( 'SAYID_THEME_URI', get_template_directory_uri() );

require_once SAYID_THEME_DIR . '/inc/helpers.php';
require_once SAYID_THEME_DIR . '/inc/setup.php';
require_once SAYID_THEME_DIR . '/inc/enqueue.php';
require_once SAYID_THEME_DIR . '/inc/taxonomies.php';
require_once SAYID_THEME_DIR . '/inc/content-types.php';
require_once SAYID_THEME_DIR . '/inc/meta-fields.php';
require_once SAYID_THEME_DIR . '/inc/relationships.php';
require_once SAYID_THEME_DIR . '/inc/now-dashboard-widget.php';
require_once SAYID_THEME_DIR . '/inc/queries.php';
require_once SAYID_THEME_DIR . '/inc/render.php';
require_once SAYID_THEME_DIR . '/inc/admin-columns.php';
require_once SAYID_THEME_DIR . '/inc/template-tags.php';
require_once SAYID_THEME_DIR . '/inc/icons.php';
require_once SAYID_THEME_DIR . '/inc/customizer.php';
require_once SAYID_THEME_DIR . '/inc/contact-form.php';
