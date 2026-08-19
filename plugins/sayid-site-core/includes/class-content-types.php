<?php
/**
 * Custom post types: Notes, Lab, Projects.
 *
 * Articles deliberately use WordPress's built-in `post` type rather than a
 * fourth custom post type. Rationale (see docs/12-plugin-reference.md):
 *   - Articles are long-form editorial content; `post` already ships with
 *     full RSS, sitemap, category/tag-shaped taxonomy support, and every
 *     theme/SEO plugin assumes `post` is the blog content type.
 *   - Re-labelling `post` as "نوشته‌ها / Articles" avoids a duplicated,
 *     weaker reimplementation of a content type WordPress already models
 *     well, in line with brief §51 ("publish faster, not engineer harder").
 *   - The shared `sayid_topic` taxonomy still applies to `post`, so Articles
 *     participate in the same cross-content topic browsing as Notes/Lab/
 *     Projects.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sayid_Core_Content_Types {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_filter( 'post_type_labels_post', array( $this, 'relabel_post_type' ) );
		add_action( 'admin_menu', array( $this, 'reorder_post_menu_icon' ) );
	}

	public function register_post_types() {
		$this->register_note();
		$this->register_lab();
		$this->register_project();
	}

	/**
	 * Re-labels the built-in `post` type as "Articles / نوشته‌ها" in the
	 * admin UI. The underlying post type, slug and REST route stay `post` /
	 * `posts` so RSS, the REST API and every ecosystem plugin keep working
	 * unmodified. Permalinks are moved to /articles/ (see register_post_types
	 * below is not needed for `post`; permalink base is set via `post_type`
	 * rewrite through the `post_link` filter is unnecessary — WordPress
	 * exposes a settings-based "Post" base; we set it programmatically to
	 * keep the Persian-facing URL clean without asking the site owner to
	 * edit Settings → Permalinks by hand).
	 */
	public function relabel_post_type( $labels ) {
		$labels->name          = __( 'نوشته‌ها', 'sayid-site-core' );
		$labels->singular_name = __( 'نوشته', 'sayid-site-core' );
		$labels->add_new_item  = __( 'افزودن نوشته جدید', 'sayid-site-core' );
		$labels->edit_item     = __( 'ویرایش نوشته', 'sayid-site-core' );
		$labels->all_items     = __( 'همه‌ی نوشته‌ها', 'sayid-site-core' );
		$labels->view_item     = __( 'مشاهده نوشته', 'sayid-site-core' );
		$labels->search_items  = __( 'جست‌وجوی نوشته‌ها', 'sayid-site-core' );
		$labels->not_found     = __( 'نوشته‌ای پیدا نشد', 'sayid-site-core' );
		$labels->menu_name     = __( 'نوشته‌ها', 'sayid-site-core' );
		return $labels;
	}

	public function reorder_post_menu_icon() {
		global $menu;
		if ( ! is_array( $menu ) ) {
			return;
		}
		foreach ( $menu as $key => $item ) {
			if ( isset( $item[2] ) && 'edit.php' === $item[2] ) {
				$menu[ $key ][6] = 'dashicons-edit-large'; // phpcs:ignore
			}
		}
	}

	private function register_note() {
		register_post_type(
			'sayid_note',
			array(
				'labels'              => array(
					'name'               => __( 'یادداشت‌ها', 'sayid-site-core' ),
					'singular_name'      => __( 'یادداشت', 'sayid-site-core' ),
					'add_new'            => __( 'یادداشت جدید', 'sayid-site-core' ),
					'add_new_item'       => __( 'افزودن یادداشت جدید', 'sayid-site-core' ),
					'edit_item'          => __( 'ویرایش یادداشت', 'sayid-site-core' ),
					'new_item'           => __( 'یادداشت جدید', 'sayid-site-core' ),
					'view_item'          => __( 'مشاهده یادداشت', 'sayid-site-core' ),
					'search_items'       => __( 'جست‌وجوی یادداشت‌ها', 'sayid-site-core' ),
					'not_found'          => __( 'یادداشتی پیدا نشد', 'sayid-site-core' ),
					'all_items'          => __( 'همه‌ی یادداشت‌ها', 'sayid-site-core' ),
					'menu_name'          => __( 'یادداشت‌ها', 'sayid-site-core' ),
				),
				'public'              => true,
				'show_in_rest'        => true,
				'menu_icon'           => 'dashicons-sticky',
				'menu_position'       => 21,
				'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'revisions', 'custom-fields' ),
				'has_archive'         => 'notes',
				'rewrite'             => array( 'slug' => 'note', 'with_front' => false ),
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
			)
		);
	}

	private function register_lab() {
		register_post_type(
			'sayid_lab',
			array(
				'labels'              => array(
					'name'               => __( 'آزمایشگاه', 'sayid-site-core' ),
					'singular_name'      => __( 'آیتم آزمایشگاه', 'sayid-site-core' ),
					'add_new'            => __( 'آیتم جدید', 'sayid-site-core' ),
					'add_new_item'       => __( 'افزودن آیتم آزمایشگاه', 'sayid-site-core' ),
					'edit_item'          => __( 'ویرایش آیتم آزمایشگاه', 'sayid-site-core' ),
					'new_item'           => __( 'آیتم جدید', 'sayid-site-core' ),
					'view_item'          => __( 'مشاهده آیتم', 'sayid-site-core' ),
					'search_items'       => __( 'جست‌وجوی آزمایشگاه', 'sayid-site-core' ),
					'not_found'          => __( 'آیتمی پیدا نشد', 'sayid-site-core' ),
					'all_items'          => __( 'همه‌ی آیتم‌ها', 'sayid-site-core' ),
					'menu_name'          => __( 'آزمایشگاه', 'sayid-site-core' ),
				),
				'public'              => true,
				'show_in_rest'        => true,
				'menu_icon'           => 'dashicons-hammer',
				'menu_position'       => 22,
				'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields' ),
				'has_archive'         => 'lab',
				'rewrite'             => array( 'slug' => 'lab', 'with_front' => false ),
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
			)
		);
	}

	private function register_project() {
		register_post_type(
			'sayid_project',
			array(
				'labels'              => array(
					'name'               => __( 'پروژه‌ها', 'sayid-site-core' ),
					'singular_name'      => __( 'پروژه', 'sayid-site-core' ),
					'add_new'            => __( 'پروژه جدید', 'sayid-site-core' ),
					'add_new_item'       => __( 'افزودن پروژه جدید', 'sayid-site-core' ),
					'edit_item'          => __( 'ویرایش پروژه', 'sayid-site-core' ),
					'new_item'           => __( 'پروژه جدید', 'sayid-site-core' ),
					'view_item'          => __( 'مشاهده پروژه', 'sayid-site-core' ),
					'search_items'       => __( 'جست‌وجوی پروژه‌ها', 'sayid-site-core' ),
					'not_found'          => __( 'پروژه‌ای پیدا نشد', 'sayid-site-core' ),
					'all_items'          => __( 'همه‌ی پروژه‌ها', 'sayid-site-core' ),
					'menu_name'          => __( 'پروژه‌ها', 'sayid-site-core' ),
				),
				'public'              => true,
				'show_in_rest'        => true,
				'menu_icon'           => 'dashicons-portfolio',
				'menu_position'       => 23,
				'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'excerpt' ),
				'has_archive'         => 'work',
				'rewrite'             => array( 'slug' => 'work', 'with_front' => false ),
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
			)
		);
	}
}
