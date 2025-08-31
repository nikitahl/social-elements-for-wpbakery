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
			$styles  = '';
			$styles .= $this->get_shape_style( $atts );
			$styles .= $this->get_align_style( $atts );
			$styles .= $this->get_gap_style( $atts );
			$styles .= $this->get_size_style( $atts );
			$styles .= $this->get_text_color_style( $atts );
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
			$link        = ! empty( $button['url'] ) ? vc_build_link( $button['url'] ) : '';
			$label       = $this->get_label( $button );
			$url         = $this->get_url( $link );
			$icon        = $this->get_icon_html( $button, $label );
			$color_style = $this->get_color_style( $button );
			$title       = $this->get_title_attr( $link );
			$target      = $this->get_target_attr( $link );
			$rel         = $this->get_rel_attr( $link );
			$class       = $this->get_button_class( $button );

			return sprintf(
				'<a href="%1$s" class="sefwpb-profile-links__button %2$s" style="%3$s" title="%4$s" target="%5$s" %6$s aria-label="%4$s">%7$s</a>',
				esc_url( $url ),
				esc_attr( $class ),
				esc_attr( $color_style ),
				$title,
				$target,
				$rel,
				$icon
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
			$atts    = vc_map_get_attributes( $this->getShortcode(), $atts );
			$values  = $atts['values'];
			$buttons = [];
			$wrapper_classes = $this->get_wrapper_classes( $atts );
			if ( ! empty( $values ) ) {
				$buttons = vc_param_group_parse_atts( $values );
			}

			$output  = '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '" style="' . esc_attr( $this->get_button_styles( $atts ) ) . '">';
			$output .= ! empty( $atts['profile_title'] ) ? '<h3 class="sefwpb-profile-links__title">' . esc_html( $atts['profile_title'] ) . '</h3>' : '';
			$output .= '<div class="sefwpb-profile-links-buttons">' . $this->get_buttons_output( $buttons ) . '</div>';
			$output .= '</div>';

			return $output;
		}

		// --- Private helper methods for complexity reduction ---

		/**
		 * Get wrapper classes.
		 *
		 * @param array $atts
		 * @return array
		 */
		private function get_wrapper_classes( $atts ) {
			$classes = [ 'sefwpb-profile-links', $atts['el_class'] ];
			if ( ! empty( $atts['style'] ) ) {
				$classes[] = 'sefwpb-profile-links--' . esc_attr( $atts['style'] );
			}
			if ( ! empty( $atts['css'] ) ) {
				$classes[] = vc_shortcode_custom_css_class( $atts['css'], ' ' );
			}
			return $classes;
		}

		/**
		 * Get label.
		 *
		 * @param array $button
		 * @return string
		 */
		private function get_label( $button ) {
			return ! empty( $button['label'] ) ? $button['label'] : '';
		}

		/**
		 * Get URL.
		 *
		 * @param array $link
		 * @return string
		 */
		private function get_url( $link ) {
			if ( ! empty( $link['url'] ) ) {
				return $link['url'];
			}
			// Fallback '#'.
			return '#';
		}

		/**
		 * Get icon HTML.
		 *
		 * @param array  $button
		 * @param string $label
		 * @return string
		 */
		private function get_icon_html( $button, $label ) {
			$icon_type = ! empty( $button['icon_type'] ) ? $button['icon_type'] : 'monosocial';
			$icon = '';
			if ( 'monosocial' === $icon_type && ! empty( $button['icon_monosocial'] ) ) {
				$icon = '<i class="' . esc_attr( $button['icon_monosocial'] ) . '" aria-hidden="true"></i>';
			}
			if ( 'fontawesome' === $icon_type && ! empty( $button['icon_fontawesome'] ) ) {
				$icon = '<i class="' . esc_attr( $button['icon_fontawesome'] ) . '" aria-hidden="true"></i>';
			}
			if ( 'custom' === $icon_type && ! empty( $button['icon_custom'] ) ) {
				$icon = '<img src="' . esc_url( $button['icon_custom'] ) . '" alt="' . esc_attr( $label ) . '"/>';
			}
			if ( $icon ) {
				return '<span class="sefwpb-profile-links__icon">' . $icon . '</span>';
			}
			return '';
		}

		/**
		 * Get color style.
		 *
		 * @param array $button
		 * @return string
		 */
		private function get_color_style( $button ) {
			return ! empty( $button['color'] ) ? '--button-bg:' . esc_attr( $button['color'] ) . ';' : '';
		}

		/**
		 * Get title attribute.
		 *
		 * @param array $link
		 * @return string
		 */
		private function get_title_attr( $link ) {
			if ( ! empty( $link['title'] ) ) {
				return $link['title'];
			}
			return '';
		}

		/**
		 * Get target attribute.
		 *
		 * @param array $link
		 * @return string
		 */
		private function get_target_attr( $link ) {
			if ( is_array( $link ) && ! empty( $link['target'] ) ) {
				return esc_attr( $link['target'] );
			}
			return '_self';
		}

		/**
		 * Get rel attribute.
		 *
		 * @param array $link
		 * @return string
		 */
		private function get_rel_attr( $link ) {
			if ( ! empty( $link['rel'] ) ) {
				return 'rel="' . esc_attr( $link['rel'] ) . '"';
			}
			return '';
		}

		/**
		 * Get button class.
		 *
		 * @param array $button
		 * @return string
		 */
		private function get_button_class( $button ) {
			$classes = [];
			if ( ! empty( $button['class'] ) ) {
				$classes[] = $button['class'];
			}
			if ( ! empty( $button['style'] ) ) {
				$classes[] = 'sefwpb-profile-link-style-' . esc_attr( $button['style'] );
			}
			return implode( ' ', $classes );
		}

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
				return '--button-font-size:11px;--button-icon-size:20px;';
			}
			if ( 'md' === $atts['size'] ) {
				return '--button-font-size:16px;--button-icon-size:40px;';
			}
			if ( 'lg' === $atts['size'] ) {
				return '--button-font-size:20px;--button-icon-size:48px;';
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
	}
}
