<?php
/**
 * Plugin Name: Social Elements for WPBakery Page Builder
 * Description: A collection of social elements (share buttons, profile links, and more) for WPBakery Page Builder.
 * Author: Nikita Hlopov
 * Author URI: https://nikitahl.com
 * Version: 1.2
 * Requires PHP: 7.0
 * Requires at least: 6.4
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: social-elements-for-wpbakery
 * Domain Path: /languages
 *
 * @package SocialElementsWPBakery
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// -----------------------------------------------------------------------------
// Constants
// -----------------------------------------------------------------------------
/**
 * Plugin variables.
 *
 * @since 1.0.0
 */

define( 'SEFWPB_VERSION', '1.2' );
define( 'SEFWPB_PATH', plugin_dir_path( __FILE__ ) );
define( 'SEFWPB_URL', plugin_dir_url( __FILE__ ) );
define( 'SEFWPB_DIR', __DIR__ . '/' );
define( 'SEFWPB_TD', 'social-elements-for-wpbakery' );
define( 'SEFWPB_ASSETS_URI', plugins_url( 'assets', __FILE__ ) );

// -----------------------------------------------------------------------------
// Bootstrap
// -----------------------------------------------------------------------------

/**
 * Loads plugin files and bootstraps components.
 *
 * @since 1.0.0
 * @return void
 */
function sefwpb_bootstrap() {
	// Admin dependency notice if WPBakery is missing.
	if ( ! function_exists( 'vc_map' ) ) {
		add_action( 'admin_notices', 'sefwpb_missing_wpbakery_notice' );
		return; // Do not proceed without WPBakery.
	}

	if ( is_admin() ) {
		require_once SEFWPB_DIR . 'includes/admin/settings.php';
	}
	require_once SEFWPB_DIR . 'includes/helpers/lazy-load.php';

	// Load the element loader class and initialize it.
	require_once SEFWPB_DIR . 'includes/classes/class-sefwpb-element-loader.php';
	new SEFWPB_Element_Loader();
}
add_action( 'plugins_loaded', 'sefwpb_bootstrap' );

/**
 * Shows admin notice if WPBakery Page Builder is not active.
 *
 * @since 1.0.0
 * @return void
 */
function sefwpb_missing_wpbakery_notice() {
	if ( current_user_can( 'activate_plugins' ) ) {
		echo '<div class="notice notice-error"><p>' . esc_html__( 'Social Elements for WPBakery requires WPBakery Page Builder to be installed and active.', 'social-elements-for-wpbakery' ) . '</p></div>';
	}
}
