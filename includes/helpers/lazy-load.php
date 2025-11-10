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

	add_action( 'wp_enqueue_scripts', 'sefwpb_enqueue_lazy_load_assets' );
}
add_action( 'init', 'sefwpb_init_lazy_load' );

/**
 * Enqueue lazy load scripts.
 *
 * @since 1.1
 */
function sefwpb_enqueue_lazy_load_assets() {
	wp_enqueue_script(
		'sefwpb-lazy-load',
		SEFWPB_URL . 'assets/js/lazy-load.js',
		[ 'jquery' ],
		SEFWPB_VERSION,
		true
	);

	// Pass AJAX data to JavaScript.
	wp_localize_script(
		'sefwpb-lazy-load',
		'sefwpbLazyLoad',
		[
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'sefwpb_lazy_load' ),
		]
	);

	wp_enqueue_style(
		'sefwpb-lazy-load',
		SEFWPB_URL . 'assets/css/lazy-load.css',
		[],
		SEFWPB_VERSION
	);
}

/**
 * Wrap embed content for lazy loading.
 *
 * @since 1.1
 * @param string $content The embed HTML content.
 * @param string $platform The social platform (flickr, instagram, etc.).
 * @param string $url Optional. The URL to link to if embed fails.
 * @return string
 */
function sefwpb_wrap_for_lazy_load( $content, $platform, $url = '' ) {
	if ( ! sefwpb_is_lazy_load_enabled() ) {
		return $content;
	}

	$platform_name = ucfirst( $platform );
	$error         = __( 'Embed data missing.', 'social-elements-for-wpbakery' );

	$placeholder  = '<div class="sefwpb-lazy-placeholder" data-platform="' . esc_attr( $platform ) . '" data-error="' . esc_attr( $error ) . '">';
	$placeholder .= '<div class="sefwpb-lazy-card">';

	if ( ! empty( $url ) ) {
		// translators: %s is the name of the social platform (e.g., Instagram, Flickr).
		$placeholder .= '<div class="sefwpb-lazy-content">';
		$placeholder .= '<p class="sefwpb-lazy-title">' . sprintf( esc_html__( '%s Post', 'social-elements-for-wpbakery' ), $platform_name ) . '</p>';
		$placeholder .= '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" class="sefwpb-lazy-link">' . esc_html__( 'View on', 'social-elements-for-wpbakery' ) . ' ' . esc_html( $platform_name ) . '</a>';
		$placeholder .= '</div>';
	} else {
		// Fallback when no URL is provided - show spinner.
		$placeholder .= '<div class="sefwpb-loading-spinner"></div>';
		// translators: %s is the name of the social platform (e.g., Instagram, Flickr).
		$placeholder .= '<p class="sefwpb-lazy-text">' . sprintf( esc_html__( 'Loading %s content...', 'social-elements-for-wpbakery' ), $platform_name ) . '</p>';
	}

	$placeholder .= '</div>';
	$placeholder .= '</div>';

	// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Used for data encoding, not obfuscation.
	return '<div class="sefwpb-lazy-container" data-content="' . esc_attr( base64_encode( $content ) ) . '">' . $placeholder . '</div>';
}

/**
 * Check if lazy load is enabled.
 *
 * @since 1.1
 * @return bool
 */
function sefwpb_is_lazy_load_enabled() {
	$options = get_option( 'sefwpb_options' );
	return isset( $options['enable_lazy_load'] ) && $options['enable_lazy_load'];
}
