<?php
/**
 * WPBakery Element: Social Share Buttons
 *
 * Renders a flexible set of share buttons for common networks.
 *
 * @package SocialElementsWPBakery
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * Class WPBakeryShortCode_Sefwpb_Social_Share
	 *
	 * Handles the Social Share Buttons element for WPBakery.
	 */
	class WPBakeryShortCode_Sefwpb_Social_Share extends WPBakeryShortCode {

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
				'vc_font_awesome',
				vc_asset_url( 'lib/vendor/dist/@fortawesome/fontawesome-free/css/all.min.css' ),
				[],
				WPB_VC_VERSION
			);
			wp_enqueue_style(
				'vc_monosocial',
				vc_asset_url( 'css/lib/monosocialiconsfont/monosocialiconsfont.min.css' ),
				[],
				WPB_VC_VERSION
			);
			wp_enqueue_style(
				'sefwpb-social-share',
				plugins_url( '/assets/css/social-share.css', __FILE__ ),
				[],
				SEFWPB_VERSION
			);
			wp_enqueue_script(
				'sefwpb-social-share-copy',
				plugins_url( '/assets/js/social-share-copy.js', __FILE__ ),
				[],
				SEFWPB_VERSION,
				true
			);
		}

		/**
		 * Get button styles.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		public function get_button_styles( $atts ) {
			$styles  = $this->get_shape_style( $atts );
			$styles .= $this->get_align_style( $atts );
			$styles .= $this->get_gap_style( $atts );
			$styles .= $this->get_size_style( $atts );
			$styles .= $this->get_text_color_style( $atts );
			return $styles;
		}

		/**
		 * Generate a single button output.
		 *
		 * @param array $btn Button data.
		 * @return string
		 */
		private function get_single_button_output( $btn ) {
			$platform   = $this->get_platform( $btn );
			$label      = $this->get_label( $btn, $platform );
			$color      = $this->get_color( $btn );
			$icon       = $this->get_icon_html( $btn, $label );
			$url        = $this->get_share_url( $platform );
			$extra_attr = $this->get_extra_attr( $platform, $url );
			$classes    = $this->get_button_classes( $platform );

			$output = '<a class="' . esc_attr( $classes ) . '" href="' . esc_url( $url ) . '" style="' . esc_attr( $color ) . '" ' . $extra_attr . '>';
			if ( $icon ) {
				$output .= '<span class="sefwpb-social-share__icon">' . $icon . '</span>';
			}
			$output .= '<span class="sefwpb-social-share__label">' . esc_html( $label ) . '</span>';
			$output .= '</a>';

			return $output;
		}

		/**
		 * Generate buttons output.
		 *
		 * @param array $buttons Buttons data.
		 * @return string
		 */
		public function get_buttons_output( $buttons ) {
			$output = '<div class="sefwpb-social-share__buttons">';
			foreach ( $buttons as $btn ) {
				$output .= $this->get_single_button_output( $btn );
			}
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
			$atts    = vc_map_get_attributes( $this->getShortcode(), $atts );
			$buttons = $this->parse_buttons( $atts );
			$classes = $this->get_wrapper_classes( $atts );
			$style   = $this->get_button_styles( $atts );
			$el_id   = ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '';

			$output  = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" style="' . esc_attr( $style ) . '"' . $el_id . '>';
			$output .= $this->get_share_title( $atts );
			$output .= $this->get_buttons_output( $buttons );
			$output .= '</div>';

			return $output;
		}

		// Private helpers for complexity reduction.

		/**
		 * Get shape style.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function get_shape_style( $atts ) {
			return ! empty( $atts['shape'] ) ? '--border-radius:' . esc_attr( $atts['shape'] ) . ';' : '';
		}

		/**
		 * Get align style.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function get_align_style( $atts ) {
			return ! empty( $atts['align'] ) ? '--justify-content:' . esc_attr( $atts['align'] ) . ';' : '';
		}

		/**
		 * Get gap style.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function get_gap_style( $atts ) {
			return ! empty( $atts['gap'] ) ? '--button-gap:' . esc_attr( $atts['gap'] ) . ';' : '';
		}

		/**
		 * Get size style.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function get_size_style( $atts ) {
			if ( empty( $atts['size'] ) ) {
				return '';
			}
			if ( 'sm' === $atts['size'] ) {
				return '--button-padding:5px;--button-font-size:11px;--button-icon-size:14px;';
			}
			if ( 'md' === $atts['size'] ) {
				return '--button-padding:12px;--button-font-size:16px;--button-icon-size:19px;';
			}
			if ( 'lg' === $atts['size'] ) {
				return '--button-padding:16px;--button-font-size:20px;--button-icon-size:25px;';
			}
			return '';
		}

		/**
		 * Get text color style.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function get_text_color_style( $atts ) {
			return ( ! empty( $atts['text_color'] ) && 'minimal' === $atts['style'] )
				? '--button-color:' . esc_attr( $atts['text_color'] ) . ';'
				: '';
		}

		/**
		 * Parse param_group values.
		 *
		 * @param array $atts
		 * @return array
		 */
		private function parse_buttons( $atts ) {
			$values = isset( $atts['values'] ) ? $atts['values'] : [];
			if ( ! empty( $values ) ) {
				return vc_param_group_parse_atts( $values );
			}
			return [];
		}

		/**
		 * Get wrapper classes.
		 *
		 * @param array $atts
		 * @return array
		 */
		private function get_wrapper_classes( $atts ) {
			$classes = [
				'sefwpb-social-share',
				'sefwpb-social-share--' . esc_attr( $atts['style'] ),
			];
			if ( ! empty( $atts['el_class'] ) ) {
				$classes[] = esc_attr( $atts['el_class'] );
			}
			if ( ! empty( $atts['css'] ) ) {
				$classes[] = vc_shortcode_custom_css_class( $atts['css'] );
			}
			return $classes;
		}

		/**
		 * Get share title HTML.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function get_share_title( $atts ) {
			if ( ! empty( $atts['share_title'] ) ) {
				return '<h3 class="sefwpb-social-share__title">' . esc_html( $atts['share_title'] ) . '</h3>';
			}
			return '';
		}

		/**
		 * Get platform.
		 *
		 * @param array $btn
		 * @return string
		 */
		private function get_platform( $btn ) {
			return isset( $btn['social_platform'] ) ? $btn['social_platform'] : '';
		}

		/**
		 * Get label.
		 *
		 * @param array  $btn
		 * @param string $platform
		 * @return string
		 */
		private function get_label( $btn, $platform ) {
			return isset( $btn['label'] ) ? $btn['label'] : ucfirst( $platform );
		}

		/**
		 * Get color style.
		 *
		 * @param array $btn
		 * @return string
		 */
		private function get_color( $btn ) {
			return ! empty( $btn['color'] ) ? '--button-bg:' . esc_attr( $btn['color'] ) . ';' : '';
		}

		/**
		 * Get icon HTML.
		 *
		 * @param array  $btn
		 * @param string $label
		 * @return string
		 */
		private function get_icon_html( $btn, $label ) {
			if ( isset( $btn['icon_type'] ) ) {
				if ( 'monosocial' === $btn['icon_type'] && ! empty( $btn['icon_monosocial'] ) ) {
					return '<i class="' . esc_attr( $btn['icon_monosocial'] ) . '" aria-hidden="true"></i>';
				}
				if ( 'fontawesome' === $btn['icon_type'] && ! empty( $btn['icon_fontawesome'] ) ) {
					return '<i class="' . esc_attr( $btn['icon_fontawesome'] ) . '" aria-hidden="true"></i>';
				}
				if ( 'custom' === $btn['icon_type'] && ! empty( $btn['icon_custom'] ) ) {
					return '<img src="' . esc_url( $btn['icon_custom'] ) . '" alt="' . esc_attr( $label ) . '"/>';
				}
			}
			return '';
		}

		/**
		 * Get share URL for platform.
		 *
		 * @param string $platform
		 * @return string
		 */
		private function get_share_url( $platform ) {
			$url   = get_permalink();
			$title = get_the_title();

			$templates = [
				'facebook'  => 'https://www.facebook.com/sharer/sharer.php?u=%s',
				'twitter'   => 'https://twitter.com/intent/tweet?url=%s&text=%s',
				'linkedin'  => 'https://www.linkedin.com/shareArticle?mini=true&url=%s&title=%s',
				'pinterest' => 'https://pinterest.com/pin/create/button/?url=%s&description=%s',
			];

			if ( isset( $templates[ $platform ] ) ) {
				if ( 'facebook' === $platform ) {
					return sprintf( $templates[ $platform ], rawurlencode( $url ) );
				}
				return sprintf( $templates[ $platform ], rawurlencode( $url ), rawurlencode( $title ) );
			}

			if ( 'copy' === $platform ) {
				return 'javascript:void(0);';
			}

			return '#';
		}

		/**
		 * Get extra attribute for button.
		 *
		 * @param string $platform
		 * @param string $url
		 * @return string
		 */
		private function get_extra_attr( $platform, $url ) {
			if ( 'copy' === $platform ) {
				return 'data-share-copy="' . esc_url( $url ) . '"';
			}
			return 'target="_blank" rel="noopener noreferrer"';
		}

		/**
		 * Get button classes.
		 *
		 * @param string $platform
		 * @return string
		 */
		private function get_button_classes( $platform ) {
			return 'sefwpb-social-share__button sefwpb-social-share__button--' . esc_attr( $platform );
		}
	}
}
