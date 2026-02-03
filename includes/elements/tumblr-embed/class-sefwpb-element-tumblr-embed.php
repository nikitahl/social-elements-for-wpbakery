<?php
/**
 * WPBakery Element: Tumblr Embed
 *
 * Embeds Tumblr post.
 *
 * @package SocialElementsWPBakery
 * @since   1.2.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * Class WPBakeryShortCode_Sefwpb_Tumblr_Embed
	 *
	 * Handles the Tumblr embed element for WPBakery.
	 */
	class WPBakeryShortCode_Sefwpb_Tumblr_Embed extends WPBakeryShortCode {

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
				'sefwpb-tumblr-embed',
				plugins_url( '/assets/css/tumblr-embed.css', __FILE__ ),
				[],
				defined( 'SEFWPB_VERSION' ) ? SEFWPB_VERSION : '1.0.0'
			);
		}

		/**
		 * Shortcode output.
		 *
		 * @param array  $atts    Shortcode attributes.
		 * @param string $content Shortcode content.
		 * @return string
		 */
		public function content( $atts, $content = '' ) {
			$atts      = vc_map_get_attributes( $this->getShortcode(), $atts );
			$css_class = $this->build_css_class( $atts );
			$el_id     = ! empty( $atts['el_id'] ) ? 'id="' . esc_attr( $atts['el_id'] ) . '"' : '';
			$style     = $this->build_style( $atts );
			$output    = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '" style="' . esc_attr( $style ) . '">';
			$output   .= $this->render_title( $atts );
			$output   .= $this->render_tumblr_embed( $atts );
			$output   .= '</div>';
			return $output;
		}

		/**
		 * Build CSS class string.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function build_css_class( $atts ) {
			$el_class      = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$css           = isset( $atts['css'] ) ? $atts['css'] : '';
			$css_animation = isset( $atts['css_animation'] ) ? $atts['css_animation'] : '';
			$css_classes   = [
				'sefwpb-element',
				'sefwpb-tumblr-embed',
				$el_class,
				$this->getCSSAnimation( $css_animation ),
			];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			return implode( ' ', array_filter( $css_classes ) );
		}

		/**
		 * Validates and returns width with proper unit.
		 *
		 * @param string|int $width Width value.
		 * @return string
		 */
		private function get_width( $width ) {
			if ( is_numeric( $width ) ) {
				return intval( $width ) . 'px';
			}
			return esc_attr( $width );
		}

		/**
		 * Build style string.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function build_style( $atts ) {
			$align = isset( $atts['align'] ) ? $atts['align'] : 'flex-start';
			$width = isset( $atts['width'] ) ? $this->get_width( $atts['width'] ) : '500px';
			return '--align: ' . $align . ';--width: ' . $width . ';';
		}

		/**
		 * Render the title if set.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_title( $atts ) {
			if ( ! empty( $atts['title'] ) ) {
				return '<h3 class="sefwpb-tumblr-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			return '';
		}

		/**
		 * Render the Tumblr embed.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_tumblr_embed( $atts ) {
			$url = isset( $atts['url'] ) ? $atts['url'] : '';

			if ( empty( $url ) ) {
				return '<p>' . esc_html__( 'Please provide a valid Tumblr URL to embed.', 'social-elements-for-wpbakery' ) . '</p>';
			}

			$embed_html = wp_oembed_get( $url, [ 'width' => 500 ] );

			if ( ! $embed_html ) {
				return '<p>' . esc_html__( 'Please provide a valid Tumblr URL to embed.', 'social-elements-for-wpbakery' ) . '</p>';
			}

			// Apply lazy loading if enabled.
			if ( function_exists( 'sefwpb_is_lazy_load_enabled' ) && sefwpb_is_lazy_load_enabled() ) {
				$embed_html = sefwpb_wrap_for_lazy_load( $embed_html, 'tumblr', $url );
			}

			return '<div class="sefwpb-tumblr-embed-container">' . $embed_html . '</div>';
		}
	}
}g
