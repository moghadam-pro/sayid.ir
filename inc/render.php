<?php
/**
 * Render functions for every homepage section + the related-content rail.
 * Called directly from template files (front-page.php, single-*.php) —
 * no shortcode/Elementor-widget indirection needed now that this theme
 * owns its own templates end to end.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** ---------- Now ---------- */
function sayid_render_now() {
	$now = sayid_get_now();
	if ( empty( $now['statement'] ) && empty( $now['building'] ) ) {
		return '';
	}
	ob_start();
	?>
	<section class="section section--now" id="now" data-sayid-now>
		<div class="site-container">
			<div class="now">
				<div class="now__intro">
					<span class="now__eyebrow">
						<span class="now__dot" aria-hidden="true"></span>
						<?php esc_html_e( 'این روزها', 'sayid' ); ?>
					</span>
					<?php if ( $now['statement'] ) : ?>
						<p class="now__statement"><?php echo esc_html( $now['statement'] ); ?></p>
					<?php endif; ?>
					<?php if ( $now['link_url'] && $now['link_label'] ) : ?>
						<a class="now__link" href="<?php echo esc_url( $now['link_url'] ); ?>">
							<?php echo esc_html( $now['link_label'] ); ?>
						</a>
					<?php endif; ?>
				</div>
				<dl class="now__signals">
					<?php foreach ( array_keys( sayid_now_default_labels() ) as $key ) : ?>
						<?php if ( $now[ $key ] ) : ?>
							<div class="now__signal">
								<dt><?php echo esc_html( $now[ $key . '_label' ] ); ?></dt>
								<dd><?php echo nl2br( esc_html( $now[ $key ] ) ); // phpcs:ignore ?></dd>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</dl>
			</div>
			<?php if ( $now['updated_at'] ) : ?>
				<p class="now__updated">
					<?php esc_html_e( 'به‌روزرسانی:', 'sayid' ); ?>
					<time datetime="<?php echo esc_attr( gmdate( 'c', (int) $now['updated_at'] ) ); ?>">
						<?php echo esc_html( sayid_format_date( (int) $now['updated_at'] ) ); ?>
					</time>
				</p>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** ---------- Selected Work ---------- */
function sayid_render_selected_work() {
	$projects = sayid_query_selected_projects( 3 );
	if ( empty( $projects ) ) {
		return '';
	}
	ob_start();
	?>
	<section class="section section--work" id="work" data-sayid-work>
		<div class="site-container">
			<div class="section__intro">
				<h2 class="section__title"><?php esc_html_e( 'چند کار منتخب', 'sayid' ); ?></h2>
				<p class="section__lede"><?php esc_html_e( 'چند پروژه‌ای که هرکدوم یه جور متفاوت من رو درگیر مسئله، سیستم و ساخت تجربه بهتر کردن.', 'sayid' ); ?></p>
			</div>
			<div class="work-grid">
				<?php foreach ( $projects as $i => $project ) : ?>
					<?php sayid_render_project_card( $project, 0 === $i ); ?>
				<?php endforeach; ?>
			</div>
			<a class="section__cta" href="<?php echo esc_url( get_post_type_archive_link( 'sayid_project' ) ); ?>">
				<?php esc_html_e( 'همه‌ی کارها', 'sayid' ); ?>
			</a>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function sayid_render_project_card( $project, $featured = false ) {
	$role       = get_post_meta( $project->ID, 'sayid_role', true );
	$org        = get_post_meta( $project->ID, 'sayid_organization', true );
	$short_desc = get_post_meta( $project->ID, 'sayid_short_description', true );
	$context    = trim( implode( ' · ', array_filter( array( $role, $org ) ) ) );
	?>
	<article class="project-card <?php echo $featured ? 'project-card--featured' : ''; ?>" data-project-card>
		<a class="project-card__link" href="<?php echo esc_url( get_permalink( $project ) ); ?>">
			<div class="project-card__media">
				<?php echo sayid_cover_html( get_post_thumbnail_id( $project ), $featured ? 'sayid-featured' : 'sayid-card', $project->post_title ); ?>
			</div>
			<div class="project-card__body">
				<?php if ( $context ) : ?>
					<p class="project-card__context"><?php echo esc_html( $context ); ?></p>
				<?php endif; ?>
				<h3 class="project-card__title"><?php echo esc_html( $project->post_title ); ?></h3>
				<?php if ( $short_desc ) : ?>
					<p class="project-card__desc"><?php echo esc_html( $short_desc ); ?></p>
				<?php endif; ?>
			</div>
		</a>
	</article>
	<?php
}

/** ---------- Lab ---------- */
function sayid_render_lab() {
	$count = max( 1, (int) get_theme_mod( 'sayid_lab_count', 6 ) );
	$items = sayid_query_lab_items( $count );
	if ( empty( $items ) ) {
		return '';
	}
	sayid_enqueue_lab_pointer();
	$title       = sayid_theme_text( 'sayid_lab_title', __( 'چیزهایی که می‌سازم', 'sayid' ) );
	$description = sayid_theme_text( 'sayid_lab_description', __( 'بعضی چیزها از یه مسئله واقعی شروع می‌شن، بعضی‌ها فقط از کنجکاوی. اینجا جاییه برای چیزهایی که می‌سازم، تست می‌کنم، خراب می‌کنم و گاهی هم منتشرشون می‌کنم.', 'sayid' ) );
	ob_start();
	?>
	<section class="section section--lab" id="lab" data-sayid-lab>
		<div class="site-container">
			<div class="section__intro">
				<h2 class="section__title"><?php echo esc_html( $title ); ?></h2>
				<p class="section__lede"><?php echo esc_html( $description ); ?></p>
			</div>
			<div class="lab-grid">
				<?php foreach ( $items as $item ) : ?>
					<?php // No "primary" (bento) card here — this grid is a uniform 3x2, per the homepage design. ?>
					<?php sayid_render_lab_card( $item ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function sayid_render_lab_card( $item ) {
	$status       = get_post_meta( $item->ID, 'sayid_status', true );
	$status_label = sayid_lab_status_label( $status );
	$short_desc   = get_post_meta( $item->ID, 'sayid_short_description', true );
	$repo_url     = get_post_meta( $item->ID, 'sayid_repo_url', true );
	$live_url     = get_post_meta( $item->ID, 'sayid_live_url', true );
	$href         = $live_url ? $live_url : ( $repo_url ? $repo_url : get_permalink( $item ) );
	$external     = ( $href === $live_url || $href === $repo_url );
	?>
	<article class="lab-card" data-lab-card>
		<span class="lab-card__border" aria-hidden="true"></span>
		<a class="lab-card__link" href="<?php echo esc_url( $href ); ?>" <?php echo $external ? 'target="_blank" rel="noopener"' : ''; ?>>
			<div class="lab-card__body">
				<?php if ( $status_label ) : ?>
					<span class="lab-card__status" data-status="<?php echo esc_attr( $status ); ?>"><?php echo esc_html( $status_label ); ?></span>
				<?php endif; ?>
				<h3 class="lab-card__title"><?php echo esc_html( $item->post_title ); ?></h3>
				<?php if ( $short_desc ) : ?>
					<p class="lab-card__desc"><?php echo esc_html( $short_desc ); ?></p>
				<?php endif; ?>
			</div>
		</a>
	</article>
	<?php
}

/** ---------- Signature: Design × Code × AI ---------- */
function sayid_render_signature() {
	if ( ! get_theme_mod( 'sayid_signature_enabled', true ) ) {
		return '';
	}
	sayid_enqueue_signature_venn();

	$eyebrow          = sayid_theme_text( 'sayid_signature_eyebrow', __( 'طرز فکر', 'sayid' ) );
	$title            = sayid_theme_text( 'sayid_signature_title', __( 'طراحی × کد × هوش مصنوعی', 'sayid' ) );
	$default_message  = sayid_theme_text( 'sayid_signature_thesis', __( 'کارهای جالب معمولاً درست جایی اتفاق می‌افتن که مرز بین چند مهارت محو می‌شه.', 'sayid' ) );

	// Three discipline circles + every zone a visitor can activate: the
	// three pairwise overlaps and the center where all three meet. Each
	// zone has its own real Persian sentence — no zone is decorative-only.
	$zones = array(
		'design-code' => 'وقتی متریال ساخت رو بشناسی، تصمیم‌های طراحی هم عوض می‌شن.',
		'design-ai'   => 'نمونه‌سازی سریع با کمک هوش مصنوعی، تصمیم‌های طراحی رو زودتر محک می‌زنه.',
		'code-ai'     => 'کد نوشتن با کمک هوش مصنوعی، مسیر رسیدن به نسخه‌ی اول رو کوتاه‌تر می‌کنه.',
		'triple'      => 'جایی که هر سه همدیگه رو قطع می‌کنن، دیگه لازم نیست ایده رو تحویل کسی بدی تا ساخته بشه؛ خودت از فکر تا نسخه‌ی کارکرده می‌بریش.',
	);

	$chips = array(
		'design-code' => 'طراحی × کد',
		'design-ai'   => 'طراحی × هوش مصنوعی',
		'code-ai'     => 'کد × هوش مصنوعی',
		'triple'      => 'هر سه با هم',
	);

	ob_start();
	?>
	<section class="section section--signature" id="signature" data-sayid-signature>
		<div class="site-container signature__grid">
			<div class="signature__intro">
				<p class="signature__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<h2 class="signature__title"><?php echo esc_html( $title ); ?></h2>
				<p class="signature__thesis"><?php echo esc_html( $default_message ); ?></p>
			</div>

			<div class="signature-venn" data-signature-venn>
				<svg class="signature-venn__diagram" viewBox="0 0 400 380" aria-hidden="true">
					<defs>
						<clipPath id="sayid-venn-clip-design"><circle cx="150" cy="150" r="125" /></clipPath>
						<clipPath id="sayid-venn-clip-code"><circle cx="250" cy="150" r="125" /></clipPath>
						<clipPath id="sayid-venn-clip-ai"><circle cx="200" cy="245" r="125" /></clipPath>
					</defs>

					<circle class="signature-venn__circle signature-venn__circle--design" cx="150" cy="150" r="125" />
					<circle class="signature-venn__circle signature-venn__circle--code" cx="250" cy="150" r="125" />
					<circle class="signature-venn__circle signature-venn__circle--ai" cx="200" cy="245" r="125" />

					<g clip-path="url(#sayid-venn-clip-design)">
						<circle class="signature-venn__overlap" data-zone="design-code" clip-path="url(#sayid-venn-clip-code)" cx="150" cy="150" r="125" />
						<circle class="signature-venn__overlap" data-zone="design-ai" clip-path="url(#sayid-venn-clip-ai)" cx="150" cy="150" r="125" />
					</g>
					<g clip-path="url(#sayid-venn-clip-code)">
						<circle class="signature-venn__overlap" data-zone="code-ai" clip-path="url(#sayid-venn-clip-ai)" cx="250" cy="150" r="125" />
					</g>
					<g clip-path="url(#sayid-venn-clip-design)">
						<g clip-path="url(#sayid-venn-clip-code)">
							<circle class="signature-venn__overlap signature-venn__overlap--triple" data-zone="triple" clip-path="url(#sayid-venn-clip-ai)" cx="150" cy="150" r="125" />
						</g>
					</g>

					<?php // text-anchor="middle" on all three: it's direction-neutral, so the labels stay put under this site's dir="rtl" root. ?>
					<text class="signature-venn__label" x="105" y="70" text-anchor="middle">طراحی</text>
					<text class="signature-venn__label" x="295" y="70" text-anchor="middle">کد</text>
					<text class="signature-venn__label" x="200" y="350" text-anchor="middle">هوش مصنوعی</text>
				</svg>

				<div class="signature-venn__chips" role="group" aria-label="<?php esc_attr_e( 'رابطه‌ها', 'sayid' ); ?>">
					<?php foreach ( $chips as $zone => $label ) : ?>
						<button type="button" class="signature-venn__chip" data-zone-trigger="<?php echo esc_attr( $zone ); ?>">
							<?php echo esc_html( $label ); ?>
						</button>
					<?php endforeach; ?>
				</div>

				<p class="signature__relationship" data-signature-relationship aria-live="polite">
					<?php echo esc_html( $default_message ); ?>
				</p>
			</div>

			<script type="application/json" data-signature-zones>
				<?php echo wp_json_encode( array( 'default' => $default_message, 'zones' => $zones ) ); ?>
			</script>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** ---------- Latest Notes ---------- */
function sayid_render_notes( $limit = 0 ) {
	if ( ! get_theme_mod( 'sayid_notes_enabled', true ) ) {
		return '';
	}
	$limit = $limit ? $limit : max( 1, (int) get_theme_mod( 'sayid_notes_count', 5 ) );
	$notes = sayid_query_latest_notes( $limit );
	if ( empty( $notes ) ) {
		return '';
	}
	sayid_enqueue_note_row_typewriter();
	ob_start();
	?>
	<section class="section section--notes" id="notes" data-sayid-notes>
		<div class="site-container">
			<div class="section__intro section__intro--row">
				<h2 class="section__title"><?php esc_html_e( 'یادداشت‌های تازه', 'sayid' ); ?></h2>
				<a class="section__cta section__cta--inline" href="<?php echo esc_url( get_category_link( sayid_notes_category_id() ) ); ?>">
					<?php esc_html_e( 'همه‌ی یادداشت‌ها', 'sayid' ); ?>
				</a>
			</div>
			<ul class="note-list">
				<?php foreach ( $notes as $note ) :
					$terms   = get_the_terms( $note->ID, 'sayid_topic' );
					$tag     = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
					// Plain-text snippet for the hover "typing" reveal — CSS
					// clips it to one line, but there's no point typing out
					// more characters than could ever fit on one, so this
					// stays generously short rather than dumping the whole
					// post body into the markup.
					$excerpt = mb_substr( wp_strip_all_tags( $note->post_content ), 0, 200 );
					?>
					<li class="note-row">
						<a class="note-row__link" href="<?php echo esc_url( get_permalink( $note ) ); ?>">
							<time class="note-row__date" datetime="<?php echo esc_attr( get_the_date( 'c', $note ) ); ?>">
								<?php echo esc_html( sayid_format_date_short( get_the_time( 'U', $note ) ) ); ?>
							</time>
							<span class="note-row__title-group">
								<span class="note-row__title"><?php echo esc_html( get_the_title( $note ) ); ?></span>
								<?php if ( $excerpt ) : ?>
									<span class="note-row__excerpt" data-note-excerpt="<?php echo esc_attr( $excerpt ); ?>"></span>
								<?php endif; ?>
							</span>
							<?php if ( $tag ) : ?>
								<span class="note-row__tag"><?php echo esc_html( $tag ); ?></span>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** ---------- Articles (compact multi-post grid, not one giant card) ---------- */
function sayid_render_articles( $limit = 3 ) {
	if ( ! get_theme_mod( 'sayid_articles_enabled', true ) ) {
		return '';
	}
	$articles = sayid_query_articles( $limit );
	if ( empty( $articles ) ) {
		return '';
	}
	ob_start();
	?>
	<section class="section section--article" id="article" data-sayid-article>
		<div class="site-container">
			<div class="section__intro section__intro--row">
				<h2 class="section__title"><?php esc_html_e( 'نوشته‌ها', 'sayid' ); ?></h2>
				<?php $archive = sayid_articles_archive_url(); ?>
				<?php if ( $archive ) : ?>
					<a class="section__cta section__cta--inline" href="<?php echo esc_url( $archive ); ?>">
						<?php esc_html_e( 'همه‌ی نوشته‌ها', 'sayid' ); ?>
					</a>
				<?php endif; ?>
			</div>
			<div class="article-grid">
				<?php foreach ( $articles as $article ) :
					$subtitle = get_post_meta( $article->ID, 'sayid_subtitle', true );
					?>
					<article class="article-card">
						<a class="article-card__media" href="<?php echo esc_url( get_permalink( $article ) ); ?>">
							<?php echo sayid_cover_html( get_post_thumbnail_id( $article ), 'sayid-card', get_the_title( $article ) ); ?>
						</a>
						<div class="article-card__body">
							<h3 class="article-card__title">
								<a href="<?php echo esc_url( get_permalink( $article ) ); ?>"><?php echo esc_html( get_the_title( $article ) ); ?></a>
							</h3>
							<?php if ( $subtitle ) : ?>
								<p class="article-card__excerpt"><?php echo esc_html( $subtitle ); ?></p>
							<?php endif; ?>
							<p class="article-card__meta">
								<?php echo esc_html( sayid_format_date_short( get_the_time( 'U', $article ) ) ); ?>
								·
								<?php echo esc_html( sayid_reading_time_label( $article->post_content ) ); ?>
							</p>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** ---------- Connect ---------- */
function sayid_render_connect() {
	$title       = sayid_theme_text( 'sayid_connect_title', __( 'یه ایده جالب توی ذهنت داری؟', 'sayid' ) );
	$subtitle    = sayid_theme_text( 'sayid_connect_subtitle', __( 'حرف بزنیم.', 'sayid' ) );
	$description = sayid_theme_text( 'sayid_connect_description', __( 'درباره محصول، طراحی، ساختن یا یه مسئله‌ای که هنوز جواب واضحی براش پیدا نکردی.', 'sayid' ) );
	$btn1_label  = sayid_theme_text( 'sayid_connect_btn1_label', __( 'شروع گفتگو', 'sayid' ) );
	$btn1_url    = sayid_theme_text( 'sayid_connect_btn1_url', sayid_contact_page_url() );
	$btn2_label  = sayid_theme_text( 'sayid_connect_btn2_label', 'LinkedIn' );
	$btn2_url    = sayid_theme_text( 'sayid_connect_btn2_url', 'https://www.linkedin.com/in/moghadampro/' );
	ob_start();
	?>
	<section class="section section--connect" id="connect" data-sayid-connect>
		<div class="site-container connect">
			<h2 class="connect__title"><?php echo esc_html( $title ); ?></h2>
			<p class="connect__sub"><?php echo esc_html( $subtitle ); ?></p>
			<p class="connect__support"><?php echo esc_html( $description ); ?></p>
			<div class="connect__actions">
				<a class="btn btn--primary" href="<?php echo esc_url( $btn1_url ); ?>"><?php echo esc_html( $btn1_label ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( $btn2_url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $btn2_label ); ?></a>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** ---------- Theme switch (header control) ---------- */
function sayid_render_theme_switch() {
	ob_start();
	?>
	<div class="theme-switch" data-theme-switch role="group" aria-label="<?php esc_attr_e( 'حالت نمایش', 'sayid' ); ?>">
		<button type="button" class="theme-switch__btn" data-theme-option="system" aria-label="<?php esc_attr_e( 'سیستم', 'sayid' ); ?>" title="<?php esc_attr_e( 'سیستم', 'sayid' ); ?>"><?php echo sayid_icon_theme( 'system' ); // phpcs:ignore ?></button>
		<button type="button" class="theme-switch__btn" data-theme-option="light" aria-label="<?php esc_attr_e( 'روشن', 'sayid' ); ?>" title="<?php esc_attr_e( 'روشن', 'sayid' ); ?>"><?php echo sayid_icon_theme( 'light' ); // phpcs:ignore ?></button>
		<button type="button" class="theme-switch__btn" data-theme-option="dark" aria-label="<?php esc_attr_e( 'تاریک', 'sayid' ); ?>" title="<?php esc_attr_e( 'تاریک', 'sayid' ); ?>"><?php echo sayid_icon_theme( 'dark' ); // phpcs:ignore ?></button>
	</div>
	<?php
	return ob_get_clean();
}

/** ---------- Related content rail (single templates) ---------- */
function sayid_render_related( $post_id ) {
	$related = sayid_get_related( $post_id );
	$all     = array_merge( $related['posts'], $related['lab'], $related['projects'] );
	if ( empty( $all ) ) {
		return '';
	}
	ob_start();
	?>
	<aside class="related-content" data-sayid-related>
		<h2 class="related-content__title"><?php esc_html_e( 'مطالب مرتبط', 'sayid' ); ?></h2>
		<ul class="related-content__list">
			<?php foreach ( $all as $item ) : ?>
				<li class="related-content__item">
					<a href="<?php echo esc_url( get_permalink( $item ) ); ?>">
						<span class="related-content__type"><?php echo esc_html( get_post_type_object( $item->post_type )->labels->singular_name ); ?></span>
						<span class="related-content__label"><?php echo esc_html( get_the_title( $item ) ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</aside>
	<?php
	return ob_get_clean();
}

/** ---------- Homepage sections wrapper ---------- */
function sayid_render_homepage_sections() {
	return implode( '', array_filter( array(
		sayid_render_now(),
		sayid_render_selected_work(),
		sayid_render_lab(),
		sayid_render_signature(),
		sayid_render_notes(),
		sayid_render_articles(),
		sayid_render_connect(),
	) ) );
}
