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
					<?php if ( $now['building'] ) : ?>
						<div class="now__signal">
							<dt><?php esc_html_e( 'دارم می‌سازم', 'sayid' ); ?></dt>
							<dd><?php echo esc_html( $now['building'] ); ?></dd>
						</div>
					<?php endif; ?>
					<?php if ( $now['exploring'] ) : ?>
						<div class="now__signal">
							<dt><?php esc_html_e( 'دارم تجربه می‌کنم', 'sayid' ); ?></dt>
							<dd><?php echo esc_html( $now['exploring'] ); ?></dd>
						</div>
					<?php endif; ?>
					<?php if ( $now['learning'] ) : ?>
						<div class="now__signal">
							<dt><?php esc_html_e( 'دارم یاد می‌گیرم', 'sayid' ); ?></dt>
							<dd><?php echo esc_html( $now['learning'] ); ?></dd>
						</div>
					<?php endif; ?>
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
	$items = sayid_query_lab_items( 4 );
	if ( empty( $items ) ) {
		return '';
	}
	sayid_enqueue_lab_pointer();
	ob_start();
	?>
	<section class="section section--lab" id="lab" data-sayid-lab>
		<div class="site-container">
			<div class="section__intro">
				<h2 class="section__title"><?php esc_html_e( 'چیزهایی که می‌سازم', 'sayid' ); ?></h2>
				<p class="section__lede"><?php esc_html_e( 'بعضی چیزها از یه مسئله واقعی شروع می‌شن، بعضی‌ها فقط از کنجکاوی. اینجا جاییه برای چیزهایی که می‌سازم، تست می‌کنم، خراب می‌کنم و گاهی هم منتشرشون می‌کنم.', 'sayid' ); ?></p>
			</div>
			<div class="lab-grid">
				<?php foreach ( $items as $i => $item ) : ?>
					<?php sayid_render_lab_card( $item, 0 === $i ); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function sayid_render_lab_card( $item, $primary = false ) {
	$status       = get_post_meta( $item->ID, 'sayid_status', true );
	$status_label = sayid_lab_status_label( $status );
	$short_desc   = get_post_meta( $item->ID, 'sayid_short_description', true );
	$repo_url     = get_post_meta( $item->ID, 'sayid_repo_url', true );
	$live_url     = get_post_meta( $item->ID, 'sayid_live_url', true );
	$href         = $live_url ? $live_url : ( $repo_url ? $repo_url : get_permalink( $item ) );
	$external     = ( $href === $live_url || $href === $repo_url );
	?>
	<article class="lab-card <?php echo $primary ? 'lab-card--primary' : ''; ?>" data-lab-card>
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
	sayid_enqueue_signature_network();

	$nodes = array(
		'design'      => array( 'label' => 'طراحی محصول', 'x' => 30, 'y' => 26 ),
		'systems'     => array( 'label' => 'سیستم‌ها',     'x' => 62, 'y' => 18 ),
		'research'    => array( 'label' => 'تحقیق',        'x' => 14, 'y' => 52 ),
		'code'        => array( 'label' => 'کد',           'x' => 80, 'y' => 48 ),
		'ai'          => array( 'label' => 'هوش مصنوعی',   'x' => 46, 'y' => 68 ),
		'prototyping' => array( 'label' => 'نمونه‌سازی',   'x' => 10, 'y' => 22 ),
		'building'    => array( 'label' => 'ساختن',        'x' => 62, 'y' => 84 ),
		'writing'     => array( 'label' => 'نوشتن',        'x' => 24, 'y' => 86 ),
	);

	$edges = array(
		array( 'design', 'code', 'وقتی متریال ساخت رو بشناسی، تصمیم‌های طراحی هم عوض می‌شن.' ),
		array( 'design', 'systems', 'یه رابط خوب فقط یه صفحه‌ی خوب نیست؛ باید در مقیاس بزرگ هم منسجم بمونه.' ),
		array( 'design', 'research', 'جواب خوب معمولاً قبل از طراحی صفحه شروع می‌شه؛ از فهمیدن مسئله.' ),
		array( 'design', 'prototyping', 'نمونه‌سازی سریع، تصمیم‌های طراحی رو زودتر محک می‌زنه.' ),
		array( 'ai', 'building', 'فاصله بین یه ایده و یه تجربه واقعی هر روز داره کمتر می‌شه.' ),
		array( 'ai', 'code', 'کد نوشتن با کمک هوش مصنوعی، مسیر رسیدن به نسخه‌ی اول رو کوتاه‌تر می‌کنه.' ),
		array( 'ai', 'research', 'هوش مصنوعی کمک می‌کنه سریع‌تر بین داده‌ها و ایده‌ها ارتباط پیدا کنی.' ),
		array( 'systems', 'code', 'توکن‌ها و پایه‌های قابل‌استفاده‌ی دوباره، پل بین طراحی و کدن.' ),
		array( 'building', 'writing', 'مستندسازی چیزی که ساختی، خودش بخشی از ساختنه.' ),
		array( 'research', 'writing', 'نوشتن، ادامه‌ی طبیعی فکر‌کردن و تحقیقه.' ),
	);

	ob_start();
	?>
	<section class="section section--signature" id="signature" data-sayid-signature>
		<div class="site-container">
			<p class="signature__eyebrow"><?php esc_html_e( 'طرز فکر', 'sayid' ); ?></p>
			<h2 class="signature__title"><?php esc_html_e( 'طراحی × کد × هوش مصنوعی', 'sayid' ); ?></h2>
			<p class="signature__thesis"><?php esc_html_e( 'کارهای جالب معمولاً درست جایی اتفاق می‌افتن که مرز بین چند مهارت محو می‌شه.', 'sayid' ); ?></p>

			<div class="signature-network" data-signature-network>
				<svg class="signature-network__lines" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
					<?php foreach ( $edges as $edge ) : list( $from, $to ) = $edge; ?>
						<line
							class="signature-network__edge"
							data-edge
							data-from="<?php echo esc_attr( $from ); ?>"
							data-to="<?php echo esc_attr( $to ); ?>"
							x1="<?php echo esc_attr( $nodes[ $from ]['x'] ); ?>"
							y1="<?php echo esc_attr( $nodes[ $from ]['y'] ); ?>"
							x2="<?php echo esc_attr( $nodes[ $to ]['x'] ); ?>"
							y2="<?php echo esc_attr( $nodes[ $to ]['y'] ); ?>"
						></line>
					<?php endforeach; ?>
				</svg>

				<div class="signature-network__nodes">
					<?php foreach ( $nodes as $key => $node ) : ?>
						<button
							type="button"
							class="signature-node"
							data-node="<?php echo esc_attr( $key ); ?>"
							style="--nx:<?php echo esc_attr( $node['x'] ); ?>%;--ny:<?php echo esc_attr( $node['y'] ); ?>%"
						><?php echo esc_html( $node['label'] ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>

			<p class="signature__relationship" data-signature-relationship aria-live="polite">
				<?php esc_html_e( 'به یکی از نقطه‌ها اشاره کن یا با کیبورد بین‌شون حرکت کن.', 'sayid' ); ?>
			</p>

			<script type="application/json" class="signature-network__edges-data" data-signature-edges>
				<?php echo wp_json_encode( array_map( function ( $edge ) {
					return array( 'from' => $edge[0], 'to' => $edge[1], 'message' => $edge[2] );
				}, $edges ) ); ?>
			</script>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** ---------- Latest Notes ---------- */
function sayid_render_notes( $limit = 5 ) {
	$notes = sayid_query_latest_notes( $limit );
	if ( empty( $notes ) ) {
		return '';
	}
	ob_start();
	?>
	<section class="section section--notes" id="notes" data-sayid-notes>
		<div class="site-container">
			<h2 class="section__title"><?php esc_html_e( 'یادداشت‌های تازه', 'sayid' ); ?></h2>
			<ul class="note-list">
				<?php foreach ( $notes as $note ) :
					$terms = get_the_terms( $note->ID, 'sayid_topic' );
					$tag   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
					?>
					<li class="note-row">
						<a class="note-row__link" href="<?php echo esc_url( get_permalink( $note ) ); ?>">
							<time class="note-row__date" datetime="<?php echo esc_attr( get_the_date( 'c', $note ) ); ?>">
								<?php echo esc_html( sayid_format_date_short( get_the_time( 'U', $note ) ) ); ?>
							</time>
							<span class="note-row__title"><?php echo esc_html( get_the_title( $note ) ); ?></span>
							<?php if ( $tag ) : ?>
								<span class="note-row__tag"><?php echo esc_html( $tag ); ?></span>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<a class="section__cta" href="<?php echo esc_url( get_post_type_archive_link( 'sayid_note' ) ); ?>">
				<?php esc_html_e( 'همه‌ی یادداشت‌ها', 'sayid' ); ?>
			</a>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** ---------- Featured Article ---------- */
function sayid_render_featured_article() {
	$article = sayid_query_featured_article();
	if ( ! $article ) {
		return '';
	}
	$subtitle = get_post_meta( $article->ID, 'sayid_subtitle', true );
	ob_start();
	?>
	<section class="section section--article" id="article" data-sayid-article>
		<div class="site-container">
			<article class="featured-article">
				<a class="featured-article__media" href="<?php echo esc_url( get_permalink( $article ) ); ?>">
					<?php echo sayid_cover_html( get_post_thumbnail_id( $article ), 'sayid-featured', get_the_title( $article ) ); ?>
				</a>
				<div class="featured-article__body">
					<span class="featured-article__eyebrow"><?php esc_html_e( 'نوشته منتخب', 'sayid' ); ?></span>
					<h2 class="featured-article__title">
						<a href="<?php echo esc_url( get_permalink( $article ) ); ?>"><?php echo esc_html( get_the_title( $article ) ); ?></a>
					</h2>
					<?php if ( $subtitle ) : ?>
						<p class="featured-article__excerpt"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
					<p class="featured-article__meta"><?php echo esc_html( sayid_reading_time_label( $article->post_content ) ); ?></p>
					<a class="featured-article__cta" href="<?php echo esc_url( get_permalink( $article ) ); ?>">
						<?php esc_html_e( 'ادامه‌ی نوشته', 'sayid' ); ?>
					</a>
				</div>
			</article>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** ---------- Connect ---------- */
function sayid_render_connect() {
	ob_start();
	?>
	<section class="section section--connect" id="connect" data-sayid-connect>
		<div class="site-container connect">
			<h2 class="connect__title"><?php esc_html_e( 'یه ایده جالب توی ذهنت داری؟', 'sayid' ); ?></h2>
			<p class="connect__sub"><?php esc_html_e( 'حرف بزنیم.', 'sayid' ); ?></p>
			<p class="connect__support"><?php esc_html_e( 'درباره محصول، طراحی، ساختن یا یه مسئله‌ای که هنوز جواب واضحی براش پیدا نکردی.', 'sayid' ); ?></p>
			<div class="connect__actions">
				<a class="btn btn--primary" href="mailto:i@moghadam.pro"><?php esc_html_e( 'شروع گفتگو', 'sayid' ); ?></a>
				<a class="btn btn--ghost" href="https://www.linkedin.com/in/moghadampro/" target="_blank" rel="noopener">LinkedIn</a>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/** ---------- Theme switch (footer control) ---------- */
function sayid_render_theme_switch() {
	ob_start();
	?>
	<div class="theme-switch" data-theme-switch role="group" aria-label="<?php esc_attr_e( 'حالت نمایش', 'sayid' ); ?>">
		<button type="button" class="theme-switch__btn" data-theme-option="system"><?php esc_html_e( 'سیستم', 'sayid' ); ?></button>
		<button type="button" class="theme-switch__btn" data-theme-option="light"><?php esc_html_e( 'روشن', 'sayid' ); ?></button>
		<button type="button" class="theme-switch__btn" data-theme-option="dark"><?php esc_html_e( 'تاریک', 'sayid' ); ?></button>
	</div>
	<?php
	return ob_get_clean();
}

/** ---------- Related content rail (single templates) ---------- */
function sayid_render_related( $post_id ) {
	$related = sayid_get_related( $post_id );
	$all     = array_merge( $related['notes'], $related['articles'], $related['lab'], $related['projects'] );
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
		sayid_render_featured_article(),
		sayid_render_connect(),
	) ) );
}
