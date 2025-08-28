<?php
/**
 * WPBakery Element: Facebook Embed
 *
 * Embeds Facebook post.
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Sefwpb_Facebook_Embed extends WPBakeryShortCode {
		/**
		 * Shortcode output.
		 *
		 * @param array  $atts    Shortcode attributes.
		 * @param string $content Shortcode content.
		 * @return string
		 */
		public function content( $atts, $content = '' ) {
			$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
			$el_class = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$css      = isset( $atts['css'] ) ? $atts['css'] : '';
			$css_animation = isset( $atts['css_animation'] ) ? $atts['css_animation'] : '';

			$style = '';
			$css_classes = [ 'sefwpb-element', 'sefwpb-facebook-embed', $el_class, $this->getCSSAnimation( $css_animation ) ];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			if ( isset( $atts['align'] ) ) {
				$style .= 'text-align: ' . $atts['align'] . ';';
			}
			$css_class = implode( ' ', array_filter( $css_classes ) );
			$el_id = ! empty( $atts['el_id'] ) ? 'id="' . esc_attr( $atts['el_id'] ) . '"' : '';
			$output = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '">';
			if ( ! empty( $atts['title'] ) ) {
				$output .= '<h3 class="sefwpb-facebook-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			$output .= '<div class="sefwpb-facebook-embed-container" style="' . esc_attr( $style ) . '">';
			$output .= '<div class="fb-post" data-href="' . esc_url( $atts['url'] ) . '" data-width="' . esc_attr( $atts['width'] ) . '"></div>';
			$output .= '<div id="fb-root"></div>';
			$output .= '<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0" nonce="SEFWPB"></script>';
			$output .= '<script>';
			$output .= 'if (typeof FB !== "undefined" && FB.XFBML && FB.XFBML.parse) {';
			$output .= 'FB.XFBML.parse();';
			$output .= '}';
			$output .= '</script>';
			$output .= '</div>'; // .sefwpb-facebook-embed-container
			$output .= '</div>'; // .sefwpb-element
			return $output;
		}
	}
}
