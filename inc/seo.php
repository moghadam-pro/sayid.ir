<?php
/**
 * Lightweight SEO fallbacks + RankMath/Yoast/AIOSEO compatibility.
 *
 * Whenever a dedicated SEO plugin is active, it already owns meta
 * description / Open Graph / Twitter Card / structured-data output —
 * adding our own on top would just duplicate (and potentially conflict
 * with) what it emits, which is the opposite of "compatible". So
 * everything here checks sayid_seo_should_output() first and does
 * nothing when a plugin is running; it only fills the gap on a bare
 * WordPress install with no SEO plugin at all. `add_theme_support(
 * 'title-tag' )` (inc/setup.php) already lets any of them fully own the
 * <title> tag without a hardcoded one to fight.
 *
 * Per explicit direction, none of this runs on page-blank.php ("قالب
 * خام") — that template is a deliberately untouched raw canvas, and SEO
 * meta on a self-contained embedded page (e.g. the /magic game) doesn't
 * make sense anyway.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sayid_seo_plugin_active() {
	return defined( 'RANK_MATH_VERSION' ) || defined( 'WPSEO_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

function sayid_seo_should_output() {
	return ! sayid_seo_plugin_active() && ! is_page_template( 'page-blank.php' );
}

/**
 * A short plain-text description for the current view — the one thing
 * search engines and social previews need that WordPress core has no
 * built-in output for.
 */
function sayid_seo_description() {
	if ( is_front_page() ) {
		return sayid_theme_text( 'sayid_hero_lede', get_bloginfo( 'description' ) );
	}
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			if ( '' !== $post->post_excerpt ) {
				return wp_strip_all_tags( $post->post_excerpt );
			}
			return wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 );
		}
	}
	if ( is_category() || is_tax() || is_archive() ) {
		$description = term_description();
		if ( $description ) {
			return wp_strip_all_tags( $description );
		}
	}
	return get_bloginfo( 'description' );
}

function sayid_seo_current_url() {
	global $wp;
	return home_url( add_query_arg( array(), $wp->request ) );
}

/**
 * Post thumbnail on a single view, falling back to the Hero photo
 * elsewhere — there's always at least one representative image on this
 * site if the Hero photo has been uploaded.
 */
function sayid_seo_og_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'sayid-featured' );
		if ( $image ) {
			return $image[0];
		}
	}
	$hero_photo_id = sayid_hero_photo_id();
	if ( $hero_photo_id ) {
		$image = wp_get_attachment_image_src( $hero_photo_id, 'sayid-featured' );
		if ( $image ) {
			return $image[0];
		}
	}
	return '';
}

add_action( 'wp_head', function () {
	if ( ! sayid_seo_should_output() ) {
		return;
	}

	$description = trim( (string) sayid_seo_description() );
	if ( '' === $description ) {
		return;
	}
	$description = wp_html_excerpt( $description, 300, '…' );
	$title        = is_front_page() ? get_bloginfo( 'name' ) : wp_strip_all_tags( wp_get_document_title() );
	$image        = sayid_seo_og_image();
	$is_article   = is_singular( 'post' ) || is_singular( array( 'sayid_project', 'sayid_lab' ) );

	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";

	echo '<meta property="og:type" content="' . esc_attr( $is_article ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( sayid_seo_current_url() ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}

	echo '<meta name="twitter:card" content="' . esc_attr( $image ? 'summary_large_image' : 'summary' ) . '">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}, 5 );

/**
 * Basic JSON-LD — a Person for the homepage (the site is a personal
 * portfolio) and Article for single posts. Intentionally small: a floor
 * for sites with no SEO plugin, not a replacement for one.
 */
add_action( 'wp_footer', function () {
	if ( ! sayid_seo_should_output() ) {
		return;
	}

	$schema = null;

	if ( is_front_page() ) {
		$schema = array_filter( array(
			'@context' => 'https://schema.org',
			'@type'    => 'Person',
			'name'     => sayid_theme_text( 'sayid_hero_name', get_bloginfo( 'name' ) ),
			'jobTitle' => sayid_theme_text( 'sayid_hero_role', '' ),
			'url'      => home_url( '/' ),
		) );
	} elseif ( is_singular( 'post' ) ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$schema = array(
				'@context'         => 'https://schema.org',
				'@type'            => 'Article',
				'headline'         => get_the_title( $post ),
				'datePublished'    => get_the_date( 'c', $post ),
				'dateModified'     => get_the_modified_date( 'c', $post ),
				'author'           => array(
					'@type' => 'Person',
					'name'  => get_the_author_meta( 'display_name', $post->post_author ),
				),
				'mainEntityOfPage' => get_permalink( $post ),
			);
		}
	}

	if ( ! $schema ) {
		return;
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n"; // phpcs:ignore
} );
