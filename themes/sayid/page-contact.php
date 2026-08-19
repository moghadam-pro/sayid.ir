<?php
/**
 * Template Name: تماس
 *
 * Assign this template to the Contact page from the block editor's Page
 * Attributes panel. Direction/copy per brief §45.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$submission = sayid_handle_contact_submission();

get_header();
while ( have_posts() ) : the_post();
	?>
	<main class="section single-page">
		<div class="site-container editorial-width">
			<header class="single-project__header">
				<h1 class="single-project__title"><?php esc_html_e( 'از اینجا می‌تونیم شروع کنیم.', 'sayid' ); ?></h1>
				<p class="single-project__lede">
					<?php esc_html_e( 'درباره محصول، دیزاین سیستم، همکاری، ساخته‌های جدید یا هر ایده‌ای که ارزش فکر کردن باهم رو داره.', 'sayid' ); ?>
				</p>
			</header>

			<?php if ( have_posts() && get_the_content() ) : ?>
				<div class="prose"><?php the_content(); ?></div>
			<?php endif; ?>

			<?php if ( $submission['sent'] ) : ?>
				<div class="notice notice-success" style="margin-top: var(--space-8)">
					<p><?php esc_html_e( 'پیامت رسید. زود جواب می‌دم.', 'sayid' ); ?></p>
				</div>
			<?php else : ?>
				<?php if ( ! empty( $submission['errors'] ) ) : ?>
					<div class="notice notice-error" style="margin-top: var(--space-8)">
						<?php foreach ( $submission['errors'] as $error ) : ?>
							<p><?php echo esc_html( $error ); ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<form class="contact-form" method="post" style="margin-top: var(--space-12); max-width: 40rem;">
					<?php wp_nonce_field( 'sayid_contact', 'sayid_contact_nonce' ); ?>
					<input type="hidden" name="sayid_contact_submitted" value="1">
					<p class="contact-form__honeypot" aria-hidden="true">
						<label>Website <input type="text" name="sayid_contact_website" tabindex="-1" autocomplete="off"></label>
					</p>

					<p>
						<label for="contact_name"><?php esc_html_e( 'اسمت چیه؟', 'sayid' ); ?></label><br>
						<input type="text" id="contact_name" name="name" required style="width:100%">
					</p>
					<p>
						<label for="contact_email"><?php esc_html_e( 'ایمیلت', 'sayid' ); ?></label><br>
						<input type="email" id="contact_email" name="email" required style="width:100%">
					</p>
					<p>
						<label for="contact_topic"><?php esc_html_e( 'درباره چی می‌خوای صحبت کنیم؟', 'sayid' ); ?></label><br>
						<input type="text" id="contact_topic" name="topic" style="width:100%">
					</p>
					<p>
						<label for="contact_message"><?php esc_html_e( 'پیامت', 'sayid' ); ?></label><br>
						<textarea id="contact_message" name="message" rows="6" required style="width:100%"></textarea>
					</p>
					<button type="submit" class="btn btn--primary"><?php esc_html_e( 'ارسال پیام', 'sayid' ); ?></button>
				</form>
			<?php endif; ?>
		</div>
	</main>
	<?php
endwhile;
get_footer();
