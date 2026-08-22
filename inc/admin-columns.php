<?php
/**
 * Small admin-list-screen improvements for faster publishing (brief §61).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sayid_insert_column_after( $columns, $after_key, $new_columns ) {
	$result = array();
	foreach ( $columns as $key => $label ) {
		$result[ $key ] = $label;
		if ( $key === $after_key ) {
			$result = array_merge( $result, $new_columns );
		}
	}
	return $result;
}

add_filter( 'manage_sayid_lab_posts_columns', function ( $columns ) {
	return sayid_insert_column_after( $columns, 'title', array( 'sayid_status' => __( 'وضعیت', 'sayid' ) ) );
} );
add_action( 'manage_sayid_lab_posts_custom_column', function ( $column, $post_id ) {
	if ( 'sayid_status' === $column ) {
		echo esc_html( sayid_lab_status_label( get_post_meta( $post_id, 'sayid_status', true ) ) );
	}
}, 10, 2 );

add_filter( 'manage_sayid_project_posts_columns', function ( $columns ) {
	return sayid_insert_column_after( $columns, 'title', array( 'sayid_featured' => __( 'صفحه‌ی اصلی', 'sayid' ) ) );
} );
add_action( 'manage_sayid_project_posts_custom_column', function ( $column, $post_id ) {
	if ( 'sayid_featured' === $column ) {
		echo get_post_meta( $post_id, 'sayid_featured_homepage', true ) // phpcs:ignore
			? '<span style="color:#1a7f37">●</span> ' . esc_html__( 'فعال', 'sayid' )
			: '—';
	}
}, 10, 2 );

add_filter( 'manage_post_posts_columns', function ( $columns ) {
	return sayid_insert_column_after( $columns, 'title', array( 'sayid_featured' => __( 'نوشته منتخب', 'sayid' ) ) );
} );
add_action( 'manage_post_posts_custom_column', function ( $column, $post_id ) {
	if ( 'sayid_featured' === $column ) {
		echo get_post_meta( $post_id, 'sayid_featured_homepage', true ) // phpcs:ignore
			? '<span style="color:#1a7f37">●</span>'
			: '—';
	}
}, 10, 2 );
