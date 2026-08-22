<?php
/**
 * A small native submission handler for page-form.php — the reusable
 * "قالب فرم" page template, separate from page-contact.php (which stays
 * its own fixed, already-shipped Contact page). Same honeypot + nonce
 * pattern as inc/contact-form.php, but the recipient is per-page
 * (sayid_form_recipient_email meta, falling back to the site admin email)
 * instead of a single hardcoded address, so this template works for any
 * future simple lead/contact-style form without touching code.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sayid_handle_form_submission( $post_id ) {
	$result = array( 'sent' => false, 'errors' => array() );

	if ( empty( $_POST['sayid_form_submitted'] ) ) {
		return $result;
	}

	if ( ! isset( $_POST['sayid_form_nonce'] ) || ! wp_verify_nonce( $_POST['sayid_form_nonce'], 'sayid_form_' . $post_id ) ) {
		$result['errors'][] = __( 'ارسال فرم منقضی شده — لطفاً صفحه را رفرش و دوباره امتحان کنید.', 'sayid' );
		return $result;
	}

	// Honeypot: a field real visitors never fill in, hidden from sighted
	// users via CSS (not `type="hidden"`, which some bots skip).
	if ( ! empty( $_POST['sayid_form_website'] ) ) {
		$result['sent'] = true; // pretend success, drop silently
		return $result;
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( '' === $name ) {
		$result['errors'][] = __( 'نام را وارد کن.', 'sayid' );
	}
	if ( '' === $email || ! is_email( $email ) ) {
		$result['errors'][] = __( 'یک ایمیل معتبر وارد کن.', 'sayid' );
	}
	if ( '' === $message ) {
		$result['errors'][] = __( 'پیامت رو بنویس.', 'sayid' );
	}

	if ( ! empty( $result['errors'] ) ) {
		return $result;
	}

	$recipient = trim( (string) get_post_meta( $post_id, 'sayid_form_recipient_email', true ) );
	if ( '' === $recipient || ! is_email( $recipient ) ) {
		$recipient = get_option( 'admin_email' );
	}

	$subject = sprintf( '[%s] %s', get_bloginfo( 'name' ), get_the_title( $post_id ) );
	$body    = sprintf(
		"%s: %s\n%s: %s\n\n%s\n%s",
		__( 'نام', 'sayid' ), $name,
		__( 'ایمیل', 'sayid' ), $email,
		__( 'پیام', 'sayid' ),
		$message
	);
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	$result['sent'] = wp_mail( $recipient, $subject, $body, $headers );
	if ( ! $result['sent'] ) {
		$result['errors'][] = __( 'ارسال ایمیل ناموفق بود — دوباره امتحان کن.', 'sayid' );
	}

	return $result;
}
