<?php
/**
 * Custom post types: Lab, Projects. Articles use the built-in `post` type,
 * relabeled — see docs/12-plugin-reference.md for the rationale (unchanged
 * by the plugin → theme migration). Notes used to be a third CPT here too;
 * since Notes and Articles are editorially the same thing (a `post`, just
 * shorter), that was a duplicate content model — Notes are now just `post`s
 * in the "یادداشت" category (see sayid_seed_notes_category() below and
 * inc/queries.php's sayid_query_latest_notes()).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'sayid_register_post_types' );
function sayid_register_post_types() {
	register_post_type( 'sayid_lab', array(
		'labels'            => array(
			'name'          => __( 'آزمایشگاه', 'sayid' ),
			'singular_name' => __( 'آیتم آزمایشگاه', 'sayid' ),
			'add_new'       => __( 'آیتم جدید', 'sayid' ),
			'add_new_item'  => __( 'افزودن آیتم آزمایشگاه', 'sayid' ),
			'edit_item'     => __( 'ویرایش آیتم آزمایشگاه', 'sayid' ),
			'new_item'      => __( 'آیتم جدید', 'sayid' ),
			'view_item'     => __( 'مشاهده آیتم', 'sayid' ),
			'search_items'  => __( 'جست‌وجوی آزمایشگاه', 'sayid' ),
			'not_found'     => __( 'آیتمی پیدا نشد', 'sayid' ),
			'all_items'     => __( 'همه‌ی آیتم‌ها', 'sayid' ),
			'menu_name'     => __( 'آزمایشگاه', 'sayid' ),
		),
		'public'            => true,
		'show_in_rest'      => true,
		'menu_icon'         => 'dashicons-hammer',
		'menu_position'     => 22,
		'supports'          => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields' ),
		'has_archive'       => 'lab',
		'rewrite'           => array( 'slug' => 'lab', 'with_front' => false ),
	) );

	register_post_type( 'sayid_project', array(
		'labels'            => array(
			'name'          => __( 'پروژه‌ها', 'sayid' ),
			'singular_name' => __( 'پروژه', 'sayid' ),
			'add_new'       => __( 'پروژه جدید', 'sayid' ),
			'add_new_item'  => __( 'افزودن پروژه جدید', 'sayid' ),
			'edit_item'     => __( 'ویرایش پروژه', 'sayid' ),
			'new_item'      => __( 'پروژه جدید', 'sayid' ),
			'view_item'     => __( 'مشاهده پروژه', 'sayid' ),
			'search_items'  => __( 'جست‌وجوی پروژه‌ها', 'sayid' ),
			'not_found'     => __( 'پروژه‌ای پیدا نشد', 'sayid' ),
			'all_items'     => __( 'همه‌ی پروژه‌ها', 'sayid' ),
			'menu_name'     => __( 'پروژه‌ها', 'sayid' ),
		),
		'public'            => true,
		'show_in_rest'      => true,
		'menu_icon'         => 'dashicons-portfolio',
		'menu_position'     => 23,
		'supports'          => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'excerpt' ),
		'has_archive'       => 'work',
		'rewrite'           => array( 'slug' => 'work', 'with_front' => false ),
	) );
}

add_filter( 'post_type_labels_post', function ( $labels ) {
	$labels->name          = __( 'نوشته‌ها', 'sayid' );
	$labels->singular_name = __( 'نوشته', 'sayid' );
	$labels->add_new_item  = __( 'افزودن نوشته جدید', 'sayid' );
	$labels->edit_item     = __( 'ویرایش نوشته', 'sayid' );
	$labels->all_items     = __( 'همه‌ی نوشته‌ها', 'sayid' );
	$labels->view_item     = __( 'مشاهده نوشته', 'sayid' );
	$labels->search_items  = __( 'جست‌وجوی نوشته‌ها', 'sayid' );
	$labels->not_found     = __( 'نوشته‌ای پیدا نشد', 'sayid' );
	$labels->menu_name     = __( 'نوشته‌ها', 'sayid' );
	return $labels;
} );

/**
 * Notes are `post`s in this one fixed-slug category, seeded once so the
 * homepage/query side (sayid_notes_category_id()) has a reliable category
 * to point at without asking anyone to get a slug exactly right by hand —
 * just tag posts "یادداشت" from the normal category picker.
 */
add_action( 'init', function () {
	if ( get_option( 'sayid_notes_category_seeded' ) ) {
		return;
	}
	if ( ! get_category_by_slug( 'note' ) ) {
		wp_insert_term( __( 'یادداشت', 'sayid' ), 'category', array( 'slug' => 'note' ) );
	}
	update_option( 'sayid_notes_category_seeded', 1 );
} );

function sayid_notes_category_id() {
	static $id = null;
	if ( null === $id ) {
		$term = get_category_by_slug( 'note' );
		$id   = $term ? (int) $term->term_id : 0;
	}
	return $id;
}
