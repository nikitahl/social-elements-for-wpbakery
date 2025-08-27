<?php

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {

	/**
	 * WPBakery Element: Pinterest Embed
	 *
	 * Embeds Ponterest post.
	 *
	 * @since 1.0.0
	 */
	class WPBakeryShortCode_Sefwpb_Pinterest_Embed extends WPBakeryShortCode {
		/**
		 * Shortcode output.
		 *
		 * @param array  $atts    Shortcode attributes.
		 * @param string $content Shortcode content.
		 * @return string
		 */
		public function content( $atts, $content = '' ) {
			$atts = shortcode_atts(
				[
					'title'      => '',
					'url'        => 'https://www.pinterest.com/pin/3940718420141988/',
					'align'      => 'left',
					'el_id'      => '',
					'css_animation' => '',
				],
				$atts,
				$this->settings['base']
			);
			$style = '';
			$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'sefwpb-element sefwpb-pinterest-embed', $this->settings['base'], $atts );
			$css_class .= $this->getCSSAnimation( $atts['css_animation']);
			if ( ! empty( $atts['el_class'] ) ) {
				$css_class .= ' ' . esc_attr( $atts['el_class'] );
			}
			if ( isset( $atts['align'] ) ) {
				$style .= 'text-align: ' . $atts['align'] . ';';
			}
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
