<?php
/**
 * Shared helper functions. Carried over unchanged from the sayid-site-core
 * plugin (see docs/16-final-implementation-report.md for the plugin → theme
 * migration note) — none of this was ever Elementor-specific.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sayid_lab_statuses() {
	return array(
		'idea'      => __( 'ایده', 'sayid' ),
		'reviewing' => __( 'در حال بررسی', 'sayid' ),
		'building'  => __( 'در حال ساخت', 'sayid' ),
		'beta'      => __( 'بتا', 'sayid' ),
		'shipped'   => __( 'منتشر شده', 'sayid' ),
		'paused'    => __( 'متوقف شده', 'sayid' ),
		'archived'  => __( 'آرشیو شده', 'sayid' ),
	);
}

function sayid_lab_status_label( $slug ) {
	$statuses = sayid_lab_statuses();
	return isset( $statuses[ $slug ] ) ? $statuses[ $slug ] : '';
}

function sayid_reading_time_minutes( $content ) {
	$text    = wp_strip_all_tags( $content );
	$words   = preg_split( '/\s+/u', trim( $text ) );
	$count   = is_array( $words ) ? count( array_filter( $words ) ) : 0;
	$minutes = (int) ceil( $count / 200 );
	return max( 1, $minutes );
}

function sayid_reading_time_label( $content ) {
	$minutes = sayid_reading_time_minutes( $content );
	/* translators: %s: number of minutes */
	return sprintf( _n( '%s دقیقه مطالعه', '%s دقیقه مطالعه', $minutes, 'sayid' ), sayid_to_persian_digits( $minutes ) );
}

/**
 * Convert ASCII digits to Persian digits for display-only contexts. Never
 * used on machine-readable values (IDs, dates in attributes, URLs).
 */
function sayid_to_persian_digits( $value ) {
	$fa = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	return strtr( (string) $value, array_combine( range( 0, 9 ), $fa ) );
}

/**
 * Both formatters go through plain `date_i18n()` rather than building the
 * string component-by-component, so a Jalali calendar plugin (e.g. "Parsi
 * Date"), which works by filtering `date_i18n`'s output, can convert these
 * to a real Shamsi day-month-year — without one, digits are still forced
 * to Persian numerals as the fallback.
 */
function sayid_format_date( $timestamp ) {
	return sayid_to_persian_digits( date_i18n( 'j F Y', $timestamp ) );
}

function sayid_format_date_short( $timestamp ) {
	return sayid_to_persian_digits( date_i18n( 'j F', $timestamp ) );
}

function sayid_resolve_related( $ids ) {
	if ( empty( $ids ) || ! is_array( $ids ) ) {
		return array();
	}
	$ids   = array_unique( array_map( 'absint', $ids ) );
	$posts = array();
	foreach ( $ids as $id ) {
		$post = get_post( $id );
		if ( $post && 'publish' === $post->post_status ) {
			$posts[] = $post;
		}
	}
	return $posts;
}

function sayid_cover_html( $attachment_id, $size = 'large', $alt_fallback = '' ) {
	if ( $attachment_id && wp_attachment_is_image( $attachment_id ) ) {
		$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		return wp_get_attachment_image(
			$attachment_id,
			$size,
			false,
			array(
				'alt'      => $alt ? $alt : $alt_fallback,
				'loading'  => 'lazy',
				'decoding' => 'async',
			)
		);
	}
	return '';
}

/**
 * Visitor IP address, for the Hero's "Your IP:" line (a deliberate,
 * confirmed piece of the design — see docs/16-final-implementation-report.md
 * "Theme pivot" section). Checks common reverse-proxy/CDN headers before
 * falling back to REMOTE_ADDR, since a site behind Cloudflare or a
 * load balancer would otherwise always show the proxy's own address.
 * Display-only: never used for access control or logging decisions.
 */
function sayid_get_visitor_ip() {
	$headers = array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' );
	foreach ( $headers as $header ) {
		if ( empty( $_SERVER[ $header ] ) ) {
			continue;
		}
		$value = sanitize_text_field( wp_unslash( $_SERVER[ $header ] ) );
		// X-Forwarded-For can be a comma-separated chain; the first entry
		// is the original client.
		$candidate = trim( explode( ',', $value )[0] );
		if ( filter_var( $candidate, FILTER_VALIDATE_IP ) ) {
			return $candidate;
		}
	}
	return '';
}

/**
 * Builds a `tel:` href from a human-entered phone number. A Persian
 * keyboard produces Persian (۰۱۲…) or Arabic-Indic (٠١٢…) digits, which
 * dialers can't parse in a tel: URI, so those are mapped to ASCII and
 * everything except digits and a leading + is dropped. The number is still
 * *displayed* exactly as entered — only the href is normalized.
 */
function sayid_tel_href( $phone ) {
	$digits = array(
		'۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
		'۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
		'٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
		'٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
	);
	$normalized = strtr( trim( $phone ), $digits );
	$plus       = ( 0 === strpos( $normalized, '+' ) ) ? '+' : '';
	return $plus . preg_replace( '/\D+/', '', $normalized );
}
