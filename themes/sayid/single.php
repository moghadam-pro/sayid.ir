<?php
/**
 * Single Article or Note — both are the native `post` type (relabeled
 * "نوشته‌ها"), Notes just carry the "یادداشت" category — see
 * inc/content-types.php.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) : the_post();
	$id       = get_the_ID();
	$subtitle = get_post_meta( $id, 'sayid_subtitle', true );
	$source   = get_post_meta( $id, 'sayid_source_url', true );
	$terms    = get_the_terms( $id, 'sayid_topic' );
	?>
	<main class="section single-article">
		<div class="site-container editorial-width">
			<article <?php post_class(); ?>>
				<header class="single-note__header">
					<a class="single-note__back" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						&rarr; <?php esc_html_e( 'همه‌ی نوشته‌ها', 'sayid' ); ?>
					</a>
					<h1 class="single-note__title"><?php the_title(); ?></h1>
					<?php if ( $subtitle ) : ?>
						<p class="single-project__lede"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
					<p class="article-card__meta">
						<?php echo esc_html( sayid_format_date( get_the_time( 'U' ) ) ); ?>
						·
						<?php echo esc_html( sayid_reading_time_label( get_the_content() ) ); ?>
					</p>
					<?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
						<ul class="single-note__tags">
							<?php foreach ( $terms as $term ) : ?>
								<li><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-note__cover"><?php the_post_thumbnail( 'sayid-featured' ); ?></div>
				<?php endif; ?>

				<div class="prose"><?php the_content(); ?></div>

				<?php if ( $source ) : ?>
					<p class="single-note__source">
						<?php esc_html_e( 'منبع:', 'sayid' ); ?>
						<a href="<?php echo esc_url( $source ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $source ); ?></a>
					</p>
				<?php endif; ?>
			</article>

			<?php echo sayid_render_related( $id ); // phpcs:ignore ?>
		</div>
	</main>
	<?php
endwhile;
get_footer();
