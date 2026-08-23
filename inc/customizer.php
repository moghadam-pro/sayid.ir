<?php
/**
 * Homepage editing surface: Appearance → Customize. Every control here
 * falls back to the original confirmed copy when untouched, so nothing
 * has to be filled in before the homepage works correctly.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sayid_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === $checked ) ? true : false;
}

add_action( 'customize_register', function ( $wp_customize ) {
	/** ---------- Hero ---------- */
	$wp_customize->add_section( 'sayid_hero', array(
		'title'    => __( 'هیرو صفحه‌ی اصلی', 'sayid' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'sayid_hero_photo', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sayid_hero_photo', array(
		'label'     => __( 'عکس هیرو (پرتره)', 'sayid' ),
		'section'   => 'sayid_hero',
		'mime_type' => 'image',
	) ) );

	$hero_text_fields = array(
		'sayid_hero_greeting'    => array( __( 'متن سلام (پیش از اسم)', 'sayid' ), 'text' ),
		'sayid_hero_name'        => array( __( 'اسم', 'sayid' ), 'text' ),
		'sayid_hero_name_suffix' => array( __( 'پسوند اسم (مثلاً «هستم»)', 'sayid' ), 'text' ),
		'sayid_hero_role'        => array( __( 'عنوان شغلی', 'sayid' ), 'text' ),
		'sayid_hero_lede'        => array( __( 'توضیح زیر عنوان شغلی', 'sayid' ), 'textarea' ),
		'sayid_hero_cta_label'   => array( __( 'متن دکمه', 'sayid' ), 'text' ),
		'sayid_hero_rotator_phrases' => array( __( 'عبارت‌های چرخشی (هرکدوم در یک خط)', 'sayid' ), 'textarea' ),
	);
	foreach ( $hero_text_fields as $key => $field ) {
		list( $label, $type ) = $field;
		$wp_customize->add_setting( $key, array(
			'default'           => '',
			'sanitize_callback' => 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field',
		) );
		$wp_customize->add_control( $key, array(
			'label'   => $label,
			'section' => 'sayid_hero',
			'type'    => $type,
		) );
	}

	/** ---------- Lab ("چیزهایی که می‌سازم") ---------- */
	$wp_customize->add_section( 'sayid_lab_section', array(
		'title'    => __( 'بخش چیزهایی که می‌سازم', 'sayid' ),
		'priority' => 32,
	) );
	$wp_customize->add_setting( 'sayid_lab_title', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sayid_lab_title', array(
		'label'   => __( 'عنوان', 'sayid' ),
		'section' => 'sayid_lab_section',
		'type'    => 'text',
	) );
	$wp_customize->add_setting( 'sayid_lab_description', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'sayid_lab_description', array(
		'label'   => __( 'توضیح', 'sayid' ),
		'section' => 'sayid_lab_section',
		'type'    => 'textarea',
	) );
	$wp_customize->add_setting( 'sayid_lab_count', array(
		'default'           => 6,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'sayid_lab_count', array(
		'label'       => __( 'تعداد نمایش', 'sayid' ),
		'section'     => 'sayid_lab_section',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 1, 'max' => 12 ),
	) );

	/** ---------- Signature ("طرز فکر") ---------- */
	$wp_customize->add_section( 'sayid_signature_section', array(
		'title'    => __( 'بخش طرز فکر', 'sayid' ),
		'priority' => 33,
	) );
	$wp_customize->add_setting( 'sayid_signature_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'sayid_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'sayid_signature_enabled', array(
		'label'   => __( 'نمایش این بخش در صفحه‌ی اصلی', 'sayid' ),
		'section' => 'sayid_signature_section',
		'type'    => 'checkbox',
	) );
	$signature_text_fields = array(
		'sayid_signature_eyebrow' => array( __( 'برچسب بالای عنوان', 'sayid' ), 'text' ),
		'sayid_signature_title'   => array( __( 'عنوان', 'sayid' ), 'text' ),
		'sayid_signature_thesis'  => array( __( 'جمله‌ی اصلی', 'sayid' ), 'textarea' ),
	);
	foreach ( $signature_text_fields as $key => $field ) {
		list( $label, $type ) = $field;
		$wp_customize->add_setting( $key, array(
			'default'           => '',
			'sanitize_callback' => 'textarea' === $type ? 'sanitize_textarea_field' : 'sanitize_text_field',
		) );
		$wp_customize->add_control( $key, array(
			'label'   => $label,
			'section' => 'sayid_signature_section',
			'type'    => $type,
		) );
	}

	/** ---------- Articles ("نوشته‌ها") ---------- */
	$wp_customize->add_section( 'sayid_articles_section', array(
		'title'    => __( 'بخش نوشته‌ها', 'sayid' ),
		'priority' => 34,
	) );
	$wp_customize->add_setting( 'sayid_articles_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'sayid_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'sayid_articles_enabled', array(
		'label'   => __( 'نمایش این بخش در صفحه‌ی اصلی', 'sayid' ),
		'section' => 'sayid_articles_section',
		'type'    => 'checkbox',
	) );

	/** ---------- Notes ("یادداشت‌های تازه") ---------- */
	$wp_customize->add_section( 'sayid_notes_section', array(
		'title'    => __( 'بخش یادداشت‌های تازه', 'sayid' ),
		'priority' => 34,
	) );
	$wp_customize->add_setting( 'sayid_notes_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'sayid_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'sayid_notes_enabled', array(
		'label'   => __( 'نمایش این بخش در صفحه‌ی اصلی', 'sayid' ),
		'section' => 'sayid_notes_section',
		'type'    => 'checkbox',
	) );
	$wp_customize->add_setting( 'sayid_notes_count', array(
		'default'           => 5,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'sayid_notes_count', array(
		'label'       => __( 'تعداد نمایش', 'sayid' ),
		'section'     => 'sayid_notes_section',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 1, 'max' => 20 ),
	) );

	/** ---------- Connect ("حرف بزنیم") ---------- */
	$wp_customize->add_section( 'sayid_connect_section', array(
		'title'    => __( 'بخش حرف بزنیم', 'sayid' ),
		'priority' => 35,
	) );
	$connect_text_fields = array(
		'sayid_connect_title'       => array( __( 'عنوان', 'sayid' ), 'text' ),
		'sayid_connect_subtitle'    => array( __( 'عنوان دوم', 'sayid' ), 'text' ),
		'sayid_connect_description' => array( __( 'توضیح', 'sayid' ), 'textarea' ),
		'sayid_connect_btn1_label'  => array( __( 'متن کلید اول', 'sayid' ), 'text' ),
		'sayid_connect_btn1_url'    => array( __( 'لینک کلید اول', 'sayid' ), 'url' ),
		'sayid_connect_btn2_label'  => array( __( 'متن کلید دوم', 'sayid' ), 'text' ),
		'sayid_connect_btn2_url'    => array( __( 'لینک کلید دوم', 'sayid' ), 'url' ),
	);
	foreach ( $connect_text_fields as $key => $field ) {
		list( $label, $type ) = $field;
		$sanitize = 'sanitize_text_field';
		if ( 'textarea' === $type ) {
			$sanitize = 'sanitize_textarea_field';
		} elseif ( 'url' === $type ) {
			$sanitize = 'esc_url_raw';
		}
		$wp_customize->add_setting( $key, array(
			'default'           => '',
			'sanitize_callback' => $sanitize,
		) );
		$wp_customize->add_control( $key, array(
			'label'   => $label,
			'section' => 'sayid_connect_section',
			'type'    => 'url' === $type ? 'url' : $type,
		) );
	}

	// Phone is optional and off by default — no real number was provided
	// during this build, so the Contact page's "you can call" option only
	// appears once a number is actually set here.
	$wp_customize->add_section( 'sayid_contact', array(
		'title'    => __( 'صفحه‌ی تماس', 'sayid' ),
		'priority' => 36,
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

	/** ---------- Header ---------- */
	$wp_customize->add_section( 'sayid_header_section', array(
		'title'       => __( 'هدر', 'sayid' ),
		'priority'    => 37,
		'description' => __( 'منوی هدر از Appearance → Menus (یا همین Customizer → Menus) انتخاب می‌شه.', 'sayid' ),
	) );
	$wp_customize->add_setting( 'sayid_header_role_label', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'sayid_header_role_label', array(
		'label'   => __( 'برچسب عنوان شغلی کنار لوگو', 'sayid' ),
		'section' => 'sayid_header_section',
		'type'    => 'text',
	) );
	$header_elements = array(
		'sayid_header_order_nav'    => __( 'ترتیب منوی اصلی', 'sayid' ),
		'sayid_header_order_mark'   => __( 'ترتیب لوگو', 'sayid' ),
		'sayid_header_order_switch' => __( 'ترتیب کلید تغییر تم', 'sayid' ),
	);
	$header_defaults = array(
		'sayid_header_order_nav'    => '1',
		'sayid_header_order_mark'   => '2',
		'sayid_header_order_switch' => '3',
	);
	foreach ( $header_elements as $key => $label ) {
		$wp_customize->add_setting( $key, array(
			'default'           => $header_defaults[ $key ],
			'sanitize_callback' => 'sayid_sanitize_order',
		) );
		$wp_customize->add_control( $key, array(
			'label'   => $label,
			'section' => 'sayid_header_section',
			'type'    => 'select',
			'choices' => array( '1' => '1', '2' => '2', '3' => '3' ),
		) );
	}

	/** ---------- Footer ---------- */
	$wp_customize->add_section( 'sayid_footer_section', array(
		'title'    => __( 'فوتر', 'sayid' ),
		'priority' => 38,
	) );
	$wp_customize->add_setting( 'sayid_footer_copyright', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'sayid_footer_copyright', array(
		'label'   => __( 'متن پایین فوتر', 'sayid' ),
		'section' => 'sayid_footer_section',
		'type'    => 'textarea',
	) );
	$footer_elements = array(
		'sayid_footer_order_links'  => __( 'ترتیب لینک‌ها', 'sayid' ),
		'sayid_footer_order_social' => __( 'ترتیب آیکن‌های سوشال', 'sayid' ),
	);
	$footer_defaults = array(
		'sayid_footer_order_links'  => '1',
		'sayid_footer_order_social' => '2',
	);
	foreach ( $footer_elements as $key => $label ) {
		$wp_customize->add_setting( $key, array(
			'default'           => $footer_defaults[ $key ],
			'sanitize_callback' => 'sayid_sanitize_order',
		) );
		$wp_customize->add_control( $key, array(
			'label'   => $label,
			'section' => 'sayid_footer_section',
			'type'    => 'select',
			'choices' => array( '1' => '1', '2' => '2' ),
		) );
	}
} );

function sayid_sanitize_order( $value ) {
	$n = absint( $value );
	return $n ? (string) $n : '1';
}

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

/**
 * A theme_mod that's blank (never touched, or explicitly cleared) falls
 * back to $default — used throughout render.php/hero.php so every
 * Customizer field is optional.
 */
function sayid_theme_text( $key, $default = '' ) {
	$value = get_theme_mod( $key, '' );
	return ( '' !== trim( (string) $value ) ) ? $value : $default;
}
