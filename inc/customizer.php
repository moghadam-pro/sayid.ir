<?php
/**
 * The Hero's copy is deliberately static, hard-coded in
 * template-parts/hero.php (brief: fixed content stays in the theme,
 * dynamic content is query-driven — see inc/render.php for the latter).
 * The one piece of the Hero that genuinely needs to change without a code
 * deploy is the portrait photo itself, so it gets a normal Customizer
 * image control instead.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'sayid_hero', array(
		'title'    => __( 'هیرو صفحه‌ی اصلی', 'sayid' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'sayid_hero_photo', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sayid_hero_photo', array(
		'label'    => __( 'عکس هیرو (پرتره)', 'sayid' ),
		'section'  => 'sayid_hero',
		'mime_type' => 'image',
	) ) );

	// Phone is optional and off by default — no real number was provided
	// during this build (brief §9: don't publish a private phone number
	// without being told to), so the Contact page's "you can call" option
	// only appears once a number is actually set here.
	$wp_customize->add_section( 'sayid_contact', array(
		'title'    => __( 'صفحه‌ی تماس', 'sayid' ),
		'priority' => 31,
	) );
	$wp_customize->add_setting( 'sayid_contact_phone', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sayid_contact_phone', array(
		'label'       => __( 'شماره تماس (اختیاری — خالی بمونه، گزینه‌ی «زنگ بزن» نشون داده نمی‌شه)', 'sayid' ),
		'section'     => 'sayid_contact',
		'type'        => 'text',
	) );
} );

/**
 * Attachment ID of the Hero photo, or 0 if not set yet — callers fall back
 * to a placeholder block (see template-parts/hero.php) rather than
 * breaking when nothing has been uploaded.
 */
function sayid_hero_photo_id() {
	return absint( get_theme_mod( 'sayid_hero_photo', 0 ) );
}

function sayid_contact_phone() {
	return get_theme_mod( 'sayid_contact_phone', '' );
}
