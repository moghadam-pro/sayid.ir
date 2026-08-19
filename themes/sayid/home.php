<?php
/**
 * Articles index (native `post` archive). WordPress uses this template for
 * the blog posts index when a static front page is configured (see
 * Settings → Reading → "Posts page"), which front-page.php owns instead.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main class="section archive-notes">
	<div class="site-container editorial-width">
		<header class="archive__header">
			<h1 class="archive__title"><?php esc_html_e( 'نوشته‌ها', 'sayid' ); ?></h1>
			<p class="archive__lede"><?php esc_html_e( 'نوشته‌های بلندتر درباره طراحی محصول، سیستم‌ها و ساختن.', 'sayid' ); ?></p>
		</header>

		<?php if ( have_posts() ) : ?>
			<ul class="note-list note-list--archive">
				<?php while ( have_posts() ) : the_post();
					$subtitle = get_post_meta( get_the_ID(), 'sayid_subtitle', true );
					?>
					<li class="note-row">
						<a class="note-row__link" href="<?php the_permalink(); ?>">
							<time class="note-row__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( sayid_format_date_short( get_the_time( 'U' ) ) ); ?>
							</time>
							<span class="note-row__title"><?php the_title(); ?></span>
							<span class="note-row__tag"><?php echo esc_html( sayid_reading_time_label( get_the_content() ) ); ?></span>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
			<div class="archive__pagination"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p class="archive__empty"><?php esc_html_e( 'هنوز نوشته‌ای منتشر نشده.', 'sayid' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
