<?php
/**
 * WPBakery Element: Reddit Embed
 *
 * Embeds Reddit post.
 *
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * Class WPBakeryShortCode_Sefwpb_Reddit_Embed
	 *
	 * Handles the Reddit embed element for WPBakery.
	 */
	class WPBakeryShortCode_Sefwpb_Reddit_Embed extends WPBakeryShortCode {

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
				'sefwpb-reddit-embed',
				plugins_url( '/assets/css/reddit-embed.css', __FILE__ ),
				[],
				defined( 'SEFWPB_VERSION' ) ? SEFWPB_VERSION : '1.0.0'
			);
		}
		/**
		 * Gets attributes and builds CSS class for the element.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function build_css_class( $atts ) {
			$el_class      = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$css           = isset( $atts['css'] ) ? $atts['css'] : '';
			$css_animation = isset( $atts['css_animation'] ) ? $atts['css_animation'] : '';
			$css_classes   = [ 'sefwpb-element', 'sefwpb-reddit-embed', $el_class, $this->getCSSAnimation( $css_animation ) ];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			return implode( ' ', array_filter( $css_classes ) );
		}

		/**
		 * Gets element title.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function render_title( $atts ) {
			if ( ! empty( $atts['title'] ) ) {
				return '<h3 class="sefwpb-reddit-embed-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			return '';
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
		 * Renders the Reddit embed blockquote and script.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		private function render_embed( $atts ) {
			$url     = isset( $atts['url'] ) ? esc_url( $atts['url'] ) : '#';
			$width   = isset( $atts['width'] ) ? $this->get_width( $atts['width'] ) : '500px';
			$align   = isset( $atts['align'] ) ? $atts['align'] : 'flex-start';
			$output  = '<div class="sefwpb-reddit-embed-container" style="--justify-content: ' . esc_attr( $align ) . ';--width: ' . esc_attr( $width ) . ';">';
			$output .= '<blockquote class="reddit-embed-bq" style="height: 400px; width: 300px" data-embed-height="400" data-embed-width="300">';
			$output .= '<a href="' . $url . '">Reddit Post</a>';
			$output .= '</blockquote>';
			// phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
			$output .= '<script async="" src="https://embed.reddit.com/widgets.js" charSet="UTF-8"></script>';
			$output .= '</div>';
			return $output;
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
			$output   .= $this->render_embed( $atts );
			$output   .= '</div>';
			return $output;
		}
	}
}
