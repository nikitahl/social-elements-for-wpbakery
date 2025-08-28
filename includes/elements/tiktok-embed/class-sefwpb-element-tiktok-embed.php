<?php

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {

	/**
	 * WPBakery Element: TikTok Embed
	 *
	 * Embeds TikTok post.
	 *
	 * @since 1.0.0
	 */
	class WPBakeryShortCode_Sefwpb_Tiktok_Embed extends WPBakeryShortCode {
		/**
		 * Constructor
		 *
		 * @param array $settings
		 */
		public function __construct( $settings ) {
			parent::__construct( $settings ); // Important to call parent constructor to activate all logic for shortcode.
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

		public function extract_tiktok_video_id ( $url ) {
			$pattern = '/https?:\/\/(www\.)?tiktok\.com\/@[\w.-]+\/video\/(\d+)/';
			if ( preg_match( $pattern, $url, $matches ) ) {
				return $matches[2]; // Return the video ID
			}
			return ''; // Return empty string if no match found

		}
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
			$width    = isset( $atts['width'] ) ? $atts['width'] : '500px';

			$style = '';
			$container_style = '';
			$css_classes = [ 'sefwpb-element', 'sefwpb-tiktok-embed', $el_class, $this->getCSSAnimation( $css_animation ) ];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			if ( isset( $atts['align'] ) ) {
				$container_style .= '--justify-content: ' . $atts['align'] . ';';
			}
			if ( isset( $width ) && is_numeric( $width ) ) {
				$style .= '--width: ' . intval( $width ) . 'px;';
			} else {
				$style .= '--width: 500px;';
			}
			$css_class = implode( ' ', array_filter( $css_classes ) );
			$el_id = ! empty( $atts['el_id'] ) ? 'id="' . esc_attr( $atts['el_id'] ) . '"' : '';
			$output = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '" style="' . esc_attr( $container_style ) . '">';
			if ( ! empty( $atts['title'] ) ) {
				$output .= '<h3 class="sefwpb-tiktok-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			if ( ! empty( $atts['url'] ) ) {
				$output .= '<div class="sefwpb-tiktok-embed-wrap" style="' . esc_attr( $style ) . '">';
				$output .= '<blockquote class="tiktok-embed" cite="' . esc_url( $atts['url'] ) . '" data-video-id="' . esc_attr( $this->extract_tiktok_video_id( $atts['url'] ) ) . '" style="max-width: 100%;min-width: 325px;" >';
				$output .= '<section> </section>';
				$output .= '</blockquote>';
				$output .= '<script async src="https://www.tiktok.com/embed.js"></script>';
				$output .= '</div>';
			} else {
				$output .= esc_html__( 'Please provide a valid TikTok post URL.', SEFWPB_TD );
			}
			$output .= '</div>';
			return $output;
		}
	}
}
