<?php
/**
 * A small native contact form — this theme has no Elementor Pro Form
 * widget to lean on anymore, so page-contact.php needs a real, working
 * submission handler. Honeypot + nonce for basic spam/CSRF protection,
 * wp_mail() to the public contact address from brief §5.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SAYID_CONTACT_TO = 'i@moghadam.pro';

function sayid_handle_contact_submission() {
	$result = array( 'sent' => false, 'errors' => array() );

	if ( empty( $_POST['sayid_contact_submitted'] ) ) {
		return $result;
	}

	if ( ! isset( $_POST['sayid_contact_nonce'] ) || ! wp_verify_nonce( $_POST['sayid_contact_nonce'], 'sayid_contact' ) ) {
		$result['errors'][] = __( 'ارسال فرم منقضی شده — لطفاً صفحه را رفرش و دوباره امتحان کنید.', 'sayid' );
		return $result;
	}

	// Honeypot: a field real visitors never fill in, hidden from sighted
	// users via CSS (not `type="hidden"`, which some bots skip).
	if ( ! empty( $_POST['sayid_contact_website'] ) ) {
		$result['sent'] = true; // pretend success, drop silently
		return $result;
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$topic   = sanitize_text_field( wp_unslash( $_POST['topic'] ?? '' ) );
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

	$subject = sprintf( '[%s] %s', get_bloginfo( 'name' ), $topic ? $topic : __( 'پیام تماس جدید', 'sayid' ) );
	$body    = sprintf(
		"%s: %s\n%s: %s\n%s: %s\n\n%s\n%s",
		__( 'نام', 'sayid' ), $name,
		__( 'ایمیل', 'sayid' ), $email,
		__( 'موضوع', 'sayid' ), $topic,
		__( 'پیام', 'sayid' ),
		$message
	);
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	$result['sent'] = wp_mail( SAYID_CONTACT_TO, $subject, $body, $headers );
	if ( ! $result['sent'] ) {
		$result['errors'][] = __( 'ارسال ایمیل ناموفق بود — لطفاً مستقیم به i@moghadam.pro ایمیل بزن.', 'sayid' );
	}

	return $result;
}
