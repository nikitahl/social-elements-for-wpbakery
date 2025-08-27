<?php

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {

	/**
	 * WPBakery Element: Reddit Embed
	 *
	 * Embeds Reddit post.
	 *
	 * @since 1.0.0
	 */
	class WPBakeryShortCode_Sefwpb_Reddit_Embed extends WPBakeryShortCode {
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

			$css_classes = [ 'sefwpb-element', 'sefwpb-reddit-embed', $el_class, $this->getCSSAnimation( $css_animation ) ];

			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			$css_class = implode( ' ', array_filter( $css_classes ) );
			$el_id = ! empty( $atts['el_id'] ) ? 'id="' . esc_attr( $atts['el_id'] ) . '"' : '';
			$output = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '">';
			if ( ! empty( $atts['title'] ) ) {
				$output .= '<h3 class="sefwpb-reddit-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			$output .= '<div class="sefwpb-reddit-embed-container">';
			$output .= '<blockquote class="reddit-embed-bq" style="height: 400px; width: 300px" data-embed-height="400" data-embed-width="300">';
			$output .= '<a href="' . esc_url( $atts['url'] ) . '">Reddit Post</a>';
			$output .= '</blockquote>';
			$output .= '<script async="" src="https://embed.reddit.com/widgets.js" charSet="UTF-8"></script>';
			$output .= '</div>'; // .sefwpb-reddit-embed-container
			$output .= '</div>'; // .sefwpb-element
			return $output;
		}
	}
}
