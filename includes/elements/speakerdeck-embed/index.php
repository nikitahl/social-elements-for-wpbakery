<?php
/**
 * Register the Speaker Deck Embed element for WPBakery.
 *
 * @package SocialElementsWPBakery
 * @since   1.3.0
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'vc_before_init',
	function () {
		require_once __DIR__ . '/class-sefwpb-element-speakerdeck-embed.php';

		vc_map(
			[
				'name'        => esc_html__( 'Speaker Deck Embed', 'social-elements-for-wpbakery' ),
				'base'        => 'sefwpb_speakerdeck_embed',
				'description' => esc_html__( 'Embed Speaker Deck presentation', 'social-elements-for-wpbakery' ),
				'category'    => esc_html__( 'Social', 'social-elements-for-wpbakery' ),
				'icon'        => SEFWPB_ASSETS_URI . '/images/icons/icon-speakerdeck-embed.svg',
				'params'      => [
					[
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'social-elements-for-wpbakery' ),
						'param_name'  => 'title',
						'description' => esc_html__( 'Optional. Enter title to display above embed.', 'social-elements-for-wpbakery' ),
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Speaker Deck URL', 'social-elements-for-wpbakery' ),
						'param_name'  => 'url',
						'description' => esc_html__( 'Enter the URL of the Speaker Deck presentation you want to embed.', 'social-elements-for-wpbakery' ),
						'value'       => 'https://speakerdeck.com/aleyda/the-state-of-ecommerce-seo-and-ai-search-in-2026-what-are-the-shifts-and-the-top-actions-to-take-smx-munich',
					],
					[
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Alignment', 'social-elements-for-wpbakery' ),
						'param_name' => 'align',
						'group'      => esc_html__( 'Styles', 'social-elements-for-wpbakery' ),
						'value'      => [
							esc_html__( 'Left', 'social-elements-for-wpbakery' )   => 'flex-start',
							esc_html__( 'Center', 'social-elements-for-wpbakery' ) => 'center',
							esc_html__( 'Right', 'social-elements-for-wpbakery' )  => 'flex-end',
						],
						'std'        => 'flex-start',
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Width', 'social-elements-for-wpbakery' ),
						'param_name'  => 'width',
						'group'       => esc_html__( 'Styles', 'social-elements-for-wpbakery' ),
						'description' => esc_html__( 'Optional. Enter width of the embedded content in pixels or percentage. Default is 100%.', 'social-elements-for-wpbakery' ),
						'value'       => '100%',
					],
					vc_map_add_css_animation(),
					[
						'type'        => 'el_id',
						'heading'     => esc_html__( 'Element ID', 'social-elements-for-wpbakery' ),
						'param_name'  => 'el_id',
						'description' => sprintf(
							// translators: %1$s: link to w3c specification, %2$s: closing anchor tag.
							esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %1$sw3c specification%2$s).', 'social-elements-for-wpbakery' ),
							'<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">',
							'</a>'
						),
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'social-elements-for-wpbakery' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'social-elements-for-wpbakery' ),
					],
					[
						'type'       => 'css_editor',
						'heading'    => esc_html__( 'CSS', 'social-elements-for-wpbakery' ),
						'param_name' => 'css',
						'group'      => esc_html__( 'Design Options', 'social-elements-for-wpbakery' ),
					],
				],
			]
		);
	}
);
