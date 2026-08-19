<?php
/**
 * Small admin-experience improvements that make publishing faster
 * (brief §61). Nothing here is required for the site to function.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sayid_Core_Admin {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_filter( 'manage_sayid_lab_posts_columns', array( $this, 'lab_columns' ) );
		add_action( 'manage_sayid_lab_posts_custom_column', array( $this, 'lab_column_content' ), 10, 2 );

		add_filter( 'manage_sayid_project_posts_columns', array( $this, 'project_columns' ) );
		add_action( 'manage_sayid_project_posts_custom_column', array( $this, 'project_column_content' ), 10, 2 );

		add_filter( 'manage_post_posts_columns', array( $this, 'article_columns' ) );
		add_action( 'manage_post_posts_custom_column', array( $this, 'article_column_content' ), 10, 2 );
	}

	public function lab_columns( $columns ) {
		return $this->insert_after( $columns, 'title', array(
			'sayid_status' => __( 'وضعیت', 'sayid-site-core' ),
		) );
	}

	public function lab_column_content( $column, $post_id ) {
		if ( 'sayid_status' === $column ) {
			echo esc_html( sayid_lab_status_label( get_post_meta( $post_id, 'sayid_status', true ) ) );
		}
	}

	public function project_columns( $columns ) {
		return $this->insert_after( $columns, 'title', array(
			'sayid_featured' => __( 'صفحه‌ی اصلی', 'sayid-site-core' ),
		) );
	}

	public function project_column_content( $column, $post_id ) {
		if ( 'sayid_featured' === $column ) {
			echo get_post_meta( $post_id, 'sayid_featured_homepage', true ) // phpcs:ignore
				? '<span style="color:#1a7f37">●</span> ' . esc_html__( 'فعال', 'sayid-site-core' )
				: '—';
		}
	}

	public function article_columns( $columns ) {
		return $this->insert_after( $columns, 'title', array(
			'sayid_featured' => __( 'نوشته منتخب', 'sayid-site-core' ),
		) );
	}

	public function article_column_content( $column, $post_id ) {
		if ( 'sayid_featured' === $column ) {
			echo get_post_meta( $post_id, 'sayid_featured_homepage', true ) // phpcs:ignore
				? '<span style="color:#1a7f37">●</span>'
				: '—';
		}
	}

	private function insert_after( $columns, $after_key, $new_columns ) {
		$result = array();
		foreach ( $columns as $key => $label ) {
			$result[ $key ] = $label;
			if ( $key === $after_key ) {
				$result = array_merge( $result, $new_columns );
			}
		}
		return $result;
	}
}
