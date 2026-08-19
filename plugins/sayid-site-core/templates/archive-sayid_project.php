<?php
/**
 * Projects archive ("کارها"). Reuses the project-card markup from the
 * homepage Selected Work section but without the featured/secondary split.
 */
get_header();
?>
<main class="section archive-projects">
	<div class="site-container">
		<header class="archive__header">
			<h1 class="archive__title"><?php esc_html_e( 'کارها', 'sayid-site-core' ); ?></h1>
			<p class="archive__lede"><?php esc_html_e( 'پروژه‌هایی که هرکدوم یه جور متفاوت من رو درگیر مسئله، سیستم و ساخت تجربه بهتر کردن.', 'sayid-site-core' ); ?></p>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="work-grid work-grid--archive">
				<?php while ( have_posts() ) : the_post();
					$id      = get_the_ID();
					$role    = get_post_meta( $id, 'sayid_role', true );
					$org     = get_post_meta( $id, 'sayid_organization', true );
					$short   = get_post_meta( $id, 'sayid_short_description', true );
					$context = trim( implode( ' · ', array_filter( array( $role, $org ) ) ) );
					?>
					<article class="project-card" data-project-card>
						<a class="project-card__link" href="<?php the_permalink(); ?>">
							<div class="project-card__media"><?php the_post_thumbnail( 'medium_large' ); ?></div>
							<div class="project-card__body">
								<?php if ( $context ) : ?><p class="project-card__context"><?php echo esc_html( $context ); ?></p><?php endif; ?>
								<h3 class="project-card__title"><?php the_title(); ?></h3>
								<?php if ( $short ) : ?><p class="project-card__desc"><?php echo esc_html( $short ); ?></p><?php endif; ?>
							</div>
						</a>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="archive__pagination"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p class="archive__empty"><?php esc_html_e( 'هنوز پروژه‌ای منتشر نشده.', 'sayid-site-core' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
