<?php
/**
 * WPBakery Element: Flickr Embed
 *
 * Embeds Flickr image.
 *
 * @package SocialElementsWPBakery
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * Class WPBakeryShortCode_Sefwpb_Flickr_Embed
	 *
	 * Handles the Flickr embed element for WPBakery.
	 */
	class WPBakeryShortCode_Sefwpb_Flickr_Embed extends WPBakeryShortCode {

		/**
		 * Constructor.
		 *
		 * @param array $settings Shortcode settings.
		 */
		public function __construct( $settings ) {
			parent::__construct( $settings );
			$this->element_enqueueing_assets();
		}

		/**
		 * Enqueue frontend assets.
		 */
		public function element_enqueueing_assets() {
			wp_enqueue_style(
				'sefwpb-flickr-embed',
				plugins_url( '/assets/css/flickr-embed.css', __FILE__ ),
				[],
				defined( 'SEFWPB_VERSION' ) ? SEFWPB_VERSION : '1.0.0'
			);
		}

		/**
		 * Shortcode output.
		 *
		 * @param array  $atts    Shortcode attributes.
		 * @param string $content Shortcode content.
		 *
		 * @return string
		 */
		public function content( $atts, $content = '' ) {
			$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

			$output  = $this->build_wrapper_open( $atts );
			$output .= $this->render_title( $atts );
			$output .= $this->get_embed_content( $atts );
			$output .= '</div>';

			return $output;
		}

		/**
		 * Build opening wrapper div with attributes.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function build_wrapper_open( $atts ) {
			$el_class      = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$css           = isset( $atts['css'] ) ? $atts['css'] : '';
			$css_animation = isset( $atts['css_animation'] ) ? $atts['css_animation'] : '';

			$css_class = $this->build_css_class( $el_class, $css, $css_animation );
			$style     = $this->build_style( $atts );
			$el_id     = ! empty( $atts['el_id'] ) ? 'id="' . esc_attr( $atts['el_id'] ) . '"' : '';

			return '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '" style="' . esc_attr( $style ) . '">';
		}

		/**
		 * Get embed content with optional lazy loading.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function get_embed_content( $atts ) {
			$width          = ! empty( $atts['width'] ) ? intval( $atts['width'] ) : 500;
			$flickr_content = $this->render_flickr_embed( $atts, $width );

			if ( $this->should_lazy_load() ) {
				$flickr_content = sefwpb_wrap_for_lazy_load( $flickr_content, 'flickr' );
			}

			return $flickr_content;
		}

		/**
		 * Check if lazy loading should be applied.
		 *
		 * @return bool
		 */
		private function should_lazy_load() {
			return function_exists( 'sefwpb_wrap_for_lazy_load' )
				&& function_exists( 'sefwpb_is_lazy_load_enabled' )
				&& sefwpb_is_lazy_load_enabled();
		}

		/**
		 * Build CSS class string.
		 *
		 * @param string $el_class      Extra class.
		 * @param string $css           Custom CSS.
		 * @param string $css_animation Animation class.
		 * @return string
		 */
		private function build_css_class( $el_class, $css, $css_animation ) {
			$css_classes = [
				'sefwpb-element',
				'sefwpb-flickr-embed',
				$el_class,
				$this->getCSSAnimation( $css_animation ),
			];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			return implode( ' ', array_filter( $css_classes ) );
		}

		/**
		 * Build style string.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function build_style( $atts ) {
			$style = '';
			if ( isset( $atts['align'] ) ) {
				$style .= 'text-align: ' . $atts['align'] . ';';
			}
			return $style;
		}

		/**
		 * Render the title if set.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_title( $atts ) {
			if ( ! empty( $atts['title'] ) ) {
				return '<h3 class="sefwpb-flickr-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			return '';
		}

		/**
		 * Render the Flickr embed.
		 *
		 * @param array $atts  Shortcode attributes.
		 * @param int   $width Max width for embed.
		 * @return string
		 */
		private function render_flickr_embed( $atts, $width ) {
			if ( ! empty( $atts['url'] ) ) {
				$flickr_url = esc_url( $atts['url'] );
				$oembed_url = 'https://www.flickr.com/services/oembed/?format=json&url=' . rawurlencode( $flickr_url ) . '&maxwidth=' . $width;
				$response   = wp_remote_get( $oembed_url );
				if ( is_wp_error( $response ) ) {
					return '<p>' . esc_html__( 'Could not retrieve Flickr embed.', 'social-elements-for-wpbakery' ) . '</p>';
				}
				$body = wp_remote_retrieve_body( $response );
				$data = json_decode( $body );
				if ( ! empty( $data->html ) ) {
					return $data->html;
				}
				return '<p>' . esc_html__( 'Invalid Flickr image URL.', 'social-elements-for-wpbakery' ) . '</p>';
			}
			return '<p>' . esc_html__( 'Please provide a valid Flickr image URL.', 'social-elements-for-wpbakery' ) . '</p>';
		}
	}
}
