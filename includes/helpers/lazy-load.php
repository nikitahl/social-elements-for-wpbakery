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
 * @return string
 */
function sefwpb_wrap_for_lazy_load( $content, $platform ) {
	if ( ! sefwpb_is_lazy_load_enabled() ) {
		return $content;
	}

	$error        = __( 'Embed data missing.', 'social-elements-for-wpbakery' );
	$placeholder  = '<div class="sefwpb-lazy-placeholder" data-platform="' . esc_attr( $platform ) . '" data-error="' . esc_attr( $error ) . '">';
	$placeholder .= '<div class="sefwpb-loading-spinner"></div>';
	// translators: %s is the name of the social platform (e.g., Instagram, Flickr).
	$placeholder .= '<p>' . sprintf( esc_html__( 'Loading %s content...', 'social-elements-for-wpbakery' ), ucfirst( $platform ) ) . '</p>';
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
