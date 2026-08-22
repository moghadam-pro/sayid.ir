<?php
/**
 * Small reusable template helpers.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Social links shown in the Hero sidebar and footer. URLs are the ones
 * confirmed in the build brief; Instagram appeared in the reference Hero
 * screenshot but has no confirmed URL anywhere in the brief — it's left as
 * '#' with a TODO rather than guessed. Edit directly here, or move to a
 * Customizer/options screen later if these need to change without a
 * code deploy.
 */
function sayid_social_links() {
	return array(
		'instagram' => array( 'label' => __( 'اینستاگرام', 'sayid' ), 'url' => 'https://www.instagram.com/moghadam.pro' ),
		'dribbble'  => array( 'label' => __( 'دریبل', 'sayid' ), 'url' => 'https://dribbble.com/moghadam' ),
		'figma'     => array( 'label' => __( 'فیگما', 'sayid' ), 'url' => 'https://www.figma.com/@moghadam' ),
		'linkedin'  => array( 'label' => __( 'لینکدین', 'sayid' ), 'url' => 'https://www.linkedin.com/in/moghadampro/' ),
		'github'    => array( 'label' => __( 'گیت‌هاب', 'sayid' ), 'url' => 'https://github.com/moghadam-pro' ),
	);
}

/**
 * Finds the one published page assigned a given template — by template
 * rather than by slug, since Persian titles don't reliably produce a clean
 * URL to hardcode. Used for both the Contact page and the Hero's
 * content-source page.
 */
function sayid_page_by_template( $template ) {
	static $cache = array();
	if ( array_key_exists( $template, $cache ) ) {
		return $cache[ $template ];
	}
	$pages           = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => $template,
	) );
	$cache[ $template ] = ! empty( $pages ) ? (int) $pages[0]->ID : 0;
	return $cache[ $template ];
}

function sayid_contact_page_url() {
	$page_id = sayid_page_by_template( 'page-contact.php' );
	return $page_id ? get_permalink( $page_id ) : home_url( '/contact/' );
}

/**
 * Reads one Hero field from the page assigned page-home-content.php
 * (inc/meta-fields.php's "page" field map), falling back to $default when
 * no such page exists yet or the field was left blank — so the homepage
 * always renders something sensible before anyone touches wp-admin.
 */
function sayid_home_field( $key, $default = '' ) {
	$page_id = sayid_page_by_template( 'page-home-content.php' );
	if ( ! $page_id ) {
		return $default;
	}
	$value = get_post_meta( $page_id, $key, true );
	return ( '' !== trim( (string) $value ) ) ? $value : $default;
}

/**
 * The Hero's rotating headline chip — cycles through these phrases with a
 * slide-down transition (assets/js/hero-rotator.js). One phrase per line
 * in the "محتوای صفحه‌ی اصلی" page field; falls back to this confirmed
 * default list when that page/field doesn't exist yet.
 */
function sayid_hero_rotator_phrases() {
	$default = array(
		__( 'توسعه وردپرس', 'sayid' ),
		__( 'طراحی‌های چاپی', 'sayid' ),
		__( 'تحقیقات کاربر', 'sayid' ),
		__( 'طراحی با محوریت کاربر', 'sayid' ),
		__( 'عکاسی استیج و محصول', 'sayid' ),
		__( 'طراحی لوگو و هویت بصری', 'sayid' ),
		__( 'ادیت و تولید ویدیوهای تبلیغاتی', 'sayid' ),
		__( 'حل مسئله محصولات', 'sayid' ),
		__( 'توسعه دیزاین سیستم', 'sayid' ),
		__( 'ری‌براندینگ محصول', 'sayid' ),
		__( 'تولید محتوای دیجیتال', 'sayid' ),
		__( 'تست‌های تجربه کاربری', 'sayid' ),
	);

	$raw = sayid_home_field( 'sayid_hero_rotator_phrases', '' );
	if ( '' === $raw ) {
		return $default;
	}
	$lines = array_filter( array_map( 'trim', explode( "\n", $raw ) ) );
	return $lines ? array_values( $lines ) : $default;
}

/**
 * Articles live on the native `post` type, whose index is whatever page is
 * set in Settings → Reading → "Posts page" — `/` can't stand in for it,
 * since front-page.php owns that URL. Returns '' when no Posts page is
 * configured, and callers drop the link rather than pointing it at a URL
 * that would just reload the homepage.
 */
function sayid_articles_archive_url() {
	$page_id = (int) get_option( 'page_for_posts' );
	if ( ! $page_id || 'publish' !== get_post_status( $page_id ) ) {
		return '';
	}
	return get_permalink( $page_id );
}

function sayid_primary_nav() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'site-nav__list',
			'depth'          => 2,
		) );
		return;
	}
	// Fallback so the header never renders empty before a menu is assigned
	// in Appearance → Menus.
	?>
	<ul class="site-nav__list">
		<li><a href="<?php echo esc_url( home_url( '/work/' ) ); ?>"><?php esc_html_e( 'کارها', 'sayid' ); ?></a></li>
		<li><a href="<?php echo esc_url( home_url( '/lab/' ) ); ?>"><?php esc_html_e( 'آزمایشگاه', 'sayid' ); ?></a></li>
		<li><a href="<?php echo esc_url( get_category_link( sayid_notes_category_id() ) ); ?>"><?php esc_html_e( 'یادداشت‌ها', 'sayid' ); ?></a></li>
		<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'نوشته‌ها', 'sayid' ); ?></a></li>
	</ul>
	<?php
}

