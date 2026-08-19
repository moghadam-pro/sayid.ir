<?php
/**
 * Single/archive templates for the custom post types and the shared topic
 * taxonomy.
 *
 * Elementor Pro's Theme Builder (DB-stored templates) would normally own
 * these, but this build has no live WordPress/Elementor database to create
 * Theme Builder templates in (see docs/16-final-implementation-report.md).
 * Rather than fabricate Elementor templates that don't exist, these are real
 * coded PHP templates using the same design tokens/CSS classes and the same
 * Sayid_Core_Render helpers as the Elementor widgets, so the site is fully
 * functional today. If/when Elementor Pro Theme Builder access exists,
 * docs/13-elementor-build-guide.md documents how to rebuild each one
 * visually; `sayid_templates_disable_for( $post_type )` (below) lets you
 * turn the coded template off per post type once its Theme Builder
 * replacement is published, without touching this file.
 *
 * A theme (or child theme) can still override any of these by placing a
 * same-named file in its own root — locate_template() is checked first.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sayid_Core_Templates {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_filter( 'template_include', array( $this, 'template_include' ) );
	}

	/**
	 * Post types (or 'sayid_topic' for the taxonomy) whose coded template
	 * should be skipped, e.g. after an Elementor Pro Theme Builder template
	 * has been published for it. Filterable so this can be flipped from
	 * `functions.php` or another plugin without editing core files.
	 */
	private function is_disabled( $key ) {
		$disabled = apply_filters( 'sayid_disabled_templates', array() );
		return in_array( $key, (array) $disabled, true );
	}

	public function template_include( $template ) {
		$map = array(
			'is_singular:sayid_note'       => 'single-sayid_note.php',
			'is_singular:sayid_lab'        => 'single-sayid_lab.php',
			'is_singular:sayid_project'    => 'single-sayid_project.php',
			'is_post_type_archive:sayid_note'    => 'archive-sayid_note.php',
			'is_post_type_archive:sayid_lab'     => 'archive-sayid_lab.php',
			'is_post_type_archive:sayid_project' => 'archive-sayid_project.php',
			'is_tax:sayid_topic'            => 'taxonomy-sayid_topic.php',
		);

		foreach ( $map as $condition => $file ) {
			list( $check, $key ) = explode( ':', $condition );
			$matches = false;
			if ( 'is_singular' === $check ) {
				$matches = is_singular( $key );
			} elseif ( 'is_post_type_archive' === $check ) {
				$matches = is_post_type_archive( $key );
			} elseif ( 'is_tax' === $check ) {
				$matches = is_tax( $key );
			}

			if ( $matches && ! $this->is_disabled( $key ) ) {
				$theme_override = locate_template( $file );
				if ( $theme_override ) {
					return $theme_override;
				}
				$plugin_template = SAYID_CORE_PATH . 'templates/' . $file;
				if ( file_exists( $plugin_template ) ) {
					return $plugin_template;
				}
			}
		}

		return $template;
	}
}
