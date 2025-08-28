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
				vc_asset_url( 'css/lib/monosocialiconsfont/monosocialiconsfont.css' ),
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
			$styles = '';
			if ( ! empty( $atts['shape'] ) ) {
				$styles .= '--border-radius:' . esc_attr( $atts['shape'] ) . ';';
			}
			if ( ! empty( $atts['align'] ) ) {
				$styles .= '--justify-content:' . esc_attr( $atts['align'] ) . ';';
			}
			if ( ! empty( $atts['gap'] ) ) {
				$styles .= '--button-gap:' . esc_attr( $atts['gap'] ) . ';';
			}
			if ( ! empty( $atts['size'] ) ) {
				if ( 'sm' === $atts['size'] ) {
					$styles .= '--button-padding:5px;';
					$styles .= '--button-font-size:11px;';
					$styles .= '--button-icon-size:14px;';
				} elseif ( 'md' === $atts['size'] ) {
					$styles .= '--button-padding:12px;';
					$styles .= '--button-font-size:16px;';
					$styles .= '--button-icon-size:19px;';
				} elseif ( 'lg' === $atts['size'] ) {
					$styles .= '--button-padding:16px;';
					$styles .= '--button-font-size:20px;';
					$styles .= '--button-icon-size:25px;';
				}
			}
			if ( ! empty( $atts['text_color'] ) && 'minimal' === $atts['style'] ) {
				$styles .= '--button-color:' . esc_attr( $atts['text_color'] ) . ';';
			}
			return $styles;
		}

		/**
		 * Generate a single button output.
		 *
		 * @param array $btn Button data.
		 * @return string
		 */
		private function get_single_button_output( $btn ) {
			$platform = isset( $btn['social_platform'] ) ? $btn['social_platform'] : '';
			$label    = isset( $btn['label'] ) ? $btn['label'] : ucfirst( $platform );
			$color    = ! empty( $btn['color'] ) ? '--button-bg:' . esc_attr( $btn['color'] ) . ';' : '';
			$icon     = '';

			if ( isset( $btn['icon_type'] ) ) {
				if ( 'monosocial' === $btn['icon_type'] && ! empty( $btn['icon_monosocial'] ) ) {
					$icon = '<i class="' . esc_attr( $btn['icon_monosocial'] ) . '" aria-hidden="true"></i>';
				} elseif ( 'fontawesome' === $btn['icon_type'] && ! empty( $btn['icon_fontawesome'] ) ) {
					$icon = '<i class="' . esc_attr( $btn['icon_fontawesome'] ) . '" aria-hidden="true"></i>';
				} elseif ( 'custom' === $btn['icon_type'] && ! empty( $btn['icon_custom'] ) ) {
					$icon = '<img src="' . esc_url( $btn['icon_custom'] ) . '" alt="' . esc_attr( $label ) . '"/>';
				}
			}

			$url   = get_permalink();
			$title = get_the_title();

			$share_url = '#';
			if ( 'facebook' === $platform ) {
				$share_url = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $url );
			} elseif ( 'twitter' === $platform ) {
				$share_url = 'https://twitter.com/intent/tweet?url=' . rawurlencode( $url ) . '&text=' . rawurlencode( $title );
			} elseif ( 'linkedin' === $platform ) {
				$share_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . rawurlencode( $url ) . '&title=' . rawurlencode( $title );
			} elseif ( 'pinterest' === $platform ) {
				$share_url = 'https://pinterest.com/pin/create/button/?url=' . rawurlencode( $url ) . '&description=' . rawurlencode( $title );
			} elseif ( 'copy' === $platform ) {
				$share_url = 'javascript:void(0);';
			}

			$extra_attr = ( 'copy' === $platform ) ? 'data-share-copy="' . esc_url( $url ) . '"' : 'target="_blank" rel="noopener noreferrer"';

			$output  = '<a class="sefwpb-social-share__button sefwpb-social-share__button--' . esc_attr( $platform ) . '" href="' . esc_url( $share_url ) . '" style="' . esc_attr( $color ) . '" ' . $extra_attr . '>';
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
			$values  = $atts['values'];

			// Parse param_group values.
			$buttons = [];
			if ( ! empty( $values ) ) {
				$buttons = vc_param_group_parse_atts( $values );
			}

			// Prepare wrapper classes and styles.
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
			$style = $this->get_button_styles( $atts );

			$output  = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" style="' . esc_attr( $style ) . '"' . ( ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '' ) . '>';
			if ( ! empty( $atts['share_title'] ) ) {
				$output .= '<div class="sefwpb-social-share__title">' . esc_html( $atts['share_title'] ) . '</div>';
			}

			if ( ! empty( $buttons ) ) {
				$output .= $this->get_buttons_output( $buttons );
			}

			$output .= '</div>';

			return $output;
		}
	}
}
