<?php
/**
 * Register the Flickr Embed element for WPBakery.
 *
 * @package SocialElementsWPBakery
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'vc_before_init',
	function () {
		require_once __DIR__ . '/class-sefwpb-element-flickr-embed.php';

		vc_map(
			[
				'name'        => esc_html__( 'Flickr Embed', 'social-elements-wpbakery' ),
				'base'        => 'sefwpb_flickr_embed',
				'description' => esc_html__( 'Embed a single Flickr image', 'social-elements-wpbakery' ),
				'category'    => esc_html__( 'Social', 'social-elements-wpbakery' ),
				'icon'        => SEFWPB_ASSETS_URI . '/images/icons/icon-flickr-embed.svg',
				'params'      => [
					[
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'social-elements-wpbakery' ),
						'param_name'  => 'title',
						'description' => esc_html__( 'Optional. Enter title to display above embed.', 'social-elements-wpbakery' ),
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Flickr image URL', 'social-elements-wpbakery' ),
						'param_name'  => 'url',
						'description' => esc_html__( 'Enter the URL of the Flickr image you want to embed. Example: https://www.flickr.com/photos/thomasheaton/31961411065/', 'social-elements-wpbakery' ),
						'value'       => 'https://www.flickr.com/photos/thomasheaton/31961411065/',
					],
					[
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Alignment', 'social-elements-wpbakery' ),
						'param_name' => 'align',
						'group'      => esc_html__( 'Styles', 'social-elements-wpbakery' ),
						'value'      => [
							esc_html__( 'Left', 'social-elements-wpbakery' )   => 'left',
							esc_html__( 'Center', 'social-elements-wpbakery' ) => 'center',
							esc_html__( 'Right', 'social-elements-wpbakery' )  => 'right',
						],
						'std'        => 'left',
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Width', 'social-elements-wpbakery' ),
						'param_name'  => 'width',
						'group'       => esc_html__( 'Styles', 'social-elements-wpbakery' ),
						'description' => esc_html__( 'Optional. Enter the width of the embedded image in pixels (minimum 500px).', 'social-elements-wpbakery' ),
						'value'       => '500',
					],
					vc_map_add_css_animation(),
					[
						'type'        => 'el_id',
						'heading'     => esc_html__( 'Element ID', 'social-elements-wpbakery' ),
						'param_name'  => 'el_id',
						'description' => sprintf(
							// translators: %1$s: link to w3c specification, %2$s: closing anchor tag.
							esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %1$sw3c specification%2$s).', 'social-elements-wpbakery' ),
							'<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">',
							'</a>'
						),
					],
					[
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'social-elements-wpbakery' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'social-elements-wpbakery' ),
					],
					[
						'type'       => 'css_editor',
						'heading'    => esc_html__( 'CSS', 'social-elements-wpbakery' ),
						'param_name' => 'css',
						'group'      => esc_html__( 'Design Options', 'social-elements-wpbakery' ),
					],
				],
			]
		);
	}
);
