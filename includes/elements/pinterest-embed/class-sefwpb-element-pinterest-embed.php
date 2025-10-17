<?php
/**
 * WPBakery Element: Pinterest Embed
 *
 * Embeds Pinterest post.
 *
 * @package SocialElementsWPBakery
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * Class WPBakeryShortCode_Sefwpb_Pinterest_Embed
	 *
	 * Handles the Pinterest embed element for WPBakery.
	 */
	class WPBakeryShortCode_Sefwpb_Pinterest_Embed extends WPBakeryShortCode {

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
				'sefwpb-pinterest-embed',
				plugins_url( '/assets/css/pinterest-embed.css', __FILE__ ),
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
			$output    = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '">';
			$output   .= $this->render_title( $atts );
			$output   .= $this->render_pinterest_embed( $atts );
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
			$css_classes   = [ 'sefwpb-element', 'sefwpb-pinterest-embed', $el_class, $this->getCSSAnimation( $css_animation ) ];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			return implode( ' ', array_filter( $css_classes ) );
		}

		/**
		 * Render the title if set.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_title( $atts ) {
			if ( ! empty( $atts['title'] ) ) {
				return '<h3 class="sefwpb-pinterest-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			return '';
		}

		/**
		 * Render the Pinterest embed.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_pinterest_embed( $atts ) {
			$style = '';
			if ( isset( $atts['align'] ) ) {
				$style .= 'text-align: ' . $atts['align'] . ';';
			}
			$url    = isset( $atts['url'] ) ? esc_url( $atts['url'] ) : '';
			$output = '<div class="sefwpb-pinterest-embed-container" style="' . esc_attr( $style ) . '">';
			if ( ! empty( $url ) ) {
				$embed_html = wp_oembed_get( $url, [ 'width' => 500 ] );
				if ( $embed_html ) {
					$output .= $embed_html;
				} else {
					$output .= esc_html__( 'Please provide a valid Pinterest post URL to embed.', 'social-elements-for-wpbakery' );
				}
			} else {
				$output .= esc_html__( 'Please provide a valid Pinterest post URL to embed.', 'social-elements-for-wpbakery' );
			}
			$output .= '</div>';
			return $output;
		}
	}
}
