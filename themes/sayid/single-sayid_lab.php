<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) : the_post();
	$id           = get_the_ID();
	$status       = get_post_meta( $id, 'sayid_status', true );
	$status_label = sayid_lab_status_label( $status );
	$started_at   = get_post_meta( $id, 'sayid_started_at', true );
	$shipped_at   = get_post_meta( $id, 'sayid_shipped_at', true );
	$tools        = get_post_meta( $id, 'sayid_tools', true );
	$repo_url     = get_post_meta( $id, 'sayid_repo_url', true );
	$live_url     = get_post_meta( $id, 'sayid_live_url', true );
	?>
	<main class="section single-lab">
		<div class="site-container editorial-width">
			<article <?php post_class(); ?>>
				<header class="single-lab__header">
					<a class="single-lab__back" href="<?php echo esc_url( get_post_type_archive_link( 'sayid_lab' ) ); ?>">
						&rarr; <?php esc_html_e( 'همه‌ی آزمایشگاه', 'sayid' ); ?>
					</a>
					<?php if ( $status_label ) : ?>
						<span class="lab-card__status" data-status="<?php echo esc_attr( $status ); ?>"><?php echo esc_html( $status_label ); ?></span>
					<?php endif; ?>
					<h1 class="single-lab__title"><?php the_title(); ?></h1>

					<dl class="single-lab__meta">
						<?php if ( $started_at ) : ?>
							<div><dt><?php esc_html_e( 'شروع', 'sayid' ); ?></dt><dd><?php echo esc_html( $started_at ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $shipped_at ) : ?>
							<div><dt><?php esc_html_e( 'انتشار', 'sayid' ); ?></dt><dd><?php echo esc_html( $shipped_at ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $tools ) : ?>
							<div><dt><?php esc_html_e( 'ابزارها', 'sayid' ); ?></dt><dd><?php echo esc_html( $tools ); ?></dd></div>
						<?php endif; ?>
					</dl>

					<div class="single-lab__links">
						<?php if ( $repo_url ) : ?>
							<a class="btn btn--ghost" href="<?php echo esc_url( $repo_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'ریپازیتوری', 'sayid' ); ?></a>
						<?php endif; ?>
						<?php if ( $live_url ) : ?>
							<a class="btn btn--primary" href="<?php echo esc_url( $live_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'مشاهده‌ی زنده', 'sayid' ); ?></a>
						<?php endif; ?>
					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-lab__cover"><?php the_post_thumbnail( 'sayid-featured' ); ?></div>
				<?php endif; ?>

				<div class="prose"><?php the_content(); ?></div>
			</article>

			<?php echo sayid_render_related( $id ); // phpcs:ignore ?>
		</div>
	</main>
	<?php
endwhile;
get_footer();
