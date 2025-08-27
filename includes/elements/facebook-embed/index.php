<?php

defined( 'ABSPATH' ) || exit;

add_action( 'vc_before_init', function () {
	require_once __DIR__ . '/class-sefwpb-element-facebook-embed.php';

	vc_map(
		[
			'name'              => esc_html__( 'Facebook Embed', SEFWPB_TD ),
			'base'              => 'sefwpb_facebook_embed',
			'description'       => esc_html__( 'Embed a single Facebook post', SEFWPB_TD ),
			'category'          => esc_html__( 'Social', SEFWPB_TD ),
			'icon'              => SEFWPB_ASSETS_URI . '/images/icons/icon-facebook-embed.svg',
			'params'            => [
				[
					'type' => 'textfield',
					'heading' => esc_html__('Title', SEFWPB_TD),
					'param_name' => 'title',
					'description' => esc_html__('Optional. Enter title to display above embed.', SEFWPB_TD),
				],
				[
					'type' => 'textfield',
					'heading' => esc_html__('Facebook Post URL', SEFWPB_TD),
					'param_name' => 'url',
					'description' => esc_html__('Enter the URL of the Facebook post you want to embed. Example: https://www.facebook.com/100064860875397/posts/10154009990506729/', SEFWPB_TD),
					'value' => 'https://www.facebook.com/natgeo/posts/pfbid0YKtpC5jfd6EksBDfCFBwAAdhUUWJkY4QUjHWSpyPEesiACiSTFptW2tdFvmqCN3rl',
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
				[
					'type'        => 'textfield',
					'heading'     => esc_html__( 'Width', SEFWPB_TD ),
					'param_name'  => 'width',
					'group' => esc_html__( 'Styles', SEFWPB_TD ),
					'description' => esc_html__( 'Optional. Enter width of the embedded post in pixels. Default is 500px.', SEFWPB_TD ),
					'value'       => '500',
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
