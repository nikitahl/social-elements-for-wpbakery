<?php
/**
 * Register the Profile Links element for WPBakery.
 *
 * @package SocialElementsWPBakery
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'vc_before_init',
	function () {
		require_once __DIR__ . '/class-sefwpb-element-profile-links.php';

		vc_map(
			[
				'name'        => esc_html__( 'Social Profile Links', 'social-elements-wpbakery' ),
				'base'        => 'sefwpb_profile_links',
				'description' => esc_html__( 'Buttons linking to your social media profiles', 'social-elements-wpbakery' ),
				'category'    => esc_html__( 'Social', 'social-elements-wpbakery' ),
				'icon'        => SEFWPB_ASSETS_URI . '/images/icons/icon-profile-links.svg',
				'params'      => [
					[
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'social-elements-wpbakery' ),
						'value'       => esc_html__( 'Follow us on:', 'social-elements-wpbakery' ),
						'param_name'  => 'profile_title',
						'description' => esc_html__( 'Optional. Used before the buttons to incidate that these are profile links', 'social-elements-wpbakery' ),
					],
					[
						'type'        => 'param_group',
						'heading'     => esc_html__( 'Profile links', 'social-elements-wpbakery' ),
						'param_name'  => 'values',
						'description' => esc_html__( 'Enter values for links - title, icon, color, URL.', 'social-elements-wpbakery' ),
						'value'       => rawurlencode(
							wp_json_encode( [
								[
									'label'            => esc_html__( 'Facebook', 'social-elements-wpbakery' ),
									'color'            => '#0866FF',
									'icon_type'        => 'fontawesome',
									'icon_fontawesome' => 'fa fa-brands fa-facebook-f',
								],
								[
									'label'            => esc_html__( 'X', 'social-elements-wpbakery' ),
									'color'            => '#000000',
									'icon_type'        => 'fontawesome',
									'icon_fontawesome' => 'fa fa-brands fa-x-twitter',
								],
								[
									'label'            => esc_html__( 'YouTube', 'social-elements-wpbakery' ),
									'color'            => '#FF0000',
									'icon_type'        => 'fontawesome',
									'icon_fontawesome' => 'fa fa-brands fa-youtube',
								],
								[
									'label'            => esc_html__( 'Instagram', 'social-elements-wpbakery' ),
									'color'            => '#E1306C',
									'icon_type'        => 'fontawesome',
									'icon_fontawesome' => 'fa fa-brands fa-instagram',
								],
								[
									'label'            => esc_html__( 'TikTok', 'social-elements-wpbakery' ),
									'color'            => '#000000',
									'icon_type'        => 'fontawesome',
									'icon_fontawesome' => 'fa fa-brands fa-tiktok',
								],
							])
						),
						'params'      => [
							[
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Label', 'social-elements-wpbakery' ),
								'admin_label' => true,
								'param_name'  => 'label',
								'description' => esc_html__( 'Enter text used as title of icon.', 'social-elements-wpbakery' ),
							],
							[
								'type'        => 'textfield',
								'heading'     => esc_html__( 'URL', 'social-elements-wpbakery' ),
								'param_name'  => 'url',
								'description' => esc_html__( 'Enter full URL to your profile, starting with http:// or https://', 'social-elements-wpbakery' ),
								'value'       => 'https://',
							],
							[
								'type'        => 'colorpicker',
								'heading'     => esc_html__( 'Color', 'social-elements-wpbakery' ),
								'param_name'  => 'color',
								'description' => esc_html__( 'Select icon color.', 'social-elements-wpbakery' ),
							],
							[
								'type'        => 'dropdown',
								'heading'     => esc_html__( 'Icon library', 'social-elements-wpbakery' ),
								'value'       => [
									esc_html__( 'Font Awesome', 'social-elements-wpbakery' ) => 'fontawesome',
									esc_html__( 'Mono Social', 'social-elements-wpbakery' ) => 'monosocial',
								],
								'std'         => 'fontawesome',
								'param_name'  => 'icon_type',
								'description' => esc_html__( 'Select icon library.', 'social-elements-wpbakery' ),
							],
							[
								'type'        => 'iconpicker',
								'heading'     => esc_html__( 'Icon', 'social-elements-wpbakery' ),
								'param_name'  => 'icon_fontawesome',
								'value'       => 'fas fa-adjust',
								'settings'    => [
									'emptyIcon'    => false,
									'type'         => 'fontawesome',
									'iconsPerPage' => 500,
								],
								'dependency'  => [
									'element' => 'icon_type',
									'value'   => 'fontawesome',
								],
								'description' => esc_html__( 'Select icon from library.', 'social-elements-wpbakery' ),
							],
							[
								'type'        => 'iconpicker',
								'heading'     => esc_html__( 'Icon', 'social-elements-wpbakery' ),
								'param_name'  => 'icon_monosocial',
								'value'       => 'vc-mono vc-mono-fivehundredpx',
								'settings'    => [
									'emptyIcon'    => false,
									'type'         => 'monosocial',
									'iconsPerPage' => 4000,
								],
								'dependency'  => [
									'element' => 'icon_type',
									'value'   => 'monosocial',
								],
								'description' => esc_html__( 'Select icon from library.', 'social-elements-wpbakery' ),
							],
						],
					],
					[
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'social-elements-wpbakery' ),
						'group'      => esc_html__( 'Styles', 'social-elements-wpbakery' ),
						'param_name' => 'style',
						'value'      => [
							esc_html__( 'Solid (brand color)', 'social-elements-wpbakery' ) => 'solid',
							esc_html__( 'Outline', 'social-elements-wpbakery' ) => 'outline',
							esc_html__( 'Minimal (no shape, icon only)', 'social-elements-wpbakery' ) => 'minimal',
						],
						'std'        => 'solid',
					],
					[
						'type'        => 'colorpicker',
						'heading'     => esc_html__( 'Text color', 'social-elements-wpbakery' ),
						'group'       => esc_html__( 'Styles', 'social-elements-wpbakery' ),
						'param_name'  => 'text_color',
						'description' => esc_html__( 'Optional. Set custom icon color in.', 'social-elements-wpbakery' ),
						'value'       => '#0a0b0e',
						'dependency'  => [
							'element' => 'style',
							'value'   => [ 'minimal' ],
						],
					],
					[
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Size', 'social-elements-wpbakery' ),
						'group'      => esc_html__( 'Styles', 'social-elements-wpbakery' ),
						'param_name' => 'size',
						'value'      => [
							esc_html__( 'Small', 'social-elements-wpbakery' ) => 'sm',
							esc_html__( 'Medium', 'social-elements-wpbakery' ) => 'md',
							esc_html__( 'Large', 'social-elements-wpbakery' ) => 'lg',
						],
						'std'        => 'md',
					],
					[
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Button shape', 'social-elements-wpbakery' ),
						'group'      => esc_html__( 'Styles', 'social-elements-wpbakery' ),
						'param_name' => 'shape',
						'value'      => [
							esc_html__( 'Square', 'social-elements-wpbakery' ) => '0px',
							esc_html__( 'Rounded', 'social-elements-wpbakery' ) => '5px',
							esc_html__( 'Circle', 'social-elements-wpbakery' ) => '50px',
						],
						'std'        => 'rounded',
					],
					[
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Alignment', 'social-elements-wpbakery' ),
						'param_name' => 'align',
						'group'      => esc_html__( 'Styles', 'social-elements-wpbakery' ),
						'value'      => [
							esc_html__( 'Left', 'social-elements-wpbakery' ) => 'left',
							esc_html__( 'Center', 'social-elements-wpbakery' ) => 'center',
							esc_html__( 'Right', 'social-elements-wpbakery' ) => 'right',
						],
						'std'        => 'left',
					],
					[
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Icon gap', 'social-elements-wpbakery' ),
						'param_name' => 'gap',
						'group'      => esc_html__( 'Styles', 'social-elements-wpbakery' ),
						'value'      => '5px',
					],
					vc_map_add_css_animation(),
					[
						'type'        => 'el_id',
						'heading'     => esc_html__( 'Element ID', 'social-elements-wpbakery' ),
						'param_name'  => 'el_id',
						// translators: %1$s: link to w3c specification, %2$s: closing anchor tag.
						'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %1$sw3c specification%2$s).', 'social-elements-wpbakery' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
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
