<?php
/**
 * Shared taxonomy across Articles (native `post`), Notes, Lab and Projects.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'sayid_register_taxonomies' );
function sayid_register_taxonomies() {
	register_taxonomy(
		'sayid_topic',
		array( 'post', 'sayid_note', 'sayid_lab', 'sayid_project' ),
		array(
			'labels'            => array(
				'name'          => __( 'موضوع‌ها', 'sayid' ),
				'singular_name' => __( 'موضوع', 'sayid' ),
				'search_items'  => __( 'جست‌وجوی موضوع‌ها', 'sayid' ),
				'all_items'     => __( 'همه‌ی موضوع‌ها', 'sayid' ),
				'edit_item'     => __( 'ویرایش موضوع', 'sayid' ),
				'update_item'   => __( 'به‌روزرسانی موضوع', 'sayid' ),
				'add_new_item'  => __( 'افزودن موضوع جدید', 'sayid' ),
				'new_item_name' => __( 'نام موضوع جدید', 'sayid' ),
				'menu_name'     => __( 'موضوع‌ها', 'sayid' ),
			),
			'hierarchical'      => false,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'topic', 'with_front' => false ),
		)
	);

	sayid_seed_topics();
}

function sayid_seed_topics() {
	if ( get_option( 'sayid_topics_seeded' ) ) {
		return;
	}
	$topics = array(
		'طراحی محصول', 'تجربه کاربری', 'رابط کاربری', 'دیزاین سیستم',
		'فیگما', 'هوش مصنوعی', 'مهندسی محصول', 'وردپرس', 'فرانت‌اند',
		'RTL', 'دسترس‌پذیری', 'تحقیق', 'نمونه‌سازی',
	);
	foreach ( $topics as $topic ) {
		if ( ! term_exists( $topic, 'sayid_topic' ) ) {
			wp_insert_term( $topic, 'sayid_topic' );
		}
	}
	update_option( 'sayid_topics_seeded', 1 );
}

/**
 * A `sayid_topic` archive is a cross-content stream (Articles, Notes, Lab,
 * Projects), not just `post`, which is WordPress's default for a taxonomy
 * query.
 */
add_action( 'pre_get_posts', function ( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_tax( 'sayid_topic' ) ) {
		$query->set( 'post_type', array( 'post', 'sayid_note', 'sayid_lab', 'sayid_project' ) );
	}
} );
