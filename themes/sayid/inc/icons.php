<?php
/**
 * Small inline SVG icons for the footer (theme switch + social links).
 *
 * Social icons are deliberately plain monogram badges (a circle + one or
 * two letters), not attempts to reproduce each platform's actual logo
 * mark — redrawing trademarked logo artwork from memory risks getting it
 * subtly wrong, and a simplified monogram is a safe, clean, common pattern
 * for a personal site footer. Swap in real brand SVGs later if wanted.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sayid_icon_theme( $mode ) {
	switch ( $mode ) {
		case 'light':
			return '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><circle cx="8" cy="8" r="3.5" stroke="currentColor" stroke-width="1.4"/><path d="M8 1v1.6M8 13.4V15M15 8h-1.6M2.6 8H1M12.7 3.3l-1.1 1.1M4.4 11.6l-1.1 1.1M12.7 12.7l-1.1-1.1M4.4 4.4L3.3 3.3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>';
		case 'dark':
			return '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M13.5 9.5A6 6 0 1 1 6.5 2.5a5 5 0 0 0 7 7Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg>';
		default: // system
			return '<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><rect x="1.5" y="2.5" width="13" height="9" rx="1.5" stroke="currentColor" stroke-width="1.4"/><path d="M5.5 14h5M8 11.5V14" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>';
	}
}

function sayid_icon_social( $key ) {
	$monograms = array(
		'instagram' => 'IG',
		'dribbble'  => 'DR',
		'figma'     => 'FG',
		'linkedin'  => 'IN',
		'github'    => 'GH',
	);
	$label = isset( $monograms[ $key ] ) ? $monograms[ $key ] : strtoupper( substr( $key, 0, 2 ) );
	ob_start();
	?>
	<svg width="28" height="28" viewBox="0 0 28 28" aria-hidden="true">
		<circle cx="14" cy="14" r="13" fill="none" stroke="currentColor" stroke-width="1.2" opacity=".4" />
		<text x="14" y="18" text-anchor="middle" font-size="9" font-weight="600" fill="currentColor" font-family="var(--font-family)"><?php echo esc_html( $label ); ?></text>
	</svg>
	<?php
	return ob_get_clean();
}
