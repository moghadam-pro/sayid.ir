<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

// Topics actually used on at least one project — not every seeded
// sayid_topic term, so the tab row never offers an empty filter.
$project_ids  = get_posts( array( 'post_type' => 'sayid_project', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids' ) );
$used_topics  = $project_ids ? wp_get_object_terms( $project_ids, 'sayid_topic', array( 'orderby' => 'name' ) ) : array();
$active_topic = isset( $_GET['sayid_topic'] ) ? sanitize_title( wp_unslash( $_GET['sayid_topic'] ) ) : '';
$archive_url  = get_post_type_archive_link( 'sayid_project' );
?>
<main class="section archive-projects">
	<div class="site-container">
		<header class="archive__header">
			<h1 class="archive__title"><?php esc_html_e( 'کارها', 'sayid' ); ?></h1>
			<p class="archive__lede"><?php esc_html_e( 'پروژه‌هایی که هرکدوم یه جور متفاوت من رو درگیر مسئله، سیستم و ساخت تجربه بهتر کردن.', 'sayid' ); ?></p>
		</header>

		<?php if ( ! empty( $used_topics ) && ! is_wp_error( $used_topics ) ) : ?>
			<nav class="archive-tabs" aria-label="<?php esc_attr_e( 'فیلتر دسته‌بندی', 'sayid' ); ?>">
				<a class="archive-tabs__item <?php echo '' === $active_topic ? 'is-active' : ''; ?>" href="<?php echo esc_url( $archive_url ); ?>">
					<?php esc_html_e( 'همه', 'sayid' ); ?>
				</a>
				<?php foreach ( $used_topics as $term ) : ?>
					<a class="archive-tabs__item <?php echo $active_topic === $term->slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'sayid_topic', $term->slug, $archive_url ) ); ?>">
						<?php echo esc_html( $term->name ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>

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
							<div class="project-card__media"><?php the_post_thumbnail( 'sayid-card' ); ?></div>
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
			<p class="archive__empty"><?php esc_html_e( 'هنوز پروژه‌ای منتشر نشده.', 'sayid' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
