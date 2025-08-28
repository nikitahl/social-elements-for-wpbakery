<?php

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {

	/**
	 * WPBakery Element: Flickr Embed
	 *
	 * Embeds Flickr image.
	 *
	 * @since 1.0.0
	 */
	class WPBakeryShortCode_Sefwpb_Flickr_Embed extends WPBakeryShortCode {

		/**
		 * Shortcode output.
		 *
		 * @param array $atts Shortcode attributes.
		 * @param string $content Shortcode content.
		 *
		 * @return string
		 */
		public function content( $atts, $content = '' ) {
			$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
			$el_class = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$css      = isset( $atts['css'] ) ? $atts['css'] : '';
			$css_animation = isset( $atts['css_animation'] ) ? $atts['css_animation'] : '';
			$width = ! empty( $atts['width'] ) && is_numeric( $atts['width'] ) ? intval( $atts['width'] ) : 500;

			$style = '';
			$css_classes = [ 'sefwpb-element', 'sefwpb-flickr-embed', $el_class, $this->getCSSAnimation( $css_animation ) ];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			if ( isset( $atts['align'] ) ) {
				$style .= 'text-align: ' . $atts['align'] . ';';
			}
			$css_class = implode( ' ', array_filter( $css_classes ) );
			$el_id = ! empty( $atts['el_id'] ) ? 'id="' . esc_attr( $atts['el_id'] ) . '"' : '';
			$output = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '" style="' . esc_attr( $style ) . '">';
			if ( ! empty( $atts['title'] ) ) {
				$output .= '<h3 class="sefwpb-flickr-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			if ( ! empty( $atts['url'] ) ) {
				$flickr_url = esc_url( $atts['url'] );
				$oembed_url = 'https://www.flickr.com/services/oembed/?format=json&url=' . rawurlencode( $flickr_url ) . '&maxwidth=' . $width;
				$response = wp_remote_get( $oembed_url );
				if ( is_wp_error( $response ) ) {
					$output .= '<p>' . esc_html__( 'Could not retrieve Flickr embed.', SEFWPB_TD ) . '</p>';
				} else {
					$body = wp_remote_retrieve_body( $response );
					$data = json_decode( $body );
					if ( ! empty( $data->html ) ) {
						$output .= $data->html;
					} else {
						$output .= '<p>' . esc_html__( 'Invalid Flickr image URL.', SEFWPB_TD ) . '</p>';
					}
				}
			} else {
				$output .= '<p>' . esc_html__( 'Please provide a valid Flickr image URL.', SEFWPB_TD ) . '</p>';
			}
			$output .= '</div>';

			return $output;
		}
	}
}
