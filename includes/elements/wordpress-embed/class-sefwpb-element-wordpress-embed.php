<?php
/**
 * WPBakery Element: WordPress Embed
 *
 * Embeds WordPress post or page.
 *
 * @package SocialElementsWPBakery
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {
	/**
	 * Class WPBakeryShortCode_Sefwpb_WordPress_Embed
	 *
	 * Handles the WordPress embed element for WPBakery.
	 */
	class WPBakeryShortCode_Sefwpb_WordPress_Embed extends WPBakeryShortCode {

		/**
		 * Constructor.
		 *
		 * @param array $settings Shortcode settings.
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
				'sefwpb-wordpress-embed',
				plugins_url( '/assets/css/wordpress-embed.css', __FILE__ ),
				[],
				defined( 'SEFWPB_VERSION' ) ? SEFWPB_VERSION : '1.0.0'
			);
		}

		/**
		 * Get the HTML content of the embedded post.
		 *
		 * @param string $url URL of the post to embed.
		 *
		 * @return string HTML content of the embedded post.
		 */
		public function get_post_content( $url ) {
			$output   = '';
			$post_id  = url_to_postid( $url );
			if ( $post_id ) {
				$post = get_post( $post_id );
				if ( $post ) {
					// Featured image.
					$featured_img = get_the_post_thumbnail( $post_id, 'large', [ 'class' => 'sefwpb-embed-featured-image' ] );
					// Title.
					$post_title   = '<h3 class="sefwpb-embed-title">' . esc_html( get_the_title( $post_id ) ) . '</h3>';
					// Excerpt.
					$excerpt      = '<p class="sefwpb-embed-excerpt">' . esc_html( get_the_excerpt( $post_id ) ) . '</p>';
					// Site logo.
					$site_logo    = '';
					$custom_logo_id = get_theme_mod( 'custom_logo' );
					if ( $custom_logo_id ) {
						$site_logo = wp_get_attachment_image( $custom_logo_id, 'full', false, [ 'class' => 'sefwpb-embed-site-logo' ] );
					}
					// Site name.
					$site_name = '<span class="sefwpb-embed-site-name">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
					// Translators: %d is the number of comments.
					$comments_count = '<div class="sefwpb-embed-comments-count">' . sprintf( esc_html__( '%d Comments', 'social-elements-wpbakery' ), get_comments_number( $post_id ) ) . '</div>';
					$output .= '<div class="sefwpb-embed-content">';
					$output .= '<a class="sefwpb-embed-image-link" href="' . esc_url( $url ) . '" target="_blank" rel="noreferrer">' . $featured_img . '</a>';
					$output .= '<a class="sefwpb-embed-title-link" href="' . esc_url( $url ) . '" target="_blank" rel="noreferrer">' . $post_title . '</a>';
					$output .= $excerpt;
					$output .= '<div class="sefwpb-embed-meta">';
					$output .= '<a class="sefwpb-embed-brand" href="' . esc_url( home_url() ) . '" target="_blank" rel="noreferrer">' . $site_logo . $site_name . '</a>';
					$output .= '<a class="sefwpb-embed-comments" href="' . esc_url( $url ) . '#respond" target="_blank" rel="noreferrer">';
					$output .= $comments_count;
					$output .= '</a>'; // .sefwpb-embed-comments.
					$output .= '</div>'; // .sefwpb-embed-meta.
					$output .= '</div>';
				} else {
					$output .= '<p>' . esc_html__( 'Unable to find the specified post.', 'social-elements-wpbakery' ) . '</p>';
				}
			} else {
				$output .= '<p>' . esc_html__( 'Unable to embed the specified URL.', 'social-elements-wpbakery' ) . '</p>';
			}
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
				'sefwpb-wordpress-embed',
				$el_class,
				$this->getCSSAnimation( $css_animation ),
			];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			return implode( ' ', array_filter( $css_classes ) );
		}

		/**
		 * Shortcode output.
		 *
		 * @param array  $atts    Shortcode attributes.
		 * @param string $content Shortcode content.
		 * @return string
		 */
		public function content( $atts, $content = '' ) {
			$atts         = vc_map_get_attributes( $this->getShortcode(), $atts );
			$title        = isset( $atts['title'] ) ? $atts['title'] : '';
			$url          = isset( $atts['url'] ) ? $atts['url'] : '';
			$align        = isset( $atts['align'] ) ? $atts['align'] : 'left';
			$width        = isset( $atts['width'] ) ? $atts['width'] : '500px';
			$el_id        = isset( $atts['el_id'] ) ? $atts['el_id'] : '';
			$css_class    = $this->build_css_class( $atts );
			$style        = 'style="--justify-content: ' . esc_attr( $align ) . ';"';
			$output       = '<div ' . ( ! empty( $el_id ) ? 'id="' . esc_attr( $el_id ) . '"' : '' ) . ' class="' . esc_attr( $css_class ) . '" ' . $style . '>';
			if ( ! empty( $title ) ) {
				$output .= '<h3 class="sefwpb-element-title">' . esc_html( $title ) . '</h3>';
			}
			if ( ! empty( $url ) ) {
				$output .= '<div class="sefwpb-embed-wrapper" style="width:' . esc_attr( intval( $width ) ) . 'px;">';
				$output .= $this->get_post_content( $url );
				$output .= '</div>';
			} else {
				$output .= '<p>' . esc_html__( 'Please provide a valid URL to embed.', 'social-elements-wpbakery' ) . '</p>';
			}
			$output .= '</div>';
			return $output;
		}
	}
}
