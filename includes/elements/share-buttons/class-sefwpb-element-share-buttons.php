<?php

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {

	/**
	 * WPBakery Element: Social Share Buttons
	 *
	 * Renders a flexible set of share buttons for common networks.
	 *
	 * @since 1.0.0
	 */
	class WPBakeryShortCode_Sefwpb_Social_Share extends WPBakeryShortCode {

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
		public function get_button_styles ( $atts ) {
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
				if ( $atts['size'] === 'sm' ) {
					$styles .= '--button-padding:4px 8px;';
					$styles .= '--button-font-size:12px;';
					$styles .= '--button-icon-size:14px;';
				} elseif ( $atts['size'] === 'md' ) {
					$styles .= '--button-padding:6px 12px;';
					$styles .= '--button-font-size:14px;';
					$styles .= '--button-icon-size:16px;';
				} elseif ( $atts['size'] === 'lg' ) {
					$styles .= '--button-padding:8px 16px;';
					$styles .= '--button-font-size:16px;';
					$styles .= '--button-icon-size:18px;';
				}
			}
			if ( ! empty( $atts['text_color'] ) && $atts['style'] === 'minimal' ) {
				$styles .= '--button-color:' . esc_attr( $atts['text_color'] ) . ';';
			}

			return $styles;
		}

		/**
		 * Generate buttons output.
		 *
		 * @param array $buttons Buttons data.
		 * @return string
		 */
		public function get_buttons_output ( $buttons ) {
			$output = '<div class="sefwpb-social-share__buttons">';
			foreach ( $buttons as $btn ) {
				$platform = isset( $btn['social_platform'] ) ? $btn['social_platform'] : '';
				$label    = isset( $btn['label'] ) ? $btn['label'] : ucfirst( $platform );
				$color    = ! empty( $btn['color'] ) ? '--button-bg:' . esc_attr( $btn['color'] ) . ';' : '';
				$icon     = '';
				if ( isset( $btn['icon_type'] ) ) {
					if ( $btn['icon_type'] === 'fontawesome' && ! empty( $btn['icon_fontawesome'] ) ) {
						$icon = '<i class="' . esc_attr( $btn['icon_fontawesome'] ) . '"></i>';
					} elseif ( $btn['icon_type'] === 'monosocial' && ! empty( $btn['icon_monosocial'] ) ) {
						$icon = '<i class="' . esc_attr( $btn['icon_monosocial'] ) . '"></i>';
					}
				}

				// Build share URL
				$url   = get_permalink();
				$title = get_the_title();
				if ( $platform === 'copy' ) {
					$output .= '<a href="javascript:void(0);" class="sefwpb-social-share__button sefwpb-social-share__button--copy" style="' . $color . '"'
						. ' data-link="' . esc_attr( $url ) . '"'
						. ' data-copy-label="' . esc_attr( __( 'Copy link', SEFWPB_TD ) ) . '"'
						. ' data-copied-label="' . esc_attr( __( 'Link copied', SEFWPB_TD ) ) . '"'
						. ' data-fail-label="' . esc_attr( __( 'Failed to copy link. Please copy it manually:', SEFWPB_TD ) ) . '"'
						. ' title="' . esc_attr( $label ) . '">'
						. $icon . '<span>' . esc_html( __( 'Copy link', SEFWPB_TD ) ) . '</span></a>';
				} else {
					switch ( $platform ) {
						case 'facebook':
							$share_url = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( $url );
							break;
						case 'x':
							$share_url = 'https://twitter.com/intent/tweet?url=' . rawurlencode( $url ) . '&text=' . rawurlencode( $title );
							break;
						case 'linkedin':
							$share_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . rawurlencode( $url ) . '&title=' . rawurlencode( $title );
							break;
						case 'reddit':
							$share_url = 'https://www.reddit.com/submit?url=' . rawurlencode( $url ) . '&title=' . rawurlencode( $title );
							break;
						case 'pinterest':
							$share_url = 'https://pinterest.com/pin/create/button/?url=' . rawurlencode( $url ) . '&description=' . rawurlencode( $title );
							break;
						case 'whatsapp':
							$share_url = 'https://wa.me/?text=' . rawurlencode( $title . ' ' . $url );
							break;
						case 'telegram':
							$share_url = 'https://t.me/share/url?url=' . rawurlencode( $url ) . '&text=' . rawurlencode( $title );
							break;
						case 'email':
							$share_url = 'mailto:?subject=' . rawurlencode( $title ) . '&body=' . rawurlencode( $url );
							break;
						default:
							$share_url = '#';
					}
					$output .= '<a href="' . esc_url( $share_url ) . '" class="sefwpb-social-share__button sefwpb-social-share__button--' . esc_attr( $platform ) . '" style="' . $color . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr( $label ) .'">'
						. $icon . '<span>' . esc_html( $label ) . '</span></a>';
				}
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
			$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
			$values = $atts['values'];

			// Parse param_group values
			$buttons = [];
			if ( ! empty( $values ) ) {
				$buttons = vc_param_group_parse_atts( $values );
			}

			// Prepare wrapper classes and styles
			$classes = [ 'sefwpb-social-share', 'sefwpb-social-share--' . esc_attr( $atts['style'] ) ];
			if ( ! empty( $atts['el_class'] ) ) {
				$classes[] = esc_attr( $atts['el_class'] );
			}
			if ( ! empty( $atts['css'] ) ) {
				$classes[] = vc_shortcode_custom_css_class( $atts['css'] );
			}
			$style = $this->get_button_styles( $atts );

			$output  = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" style="' . esc_attr( $style ) . '"' . ( $atts['el_id'] ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '' ) . '>';
			if ( ! empty( $atts['share_title'] ) ) {
				$output .= '<div class="sefwpb-social-share__title">' . esc_html( $atts['share_title'] ) . '</div>';
			}

			if ( $buttons ) {
				$output .= $this->get_buttons_output( $buttons );
			}

			$output .= '</div>';

			return $output;
		}
	}
}
