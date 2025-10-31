<?php
 
add_action( 'pxl_post_metabox_register', 'autoev_page_options_register' );
function autoev_page_options_register( $metabox ) {
 
	$panels = [
		'post' => [
			'opt_name'            => 'post_option',
			'display_name'        => esc_html__( 'Post Options', 'autoev' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'post_settings' => [
					'title'  => esc_html__( 'Post Options', 'autoev' ),
					'icon'   => 'el el-refresh',
					'fields' => array_merge(
						autoev_sidebar_pos_opts(['prefix' => 'post_', 'default' => true, 'default_value' => '-1']),
						array(
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Spacing Top/Bottom', 'autoev' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							),
					    )
					)
				]
			]
		],
		'page' => [
			'opt_name'            => 'pxl_page_options',
			'display_name'        => esc_html__( 'Page Options', 'autoev' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'Header', 'autoev' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
				        autoev_header_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				           		'id'       => 'logo_m',
					            'type'     => 'media',
					            'title'    => esc_html__('Mobile Logo', 'autoev'),
					            'default'  => '',
					            'url'      => false,
					        ),
					        array(
					            'id'       => 'mobile_style',
					            'type'     => 'button_set',
					            'title'    => esc_html__('Mobile Style', 'autoev'),
					            'options'  => array(
					                'inherit'  => esc_html__('Inherit', 'autoev'),
					                'light'  => esc_html__('Light', 'autoev'),
					                'dark'  => esc_html__('Dark', 'autoev'),
					            ),
					            'default'  => 'inherit',
					        ),
					        array(
				                'id'       => 'p_menu',
				                'type'     => 'select',
				                'title'    => esc_html__( 'Menu', 'autoev' ),
				                'options'  => autoev_get_nav_menu_slug(),
				                'default' => '',
				            ),
					    ),
					    array(
				            array(
				                'id'       => 'sticky_scroll',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Sticky Scroll', 'autoev'),
				                'options'  => array(
				                    '-1' => esc_html__('Inherit', 'autoev'),
				                    'pxl-sticky-stt' => esc_html__('Scroll To Top', 'autoev'),
				                    'pxl-sticky-stb'  => esc_html__('Scroll To Bottom', 'autoev'),
				                ),
				                'default'  => '-1',
				            ),
				        )
				    )
					 
				],
				'page_title' => [
					'title'  => esc_html__( 'Page Title', 'autoev' ),
					'icon'   => 'el el-indent-left',
					'fields' => array_merge(
				        autoev_page_title_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
					        array(
					            'id' => 'custom_ptitle',
					            'type' => 'text',
					            'title' => esc_html__('Custom Page Title', 'autoev'),
					        ),
					    )
				    )
				],
				'content' => [
							'title'  => esc_html__( 'Content', 'autoev' ),
							'icon'   => 'el-icon-pencil',
							'fields' => array_merge(
								autoev_sidebar_pos_opts(['prefix' => 'page_', 'default' => false, 'default_value' => '0']),
								array(
									array(
										'id'             => 'content_spacing',
										'type'           => 'spacing',
										'output'         => array( '#pxl-wapper #pxl-main' ),
										'top'            => false,
										'bottom'         => false,
										'mode'           => 'margin',
										'units'          => array( 'px' ),
										'units_extended' => 'false',
										'title'          => esc_html__( 'Spacing top/Bottom', 'autoev' ),
										'default'        => array(
											'margin-top'    => '',
											'margin-bottom'   => '',
											'units'          => 'px',
										)
									), 
								)
							)
						],

				'footer' => [
					'title'  => esc_html__( 'Footer', 'autoev' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        autoev_footer_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
				                'id'       => 'p_footer_fixed',
				                'type'     => 'button_set',
				                'title'    => esc_html__('Footer Fixed', 'autoev'),
				                'options'  => array(
				                    'inherit' => esc_html__('Inherit', 'autoev'),
				                    'on' => esc_html__('On', 'autoev'),
				                    'off' => esc_html__('Off', 'autoev'),
				                ),
				                'default'  => 'inherit',
				            ),
						)
				    )
				],
				'colors' => [
					'title'  => esc_html__( 'Colors', 'autoev' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        array(
				        	array(
					            'id'          => 'body_bg_color',
					            'type'        => 'color',
					            'title'       => esc_html__('Body Background Color', 'autoev'),
					            'transparent' => false,
					            'default'     => ''
					        ),
				        	array(
					            'id'          => 'primary_color',
					            'type'        => 'color',
					            'title'       => esc_html__('Primary Color', 'autoev'),
					            'transparent' => false,
					            'default'     => ''
					        ),
					        array(
					            'id'          => 'secondary_color',
					            'type'        => 'color',
					            'title'       => esc_html__('Secondary Color', 'autoev'),
					            'transparent' => false,
					            'default'     => ''
					        ),
					        array(
					            'id'          => 'gradient_color',
					            'type'        => 'color_gradient',
					            'title'       => esc_html__('Gradient Color', 'autoev'),
					            'transparent' => false,
					            'default'  => array(
					                'from' => '',
					                'to'   => '', 
					            ),
					        ),
					    )
				    )
				],
				'extra' => [
					'title'  => esc_html__( 'Extra', 'autoev' ),
					'icon'   => 'el el-website',
					'fields' => array_merge(
				        array(
				        	array(
					            'id' => 'body_custom_class',
					            'type' => 'text',
					            'title' => esc_html__('Body Custom Class', 'autoev'),
					        ),
					    )
				    )
				]
			]
		],
		'portfolio' => [
			'opt_name'            => 'pxl_portfolio_options',
			'display_name'        => esc_html__( 'Portfolio Options', 'autoev' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'autoev' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
						array(
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Content Spacing Top/Bottom', 'autoev' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							),
						)
				    )
				],
			]
		],
		'service' => [
			'opt_name'            => 'pxl_service_options',
			'display_name'        => esc_html__( 'Service Options', 'autoev' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'autoev' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
						autoev_header_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
							array(
					            'id'=> 'service_external_link',
					            'type' => 'text',
					            'title' => esc_html__('External Link', 'autoev'),
					            'validate' => 'url',
					            'default' => '',
					        ),
							array(
					            'id'=> 'service_excerpt',
					            'type' => 'textarea',
					            'title' => esc_html__('Excerpt', 'autoev'),
					            'validate' => 'html_custom',
					            'default' => '',
					        ),
					        array(
					            'id'       => 'service_icon_type',
					            'type'     => 'button_set',
					            'title'    => esc_html__('Icon Type', 'autoev'),
					            'options'  => array(
					                'icon'  => esc_html__('Icon', 'autoev'),
					                'image'  => esc_html__('Image', 'autoev'),
					            ),
					            'default'  => 'icon'
					        ),
					        array(
					            'id'       => 'service_icon_font',
					            'type'     => 'pxl_iconpicker',
					            'title'    => esc_html__('Icon', 'autoev'),
					            'required' => array( 0 => 'service_icon_type', 1 => 'equals', 2 => 'icon' ),
            					'force_output' => true
					        ),
					        array(
					            'id'       => 'service_icon_img',
					            'type'     => 'media',
					            'title'    => esc_html__('Icon Image', 'autoev'),
					            'default' => '',
					            'required' => array( 0 => 'service_icon_type', 1 => 'equals', 2 => 'image' ),
				            	'force_output' => true
					        ),
					        array(
								'id'             => 'content_spacing',
								'type'           => 'spacing',
								'output'         => array( '#pxl-wapper #pxl-main' ),
								'right'          => false,
								'left'           => false,
								'mode'           => 'padding',
								'units'          => array( 'px' ),
								'units_extended' => 'false',
								'title'          => esc_html__( 'Content Spacing Top/Bottom', 'autoev' ),
								'default'        => array(
									'padding-top'    => '',
									'padding-bottom' => '',
									'units'          => 'px',
								)
							),
							array(
					            'id'=> 'custom_service_number',
					            'type' => 'text',
					            'title' => esc_html__('Number', 'autoev'),
					            'validate' => 'html_custom',
					            'default' => '',
					        ),
						)
				    )
				],
				'page_title_service' => [
					'title'  => esc_html__( 'Page Title Service', 'autoev' ),
					'icon'   => 'el el-indent-left',
					'fields' => array_merge(
				        autoev_page_title_opts([
							'default'         => true,
							'default_value'   => '-1'
						]),
						array(
					        array(
					            'id' => 'custom_ptitle',
					            'type' => 'text',
					            'title' => esc_html__('Custom Page Title Service', 'autoev'),
					        ),
					    )
				    )
				],
			]
		],
		'product' => [
			'opt_name'            => 'pxl_product_options',
			'display_name'        => esc_html__( 'Product Options', 'autoev' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'autoev' ),
					'icon'   => 'el-icon-website',
					'fields' => array_merge(
						array(
							array(
					            'id'=> 'product_label',
					            'type' => 'text',
					            'title' => esc_html__('Label', 'autoev'),
					            'default' => '',
					        ),
					        array(
					            'id'=> 'product_text_btn',
					            'type' => 'text',
					            'title' => esc_html__('Text Button Video', 'autoev'),
					            'default' => '',
					        ),
					        array(
								'id'           => 'link_video',
								'type'         => 'text',
								'title'        => esc_html__( 'Link Video', 'autoev' ),
								'default'		=> '',
								'force_output' => true
							),
						)
				    )
				],
			]
		],
		'pxl-template' => [
			'opt_name'            => 'pxl_hidden_template_options',
			'display_name'        => esc_html__( 'Template Options', 'autoev' ),
			'show_options_object' => false,
			'context'  => 'advanced',
			'priority' => 'default',
			'sections'  => [
				'header' => [
					'title'  => esc_html__( 'General', 'autoev' ),
					'icon'   => 'el-icon-website',
					'fields' => array(
						array(
							'id'    => 'template_type',
							'type'  => 'select',
							'title' => esc_html__('Type', 'autoev'),
				            'options' => [
				            	'df'       	   => esc_html__('Select Type', 'autoev'), 
								'header'       => esc_html__('Header', 'autoev'), 
								'footer'       => esc_html__('Footer', 'autoev'), 
								'mega-menu'    => esc_html__('Mega Menu', 'autoev'), 
								'page-title'   => esc_html__('Page Title', 'autoev'), 
								'tab' => esc_html__('Tab', 'autoev'),
								'wgaboutauthor' => esc_html__('Widget Sidebar', 'autoev'),
								'hidden-panel' => esc_html__('Hidden Panel', 'autoev'),
								'popup' => esc_html__('Popup', 'autoev'),
								'slider' => esc_html__('Slider', 'autoev'),
				            ],
				            'default' => 'df',
				        ),
				        array(
							'id'    => 'header_type',
							'type'  => 'select',
							'title' => esc_html__('Header Type', 'autoev'),
				            'options' => [
				            	'px-header--default'       	   => esc_html__('Default', 'autoev'), 
								'px-header--transparent'       => esc_html__('Transparent', 'autoev'),
				            ],
				            'default' => 'px-header--default',
				            'indent' => true,
                			'required' => array( 0 => 'template_type', 1 => 'equals', 2 => 'header' ),
				        ),
					),
				    
				],
			]
		],
	];
 
	$metabox->add_meta_data( $panels );
}
 