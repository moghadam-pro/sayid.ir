<?php
/**
 * Single Project / case study. The richest template — role, context,
 * process, decisions, outcome, metrics, gallery — but every field is
 * optional so a lightly-documented project doesn't render broken sections.
 */
get_header();
while ( have_posts() ) : the_post();
	$id       = get_the_ID();
	$role     = get_post_meta( $id, 'sayid_role', true );
	$org      = get_post_meta( $id, 'sayid_organization', true );
	$type     = get_post_meta( $id, 'sayid_project_type', true );
	$start    = get_post_meta( $id, 'sayid_date_start', true );
	$end      = get_post_meta( $id, 'sayid_date_end', true );
	$tools    = get_post_meta( $id, 'sayid_tools', true );
	$collab   = get_post_meta( $id, 'sayid_collaborators', true );
	$short    = get_post_meta( $id, 'sayid_short_description', true );
	$sections = array(
		'challenge' => array( __( 'چالش', 'sayid-site-core' ), get_post_meta( $id, 'sayid_challenge', true ) ),
		'context'   => array( __( 'زمینه و بستر', 'sayid-site-core' ), get_post_meta( $id, 'sayid_context', true ) ),
		'process'   => array( __( 'فرایند', 'sayid-site-core' ), get_post_meta( $id, 'sayid_process', true ) ),
		'decisions' => array( __( 'تصمیم‌های کلیدی', 'sayid-site-core' ), get_post_meta( $id, 'sayid_decisions', true ) ),
		'outcome'   => array( __( 'نتیجه', 'sayid-site-core' ), get_post_meta( $id, 'sayid_outcome', true ) ),
	);
	$metrics    = get_post_meta( $id, 'sayid_metrics', true );
	$gallery    = get_post_meta( $id, 'sayid_gallery', true );
	$external   = get_post_meta( $id, 'sayid_external_url', true );
	?>
	<main class="section single-project">
		<div class="site-container editorial-width">
			<article <?php post_class(); ?>>
				<header class="single-project__header">
					<a class="single-project__back" href="<?php echo esc_url( get_post_type_archive_link( 'sayid_project' ) ); ?>">
						&rarr; <?php esc_html_e( 'همه‌ی کارها', 'sayid-site-core' ); ?>
					</a>
					<h1 class="single-project__title"><?php the_title(); ?></h1>
					<?php if ( $short ) : ?>
						<p class="single-project__lede"><?php echo esc_html( $short ); ?></p>
					<?php endif; ?>

					<dl class="single-project__meta">
						<?php if ( $role ) : ?><div><dt><?php esc_html_e( 'نقش', 'sayid-site-core' ); ?></dt><dd><?php echo esc_html( $role ); ?></dd></div><?php endif; ?>
						<?php if ( $org ) : ?><div><dt><?php esc_html_e( 'سازمان', 'sayid-site-core' ); ?></dt><dd><?php echo esc_html( $org ); ?></dd></div><?php endif; ?>
						<?php if ( $type ) : ?><div><dt><?php esc_html_e( 'نوع پروژه', 'sayid-site-core' ); ?></dt><dd><?php echo esc_html( $type ); ?></dd></div><?php endif; ?>
						<?php if ( $start || $end ) : ?><div><dt><?php esc_html_e( 'بازه', 'sayid-site-core' ); ?></dt><dd><?php echo esc_html( trim( "$start – $end", ' –' ) ); ?></dd></div><?php endif; ?>
						<?php if ( $tools ) : ?><div><dt><?php esc_html_e( 'ابزارها', 'sayid-site-core' ); ?></dt><dd><?php echo esc_html( $tools ); ?></dd></div><?php endif; ?>
						<?php if ( $collab ) : ?><div><dt><?php esc_html_e( 'همکاران', 'sayid-site-core' ); ?></dt><dd><?php echo esc_html( $collab ); ?></dd></div><?php endif; ?>
					</dl>

					<?php if ( $external ) : ?>
						<a class="btn btn--ghost" href="<?php echo esc_url( $external ); ?>" target="_blank" rel="noopener">
							<?php esc_html_e( 'نسخه‌ی کامل در پورتفولیو', 'sayid-site-core' ); ?>
						</a>
					<?php endif; ?>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-project__cover"><?php the_post_thumbnail( 'full' ); ?></div>
				<?php endif; ?>

				<div class="prose"><?php the_content(); ?></div>

				<?php foreach ( $sections as $key => $section ) :
					list( $label, $body ) = $section;
					if ( ! $body ) {
						continue;
					}
					?>
					<section class="single-project__block single-project__block--<?php echo esc_attr( $key ); ?>">
						<h2><?php echo esc_html( $label ); ?></h2>
						<div class="prose"><?php echo wp_kses_post( $body ); ?></div>
					</section>
				<?php endforeach; ?>

				<?php if ( $metrics ) : ?>
					<section class="single-project__block single-project__block--metrics">
						<h2><?php esc_html_e( 'متریک‌ها', 'sayid-site-core' ); ?></h2>
						<p><?php echo esc_html( $metrics ); ?></p>
					</section>
				<?php endif; ?>

				<?php if ( ! empty( $gallery ) && is_array( $gallery ) ) : ?>
					<section class="single-project__gallery">
						<?php foreach ( $gallery as $attachment_id ) : ?>
							<?php echo sayid_cover_html( $attachment_id, 'large' ); ?>
						<?php endforeach; ?>
					</section>
				<?php endif; ?>
			</article>

			<?php echo Sayid_Core_Render::related( $id ); // phpcs:ignore ?>
		</div>
	</main>
	<?php
endwhile;
get_footer();
