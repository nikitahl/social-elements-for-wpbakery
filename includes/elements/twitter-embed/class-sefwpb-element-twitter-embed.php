<?php

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPBakeryShortCode' ) ) {

	/**
	 * WPBakery Element: Twitter Embed
	 *
	 * Embeds X (Twitter) timeline or tweet.
	 *
	 * @since 1.0.0
	 */
	class WPBakeryShortCode_Sefwpb_Twitter_Embed extends WPBakeryShortCode {

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
				'sefwpb-profile-links',
				plugins_url( '/assets/css/twitter-embed.css', __FILE__ ),
				[],
				SEFWPB_VERSION );
            wp_enqueue_script(
                'sefwpb-twitter-embed',
                plugins_url( '/assets/js/twitter-embed.js', __FILE__ ),
                ['jquery'],
                SEFWPB_VERSION,
                true
            );
//			wp_enqueue_script(
//				'twitter-widgets',
//				'https://platform.twitter.com/widgets.js',
//				[],
//				null,
//				true
//			);
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
			// Extract shortcode attributes.
			$title    = isset( $atts['title'] ) ? $atts['title'] : '';
			$url      = isset( $atts['url'] ) ? $atts['url'] : '';
			$align    = isset( $atts['align'] ) ? $atts['align'] : 'left';
			$el_id    = isset( $atts['el_id'] ) ? $atts['el_id'] : '';
			$el_class = isset( $atts['el_class'] ) ? $atts['el_class'] : '';
			$css      = isset( $atts['css'] ) ? $atts['css'] : '';
			$css_animation = isset( $atts['css_animation'] ) ? $atts['css_animation'] : '';
			// Build CSS classes.
			$css_classes = ['sefwpb-element', 'sefwpb-twitter-embed', $el_class, $this->getCSSAnimation( $css_animation )];
			if ( ! empty( $css ) ) {
				$css_classes[] = vc_shortcode_custom_css_class( $css );
			}
			$css_class = implode( ' ', array_filter( $css_classes ) );
			// Start output buffering.
			ob_start();
			?>
			<div <?php echo ! empty( $el_id ) ? 'id="' . esc_attr( $el_id ) . '"' : ''; ?> class="<?php echo esc_attr( $css_class ); ?> sefwpb-align-<?php echo esc_attr( $align ); ?>">
				<?php if ( ! empty( $title ) ) : ?>
					<h3 class="sefwpb-twitter-embed-title"><?php echo esc_html( $title ); ?></h3>
				<?php endif; ?>
				<?php if ( ! empty( $url ) ) : ?>
                    <div class="sefwpb-twitter-embed-container" data-tweet-url="<?php echo esc_url($url); ?>" data-theme="light" data-is-rendered="false">
                        Loading...
                        <script>
                            if (typeof window.sefwpbLoadTwitterEmbed === 'function') {
								window.sefwpbLoadTwitterEmbed();
                            }
                        </script>
                    </div>
<!--					<blockquote class="sefwpb-twitter-tweet twitter-tweet">-->
<!--						<a href="--><?php //echo esc_url( $url ); ?><!--"></a>-->
<!--					</blockquote>-->
<!--                    <script async src="https://platform.twitter.com/widgets.js"></script>-->
<!--                    <script>-->
<!--						function loadTwitterEmbed() {-->
<!--							if (window.twttr && window.twttr.widgets) {-->
<!--								window.twttr.widgets.load();-->
<!--								console.log('load twttr.widgets');-->
<!--							} else {-->
<!--								setTimeout(loadTwitterEmbed, 100);-->
<!--							}-->
<!--						}-->
<!--						loadTwitterEmbed();-->
<!--                    </script>-->
                <?php else : ?>
					<p><?php echo esc_html__( 'Please provide a valid Tweet URL to embed.', SEFWPB_TD ); ?></p>
				<?php endif; ?>
			</div>
			<?php
			// Get output and clean buffer.
			$output = ob_get_clean();
			// Return output.

			return $output;
		}

	}

}
