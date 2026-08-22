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

/**
 * The three signal titles ("دارم می‌سازم" etc.) are editable, but a blank
 * one falls back to this rather than rendering an empty <dt> — so leaving
 * the label field untouched keeps today's wording without the widget ever
 * shipping an unlabeled row.
 */
function sayid_now_default_labels() {
	return array(
		'building'  => __( 'دارم می‌سازم', 'sayid' ),
		'exploring' => __( 'دارم تجربه می‌کنم', 'sayid' ),
		'learning'  => __( 'دارم یاد می‌گیرم', 'sayid' ),
	);
}

function sayid_get_now() {
	$defaults = array(
		'statement'       => '',
		'building_label'  => '',
		'building'        => '',
		'exploring_label' => '',
		'exploring'       => '',
		'learning_label'  => '',
		'learning'        => '',
		'link_label'      => '',
		'link_url'        => '',
		'updated_at'      => 0,
	);
	$now = wp_parse_args( get_option( SAYID_NOW_OPTION, array() ), $defaults );
	foreach ( sayid_now_default_labels() as $key => $label ) {
		if ( '' === $now[ $key . '_label' ] ) {
			$now[ $key . '_label' ] = $label;
		}
	}
	return $now;
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
			<textarea name="statement" id="sayid_now_statement" rows="5" style="width:100%"><?php echo esc_textarea( $now['statement'] ); ?></textarea>
		</p>
		<?php foreach ( sayid_now_default_labels() as $key => $default_label ) : ?>
			<p>
				<label for="sayid_now_<?php echo esc_attr( $key ); ?>_label"><strong><?php esc_html_e( 'عنوان', 'sayid' ); ?></strong></label>
				<input type="text" name="<?php echo esc_attr( $key ); ?>_label" id="sayid_now_<?php echo esc_attr( $key ); ?>_label" value="<?php echo esc_attr( $now[ $key . '_label' ] ); ?>" placeholder="<?php echo esc_attr( $default_label ); ?>" style="width:100%">
				<textarea name="<?php echo esc_attr( $key ); ?>" id="sayid_now_<?php echo esc_attr( $key ); ?>" rows="2" style="width:100%; margin-top:4px;"><?php echo esc_textarea( $now[ $key ] ); ?></textarea>
			</p>
		<?php endforeach; ?>
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

	$data = array(
		'statement'  => sanitize_textarea_field( wp_unslash( $_POST['statement'] ?? '' ) ),
		'link_label' => sanitize_text_field( wp_unslash( $_POST['link_label'] ?? '' ) ),
		'link_url'   => esc_url_raw( wp_unslash( $_POST['link_url'] ?? '' ) ),
		'updated_at' => time(),
	);
	foreach ( array_keys( sayid_now_default_labels() ) as $key ) {
		$data[ $key . '_label' ] = sanitize_text_field( wp_unslash( $_POST[ $key . '_label' ] ?? '' ) );
		$data[ $key ]            = sanitize_textarea_field( wp_unslash( $_POST[ $key ] ?? '' ) );
	}

	update_option( SAYID_NOW_OPTION, $data );

	wp_safe_redirect( add_query_arg( 'sayid_now_updated', '1', admin_url( 'index.php' ) ) );
	exit;
} );
