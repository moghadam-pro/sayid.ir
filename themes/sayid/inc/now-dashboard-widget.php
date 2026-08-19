<?php
/**
 * "Now" / این‌روزها — a WordPress Dashboard widget rather than a separate
 * settings page, per explicit request: it should live right on the screen
 * Sayid sees first after logging in, so updating it is as fast as possible
 * (brief §17/§39: "updating Now must take seconds"). Storage is still a
 * single `sayid_now` option — the front-end render function
 * (`sayid_render_now()`) doesn't know or care whether the value came from
 * a dashboard widget or a settings page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SAYID_NOW_OPTION = 'sayid_now';

function sayid_get_now() {
	$defaults = array(
		'statement'  => '',
		'building'   => '',
		'exploring'  => '',
		'learning'   => '',
		'link_label' => '',
		'link_url'   => '',
		'updated_at' => 0,
	);
	return wp_parse_args( get_option( SAYID_NOW_OPTION, array() ), $defaults );
}

add_action( 'wp_dashboard_setup', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	wp_add_dashboard_widget( 'sayid_now_widget', __( 'این روزها', 'sayid' ), 'sayid_render_now_dashboard_widget' );
} );

/**
 * Dashboard widgets render inline on wp-admin's main screen — pull this
 * one to the top of the widget list so it's the first thing seen, matching
 * its role as the fastest-changing, most-visible piece of the site.
 */
add_action( 'wp_dashboard_setup', function () {
	global $wp_meta_boxes;
	if ( empty( $wp_meta_boxes['dashboard']['normal']['core']['sayid_now_widget'] ) ) {
		return;
	}
	$widget = $wp_meta_boxes['dashboard']['normal']['core']['sayid_now_widget'];
	unset( $wp_meta_boxes['dashboard']['normal']['core']['sayid_now_widget'] );
	$wp_meta_boxes['dashboard']['normal']['core'] = array( 'sayid_now_widget' => $widget )
		+ $wp_meta_boxes['dashboard']['normal']['core'];
}, 20 );

function sayid_render_now_dashboard_widget() {
	$now = sayid_get_now();
	?>
	<?php if ( ! empty( $_GET['sayid_now_updated'] ) ) : ?>
		<div class="notice notice-success inline"><p><?php esc_html_e( 'ذخیره شد.', 'sayid' ); ?></p></div>
	<?php endif; ?>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<input type="hidden" name="action" value="sayid_save_now">
		<?php wp_nonce_field( 'sayid_save_now', 'sayid_now_nonce' ); ?>
		<p>
			<label for="sayid_now_statement"><strong><?php esc_html_e( 'جمله‌ی اصلی', 'sayid' ); ?></strong></label>
			<textarea name="statement" id="sayid_now_statement" rows="2" style="width:100%"><?php echo esc_textarea( $now['statement'] ); ?></textarea>
		</p>
		<p>
			<label for="sayid_now_building"><strong><?php esc_html_e( 'دارم می‌سازم', 'sayid' ); ?></strong></label>
			<input type="text" name="building" id="sayid_now_building" value="<?php echo esc_attr( $now['building'] ); ?>" style="width:100%">
		</p>
		<p>
			<label for="sayid_now_exploring"><strong><?php esc_html_e( 'دارم تجربه می‌کنم', 'sayid' ); ?></strong></label>
			<input type="text" name="exploring" id="sayid_now_exploring" value="<?php echo esc_attr( $now['exploring'] ); ?>" style="width:100%">
		</p>
		<p>
			<label for="sayid_now_learning"><strong><?php esc_html_e( 'دارم یاد می‌گیرم', 'sayid' ); ?></strong></label>
			<input type="text" name="learning" id="sayid_now_learning" value="<?php echo esc_attr( $now['learning'] ); ?>" style="width:100%">
		</p>
		<p style="display:flex; gap:8px;">
			<input type="text" name="link_label" placeholder="<?php esc_attr_e( 'عنوان لینک (اختیاری)', 'sayid' ); ?>" value="<?php echo esc_attr( $now['link_label'] ); ?>" style="flex:1">
			<input type="url" name="link_url" placeholder="https://" value="<?php echo esc_attr( $now['link_url'] ); ?>" style="flex:1">
		</p>
		<p class="description">
			<?php
			echo $now['updated_at']
				? esc_html__( 'آخرین به‌روزرسانی: ', 'sayid' ) . esc_html( sayid_format_date( (int) $now['updated_at'] ) )
				: esc_html__( 'هنوز ذخیره نشده.', 'sayid' );
			?>
		</p>
		<?php submit_button( __( 'ذخیره', 'sayid' ), 'primary', 'submit', false ); ?>
	</form>
	<?php
}

add_action( 'admin_post_sayid_save_now', function () {
	if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['sayid_now_nonce'] ) || ! wp_verify_nonce( $_POST['sayid_now_nonce'], 'sayid_save_now' ) ) {
		wp_die( esc_html__( 'دسترسی نامعتبر است.', 'sayid' ) );
	}

	update_option( SAYID_NOW_OPTION, array(
		'statement'  => sanitize_textarea_field( wp_unslash( $_POST['statement'] ?? '' ) ),
		'building'   => sanitize_text_field( wp_unslash( $_POST['building'] ?? '' ) ),
		'exploring'  => sanitize_text_field( wp_unslash( $_POST['exploring'] ?? '' ) ),
		'learning'   => sanitize_text_field( wp_unslash( $_POST['learning'] ?? '' ) ),
		'link_label' => sanitize_text_field( wp_unslash( $_POST['link_label'] ?? '' ) ),
		'link_url'   => esc_url_raw( wp_unslash( $_POST['link_url'] ?? '' ) ),
		'updated_at' => time(),
	) );

	wp_safe_redirect( add_query_arg( 'sayid_now_updated', '1', admin_url( 'index.php' ) ) );
	exit;
} );
