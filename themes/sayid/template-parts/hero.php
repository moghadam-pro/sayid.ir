<?php
/**
 * The homepage Hero — static, hard-coded content matching the reference
 * screenshot (see docs/16-final-implementation-report.md "Theme pivot"
 * section for the full context of this rebuild). Nav lives inside this
 * section so header + hero read as one continuous first-viewport
 * composition, per the original brief's Hero direction.
 *
 * The background text is two independent marquee lines moving opposite
 * directions at a slow, constant speed — animated in hero.css, no JS
 * required for the motion itself; hero-marquee.js only pauses it under
 * `prefers-reduced-motion` and clones each line's content for a seamless
 * loop.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

sayid_enqueue_hero_marquee();
sayid_enqueue_hero_rotator();

$hero_photo_id = sayid_hero_photo_id();
$visitor_ip    = sayid_get_visitor_ip();
?>
<section class="home-hero" data-sayid-hero>
	<div class="home-hero__marquee" aria-hidden="true" data-hero-marquee>
		<div class="home-hero__marquee-row home-hero__marquee-row--a">
			<span>SENIOR PRODUCT DESIGNER&nbsp;</span>
			<span>SENIOR PRODUCT DESIGNER&nbsp;</span>
		</div>
		<div class="home-hero__marquee-row home-hero__marquee-row--b home-hero__marquee-row--outline">
			<span>SENIOR PRODUCT STORIES&nbsp;</span>
			<span>SENIOR PRODUCT STORIES&nbsp;</span>
		</div>
	</div>

	<header class="home-hero__nav">
		<div class="site-container">
			<?php get_template_part( 'template-parts/site-nav' ); ?>
		</div>
	</header>

	<div class="home-hero__inner site-container">
		<aside class="home-hero__social" aria-label="<?php esc_attr_e( 'شبکه‌های اجتماعی', 'sayid' ); ?>">
			<span class="home-hero__social-line" aria-hidden="true"></span>
			<ul>
				<?php foreach ( array( 'instagram', 'dribbble', 'figma' ) as $key ) :
					$links = sayid_social_links();
					if ( empty( $links[ $key ] ) ) {
						continue;
					}
					$link = $links[ $key ];
					?>
					<li><a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $link['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</aside>

		<div class="home-hero__media">
			<?php if ( $hero_photo_id ) : ?>
				<?php echo wp_get_attachment_image( $hero_photo_id, 'sayid-featured', false, array( 'class' => 'home-hero__photo' ) ); ?>
			<?php else : ?>
				<div class="home-hero__photo home-hero__photo--placeholder" role="img" aria-label="<?php esc_attr_e( 'عکس پرتره‌ی سید مقدم', 'sayid' ); ?>"></div>
			<?php endif; ?>
		</div>

		<div class="home-hero__content">
			<p class="home-hero__greeting"><?php esc_html_e( 'سلام ، من', 'sayid' ); ?></p>
			<h1 class="home-hero__name">
				<?php esc_html_e( 'سعید مقدم', 'sayid' ); ?>
				<span class="home-hero__name-suffix"><?php esc_html_e( 'هستم', 'sayid' ); ?></span>
			</h1>
			<p class="home-hero__role home-hero__role--outline"><?php esc_html_e( 'طراح ارشد محصول', 'sayid' ); ?></p>
			<p class="home-hero__lede"><?php esc_html_e( 'با بیش از ۱۵ سال تجربه کاری حرفه‌ای، داستان‌های زیادی برای گفتن دارم', 'sayid' ); ?></p>

			<div class="home-hero__actions">
				<?php // Only the phrase currently in view is exposed to assistive tech — the other eleven are visually clipped, so leaving them in the tree would read all twelve as one run-on string. hero-rotator.js moves the aria-hidden as it advances, which is the content change the live region announces. ?>
				<div class="home-hero__rotator" data-hero-rotator aria-live="polite">
					<ul class="home-hero__rotator-track">
						<?php foreach ( sayid_hero_rotator_phrases() as $i => $phrase ) : ?>
							<li<?php echo $i ? ' aria-hidden="true"' : ''; ?>><?php echo esc_html( $phrase ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<a class="btn btn--accent" href="<?php echo esc_url( get_post_type_archive_link( 'sayid_project' ) ); ?>">
					<?php esc_html_e( 'بزن بریم نمونه کار ببینیم', 'sayid' ); ?>
				</a>
			</div>

			<?php if ( $visitor_ip ) : ?>
				<p class="home-hero__ip">
					Your IP: <?php echo esc_html( $visitor_ip ); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<div class="scroll-cue" data-scroll-cue>
		<button type="button" class="scroll-cue__btn" data-scroll-cue-btn>
			<span class="scroll-cue__label"><?php esc_html_e( 'شروع به اسکرول کنید', 'sayid' ); ?></span>
			<span class="scroll-cue__icon" aria-hidden="true">
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 6L8 11L13 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
			</span>
		</button>
	</div>
</section>
