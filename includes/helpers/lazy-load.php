<?php
/**
 * Lazy Load Handler
 *
 * @package SocialElementsWPBakery
 * @since 1.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialize lazy loading if enabled.
 *
 * @since 1.1
 */
function sefwpb_init_lazy_load() {
	if ( ! sefwpb_is_lazy_load_enabled() ) {
		return;
	}

	add_action( 'wp_enqueue_scripts', 'sefwpb_enqueue_lazy_load_scripts' );
}
add_action( 'init', 'sefwpb_init_lazy_load' );

/**
 * Enqueue lazy load scripts.
 *
 * @since 1.1
 */
function sefwpb_enqueue_lazy_load_scripts() {
	wp_enqueue_script(
		'sefwpb-lazy-load',
		SEFWPB_URL . 'assets/js/lazy-load.js',
		array( 'jquery' ),
		SEFWPB_VER,
		true
	);
}

/**
 * Wrap embed content for lazy loading.
 *
 * @since 1.1
 * @param string $content The embed content.
 * @param string $platform The social platform (flickr, instagram, etc.).
 * @param array $data The embed data.
 * @return string
 */
function sefwpb_wrap_for_lazy_load( $content, $platform, $data = array() ) {
	if ( ! sefwpb_is_lazy_load_enabled() ) {
		return $content;
	}

	$placeholder = '<div class="sefwpb-lazy-placeholder" data-platform="' . esc_attr( $platform ) . '">';
	$placeholder .= '<div class="sefwpb-loading-spinner"></div>';
	$placeholder .= '<p>' . sprintf( __( 'Loading %s content...', SEFWPB_TD ), ucfirst( $platform ) ) . '</p>';
	$placeholder .= '</div>';

	return '<div class="sefwpb-lazy-container" data-content="' . esc_attr( base64_encode( $content ) ) . '">' . $placeholder . '</div>';
}
