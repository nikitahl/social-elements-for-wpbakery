<?php
/**
 * WPBakery Element: Social Profile Links
 *
 * Renders a flexible set of buttons linking to social media profiles.
 *
 * @package SocialElementsWPBakery
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * Class WPBakeryShortCode_Sefwpb_Profile_Links
	 *
	 * Handles the Social Profile Links element for WPBakery.
	 */
	class WPBakeryShortCode_Sefwpb_Profile_Links extends WPBakeryShortCode {

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
			// Fontawesome bundled with WPBakery.
			wp_enqueue_style(
				'vc_font_awesome',
				vc_asset_url( 'lib/vendor/dist/@fortawesome/fontawesome-free/css/all.min.css' ),
				[],
				WPB_VC_VERSION
			);

			// Monosocial bundled with WPBakery.
			wp_enqueue_style(
				'vc_monosocial',
				vc_asset_url( 'css/lib/monosocialiconsfont/monosocialiconsfont.css' ),
				[],
				WPB_VC_VERSION
			);
			wp_enqueue_style(
				'sefwpb-profile-links',
				plugins_url( '/assets/css/profile-links.css', __FILE__ ),
				[],
				SEFWPB_VERSION
			);
		}

		/**
		 * Get button styles.
		 *
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		public function get_button_styles( array $atts ) {
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
		 * Generate buttons output.
		 *
		 * @param array $buttons Buttons data.
		 * @return string
		 */
		public function get_buttons_output( $buttons ) {
			$output = '';
			foreach ( $buttons as $button ) {
				$output .= $this->get_single_button_output( $button );
			}
			return $output;
		}

		/**
		 * Generate a single button output.
		 *
		 * @param array $button Button data.
		 * @return string
		 */
		private function get_single_button_output( $button ) {
			$label     = ! empty( $button['label'] ) ? $button['label'] : '';
			$url       = ! empty( $button['url'] ) ? $button['url'] : '#';
			$icon_type = ! empty( $button['icon_type'] ) ? $button['icon_type'] : 'monosocial';
			$icon      = '';

			if ( 'monosocial' === $icon_type && ! empty( $button['icon_monosocial'] ) ) {
				$icon = '<i class="' . esc_attr( $button['icon_monosocial'] ) . '" aria-hidden="true"></i>';
			} elseif ( 'fontawesome' === $icon_type && ! empty( $button['icon_fontawesome'] ) ) {
				$icon = '<i class="' . esc_attr( $button['icon_fontawesome'] ) . '" aria-hidden="true"></i>';
			} elseif ( 'custom' === $icon_type && ! empty( $button['icon_custom'] ) ) {
				$icon = '<img src="' . esc_url( $button['icon_custom'] ) . '" alt="' . esc_attr( $label ) . '"/>';
			}

			$color_style = '';
			if ( ! empty( $button['color'] ) ) {
				$color_style = 'style="--button-bg: ' . esc_attr( $button['color'] ) . ';"';
			}

			$title = '';
			if ( $label ) {
				$title = 'title="' . esc_html( $label ) . '"';
			}

			$output  = '<a class="sefwpb-profile-links__button" href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" ' . $color_style . ' ' . $title . '>';
			if ( $icon ) {
				$output .= '<span class="sefwpb-profile-links__icon">' . $icon . '</span>';
			}
			$output .= '</a>';

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
				'sefwpb-profile-links',
				'sefwpb-profile-links--' . esc_attr( $atts['style'] ),
			];
			if ( ! empty( $atts['el_class'] ) ) {
				$classes[] = esc_attr( $atts['el_class'] );
			}
			if ( ! empty( $atts['css'] ) ) {
				$classes[] = vc_shortcode_custom_css_class( $atts['css'] );
			}
			$style = $this->get_button_styles( $atts );

			$output  = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '" style="' . esc_attr( $style ) . '"' . ( ! empty( $atts['el_id'] ) ? ' id="' . esc_attr( $atts['el_id'] ) . '"' : '' ) . '>';
			if ( ! empty( $atts['profile_title'] ) ) {
				$output .= '<div class="sefwpb-profile-links__title">' . esc_html( $atts['profile_title'] ) . '</div>';
			}

			if ( ! empty( $buttons ) ) {
				$output .= $this->get_buttons_output( $buttons );
			}

			$output .= '</div>';

			return $output;
		}
	}
}
