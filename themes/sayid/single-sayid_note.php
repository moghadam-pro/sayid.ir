<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) : the_post();
	$source = get_post_meta( get_the_ID(), 'sayid_source_url', true );
	$terms  = get_the_terms( get_the_ID(), 'sayid_topic' );
	?>
	<main class="section single-note">
		<div class="site-container editorial-width">
			<article <?php post_class(); ?>>
				<header class="single-note__header">
					<a class="single-note__back" href="<?php echo esc_url( get_post_type_archive_link( 'sayid_note' ) ); ?>">
						&rarr; <?php esc_html_e( 'همه‌ی یادداشت‌ها', 'sayid' ); ?>
					</a>
					<time class="single-note__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<?php echo esc_html( sayid_format_date( get_the_time( 'U' ) ) ); ?>
					</time>
					<h1 class="single-note__title"><?php the_title(); ?></h1>
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

			<?php echo sayid_render_related( get_the_ID() ); // phpcs:ignore ?>
		</div>
	</main>
	<?php
endwhile;
get_footer();
