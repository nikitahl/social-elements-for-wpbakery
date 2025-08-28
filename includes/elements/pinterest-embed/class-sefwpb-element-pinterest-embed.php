<?php
/**
 * WPBakery Element: Pinterest Embed
 *
 * Embeds Ponterest post.
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_Sefwpb_Pinterest_Embed extends WPBakeryShortCode {
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
			$css_classes = [ 'sefwpb-element', 'sefwpb-pinterest-embed', $el_class, $this->getCSSAnimation( $css_animation ) ];
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
				$output .= '<h3 class="sefwpb-pinterest-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			$output .= '<div class="sefwpb-pinterest-embed-container" style="' . esc_attr( $style ) . '">';
			$output .= '<a data-pin-do="embedPin" href="' . esc_url( $atts['url'] ) . '">Pinterest Post</a>';
			$output .= '<script async defer src="https://assets.pinterest.com/js/pinit.js"></script>';
			$output .= '<script>';
			$output .= 'if (window.PinUtils && typeof window.PinUtils.build === "function") {';
			$output .= 'window.PinUtils.build();';
			$output .= '}';
			$output .= '</script>';
			$output .= '</div>'; // .sefwpb-pinterest-embed-container
			$output .= '</div>'; // .sefwpb-element
			return $output;
		}
	}
}
