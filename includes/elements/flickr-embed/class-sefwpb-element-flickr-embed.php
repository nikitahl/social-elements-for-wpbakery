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
		 * Shortcode output.
		 *
		 * @param array  $atts    Shortcode attributes.
		 * @param string $content Shortcode content.
		 *
		 * @return string
		 */
		public function content( $atts, $content = '' ) {
			$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );
			$el_class      = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$css           = isset( $atts['css'] ) ? $atts['css'] : '';
			$css_animation = isset( $atts['css_animation'] ) ? $atts['css_animation'] : '';
			$width         = ! empty( $atts['width'] ) && is_numeric( $atts['width'] ) ? intval( $atts['width'] ) : 500;

			$css_class = $this->build_css_class( $el_class, $css, $css_animation );
			$style     = $this->build_style( $atts );
			$el_id     = ! empty( $atts['el_id'] ) ? 'id="' . esc_attr( $atts['el_id'] ) . '"' : '';

			$output  = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '" style="' . esc_attr( $style ) . '">';
			$output .= $this->render_title( $atts );
			$output .= $this->render_flickr_embed( $atts, $width );
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
					return '<p>' . esc_html__( 'Could not retrieve Flickr embed.', 'social-elements-wpbakery' ) . '</p>';
				}
				$body = wp_remote_retrieve_body( $response );
				$data = json_decode( $body );
				if ( ! empty( $data->html ) ) {
					return $data->html;
				}
				return '<p>' . esc_html__( 'Invalid Flickr image URL.', 'social-elements-wpbakery' ) . '</p>';
			}
			return '<p>' . esc_html__( 'Please provide a valid Flickr image URL.', 'social-elements-wpbakery' ) . '</p>';
		}
	}
}
