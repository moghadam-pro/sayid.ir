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

function sayid_footer_nav() {
	if ( has_nav_menu( 'footer' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'footer',
			'container'      => false,
			'menu_class'     => 'footer-nav__list',
			'depth'          => 1,
		) );
		return;
	}
	?>
	<ul class="footer-nav__list">
		<li><a href="<?php echo esc_url( get_post_type_archive_link( 'sayid_project' ) ); ?>"><?php esc_html_e( 'کارها', 'sayid' ); ?></a></li>
		<li><a href="<?php echo esc_url( get_post_type_archive_link( 'sayid_lab' ) ); ?>"><?php esc_html_e( 'آزمایشگاه', 'sayid' ); ?></a></li>
		<li><a href="<?php echo esc_url( get_post_type_archive_link( 'sayid_note' ) ); ?>"><?php esc_html_e( 'یادداشت‌ها', 'sayid' ); ?></a></li>
		<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'درباره من', 'sayid' ); ?></a></li>
		<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'تماس', 'sayid' ); ?></a></li>
	</ul>
	<?php
}
