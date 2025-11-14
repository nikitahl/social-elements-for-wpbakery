<?php
/**
 * Element Loader Class
 *
 * Handles dynamic loading of WPBakery elements.
 *
 * @package SocialElementsWPBakery
 * @since   1.1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class SEFWPB_Element_Loader
 *
 * Dynamically loads and registers all available elements.
 */
class SEFWPB_Element_Loader {

	/**
	 * Array of available elements.
	 *
	 * @var array
	 */
	private $elements = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->discover_elements();
		$this->load_all_elements();
	}

	/**
	 * Discover all available elements in the elements directory.
	 *
	 * @return void
	 */
	private function discover_elements() {
		$elements_dir = SEFWPB_DIR . 'includes/elements/';

		if ( ! is_dir( $elements_dir ) ) {
			return;
		}

		// Get all subdirectories in the elements folder.
		$element_folders = glob( $elements_dir . '*', GLOB_ONLYDIR );

		foreach ( $element_folders as $folder ) {
			$element_slug = basename( $folder );
			$index_file   = $folder . '/index.php';

			// Only add elements that have an index.php file.
			if ( file_exists( $index_file ) ) {
				$this->elements[ $element_slug ] = $index_file;
			}
		}
	}

	/**
	 * Load all available elements.
	 *
	 * @return void
	 */
	private function load_all_elements() {
		foreach ( $this->elements as $element_file ) {
			require_once $element_file;
		}
	}
}
