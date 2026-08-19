<?php
/**
 * "Now" / این‌روزها settings — a single options screen, deliberately not a
 * custom post type (brief §17 & §39: "Do not create a full CPT. Use
 * Options/Settings page."). Updating Now must take seconds.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Sayid_Core_Settings {

	const OPTION_KEY = 'sayid_now';

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_post_sayid_save_now', array( $this, 'save' ) );
	}

	public function add_menu() {
		add_menu_page(
			__( 'این روزها', 'sayid-site-core' ),
			__( 'این روزها', 'sayid-site-core' ),
			'manage_options',
			'sayid-now',
			array( $this, 'render_page' ),
			'dashicons-clock',
			3
		);
	}

	public static function get() {
		$defaults = array(
			'statement'  => '',
			'building'   => '',
			'exploring'  => '',
			'learning'   => '',
			'link_label' => '',
			'link_url'   => '',
			'updated_at' => 0,
		);
		$value = get_option( self::OPTION_KEY, array() );
		return wp_parse_args( $value, $defaults );
	}

	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$now = self::get();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'این روزها', 'sayid-site-core' ); ?></h1>
			<p><?php esc_html_e( 'این بخش در صفحه‌ی اصلی نمایش داده می‌شود. هدف این‌جاست که به‌روزرسانی آن چند ثانیه بیشتر طول نکشد.', 'sayid-site-core' ); ?></p>
			<?php if ( ! empty( $_GET['updated'] ) ) : ?>
				<div class="notice notice-success"><p><?php esc_html_e( 'ذخیره شد.', 'sayid-site-core' ); ?></p></div>
			<?php endif; ?>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="sayid_save_now">
				<?php wp_nonce_field( 'sayid_save_now', 'sayid_now_nonce' ); ?>
				<table class="form-table">
					<tr>
						<th><label for="statement"><?php esc_html_e( 'جمله‌ی اصلی', 'sayid-site-core' ); ?></label></th>
						<td><textarea name="statement" id="statement" rows="3" class="large-text"><?php echo esc_textarea( $now['statement'] ); ?></textarea></td>
					</tr>
					<tr>
						<th><label for="building"><?php esc_html_e( 'دارم می‌سازم', 'sayid-site-core' ); ?></label></th>
						<td><input type="text" name="building" id="building" value="<?php echo esc_attr( $now['building'] ); ?>" class="large-text"></td>
					</tr>
					<tr>
						<th><label for="exploring"><?php esc_html_e( 'دارم تجربه می‌کنم', 'sayid-site-core' ); ?></label></th>
						<td><input type="text" name="exploring" id="exploring" value="<?php echo esc_attr( $now['exploring'] ); ?>" class="large-text"></td>
					</tr>
					<tr>
						<th><label for="learning"><?php esc_html_e( 'دارم یاد می‌گیرم', 'sayid-site-core' ); ?></label></th>
						<td><input type="text" name="learning" id="learning" value="<?php echo esc_attr( $now['learning'] ); ?>" class="large-text"></td>
					</tr>
					<tr>
						<th><label for="link_label"><?php esc_html_e( 'لینک اختیاری — عنوان', 'sayid-site-core' ); ?></label></th>
						<td><input type="text" name="link_label" id="link_label" value="<?php echo esc_attr( $now['link_label'] ); ?>" class="regular-text"></td>
					</tr>
					<tr>
						<th><label for="link_url"><?php esc_html_e( 'لینک اختیاری — آدرس', 'sayid-site-core' ); ?></label></th>
						<td><input type="url" name="link_url" id="link_url" value="<?php echo esc_attr( $now['link_url'] ); ?>" class="regular-text" placeholder="https://"></td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'آخرین به‌روزرسانی', 'sayid-site-core' ); ?></th>
						<td>
							<?php
							echo $now['updated_at']
								? esc_html( sayid_format_date( (int) $now['updated_at'] ) )
								: esc_html__( 'هنوز ذخیره نشده', 'sayid-site-core' );
							?>
							<p class="description"><?php esc_html_e( 'این تاریخ به‌طور خودکار در زمان ذخیره‌سازی ثبت می‌شود.', 'sayid-site-core' ); ?></p>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'ذخیره', 'sayid-site-core' ) ); ?>
			</form>
		</div>
		<?php
	}

	public function save() {
		if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['sayid_now_nonce'] ) || ! wp_verify_nonce( $_POST['sayid_now_nonce'], 'sayid_save_now' ) ) {
			wp_die( esc_html__( 'دسترسی نامعتبر است.', 'sayid-site-core' ) );
		}

		$value = array(
			'statement'  => sanitize_textarea_field( wp_unslash( $_POST['statement'] ?? '' ) ),
			'building'   => sanitize_text_field( wp_unslash( $_POST['building'] ?? '' ) ),
			'exploring'  => sanitize_text_field( wp_unslash( $_POST['exploring'] ?? '' ) ),
			'learning'   => sanitize_text_field( wp_unslash( $_POST['learning'] ?? '' ) ),
			'link_label' => sanitize_text_field( wp_unslash( $_POST['link_label'] ?? '' ) ),
			'link_url'   => esc_url_raw( wp_unslash( $_POST['link_url'] ?? '' ) ),
			'updated_at' => time(),
		);
		update_option( self::OPTION_KEY, $value );

		wp_safe_redirect( add_query_arg( 'updated', '1', admin_url( 'admin.php?page=sayid-now' ) ) );
		exit;
	}
}
