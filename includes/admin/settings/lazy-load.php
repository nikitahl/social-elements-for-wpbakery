<?php
/**
 * Lazy Load Settings
 *
 * @package SocialElementsWPBakery
 * @since 1.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialize lazy load settings.
 *
 * @since 1.1
 */
function sefwpb_lazy_load_settings_init() {
	add_settings_section(
		'sefwpb_lazy_load_section',
		__( 'Lazy Load Settings', 'social-elements-for-wpbakery' ),
		'sefwpb_lazy_load_section_callback',
		'sefwpb_settings'
	);

	add_settings_field(
		'enable_lazy_load',
		__( 'Enable Lazy Load', 'social-elements-for-wpbakery' ),
		'sefwpb_enable_lazy_load_callback',
		'sefwpb_settings',
		'sefwpb_lazy_load_section'
	);
}

/**
 * Lazy load section description.
 *
 * @since 1.1
 */
function sefwpb_lazy_load_section_callback() {
	echo '<p>' . __( 'Configure lazy loading options for social embeds.', 'social-elements-for-wpbakery' ) . '</p>';
}

/**
 * Enable lazy load checkbox.
 *
 * @since 1.1
 */
function sefwpb_enable_lazy_load_callback() {
	$options = get_option( 'sefwpb_options' );
	$checked = isset( $options['enable_lazy_load'] ) ? checked( $options['enable_lazy_load'], 1, false ) : '';
	echo '<input type="checkbox" id="enable_lazy_load" name="sefwpb_options[enable_lazy_load]" value="1" ' . $checked . '>';
	echo '<label for="enable_lazy_load">' . __( 'Enable lazy loading for social embeds', 'social-elements-for-wpbakery' ) . '</label>';
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
