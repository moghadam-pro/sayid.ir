<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$term = get_queried_object();
?>
<main class="section archive-topic">
	<div class="site-container editorial-width">
		<header class="archive__header">
			<p class="archive__eyebrow"><?php esc_html_e( 'موضوع', 'sayid' ); ?></p>
			<h1 class="archive__title"><?php echo esc_html( $term->name ); ?></h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<ul class="note-list note-list--mixed">
				<?php while ( have_posts() ) : the_post(); ?>
					<li class="note-row">
						<a class="note-row__link" href="<?php the_permalink(); ?>">
							<time class="note-row__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( sayid_format_date_short( get_the_time( 'U' ) ) ); ?>
							</time>
							<span class="note-row__title"><?php the_title(); ?></span>
							<span class="note-row__tag"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
			<div class="archive__pagination"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p class="archive__empty"><?php esc_html_e( 'هنوز محتوایی با این موضوع منتشر نشده.', 'sayid' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
