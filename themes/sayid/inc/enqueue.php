<?php
/**
 * Asset loading. CSS is split by ownership (tokens/base/layout/components/
 * hero/interactions), same architecture as the retired plugin.
 *
 * theme.js (theme switch) and nav.js (touch dropdowns) load everywhere,
 * since the header and footer are on every page. The section-specific
 * interaction scripts — Hero entry, Lab pointer, Signature Venn, Hero
 * marquee, Hero rotator — are only *registered* here and get enqueued from
 * inc/render.php at the point a section actually renders, so a page never
 * pays for a script covering content it doesn't contain.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', 'sayid_inline_theme_bootstrap', 0 );
function sayid_inline_theme_bootstrap() {
	?>
	<script>
	(function(){
		try {
			var stored = localStorage.getItem('sayid-theme');
			if (stored === 'light' || stored === 'dark') {
				document.documentElement.setAttribute('data-theme', stored);
			}
		} catch (e) {}
	})();
	</script>
	<?php
}

add_action( 'wp_enqueue_scripts', function () {
	$ver = SAYID_THEME_VERSION;

	wp_enqueue_style( 'sayid-tokens', SAYID_THEME_URI . '/assets/css/tokens.css', array(), $ver );
	wp_enqueue_style( 'sayid-base', SAYID_THEME_URI . '/assets/css/base.css', array( 'sayid-tokens' ), $ver );
	wp_enqueue_style( 'sayid-layout', SAYID_THEME_URI . '/assets/css/layout.css', array( 'sayid-base' ), $ver );
	wp_enqueue_style( 'sayid-components', SAYID_THEME_URI . '/assets/css/components.css', array( 'sayid-layout' ), $ver );
	wp_enqueue_style( 'sayid-hero', SAYID_THEME_URI . '/assets/css/hero.css', array( 'sayid-components' ), $ver );
	wp_enqueue_style( 'sayid-interactions', SAYID_THEME_URI . '/assets/css/interactions.css', array( 'sayid-hero' ), $ver );

	wp_enqueue_script( 'sayid-theme', SAYID_THEME_URI . '/assets/js/theme.js', array(), $ver, true );
	wp_enqueue_script( 'sayid-nav', SAYID_THEME_URI . '/assets/js/nav.js', array(), $ver, true );

	wp_register_script( 'sayid-homepage-entry', SAYID_THEME_URI . '/assets/js/homepage-entry.js', array(), $ver, true );
	wp_register_script( 'sayid-lab-pointer', SAYID_THEME_URI . '/assets/js/lab-pointer.js', array(), $ver, true );
	wp_register_script( 'sayid-signature-venn', SAYID_THEME_URI . '/assets/js/signature-venn.js', array(), $ver, true );
	wp_register_script( 'sayid-hero-marquee', SAYID_THEME_URI . '/assets/js/hero-marquee.js', array(), $ver, true );
	wp_register_script( 'sayid-hero-rotator', SAYID_THEME_URI . '/assets/js/hero-rotator.js', array(), $ver, true );
} );

function sayid_enqueue_homepage_entry() { wp_enqueue_script( 'sayid-homepage-entry' ); }
function sayid_enqueue_lab_pointer() { wp_enqueue_script( 'sayid-lab-pointer' ); }
function sayid_enqueue_signature_venn() { wp_enqueue_script( 'sayid-signature-venn' ); }
function sayid_enqueue_hero_marquee() { wp_enqueue_script( 'sayid-hero-marquee' ); }
function sayid_enqueue_hero_rotator() { wp_enqueue_script( 'sayid-hero-rotator' ); }
