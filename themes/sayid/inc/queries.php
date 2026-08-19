<?php
/**
 * Homepage dynamic query rules — every homepage section is a query result,
 * never manually duplicated content (brief §42).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Both queries below order by a meta value that is optional (an item
 * without it should still appear, just without a specific position). A
 * naive top-level `meta_key` + `orderby => meta_value_num` silently turns
 * into a required meta_query clause in WP_Query, excluding any post that
 * doesn't already have that key set — so instead this uses a named
 * meta_query clause with an EXISTS/NOT EXISTS OR branch, which orders by
 * the value when present without excluding posts where it's absent.
 */
function sayid_priority_meta_query_branch() {
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

function sayid_query_selected_projects( $limit = 3 ) {
	return get_posts( array(
		'post_type'      => 'sayid_project',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => array( 'priority_clause' => 'ASC', 'date' => 'DESC' ),
		'meta_query'     => array(
			'relation' => 'AND',
			array( 'key' => 'sayid_featured_homepage', 'value' => '1', 'compare' => '=' ),
			sayid_priority_meta_query_branch(),
		),
	) );
}

function sayid_query_lab_items( $limit = 4 ) {
	return get_posts( array(
		'post_type'      => 'sayid_lab',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => array( 'priority_clause' => 'ASC', 'modified' => 'DESC' ),
		'meta_query'     => array(
			'relation' => 'AND',
			// Exclude only items explicitly marked archived — a Lab item
			// with no sayid_status meta at all (e.g. created via REST)
			// must still show up, not be silently dropped.
			array(
				'relation' => 'OR',
				array( 'key' => 'sayid_status', 'value' => 'archived', 'compare' => '!=' ),
				array( 'key' => 'sayid_status', 'compare' => 'NOT EXISTS' ),
			),
			sayid_priority_meta_query_branch(),
		),
	) );
}

function sayid_query_latest_notes( $limit = 5 ) {
	return get_posts( array(
		'post_type'      => 'sayid_note',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => 'date',
		'order'          => 'DESC',
	) );
}

/**
 * Homepage Articles: newest first. A manually featured article
 * (sayid_featured_homepage = 1) is pinned first when one exists, so the
 * "featured" concept still means something even though the section is now
 * a compact multi-post grid rather than one large single card.
 */
function sayid_query_articles( $limit = 3 ) {
	$featured = get_posts( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'meta_query'     => array(
			array( 'key' => 'sayid_featured_homepage', 'value' => '1', 'compare' => '=' ),
		),
	) );

	$remaining = max( 0, $limit - count( $featured ) );
	$rest      = $remaining ? get_posts( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $remaining,
		'post__not_in'   => wp_list_pluck( $featured, 'ID' ),
		'orderby'        => 'date',
		'order'          => 'DESC',
	) ) : array();

	return array_slice( array_merge( $featured, $rest ), 0, $limit );
}
