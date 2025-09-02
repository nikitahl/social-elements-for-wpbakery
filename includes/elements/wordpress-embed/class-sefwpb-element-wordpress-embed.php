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
		 * @param array $atts Shortcode attributes.
		 * @param array $link Link array from vc_build_link().
		 *
		 * @return string HTML content of the embedded post.
		 */
		public function get_post_content( $atts, $link ) {
			$output  = '';
			$url     = $link['url'];
			$post_id = url_to_postid( $url );
			if ( $post_id ) {
				$post = get_post( $post_id );
				if ( $post ) {
					// Featured image.
					$featured_img = get_the_post_thumbnail( $post_id, 'large', [ 'class' => 'sefwpb-embed-featured-image' ] );
					// Title.
					$post_title = '<h3 class="sefwpb-embed-title">' . esc_html( get_the_title( $post_id ) ) . '</h3>';
					// Excerpt.
					$excerpt = '<p class="sefwpb-embed-excerpt">' . esc_html( get_the_excerpt( $post_id ) ) . '</p>';
					// Site logo.
					$site_logo      = '';
					$custom_logo_id = get_theme_mod( 'custom_logo' );
					if ( $custom_logo_id ) {
						$site_logo = wp_get_attachment_image( $custom_logo_id, 'full', false, [ 'class' => 'sefwpb-embed-site-logo' ] );
					}
					$width         = ! empty( $atts['width'] ) ? intval( $atts['width'] ) : 500;
					$rel           = ( isset( $link['rel'] ) && ! empty( $link['rel'] ) ) ? $link['rel'] : '';
					$target        = ( isset( $link['target'] ) && ! empty( $link['target'] ) ) ? $link['target'] : '_self';
					$rel_attribute = $rel ? ' rel="' . esc_attr( $rel ) . '"' : '';
					// Site name.
					$site_name = '<span class="sefwpb-embed-site-name">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
					// Translators: %d is the number of comments.
					$comments_count = '<div class="sefwpb-embed-comments-count">' . sprintf( esc_html__( '%d Comments', 'social-elements-wpbakery' ), get_comments_number( $post_id ) ) . '</div>';
					$output        .= '<div class="sefwpb-embed-content" style="width:' . esc_attr( $width ) . 'px;">';
					$output        .= '<a class="sefwpb-embed-image-link" href="' . esc_url( $url ) . '" target="' . $target . '"' . $rel_attribute .  '>' . $featured_img . '</a>';
					$output        .= '<a class="sefwpb-embed-title-link" href="' . esc_url( $url ) . '" target="' . $target . '"' . $rel_attribute .  '>' . $post_title . '</a>';
					$output        .= $excerpt;
					$output        .= '<div class="sefwpb-embed-meta">';
					$output        .= '<a class="sefwpb-embed-brand" href="' . esc_url( home_url() ) . '" target="_blank" rel="noreferrer">' . $site_logo . $site_name . '</a>';
					$output        .= '<a class="sefwpb-embed-comments" href="' . esc_url( $url ) . '#respond" target="' . $target . '"' . $rel_attribute . '>';
					$output        .= $comments_count;
					$output        .= '</a>'; // .sefwpb-embed-comments.
					$output        .= '</div>'; // .sefwpb-embed-meta.
					$output        .= '</div>';
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
			$atts    = vc_map_get_attributes( $this->getShortcode(), $atts );
			$output  = $this->build_wrapper_open( $atts );
			$output .= $this->build_title( $atts );
			$output .= $this->build_embed_or_error( $atts );
			$output .= '</div>';
			return $output;
		}

		/**
		 * Build the opening wrapper div.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function build_wrapper_open( $atts ) {
			$el_id     = isset( $atts['el_id'] ) ? $atts['el_id'] : '';
			$css_class = $this->build_css_class( $atts );
			$id_attr   = ! empty( $el_id ) ? 'id="' . esc_attr( $el_id ) . '"' : '';
			return '<div ' . $id_attr . ' class="' . esc_attr( $css_class ) . '">';
		}

		/**
		 * Build the title HTML if present.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function build_title( $atts ) {
			if ( ! empty( $atts['title'] ) ) {
				return '<h3 class="sefwpb-element-title">' . esc_html( $atts['title'] ) . '</h3>';
			}
			return '';
		}

		/**
		 * Build the embed or error message.
		 *
		 * @param array $atts
		 * @return string
		 */
		private function build_embed_or_error( $atts ) {
			$link = ! empty( $atts['url'] ) ? vc_build_link( $atts['url'] ) : '';
			if ( ! empty( $link['url'] ) ) {
				$align = isset( $atts['align'] ) ? $atts['align'] : 'left';
				$style = 'style="--justify-content: ' . esc_attr( $align ) . ';"';
				return '<div class="sefwpb-embed-wrapper"' . $style . '">'
					. $this->get_post_content( $atts, $link )
					. '</div>';
			}
			return $this->build_error_message();
		}

		/**
		 * Build the error message for missing URL.
		 *
		 * @return string
		 */
		private function build_error_message() {
			return '<p>' . esc_html__( 'Please provide a valid URL to embed.', 'social-elements-wpbakery' ) . '</p>';
		}
	}
}
