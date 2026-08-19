<?php
/**
 * Cross-content relationship resolution.
 *
 * Storage and admin UI for relationship fields live in class-meta-fields.php
 * (they are just postmeta arrays of IDs, manually curated per docs/02 §4 —
 * "manual curation is fine for V1, do not overbuild a recommendation
 * engine"). This class is the read-side API that render functions and
 * templates use to fetch "related content" without knowing about meta keys.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sayid_Core_Relationships {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {}

	/**
	 * Returns every related item for a post, grouped by content type, resolved
	 * to published WP_Post objects only.
	 *
	 * @return array{notes: WP_Post[], articles: WP_Post[], lab: WP_Post[], projects: WP_Post[]}
	 */
	public function get_related( $post_id ) {
		return array(
			'notes'    => sayid_resolve_related( get_post_meta( $post_id, 'sayid_related_notes', true ) ),
			'articles' => sayid_resolve_related( get_post_meta( $post_id, 'sayid_related_articles', true ) ),
			'lab'      => sayid_resolve_related( get_post_meta( $post_id, 'sayid_related_lab', true ) ),
			'projects' => sayid_resolve_related( get_post_meta( $post_id, 'sayid_related_projects', true ) ),
		);
	}

	/**
	 * Flat list across all related types, for a single "related content" rail.
	 *
	 * @return WP_Post[]
	 */
	public function get_related_flat( $post_id, $limit = 6 ) {
		$grouped = $this->get_related( $post_id );
		$flat    = array_merge( $grouped['notes'], $grouped['articles'], $grouped['lab'], $grouped['projects'] );
		return array_slice( $flat, 0, $limit );
	}

	public function has_related( $post_id ) {
		foreach ( $this->get_related( $post_id ) as $group ) {
			if ( ! empty( $group ) ) {
				return true;
			}
		}
		return false;
	}
}
