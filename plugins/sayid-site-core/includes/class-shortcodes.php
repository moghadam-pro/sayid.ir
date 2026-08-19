<?php
/**
 * Shortcodes wrapping Sayid_Core_Render. This is the primary way Elementor
 * pages consume homepage sections: drop a Shortcode widget where the
 * section should appear. See docs/13-elementor-build-guide.md.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sayid_Core_Shortcodes {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_shortcode( 'sayid_now', array( 'Sayid_Core_Render', 'now' ) );
		add_shortcode( 'sayid_selected_work', array( 'Sayid_Core_Render', 'selected_work' ) );
		add_shortcode( 'sayid_lab', array( 'Sayid_Core_Render', 'lab' ) );
		add_shortcode( 'sayid_signature', array( 'Sayid_Core_Render', 'signature' ) );
		add_shortcode( 'sayid_notes', array( $this, 'notes' ) );
		add_shortcode( 'sayid_featured_article', array( 'Sayid_Core_Render', 'featured_article' ) );
		add_shortcode( 'sayid_connect', array( 'Sayid_Core_Render', 'connect' ) );
		add_shortcode( 'sayid_scroll_cue', array( 'Sayid_Core_Render', 'scroll_cue' ) );
		add_shortcode( 'sayid_theme_switch', array( 'Sayid_Core_Render', 'theme_switch' ) );
		add_shortcode( 'sayid_homepage_deferred', array( 'Sayid_Core_Render', 'deferred_homepage' ) );
		add_shortcode( 'sayid_related', array( $this, 'related' ) );
	}

	public function notes( $atts ) {
		$atts = shortcode_atts( array( 'count' => 5 ), $atts, 'sayid_notes' );
		return Sayid_Core_Render::notes( (int) $atts['count'] );
	}

	public function related( $atts ) {
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return '';
		}
		return Sayid_Core_Render::related( $post_id );
	}
}
