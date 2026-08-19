<?php
/**
 * Asset loading. CSS is split by ownership (tokens/base/layout/components/
 * interactions) per docs/11; JS is loaded conditionally so pages that don't
 * use an interaction never pay for its script (brief §52 performance rules).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sayid_Core_Assets {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp_head', array( $this, 'inline_theme_bootstrap' ), 0 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Runs as early as possible in <head>, before any stylesheet paints, so
	 * a stored theme override applies before first paint and there is no
	 * flash of the wrong theme. Deliberately inline and dependency-free.
	 */
	public function inline_theme_bootstrap() {
		?>
		<script>
		(function(){
			try {
				var stored = localStorage.getItem('sayid-theme');
				if (stored === 'light' || stored === 'dark') {
					document.documentElement.setAttribute('data-theme', stored);
				}
			} catch (e) {}
		})();
		</script>
		<?php
	}

	public function enqueue() {
		$ver = SAYID_CORE_VERSION;

		// Always-on design system layers.
		wp_enqueue_style( 'sayid-tokens', SAYID_CORE_URL . 'assets/css/tokens.css', array(), $ver );
		wp_enqueue_style( 'sayid-base', SAYID_CORE_URL . 'assets/css/base.css', array( 'sayid-tokens' ), $ver );
		wp_enqueue_style( 'sayid-layout', SAYID_CORE_URL . 'assets/css/layout.css', array( 'sayid-base' ), $ver );
		wp_enqueue_style( 'sayid-components', SAYID_CORE_URL . 'assets/css/components.css', array( 'sayid-layout' ), $ver );
		wp_enqueue_style( 'sayid-interactions', SAYID_CORE_URL . 'assets/css/interactions.css', array( 'sayid-components' ), $ver );

		wp_enqueue_script( 'sayid-theme', SAYID_CORE_URL . 'assets/js/theme.js', array(), $ver, true );

		if ( is_front_page() ) {
			wp_enqueue_script( 'sayid-homepage-entry', SAYID_CORE_URL . 'assets/js/homepage-entry.js', array(), $ver, true );
			wp_enqueue_script( 'sayid-lab-pointer', SAYID_CORE_URL . 'assets/js/lab-pointer.js', array(), $ver, true );
			wp_enqueue_script( 'sayid-signature-network', SAYID_CORE_URL . 'assets/js/signature-network.js', array(), $ver, true );
		} elseif ( is_singular( 'sayid_lab' ) || is_post_type_archive( 'sayid_lab' ) ) {
			// Lab pointer interaction also applies to the Lab archive/single templates.
			wp_enqueue_script( 'sayid-lab-pointer', SAYID_CORE_URL . 'assets/js/lab-pointer.js', array(), $ver, true );
		}
	}
}
