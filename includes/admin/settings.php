<?php
/**
 * Admin Settings Controller
 *
 * @package SocialElementsWPBakery
 * @since 1.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialize admin settings.
 *
 * @since 1.1
 */
function sefwpb_admin_init() {
	add_action( 'admin_menu', 'sefwpb_add_admin_menu' );
	add_action( 'admin_init', 'sefwpb_settings_init' );

	// Load settings modules
	require_once SEFWPB_DIR . 'includes/admin/settings/lazy-load.php';
}
add_action( 'init', 'sefwpb_admin_init' );

/**
 * Add admin menu item.
 *
 * @since 1.1
 */
function sefwpb_add_admin_menu() {
	add_options_page(
		__( 'Social Elements Settings', SEFWPB_TD ),
		__( 'Social Elements', SEFWPB_TD ),
		'manage_options',
		'social-elements-for-wpbakery',
		'sefwpb_settings_page'
	);
}

/**
 * Initialize all settings.
 *
 * @since 1.1
 */
function sefwpb_settings_init() {
	register_setting( 'sefwpb_settings', 'sefwpb_options' );

	// Initialize lazy load settings
	sefwpb_lazy_load_settings_init();
}

/**
 * Settings page HTML.
 *
 * @since 1.1
 */
function sefwpb_settings_page() {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'sefwpb_settings' );
			do_settings_sections( 'sefwpb_settings' );
			submit_button( __( 'Save Settings', SEFWPB_TD ) );
			?>
		</form>
	</div>
	<?php
}
