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
	echo '<p>' . esc_html__( 'Configure lazy loading options for social embeds.', 'social-elements-for-wpbakery' ) . '</p>';
}

/**
 * Enable lazy load checkbox.
 *
 * @since 1.1
 */
function sefwpb_enable_lazy_load_callback() {
	$options = get_option( 'sefwpb_options' );
	$checked = isset( $options['enable_lazy_load'] ) ? checked( $options['enable_lazy_load'], 1, false ) : '';
	echo '<input type="checkbox" id="enable_lazy_load" name="sefwpb_options[enable_lazy_load]" value="1" ' . esc_attr( $checked ) . '>';
	echo '<label for="enable_lazy_load">' . esc_html__( 'Enable lazy loading for social embeds', 'social-elements-for-wpbakery' ) . '</label>';
}
