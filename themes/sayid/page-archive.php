<?php
/**
 * Template Name: قالب آرشیو
 *
 * Page title + content render as the intro (both fully editable in the
 * block editor, as usual), followed by a paginated list of posts — every
 * post, or just one دسته‌بندی if this page's "تنظیمات آرشیو" meta box
 * has one selected. Reuses the same note-list markup as category-note.php
 * and home.php.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) : the_post();
	$id          = get_the_ID();
	$category_id = (int) get_post_meta( $id, 'sayid_archive_category', true );
	$paged       = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );

	$posts_query = new WP_Query( array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 10,
		'paged'          => $paged,
		'category__in'   => $category_id ? array( $category_id ) : array(),
	) );
	?>
	<main class="section archive-notes">
		<div class="site-container editorial-width">
			<header class="archive__header">
				<h1 class="archive__title"><?php the_title(); ?></h1>
			</header>

			<?php if ( get_the_content() ) : ?>
				<div class="prose"><?php the_content(); ?></div>
			<?php endif; ?>

			<?php if ( $posts_query->have_posts() ) : ?>
				<ul class="note-list note-list--archive">
					<?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
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
				<div class="archive__pagination">
					<?php
					echo paginate_links( array( // phpcs:ignore
						'total'   => $posts_query->max_num_pages,
						'current' => $paged,
					) );
					?>
				</div>
			<?php else : ?>
				<p class="archive__empty"><?php esc_html_e( 'هنوز چیزی منتشر نشده.', 'sayid' ); ?></p>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</main>
	<?php
endwhile;
get_footer();
