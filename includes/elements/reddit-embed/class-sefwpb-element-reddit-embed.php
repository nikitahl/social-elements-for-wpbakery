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
			$atts = shortcode_atts(
				[
					'title'      => '',
					'url'        => 'https://www.reddit.com/r/redditdev/comments/1lhdu8i/are_there_are_redditsponsored_initiatives_for/',
					'el_id'      => '',
					'css_animation' => '',
				],
				$atts,
				$this->settings['base']
			);
			$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'sefwpb-element sefwpb-reddit-embed', $this->settings['base'], $atts );
			$css_class .= $this->getCSSAnimation( $atts['css_animation'] );
			if ( ! empty( $atts['el_class'] ) ) {
				$css_class .= ' ' . esc_attr( $atts['el_class'] );
			}
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
