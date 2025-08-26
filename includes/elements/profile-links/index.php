<?php

defined( 'ABSPATH' ) || exit;

add_action( 'vc_before_init', function () {
	require_once __DIR__ . '/class-sefwpb-element-profile-links.php';

	vc_map(
		[
			'name'        => esc_html__( 'Social Profile Links', SEFWPB_TD ),
			'base'        => 'sefwpb_profile_links',
			'description' => esc_html__( 'Buttons linking to your social media profiles', SEFWPB_TD ),
			'category'    => esc_html__( 'Social', SEFWPB_TD ),
			'icon'        => 'sefwpb-icon-profile-links',
			'front_enqueue_css'       => preg_replace( '/\s/', '%20', plugins_url( 'assets/css/profile-links.css', __FILE__ ) ),
			'params'      => [
				[
					'type' => 'textfield',
					'heading' => esc_html__('Title', SEFWPB_TD),
					'value' => esc_html__('Follow us on:', SEFWPB_TD),
					'param_name' => 'profile_title',
					'description' => esc_html__('Optional. Used before the buttons to incidate that these are profile links', SEFWPB_TD),
				],
				[
					'type' => 'param_group',
					'heading' => esc_html__( 'Profile links', SEFWPB_TD ),
					'param_name' => 'values',
					'description' => esc_html__( 'Enter values for links - title, icon, color, URL.', SEFWPB_TD ),
					'value' => rawurlencode( wp_json_encode( [
						[
							'label' => esc_html__( 'Facebook', SEFWPB_TD ),
							'color' => '#0866FF',
							'icon_type' => 'fontawesome',
							'icon_fontawesome' => 'fa fa-brands fa-facebook-f',
						],
						[
							'label' => esc_html__( 'X', SEFWPB_TD ),
							'color' => '#000000',
							'icon_type' => 'fontawesome',
							'icon_fontawesome' => 'fa fa-brands fa-x-twitter',
						],
						[
							'label' => esc_html__( 'YouTube', SEFWPB_TD ),
							'color' => '#FF0000',
							'icon_type' => 'fontawesome',
							'icon_fontawesome' => 'fa fa-brands fa-youtube',
						],
						[
							'label' => esc_html__( 'Instagram', SEFWPB_TD ),
							'color' => '#E1306C',
							'icon_type' => 'fontawesome',
							'icon_fontawesome' => 'fa fa-brands fa-instagram',
						],
						[
							'label' => esc_html__( 'TikTok', SEFWPB_TD ),
							'color' => '#000000',
							'icon_type' => 'fontawesome',
							'icon_fontawesome' => 'fa fa-brands fa-tiktok',
						],
					] ) ),
					'params' => [
						[
							'type' => 'textfield',
							'heading' => esc_html__( 'Label', SEFWPB_TD ),
							'admin_label' => true,
							'param_name' => 'label',
							'description' => esc_html__( 'Enter text used as title of icon.', SEFWPB_TD ),
						],
						[
							'type' => 'textfield',
							'heading' => esc_html__( 'URL', SEFWPB_TD ),
							'param_name' => 'url',
							'description' => esc_html__( 'Enter full URL to your profile, starting with http:// or https://', SEFWPB_TD ),
							'value' => 'https://',
						],
						[
							'type' => 'colorpicker',
							'heading' => esc_html__( 'Color', SEFWPB_TD ),
							'param_name' => 'color',
							'description' => esc_html__( 'Select icon color.', SEFWPB_TD ),
						],
						[
							'type' => 'dropdown',
							'heading' => esc_html__( 'Icon library', SEFWPB_TD ),
							'value' => [
								esc_html__( 'Font Awesome', SEFWPB_TD ) => 'fontawesome',
								esc_html__( 'Mono Social', SEFWPB_TD ) => 'monosocial',
							],
							'std' => 'fontawesome',
							'param_name' => 'icon_type',
							'description' => esc_html__( 'Select icon library.', SEFWPB_TD ),
						],
						[
							'type' => 'iconpicker',
							'heading' => esc_html__( 'Icon', SEFWPB_TD ),
							'param_name' => 'icon_fontawesome',
							'value' => 'fas fa-adjust',
							'settings' => [
								'emptyIcon' => false,
								'type' => 'fontawesome',
								'iconsPerPage' => 500,
							],
							'dependency' => [
								'element' => 'icon_type',
								'value' => 'fontawesome',
							],
							'description' => esc_html__( 'Select icon from library.', SEFWPB_TD ),
						],
						[
							'type' => 'iconpicker',
							'heading' => esc_html__( 'Icon', SEFWPB_TD ),
							'param_name' => 'icon_monosocial',
							'value' => 'vc-mono vc-mono-fivehundredpx',
							'settings' => [
								'emptyIcon' => false,
								'type' => 'monosocial',
								'iconsPerPage' => 4000,
							],
							'dependency' => [
								'element' => 'icon_type',
								'value' => 'monosocial',
							],
							'description' => esc_html__( 'Select icon from library.', SEFWPB_TD ),
						],
					],
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Style', SEFWPB_TD ),
					'group' => esc_html__( 'Styles', SEFWPB_TD ),
					'param_name' => 'style',
					'value' => [
						esc_html__( 'Solid (brand color)', SEFWPB_TD ) => 'solid',
						esc_html__( 'Outline', SEFWPB_TD ) => 'outline',
						esc_html__( 'Minimal (no shape, icon only)', SEFWPB_TD ) => 'minimal',
					],
					'std' => 'solid',
				],
				[
					'type' => 'colorpicker',
					'heading' => esc_html__( 'Text color', SEFWPB_TD ),
					'group' => esc_html__( 'Styles', SEFWPB_TD ),
					'param_name' => 'text_color',
					'description' => esc_html__( 'Optional. Set custom icon color in.', SEFWPB_TD ),
					'value' => '#0a0b0e',
					'dependency' => [
						'element' => 'style',
						'value' => [ 'minimal' ],
					],
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Size', SEFWPB_TD ),
					'group' => esc_html__( 'Styles', SEFWPB_TD ),
					'param_name' => 'size',
					'value' => [
						esc_html__( 'Small', SEFWPB_TD ) => 'sm',
						esc_html__( 'Medium', SEFWPB_TD ) => 'md',
						esc_html__( 'Large', SEFWPB_TD ) => 'lg',
					],
					'std' => 'md',
				],
				[
					'type' => 'dropdown',
					'heading' => esc_html__( 'Button shape', SEFWPB_TD ),
					'group' => esc_html__( 'Styles', SEFWPB_TD ),
					'param_name' => 'shape',
					'value' => [
						esc_html__( 'Square', SEFWPB_TD ) => '0px',
						esc_html__( 'Rounded', SEFWPB_TD ) => '5px',
						esc_html__( 'Circle', SEFWPB_TD ) => '50px',
					],
					'std' => 'rounded',
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
					'type' => 'textfield',
					'heading' => esc_html__( 'Icon gap', SEFWPB_TD ),
					'param_name' => 'gap',
					'group' => esc_html__( 'Styles', SEFWPB_TD ),
					'value' => '5px',
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
