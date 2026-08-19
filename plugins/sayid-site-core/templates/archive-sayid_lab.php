<?php
/**
 * Lab archive — full Bento grid of every non-archived (and archived, shown
 * quietly) item, reusing the same lab-card markup/pointer interaction as
 * the homepage section.
 */
Sayid_Core_Assets::enqueue_lab_pointer();
get_header();
?>
<main class="section archive-lab" data-sayid-lab>
	<div class="site-container">
		<header class="archive__header">
			<h1 class="archive__title"><?php esc_html_e( 'آزمایشگاه', 'sayid-site-core' ); ?></h1>
			<p class="archive__lede"><?php esc_html_e( 'بعضی چیزها از یه مسئله واقعی شروع می‌شن، بعضی‌ها فقط از کنجکاوی.', 'sayid-site-core' ); ?></p>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="lab-grid lab-grid--archive">
				<?php while ( have_posts() ) : the_post();
					$id           = get_the_ID();
					$status       = get_post_meta( $id, 'sayid_status', true );
					$status_label = sayid_lab_status_label( $status );
					$short_desc   = get_post_meta( $id, 'sayid_short_description', true );
					?>
					<article class="lab-card" data-lab-card tabindex="0">
						<span class="lab-card__border" aria-hidden="true"></span>
						<a class="lab-card__link" href="<?php the_permalink(); ?>">
							<div class="lab-card__body">
								<?php if ( $status_label ) : ?>
									<span class="lab-card__status" data-status="<?php echo esc_attr( $status ); ?>"><?php echo esc_html( $status_label ); ?></span>
								<?php endif; ?>
								<h3 class="lab-card__title"><?php the_title(); ?></h3>
								<?php if ( $short_desc ) : ?>
									<p class="lab-card__desc"><?php echo esc_html( $short_desc ); ?></p>
								<?php endif; ?>
							</div>
						</a>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="archive__pagination"><?php the_posts_pagination(); ?></div>
		<?php else : ?>
			<p class="archive__empty"><?php esc_html_e( 'هنوز چیزی منتشر نشده.', 'sayid-site-core' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
