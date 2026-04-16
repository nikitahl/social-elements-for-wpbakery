<?php
/**
 * WPBakery Element: Imgur Embed
 *
 * Embeds Imgur post via WordPress oEmbed.
 *
 * @package SocialElementsWPBakery
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * Class WPBakeryShortCode_Sefwpb_Imgur_Embed
	 */
	class WPBakeryShortCode_Sefwpb_Imgur_Embed extends WPBakeryShortCode {

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
				'sefwpb-imgur-embed',
				plugins_url( '/assets/css/imgur-embed.css', __FILE__ ),
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

			$output  = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '" style="' . esc_attr( $style ) . '">';
			$output .= $this->render_title( $atts );
			$output .= $this->render_imgur_embed( $atts );
			$output .= '</div>';

			return $output;
		}

		private function build_css_class( $atts ) {
			$el_class      = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$css           = isset( $atts['css'] ) ? $atts['css'] : '';
			$css_animation = isset( $atts['css_animation'] ) ? $atts['css_animation'] : '';

			$css_classes = [
				'sefwpb-element',
				'sefwpb-imgur-embed',
				$el_class,
				$this->getCSSAnimation( $css_animation ),
			];

			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}

			return implode( ' ', array_filter( $css_classes ) );
		}

		private function get_width( $width ) {
			if ( is_numeric( $width ) ) {
				return intval( $width ) . 'px';
			}
			return esc_attr( $width );
		}

		private function build_style( $atts ) {
			$align = isset( $atts['align'] ) ? $atts['align'] : 'flex-start';
			$width = isset( $atts['width'] ) ? $this->get_width( $atts['width'] ) : '100%';
			return '--align: ' . $align . ';--width: ' . $width . ';';
		}

		private function render_title( $atts ) {
			if ( ! empty( $atts['title'] ) ) {
				return '<h3 class="sefwpb-imgur-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			return '';
		}

		private function render_imgur_embed( $atts ) {
			$url = isset( $atts['url'] ) ? $atts['url'] : '';

			if ( empty( $url ) ) {
				return '<p>' . esc_html__( 'Please provide a valid Imgur URL to embed.', 'social-elements-for-wpbakery' ) . '</p>';
			}

			$embed_html = wp_oembed_get( $url );

			if ( ! $embed_html ) {
				return '<p>' . esc_html__( 'Please provide a valid Imgur URL to embed.', 'social-elements-for-wpbakery' ) . '</p>';
			}

			if ( function_exists( 'sefwpb_is_lazy_load_enabled' ) && sefwpb_is_lazy_load_enabled() ) {
				$embed_html = sefwpb_wrap_for_lazy_load( $embed_html, 'imgur', $url );
			}

			return '<div class="sefwpb-imgur-embed-container">' . $embed_html . '</div>';
		}
	}
}

