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
		'instagram' => array( 'label' => __( 'اینستاگرام', 'sayid' ), 'url' => '#', 'todo' => true ),
		'dribbble'  => array( 'label' => __( 'دریبل', 'sayid' ), 'url' => 'https://dribbble.com/moghadam' ),
		'figma'     => array( 'label' => __( 'فیگما', 'sayid' ), 'url' => 'https://www.figma.com/@moghadam' ),
		'linkedin'  => array( 'label' => __( 'لینکدین', 'sayid' ), 'url' => 'https://www.linkedin.com/in/moghadampro/' ),
		'github'    => array( 'label' => __( 'گیت‌هاب', 'sayid' ), 'url' => 'https://github.com/moghadam-pro' ),
	);
}

/**
 * The Hero's rotating headline chip — cycles through these phrases with a
 * slide-down transition (assets/js/hero-rotator.js). Confirmed content,
 * not placeholder.
 */
function sayid_hero_rotator_phrases() {
	return array(
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
}

/**
 * Finds the page using page-contact.php by its assigned template rather
 * than assuming a slug — Persian page titles don't reliably produce a
 * clean `/contact/` slug, so this stays correct regardless of what the
 * page ends up being called.
 */
function sayid_contact_page_url() {
	static $url = null;
	if ( null !== $url ) {
		return $url;
	}
	$pages = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-contact.php',
	) );
	$url = ! empty( $pages ) ? get_permalink( $pages[0] ) : home_url( '/contact/' );
	return $url;
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
		<li><a href="<?php echo esc_url( home_url( '/notes/' ) ); ?>"><?php esc_html_e( 'یادداشت‌ها', 'sayid' ); ?></a></li>
		<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'نوشته‌ها', 'sayid' ); ?></a></li>
	</ul>
	<?php
}

