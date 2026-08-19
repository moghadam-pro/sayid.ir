<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main class="section archive-notes">
	<div class="site-container editorial-width">
		<header class="archive__header">
			<h1 class="archive__title"><?php esc_html_e( 'یادداشت‌ها', 'sayid' ); ?></h1>
			<p class="archive__lede"><?php esc_html_e( 'ایده‌ها، مشاهده‌ها و درس‌های کوتاهی که در مسیر ساختن و طراحی جمع می‌شن.', 'sayid' ); ?></p>
		</header>

		<?php if ( have_posts() ) : ?>
			<ul class="note-list note-list--archive">
				<?php while ( have_posts() ) : the_post();
					$terms = get_the_terms( get_the_ID(), 'sayid_topic' );
					$tag   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
					?>
					<li class="note-row">
						<a class="note-row__link" href="<?php the_permalink(); ?>">
							<time class="note-row__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( sayid_format_date_short( get_the_time( 'U' ) ) ); ?>
							</time>
							<span class="note-row__title"><?php the_title(); ?></span>
							<?php if ( $tag ) : ?>
								<span class="note-row__tag"><?php echo esc_html( $tag ); ?></span>
							<?php endif; ?>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
			<div class="archive__pagination"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p class="archive__empty"><?php esc_html_e( 'هنوز یادداشتی منتشر نشده.', 'sayid' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
