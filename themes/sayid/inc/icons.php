<?php
/**
 * Small inline SVG icons for the header/footer (theme switch + social
 * links). The social marks are the outline glyphs supplied directly by
 * Sayid (not brand wordmarks) — `stroke="currentColor"` so they inherit
 * `color` from CSS instead of the fixed gray they were authored with,
 * which is what lets `.footer-icons__social a:hover` turn them yellow.
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
	$icons = array(
		'github'    => '<svg width="20" height="20" viewBox="0 0 24 25" fill="none" aria-hidden="true"><path d="M8.57148 19.4286C4.28548 20.7786 4.48548 16.6786 2.77148 16.2286M15.4286 23.4286V17.7286C15.4286 16.7286 15.4562 16.0236 14.8572 15.4286C17.6482 15.1286 20 14.134 20 9.46C19.9962 8.25613 19.5171 7.10249 18.667 6.25C18.8676 5.73351 18.9638 5.18239 18.9501 4.62849C18.9364 4.07459 18.813 3.52891 18.587 3.023C18.587 3.023 17.537 2.723 15.111 4.29C13.0711 3.75828 10.929 3.75828 8.88904 4.29C6.46204 2.723 5.41304 3.023 5.41304 3.023C5.18711 3.52891 5.06371 4.07459 5.04998 4.62849C5.03624 5.18239 5.13245 5.73351 5.33304 6.25C4.90896 6.67522 4.57293 7.17993 4.34419 7.73521C4.11545 8.2905 3.9985 8.88545 4.00004 9.486C4.00004 14.126 6.35191 15.0946 9.14291 15.4286C8.55191 16.0176 8.51148 17.0116 8.57148 17.8286V23.4286" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'linkedin'  => '<svg width="20" height="20" viewBox="0 0 25 25" fill="none" aria-hidden="true"><path d="M6.46429 9.32143V18.4643M17.8929 18.4643V13.8929C17.8929 11.6837 16.102 9.89286 13.8929 9.89286C11.6837 9.89286 9.89286 11.6837 9.89286 13.8929V18.4643V9.32143M5.89286 6.46429H7.03571M1.89286 0.75H22.4643C23.0955 0.75 23.6071 1.26167 23.6071 1.89286V22.4643C23.6071 23.0955 23.0955 23.6071 22.4643 23.6071H1.89286C1.26167 23.6071 0.75 23.0955 0.75 22.4643V1.89286C0.75 1.26167 1.26167 0.75 1.89286 0.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
		'figma'     => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12.0098 1.71429L12.0001 15.4286M12.0001 15.4286V18.8572C12.0001 20.7507 10.465 22.2857 8.57149 22.2857C6.67794 22.2857 5.14292 20.7507 5.14292 18.8572C5.14292 16.9636 6.67794 15.4286 8.57149 15.4286H12.0001ZM12.0001 15.4286L8.59053 15.4286C6.68647 15.4286 5.14292 13.885 5.14292 11.981C5.14292 10.0919 6.66322 8.55456 8.55223 8.53357L12.0001 8.49527M15.4286 15.4286C13.5351 15.4286 12.0001 13.8745 12.0001 11.981C12.0001 10.1023 13.512 8.55466 15.3905 8.53378C17.2989 8.51258 18.8572 10.0727 18.8572 11.9812C18.8572 13.8747 17.3222 15.4286 15.4286 15.4286ZM8.48676 1.71429H15.5328C17.3808 1.71429 18.8904 3.19037 18.9319 5.03791C18.9746 6.93778 17.4518 8.50381 15.5515 8.51425L8.50555 8.55296C6.63941 8.56322 5.1097 7.07533 5.06824 5.20962C5.02563 3.2919 6.56856 1.71429 8.48676 1.71429Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
		'dribbble'  => '<svg width="20" height="20" viewBox="0 0 25 25" fill="none" aria-hidden="true"><path d="M7.92055 1.81655C13.2742 8.28333 16.0742 14.492 17.0481 22.2677M1.03083 11.0901C10.6073 10.8699 16.0739 9.35843 20.1838 4.3457M4.49861 20.3303C9.65255 12.4864 15.9723 11.2904 23.3058 13.4603M23.6071 12.1786C23.6071 5.99325 18.3639 0.75 12.1786 0.75C5.99325 0.75 0.75 5.99325 0.75 12.1786C0.75 18.3639 5.99325 23.6071 12.1786 23.6071C18.3639 23.6071 23.6071 18.3639 23.6071 12.1786Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
		'instagram' => '<svg width="20" height="20" viewBox="0 0 25 25" fill="none" aria-hidden="true"><path d="M18.4643 6.46429H19.6071M5.32143 0.75H19.0357C21.5604 0.75 23.6071 2.7967 23.6071 5.32143V19.0357C23.6071 21.5604 21.5604 23.6071 19.0357 23.6071H5.32143C2.7967 23.6071 0.75 21.5604 0.75 19.0357V5.32143C0.75 2.7967 2.7967 0.75 5.32143 0.75ZM12.1786 16.75C9.65384 16.75 7.60714 14.7033 7.60714 12.1786C7.60714 9.65384 9.65384 7.60714 12.1786 7.60714C14.7033 7.60714 16.75 9.65384 16.75 12.1786C16.75 14.7033 14.7033 16.75 12.1786 16.75Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
	);
	return isset( $icons[ $key ] ) ? $icons[ $key ] : '';
}
