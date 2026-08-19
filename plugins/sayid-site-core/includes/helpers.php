<?php
/**
 * Shared helper functions. Framework-free, small, and reused by queries,
 * render functions, shortcodes and Elementor widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Lab / build status vocabulary. Kept as a fixed whitelist (postmeta),
 * not a taxonomy, because the set is closed and small.
 */
function sayid_lab_statuses() {
	return array(
		'idea'         => __( 'ایده', 'sayid-site-core' ),
		'reviewing'    => __( 'در حال بررسی', 'sayid-site-core' ),
		'building'     => __( 'در حال ساخت', 'sayid-site-core' ),
		'beta'         => __( 'بتا', 'sayid-site-core' ),
		'shipped'      => __( 'منتشر شده', 'sayid-site-core' ),
		'paused'       => __( 'متوقف شده', 'sayid-site-core' ),
		'archived'     => __( 'آرشیو شده', 'sayid-site-core' ),
	);
}

function sayid_lab_status_label( $slug ) {
	$statuses = sayid_lab_statuses();
	return isset( $statuses[ $slug ] ) ? $statuses[ $slug ] : '';
}

/**
 * Approximate Persian reading time. Persian averages ~180-220 words/min
 * for online reading; we use word-ish token counting via whitespace split
 * since Persian does not use Latin word-boundary rules reliably with
 * str_word_count().
 */
function sayid_reading_time_minutes( $content ) {
	$text  = wp_strip_all_tags( $content );
	$words = preg_split( '/\s+/u', trim( $text ) );
	$count = is_array( $words ) ? count( array_filter( $words ) ) : 0;
	$minutes = (int) ceil( $count / 200 );
	return max( 1, $minutes );
}

function sayid_reading_time_label( $content ) {
	$minutes = sayid_reading_time_minutes( $content );
	/* translators: %s: number of minutes */
	return sprintf( _n( '%s دقیقه مطالعه', '%s دقیقه مطالعه', $minutes, 'sayid-site-core' ), sayid_to_persian_digits( $minutes ) );
}

/**
 * Convert ASCII digits to Persian digits for display-only contexts.
 * Never used on machine-readable values (IDs, dates in attributes, URLs).
 */
function sayid_to_persian_digits( $value ) {
	$fa = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	return strtr( (string) $value, array_combine( range( 0, 9 ), $fa ) );
}

/**
 * Persian month names for lightweight display formatting. sayid.ir does not
 * require a full Jalali calendar system for v1; Gregorian dates are shown
 * with Persian month names and Persian digits, which reads naturally and
 * avoids taking a dependency on a Jalali conversion library. If a Jalali
 * calendar becomes a real requirement, isolate the conversion behind this
 * function so callers do not need to change.
 */
function sayid_format_date( $timestamp ) {
	$months = array(
		1 => 'ژانویه', 2 => 'فوریه', 3 => 'مارس', 4 => 'آوریل', 5 => 'می',
		6 => 'ژوئن', 7 => 'ژوئیه', 8 => 'اوت', 9 => 'سپتامبر', 10 => 'اکتبر',
		11 => 'نوامبر', 12 => 'دسامبر',
	);
	$day   = sayid_to_persian_digits( date_i18n( 'j', $timestamp ) );
	$month = $months[ (int) date_i18n( 'n', $timestamp ) ];
	$year  = sayid_to_persian_digits( date_i18n( 'Y', $timestamp ) );
	return "{$day} {$month} {$year}";
}

/**
 * Short date used in dense list rows (e.g. Latest Notes).
 */
function sayid_format_date_short( $timestamp ) {
	$months = array(
		1 => 'ژانویه', 2 => 'فوریه', 3 => 'مارس', 4 => 'آوریل', 5 => 'می',
		6 => 'ژوئن', 7 => 'ژوئیه', 8 => 'اوت', 9 => 'سپتامبر', 10 => 'اکتبر',
		11 => 'نوامبر', 12 => 'دسامبر',
	);
	$day   = sayid_to_persian_digits( date_i18n( 'j', $timestamp ) );
	$month = $months[ (int) date_i18n( 'n', $timestamp ) ];
	return "{$day} {$month}";
}

/**
 * Resolve an array of related post IDs (stored as postmeta) into a safe,
 * de-duplicated list of published posts only.
 */
function sayid_resolve_related( $ids ) {
	if ( empty( $ids ) || ! is_array( $ids ) ) {
		return array();
	}
	$ids = array_unique( array_map( 'absint', $ids ) );
	$posts = array();
	foreach ( $ids as $id ) {
		$post = get_post( $id );
		if ( $post && 'publish' === $post->post_status ) {
			$posts[] = $post;
		}
	}
	return $posts;
}

/**
 * Safe wrapper for outputting an <img> from an attachment ID with a
 * documented fallback so templates never break on a missing cover.
 */
function sayid_cover_html( $attachment_id, $size = 'large', $alt_fallback = '' ) {
	if ( $attachment_id && wp_attachment_is_image( $attachment_id ) ) {
		$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		return wp_get_attachment_image(
			$attachment_id,
			$size,
			false,
			array(
				'alt'     => $alt ? $alt : $alt_fallback,
				'loading' => 'lazy',
				'decoding' => 'async',
			)
		);
	}
	return '';
}
