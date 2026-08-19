<?php
/**
 * Homepage dynamic query rules (brief §42 / docs/02 §6).
 *
 * Every homepage section is driven by a query, never by manually duplicated
 * content in Elementor. Publishing a Note, marking a Project as featured, or
 * changing Now updates the homepage automatically.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sayid_Core_Queries {

	/**
	 * A top-level `meta_key` (used to enable `orderby => meta_value_num`)
	 * silently becomes a required meta_query clause in WP_Query, excluding
	 * any post that doesn't already have that key set. This named
	 * EXISTS/NOT EXISTS OR-branch orders by the value when present without
	 * dropping posts where it's simply unset.
	 */
	private static function priority_meta_query_branch() {
		return array(
			'relation'        => 'OR',
			'priority_clause' => array(
				'key'     => 'sayid_homepage_priority',
				'compare' => 'EXISTS',
				'type'    => 'NUMERIC',
			),
			array(
				'key'     => 'sayid_homepage_priority',
				'compare' => 'NOT EXISTS',
			),
		);
	}

	/**
	 * Selected Work: Projects with sayid_featured_homepage = 1, ordered by
	 * sayid_homepage_priority ascending, newest first as a tiebreaker.
	 */
	public static function selected_projects( $limit = 3 ) {
		return get_posts( array(
			'post_type'      => 'sayid_project',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'orderby'        => array( 'priority_clause' => 'ASC', 'date' => 'DESC' ),
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'sayid_featured_homepage',
					'value'   => '1',
					'compare' => '=',
				),
				self::priority_meta_query_branch(),
			),
		) );
	}

	/**
	 * Lab: everything except Archived, ordered by homepage priority then
	 * most-recently-modified (a Lab item's "aliveness" is its update date).
	 */
	public static function lab_items( $limit = 4 ) {
		return get_posts( array(
			'post_type'      => 'sayid_lab',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'orderby'        => array( 'priority_clause' => 'ASC', 'modified' => 'DESC' ),
			'meta_query'     => array(
				'relation' => 'AND',
				// A Lab item with no sayid_status meta at all must still
				// show up, not be silently dropped by the `!=` compare.
				array(
					'relation' => 'OR',
					array( 'key' => 'sayid_status', 'value' => 'archived', 'compare' => '!=' ),
					array( 'key' => 'sayid_status', 'compare' => 'NOT EXISTS' ),
				),
				self::priority_meta_query_branch(),
			),
		) );
	}

	/**
	 * Latest Notes: newest first, no curation needed.
	 */
	public static function latest_notes( $limit = 5 ) {
		return get_posts( array(
			'post_type'      => 'sayid_note',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
	}

	/**
	 * Featured Article: manual override via sayid_featured_homepage on a
	 * post, falling back to the newest Article if nothing is curated.
	 */
	public static function featured_article() {
		$featured = get_posts( array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_query'     => array(
				array(
					'key'     => 'sayid_featured_homepage',
					'value'   => '1',
					'compare' => '=',
				),
			),
		) );
		if ( ! empty( $featured ) ) {
			return $featured[0];
		}

		$latest = get_posts( array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
		return ! empty( $latest ) ? $latest[0] : null;
	}
}
