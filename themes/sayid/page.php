<?php
/**
 * Generic page template — used for About and any other plain editorial
 * page. Content/structure (per brief §44's About direction) is authored
 * directly in the block editor; this template only provides the
 * typographic frame.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) : the_post();
	?>
	<main class="section single-page">
		<div class="site-container editorial-width">
			<article <?php post_class(); ?>>
				<header class="single-project__header">
					<h1 class="single-project__title"><?php the_title(); ?></h1>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-note__cover"><?php the_post_thumbnail( 'sayid-featured' ); ?></div>
				<?php endif; ?>
				<div class="prose"><?php the_content(); ?></div>
			</article>
		</div>
	</main>
	<?php
endwhile;
get_footer();
