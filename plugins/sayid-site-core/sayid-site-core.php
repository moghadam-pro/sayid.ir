<?php
/**
 * Plugin Name:       Sayid Site Core
 * Plugin URI:        https://github.com/moghadam-pro/sayid.ir
 * Description:       Content model, design tokens, theme system and interaction layer that powers sayid.ir v2. Provides the structured content (Notes, Articles, Lab, Projects, Now), shared taxonomy, relationships, Elementor widgets/shortcodes, and the version-controlled front-end behavior (Hero entry, deferred homepage mount, Lab pointer interaction, Design × Code × AI network) that Elementor composes on top of.
 * Version:           1.0.0
 * Author:             Sayid Moghadam
 * Author URI:        https://moghadam.pro
 * Text Domain:        sayid-site-core
 * Domain Path:        /languages
 * Requires PHP:        7.4
 * Requires at least:   6.0
 *
 * This plugin does not replace Elementor. It provides the system underneath it:
 * WordPress owns content, taxonomies, settings and relationships. Elementor owns
 * page composition and presentation. This plugin owns design tokens, responsive
 * primitives, theme behavior, the Hero entry / deferred homepage mount, the Lab
 * pointer interaction, the Design × Code × AI network, and the render functions
 * shared by shortcodes and Elementor widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SAYID_CORE_VERSION', '1.0.0' );
define( 'SAYID_CORE_FILE', __FILE__ );
define( 'SAYID_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SAYID_CORE_URL', plugin_dir_url( __FILE__ ) );

final class Sayid_Site_Core {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->includes();
		add_action( 'init', array( $this, 'load_textdomain' ) );
		register_activation_hook( SAYID_CORE_FILE, array( $this, 'on_activate' ) );
		register_deactivation_hook( SAYID_CORE_FILE, array( $this, 'on_deactivate' ) );
	}

	private function includes() {
		require_once SAYID_CORE_PATH . 'includes/helpers.php';
		require_once SAYID_CORE_PATH . 'includes/class-assets.php';
		require_once SAYID_CORE_PATH . 'includes/class-taxonomies.php';
		require_once SAYID_CORE_PATH . 'includes/class-content-types.php';
		require_once SAYID_CORE_PATH . 'includes/class-meta-fields.php';
		require_once SAYID_CORE_PATH . 'includes/class-relationships.php';
		require_once SAYID_CORE_PATH . 'includes/class-settings.php';
		require_once SAYID_CORE_PATH . 'includes/class-queries.php';
		require_once SAYID_CORE_PATH . 'includes/class-render.php';
		require_once SAYID_CORE_PATH . 'includes/class-shortcodes.php';
		require_once SAYID_CORE_PATH . 'includes/class-templates.php';
		require_once SAYID_CORE_PATH . 'includes/class-rest.php';
		require_once SAYID_CORE_PATH . 'includes/class-admin.php';

		// Elementor integration only loads once Elementor itself has fully
		// finished loading. `elementor/loaded` is Elementor's own action for
		// this — it only fires after Elementor has required its base
		// classes (including \Elementor\Widget_Base). An earlier attempt
		// here also accepted `class_exists( '\Elementor\Plugin' )` as a
		// fallback signal, but that class can exist before Widget_Base has
		// been loaded, which required this file too early and fataled with
		// "Class Elementor\Widget_Base not found". Only the action is safe.
		add_action( 'elementor/loaded', function () {
			require_once SAYID_CORE_PATH . 'includes/class-elementor-widgets.php';
		} );

		Sayid_Core_Assets::instance();
		Sayid_Core_Taxonomies::instance();
		Sayid_Core_Content_Types::instance();
		Sayid_Core_Meta_Fields::instance();
		Sayid_Core_Relationships::instance();
		Sayid_Core_Settings::instance();
		Sayid_Core_Shortcodes::instance();
		Sayid_Core_Templates::instance();
		Sayid_Core_Rest::instance();
		Sayid_Core_Admin::instance();
	}

	public function load_textdomain() {
		load_plugin_textdomain( 'sayid-site-core', false, dirname( plugin_basename( SAYID_CORE_FILE ) ) . '/languages' );
	}

	public function on_activate() {
		require_once SAYID_CORE_PATH . 'includes/class-taxonomies.php';
		require_once SAYID_CORE_PATH . 'includes/class-content-types.php';
		Sayid_Core_Taxonomies::instance()->register_taxonomies();
		Sayid_Core_Content_Types::instance()->register_post_types();
		flush_rewrite_rules();
	}

	public function on_deactivate() {
		flush_rewrite_rules();
	}
}

Sayid_Site_Core::instance();
