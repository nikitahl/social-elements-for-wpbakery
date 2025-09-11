<?php
/**
 * WPBakery Element: Twitter Embed
 *
 * Embeds X (Twitter) timeline or tweet.
 *
 * @package SocialElementsWPBakery
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * Class WPBakeryShortCode_Sefwpb_Twitter_Embed
	 *
	 * Handles the Twitter embed element for WPBakery.
	 */
	class WPBakeryShortCode_Sefwpb_Twitter_Embed extends WPBakeryShortCode {

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
				'sefwpb-twitter-embed',
				plugins_url( '/assets/css/twitter-embed.css', __FILE__ ),
				[],
				defined( 'SEFWPB_VERSION' ) ? SEFWPB_VERSION : '1.0.0'
			);
			wp_enqueue_script(
				'sefwpb-twitter-embed',
				plugins_url( '/assets/js/twitter-embed.js', __FILE__ ),
				[ 'jquery' ],
				defined( 'SEFWPB_VERSION' ) ? SEFWPB_VERSION : '1.0.0',
				true
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
			$style     = $this->build_style( $atts );
			$output    = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '" style="' . esc_attr( $style ) . '">';
			$output   .= $this->render_title( $atts );
			$output   .= $this->render_twitter_embed( $atts );
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
			$css_classes   = [
				'sefwpb-element',
				'sefwpb-twitter-embed',
				$el_class,
				$this->getCSSAnimation( $css_animation ),
			];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			return implode( ' ', array_filter( $css_classes ) );
		}

		/**
		 * Validates and returns width with proper unit.
		 *
		 * @param string|int $width
		 * @return string
		 */
		private function get_width( $width ) {
			if ( is_numeric( $width ) ) {
				return intval( $width ) . 'px';
			}
			return esc_attr( $width );
		}

		/**
		 * Build style string.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function build_style( $atts ) {
			$align = isset( $atts['align'] ) ? $atts['align'] : 'left';
			$width = isset( $atts['width'] ) ? $this->get_width( $atts['width'] ) : '500px';
			return '--align: ' . $align . ';--width: ' . $width . ';';
		}

		/**
		 * Render the title if set.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_title( $atts ) {
			if ( ! empty( $atts['title'] ) ) {
				return '<h3 class="sefwpb-twitter-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			return '';
		}

		/**
		 * Render the Twitter embed.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_twitter_embed( $atts ) {
			$url   = isset( $atts['url'] ) ? $atts['url'] : '';
			$theme = isset( $atts['theme'] ) ? $atts['theme'] : 'light';
			if ( ! empty( $url ) ) {
				$output  = '<div class="sefwpb-twitter-embed-container" data-tweet-url="' . esc_url( $url ) . '" data-theme="' . esc_attr( $theme ) . '" data-is-rendered="false">';
				$output .= '<div class="sefwpb-twitter-embed-temp"><a href="' . esc_url( $url ) . '" target="_blank" rel="noreferrer noopener">' . esc_url( $url ) . '</a></div>';
				$output .= '</div>';
				return $output;
			}
			return '<p>' . esc_html__( 'Please provide a valid Tweet URL to embed.', 'social-elements-for-wpbakery' ) . '</p>';
		}
	}
}
