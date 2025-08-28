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
		public function __construct( $settings ) {
			parent::__construct( $settings );
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_facebook_sdk' ] );
		}

		public function enqueue_facebook_sdk() {
			// Only enqueue on frontend
			if ( ! is_admin() ) {
				wp_enqueue_script(
					'facebook-jssdk',
					'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0',
					[],
					null,
					true
				);
			}
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
			$output    = '<div ' . $el_id . ' class="' . esc_attr( $css_class ) . '">';
			$output   .= $this->render_title( $atts );
			$output   .= $this->render_facebook_embed( $atts );
			$output   .= '</div>';
			return $output;
		}

		/**
		 * Build CSS class string.
		 *
		 * @param $atts
		 * @return string
		 */
		private function build_css_class( $atts ) {
			$el_class      = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$css           = isset( $atts['css'] ) ? $atts['css'] : '';
			$css_animation = isset( $atts['css_animation'] ) ? $atts['css_animation'] : '';
			$css_classes   = [ 'sefwpb-element', 'sefwpb-facebook-embed', $el_class, $this->getCSSAnimation( $css_animation ) ];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			return implode( ' ', array_filter( $css_classes ) );
		}

		/**
		 * Render title if provided.
		 *
		 * @param $atts
		 * @return string
		 */
		private function render_title( $atts ) {
			if ( ! empty( $atts['title'] ) ) {
				return '<h3 class="sefwpb-facebook-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			return '';
		}

		/**
		 * Render Facebook embed code.
		 *
		 * @param $atts
		 * @return string
		 */
		private function render_facebook_embed( $atts ) {
			$style = '';
			if ( isset( $atts['align'] ) ) {
				$style .= 'text-align: ' . $atts['align'] . ';';
			}
			$url     = isset( $atts['url'] ) ? esc_url( $atts['url'] ) : '';
			$width   = isset( $atts['width'] ) ? esc_attr( $atts['width'] ) : '';
			$output  = '<div class="sefwpb-facebook-embed-container" style="' . esc_attr( $style ) . '">';
			$output .= '<div class="fb-post" data-href="' . $url . '" data-width="' . $width . '"></div>';
			$output .= '<div id="fb-root"></div>';
			$output .= '<script>';
			$output .= 'if (typeof FB !== "undefined" && FB.XFBML && FB.XFBML.parse) {';
			$output .= 'FB.XFBML.parse();';
			$output .= '}';
			$output .= '</script>';
			$output .= '</div>';
			return $output;
		}
	}
}
