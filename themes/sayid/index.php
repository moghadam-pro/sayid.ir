<?php
/**
 * Fallback template, required by WordPress. Front-page.php, home.php and
 * the more specific single-/archive-/taxonomy-prefixed templates cover
 * every URL this theme actually expects visitors to reach; this only
 * renders for something unanticipated (e.g. search results without a
 * dedicated search.php, which this theme doesn't ship yet).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main class="section archive-notes">
	<div class="site-container editorial-width">
		<?php if ( have_posts() ) : ?>
			<ul class="note-list note-list--mixed">
				<?php while ( have_posts() ) : the_post(); ?>
					<li class="note-row">
						<a class="note-row__link" href="<?php the_permalink(); ?>">
							<time class="note-row__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( sayid_format_date_short( get_the_time( 'U' ) ) ); ?>
							</time>
							<span class="note-row__title"><?php the_title(); ?></span>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
			<div class="archive__pagination"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p class="archive__empty"><?php esc_html_e( 'چیزی پیدا نشد.', 'sayid' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
