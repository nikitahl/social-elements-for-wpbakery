<?php
/**
 * WPBakery Element: TikTok Embed
 *
 * Embeds TikTok post.
 *
 * @package SocialElementsWPBakery
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * Class WPBakeryShortCode_Sefwpb_Tiktok_Embed
	 *
	 * Handles the TikTok embed element for WPBakery.
	 */
	class WPBakeryShortCode_Sefwpb_Tiktok_Embed extends WPBakeryShortCode {
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
				'sefwpb-tiktok-embed',
				plugins_url( '/assets/css/tiktok-embed.css', __FILE__ ),
				[],
				SEFWPB_VERSION
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
			$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );
			$el_class      = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$css           = isset( $atts['css'] ) ? $atts['css'] : '';
			$css_animation = isset( $atts['css_animation'] ) ? $atts['css_animation'] : '';
			$width         = isset( $atts['width'] ) ? $atts['width'] : '500px';

			$css_class   = $this->build_css_class( $el_class, $css, $css_animation );
			$align_style = $this->build_container_style( $atts );
			$width_style = $this->build_width_style( $width );
			$el_id       = ! empty( $atts['el_id'] ) ? 'id="' . esc_attr( $atts['el_id'] ) . '"' : '';

			$output  = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '">';
			$output .= $this->render_title( $atts );
			$output .= $this->render_tiktok_embed( $atts, $align_style, $width_style );
			$output .= '</div>';

			return $output;
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
				'sefwpb-tiktok-embed',
				$el_class,
				$this->getCSSAnimation( $css_animation ),
			];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			return implode( ' ', array_filter( $css_classes ) );
		}

		/**
		 * Build container style string.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function build_container_style( $atts ) {
			$container_style = '';
			if ( isset( $atts['align'] ) ) {
				$container_style .= '--justify-content: ' . $atts['align'] . ';';
			}
			return $container_style;
		}

		/**
		 * Build width style string.
		 *
		 * @param string $width Width value.
		 * @return string
		 */
		private function build_width_style( $width ) {
			if ( isset( $width ) && is_numeric( $width ) ) {
				return '--width: ' . intval( $width ) . 'px;';
			}
			return '--width: 500px;';
		}

		/**
		 * Render the title if set.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_title( $atts ) {
			if ( ! empty( $atts['title'] ) ) {
				return '<h3 class="sefwpb-tiktok-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			return '';
		}

		/**
		 * Render the TikTok embed.
		 *
		 * @param array  $atts  Shortcode attributes.
		 * @param string $align_style Container style.
		 * @param string $width_style Width style.
		 * @return string
		 */
		private function render_tiktok_embed( $atts, $align_style, $width_style ) {
			if ( ! empty( $atts['url'] ) ) {
				$embed_html = wp_oembed_get( $atts['url'], [ 'width' => 500 ] );
				if ( $embed_html ) {
					$output  = '<div class="sefwpb-tiktok-embed-wrap" style="' . esc_attr( $align_style ) . '">';
					$output .= '<div class="sefwpb-tiktok-embed-content" style="' . esc_attr( $width_style ) . '">';
					$output .= $embed_html;
					$output .= '</div></div>';
					return $output;
				}
			}
			return esc_html__( 'Please provide a valid TikTok post URL.', 'social-elements-for-wpbakery' );
		}
	}
}
