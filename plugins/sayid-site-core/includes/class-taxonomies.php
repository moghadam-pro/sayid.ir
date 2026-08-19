<?php
/**
 * Shared taxonomy across Articles (native `post`), Notes, Lab and Projects.
 * One topic system, not four duplicated ones, per docs/02-content-model.md §3.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sayid_Core_Taxonomies {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'pre_get_posts', array( $this, 'include_all_types_on_topic_archive' ) );
	}

	/**
	 * A `sayid_topic` archive should be a cross-content stream (Articles,
	 * Notes, Lab, Projects), not just `post`, which is WordPress's default
	 * for a taxonomy query. See docs/02-content-model.md §3.
	 */
	public function include_all_types_on_topic_archive( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}
		if ( $query->is_tax( 'sayid_topic' ) ) {
			$query->set( 'post_type', array( 'post', 'sayid_note', 'sayid_lab', 'sayid_project' ) );
		}
	}

	/**
	 * Seed topics. Runs once; editors are free to add/rename/remove terms
	 * afterwards from the normal taxonomy screen.
	 */
	public function seed_topics() {
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

	public function register_taxonomies() {
		register_taxonomy(
			'sayid_topic',
			array( 'post', 'sayid_note', 'sayid_lab', 'sayid_project' ),
			array(
				'labels'            => array(
					'name'          => __( 'موضوع‌ها', 'sayid-site-core' ),
					'singular_name' => __( 'موضوع', 'sayid-site-core' ),
					'search_items'  => __( 'جست‌وجوی موضوع‌ها', 'sayid-site-core' ),
					'all_items'     => __( 'همه‌ی موضوع‌ها', 'sayid-site-core' ),
					'edit_item'     => __( 'ویرایش موضوع', 'sayid-site-core' ),
					'update_item'   => __( 'به‌روزرسانی موضوع', 'sayid-site-core' ),
					'add_new_item'  => __( 'افزودن موضوع جدید', 'sayid-site-core' ),
					'new_item_name' => __( 'نام موضوع جدید', 'sayid-site-core' ),
					'menu_name'     => __( 'موضوع‌ها', 'sayid-site-core' ),
				),
				'hierarchical'      => false,
				'public'            => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'topic', 'with_front' => false ),
			)
		);

		$this->seed_topics();
	}
}
