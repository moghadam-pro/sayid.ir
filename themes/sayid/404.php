<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main class="section connect">
	<div class="site-container connect">
		<h1 class="connect__title"><?php esc_html_e( 'این صفحه پیدا نشد.', 'sayid' ); ?></h1>
		<p class="connect__support"><?php esc_html_e( 'شاید لینک اشتباه بوده یا صفحه جابه‌جا شده.', 'sayid' ); ?></p>
		<div class="connect__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'برگشت به خانه', 'sayid' ); ?></a>
		</div>
	</div>
</main>
<?php
get_footer();
