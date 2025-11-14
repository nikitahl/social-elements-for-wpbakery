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
	/**
	 * Class WPBakeryShortCode_Sefwpb_Facebook_Embed
	 *
	 * Handles the Facebook embed element for WPBakery.
	 */
	class WPBakeryShortCode_Sefwpb_Facebook_Embed extends WPBakeryShortCode {

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
				'sefwpb-facebook-embed',
				plugins_url( '/assets/css/facebook-embed.css', __FILE__ ),
				[],
				defined( 'SEFWPB_VERSION' ) ? SEFWPB_VERSION : '1.0.0'
			);
			// Enqueue Facebook SDK only once.
			if ( ! wp_script_is( 'facebook-jssdk', 'enqueued' ) ) {
				wp_enqueue_script(
					'facebook-jssdk',
					'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v12.0',
					[],
					defined( 'SEFWPB_VERSION' ) ? SEFWPB_VERSION : '1.0.0',
					[
						'in_footer' => true,
						'strategy'  => 'async',
					]
				);
				// Inline script to parse embeds after SDK loads.
				wp_add_inline_script(
					'facebook-jssdk',
					'if (typeof FB !== "undefined" && FB.XFBML && FB.XFBML.parse) { FB.XFBML.parse(); }'
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
		 * @param array $atts Shortcode attributes.
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
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_title( $atts ) {
			if ( ! empty( $atts['title'] ) ) {
				return '<h3 class="sefwpb-facebook-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			return '';
		}

		/**
		 * Validate Facebook URL.
		 *
		 * @param string $url The URL to validate.
		 * @return bool
		 */
		private function is_valid_facebook_url( $url ) {
			// Check if the URL is valid.
			if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
				return false;
			}

			// Check if the URL is a Facebook URL.
			$facebook_pattern = '/^(https?:\/\/)?(www\.)?(facebook\.com|fb\.com)\/.+$/i';
			return preg_match( $facebook_pattern, $url ) === 1;
		}

		/**
		 * Render Facebook embed code.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_facebook_embed( $atts ) {
			$style = '';
			if ( isset( $atts['align'] ) ) {
				$style .= 'text-align: ' . $atts['align'] . ';';
			}

			if ( empty( $atts['url'] ) || ! $this->is_valid_facebook_url( $atts['url'] ) ) {
				return '<p>' . esc_html__( 'Please provide a valid Facebook post URL.', 'social-elements-for-wpbakery' ) . '</p>';
			}

			$url   = isset( $atts['url'] ) ? esc_url( $atts['url'] ) : '';
			$width = isset( $atts['width'] ) ? esc_attr( intval( $atts['width'] ) ) : '';

			$embed_html  = '<div class="fb-post" data-href="' . $url . '" data-width="' . $width . '"></div>';
			$embed_html .= '<div id="fb-root"></div>';

			// Apply lazy loading if enabled.
			if ( function_exists( 'sefwpb_is_lazy_load_enabled' ) && sefwpb_is_lazy_load_enabled() ) {
				$embed_html = sefwpb_wrap_for_lazy_load( $embed_html, 'facebook', $url );
			}

			return '<div class="sefwpb-facebook-embed-container" style="' . esc_attr( $style ) . '">' . $embed_html . '</div>';
		}
	}
}
