<?php

defined( 'ABSPATH' ) || exit;

add_action( 'vc_before_init', function () {
	require_once __DIR__ . '/class-sefwpb-element-pinterest-embed.php';

	vc_map(
		[
			'name'              => esc_html__( 'Pinterest Embed', SEFWPB_TD ),
			'base'              => 'sefwpb_pinterest_embed',
			'description'       => esc_html__( 'Embed a single Pinterest post', SEFWPB_TD ),
			'category'          => esc_html__( 'Social', SEFWPB_TD ),
			'icon'              => SEFWPB_ASSETS_URI . '/images/icons/icon-pinterest-embed.svg',
			'params'            => [
				[
					'type' => 'textfield',
					'heading' => esc_html__('Title', SEFWPB_TD),
					'param_name' => 'title',
					'description' => esc_html__('Optional. Enter title to display above embed.', SEFWPB_TD),
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__('Pinterest Post URL', SEFWPB_TD),
					'param_name' => 'url',
					'description' => esc_html__('Enter the URL of the Pinterest post you want to embed. Example: https://www.pinterest.com/pin/3940718420141988/', SEFWPB_TD),
					'value' => 'https://www.pinterest.com/pin/3940718420141988/',
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Alignment', SEFWPB_TD ),
					'param_name' => 'align',
					'group' => esc_html__( 'Styles', SEFWPB_TD ),
					'value' => [
						esc_html__( 'Left', SEFWPB_TD ) => 'left',
						esc_html__( 'Center', SEFWPB_TD ) => 'center',
						esc_html__( 'Right', SEFWPB_TD ) => 'right',
					],
					'std' => 'left',
				],
				vc_map_add_css_animation(),
				[
					'type'        => 'el_id',
					'heading'     => esc_html__( 'Element ID', SEFWPB_TD ),
					'param_name'  => 'el_id',
					// translators: %1$s: link to w3c specification, %2$s: closing anchor tag.
					'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %1$sw3c specification%2$s).', SEFWPB_TD ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
				],
				[
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Extra class name', SEFWPB_TD ),
					'param_name'  => 'el_class',
					'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', SEFWPB_TD ),
				],
				[
					'type'       => 'css_editor',
					'heading'    => esc_html__( 'CSS', SEFWPB_TD ),
					'param_name' => 'css',
					'group'      => esc_html__( 'Design Options', SEFWPB_TD ),
				],
			]
		]
	);
} );
