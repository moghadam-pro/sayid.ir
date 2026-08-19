<?php
/**
 * Optional REST endpoints.
 *
 * The v1 deferred-homepage mount (brief §16) deliberately does NOT use
 * these — it clones a server-rendered <template> already in the page, which
 * needs no network request and keeps SEO/no-JS behavior simple. These
 * endpoints exist for: (a) the Now block being readable by other tools /
 * a future moghadam.pro cross-link, and (b) the "optional later strategy:
 * REST fetch" escape hatch described in docs/11, if a future measurement
 * shows the inline-template payload is too large.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sayid_Core_Rest {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		register_rest_route( 'sayid/v1', '/now', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_now' ),
			'permission_callback' => '__return_true',
		) );

		register_rest_route( 'sayid/v1', '/homepage-deferred', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_homepage_deferred' ),
			'permission_callback' => '__return_true',
		) );
	}

	public function get_now( $request ) {
		return rest_ensure_response( Sayid_Core_Settings::get() );
	}

	/**
	 * Renders the same markup used inside the homepage's <template> tag, as
	 * an HTML fragment. Not used by v1's default mount path — kept as the
	 * documented upgrade path if the inline-template payload ever needs to
	 * become a real network fetch (see class docblock above).
	 */
	public function get_homepage_deferred( $request ) {
		$html = implode(
			'',
			array_filter( array(
				Sayid_Core_Render::now(),
				Sayid_Core_Render::selected_work(),
				Sayid_Core_Render::lab(),
				Sayid_Core_Render::signature(),
				Sayid_Core_Render::notes(),
				Sayid_Core_Render::featured_article(),
				Sayid_Core_Render::connect(),
			) )
		);
		return new WP_REST_Response( array( 'html' => $html ), 200 );
	}
}
