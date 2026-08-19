<?php
/**
 * Cross-content relationship resolution (read side). Storage/UI lives in
 * meta-fields.php — see docs/02-content-model.md §4.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sayid_get_related( $post_id ) {
	$groups = array(
		'notes'    => sayid_resolve_related( get_post_meta( $post_id, 'sayid_related_notes', true ) ),
		'articles' => sayid_resolve_related( get_post_meta( $post_id, 'sayid_related_articles', true ) ),
		'lab'      => sayid_resolve_related( get_post_meta( $post_id, 'sayid_related_lab', true ) ),
		'projects' => sayid_resolve_related( get_post_meta( $post_id, 'sayid_related_projects', true ) ),
	);

	// Filter self-references defensively (the admin picker already excludes
	// the current post, but postmeta could have been edited directly).
	foreach ( $groups as $type => $posts ) {
		$groups[ $type ] = array_values( array_filter( $posts, function ( $post ) use ( $post_id ) {
			return (int) $post->ID !== (int) $post_id;
		} ) );
	}

	return $groups;
}

function sayid_has_related( $post_id ) {
	foreach ( sayid_get_related( $post_id ) as $group ) {
		if ( ! empty( $group ) ) {
			return true;
		}
	}
	return false;
}
