<?php
/**
 * Template Name: قالب فرم
 *
 * A reusable native form: page title + content render as usual, followed
 * by a name/email/message form that emails the address set in this page's
 * "تنظیمات فرم" meta box (falls back to the site admin email). No
 * third-party form plugin — same honeypot + nonce pattern as the Contact
 * page (inc/contact-form.php), generalized in inc/form-template.php.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$submission = sayid_handle_form_submission( get_the_ID() );

get_header();
while ( have_posts() ) : the_post();
	$id = get_the_ID();
	?>
	<main class="section single-page">
		<div class="site-container editorial-width">
			<article <?php post_class(); ?>>
				<header class="single-project__header">
					<h1 class="single-project__title"><?php the_title(); ?></h1>
				</header>

				<?php if ( get_the_content() ) : ?>
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
						<?php wp_nonce_field( 'sayid_form_' . $id, 'sayid_form_nonce' ); ?>
						<input type="hidden" name="sayid_form_submitted" value="1">
						<p class="contact-form__honeypot" aria-hidden="true">
							<label>Website <input type="text" name="sayid_form_website" tabindex="-1" autocomplete="off"></label>
						</p>

						<p>
							<label for="form_name"><?php esc_html_e( 'اسمت چیه؟', 'sayid' ); ?></label><br>
							<input type="text" id="form_name" name="name" required style="width:100%">
						</p>
						<p>
							<label for="form_email"><?php esc_html_e( 'ایمیلت', 'sayid' ); ?></label><br>
							<input type="email" id="form_email" name="email" required style="width:100%">
						</p>
						<p>
							<label for="form_message"><?php esc_html_e( 'پیامت', 'sayid' ); ?></label><br>
							<textarea id="form_message" name="message" rows="6" required style="width:100%"></textarea>
						</p>
						<button type="submit" class="btn btn--primary"><?php esc_html_e( 'ارسال پیام', 'sayid' ); ?></button>
					</form>
				<?php endif; ?>
			</article>
		</div>
	</main>
	<?php
endwhile;
get_footer();
