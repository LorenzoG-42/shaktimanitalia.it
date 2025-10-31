<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_banner_box',
        'title' => esc_html__('BR Banner Box', 'autoev'),
        'icon' => 'eicon-posts-ticker',
        'categories' => array('pxltheme-core'),
        'scripts' => array(
            'elementor-waypoints',
            'jquery-numerator',
            'pxl-progressbar',
            'autoev-progressbar',
        ),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_layout',
                    'label' => esc_html__('Layout', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
                    'controls' => array(
                        array(
                            'name' => 'layout',
                            'label' => esc_html__('Templates', 'autoev' ),
                            'type' => 'layoutcontrol',
                            'default' => '1',
                            'options' => [
                                '1' => [
                                    'label' => esc_html__('Layout 1', 'autoev' ),
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_banner_box/layout1.jpg'
                                ],
                            ],
                        ),
                    ),
                ),
                
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'autoev'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'image',
                            'label' => esc_html__('Image', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                        array(
                            'name' => 'img_size',
                            'label' => esc_html__('Image Size', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'description' => 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Default: 370x300 (Width x Height)).',
                        ),
                        array(
                            'name' => 'title_heading',
                            'label' => esc_html__('Title Heading', 'autoev'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                        ),
                        
                    ),
                ),
                array(
                    'name' => 'section_parallax',
                    'label' => esc_html__('Parallax', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        [
                            'name' => 'pxl_parallax',
                            'label' => esc_html__( 'Parallax Type', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                ''        => esc_html__( 'None', 'autoev' ),
                                'x'       => esc_html__( 'Transform X', 'autoev' ),
                                'y'       => esc_html__( 'Transform Y', 'autoev' ),
                                'z'       => esc_html__( 'Transform Z', 'autoev' ),
                                'rotateX' => esc_html__( 'RotateX', 'autoev' ),
                                'rotateY' => esc_html__( 'RotateY', 'autoev' ),
                                'rotateZ' => esc_html__( 'RotateZ', 'autoev' ),
                                'scaleX'  => esc_html__( 'ScaleX', 'autoev' ),
                                'scaleY'  => esc_html__( 'ScaleY', 'autoev' ),
                                'scaleZ'  => esc_html__( 'ScaleZ', 'autoev' ),
                                'scale'   => esc_html__( 'Scale', 'autoev' ),
                            ],
                        ],
                        [
                            'name' => 'parallax_value',
                            'label' => esc_html__('Value', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '',
                            'condition' => [ 'pxl_parallax!' => '']  
                        ],
                        [
                            'name' => 'pxl_parallax_screen',
                            'label'   => esc_html__( 'Parallax In Screen', 'autoev' ),
                            'type'    => \Elementor\Controls_Manager::SELECT,
                            'control_type' => 'responsive',
                            'default' => '',
                            'options' => array(
                                '' => esc_html__( 'Default', 'autoev' ),
                                'no'   => esc_html__( 'No', 'autoev' ),
                            ),
                            'prefix_class' => 'pxl-parallax%s-',
                            'condition' => [ 'pxl_parallax!' => '']  
                        ]
                    ),
                ),
                array( 
                   'name' => 'section_style_general', 
                   'label' => esc_html__('General', 'autoev' ), 
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE, 
                    'controls' => array( 
                        array( 
                            'name' => 'style', 
                            'label' => esc_html__('Style', 'autoev' ), 
                            'type' => \Elementor\Controls_Manager::SELECT, 
                            'options' => [ 
                                'style-1' => 'Style 1', 
                                'style-2' => 'Style 2', 
                            ], 
                            'default' => 'style-1', 
                            ), 
                        ), 
                     ),
                      
                array(
                    'name' => 'section_style',
                    'label' => esc_html__('Style', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'icon_color',
                            'label' => esc_html__('Icon Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-banner-box .pxl-item-heading .pxl-icon i' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'heading_color',
                            'label' => esc_html__('Heading Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-banner-box .pxl-item-heading .pxl-title' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'heading_typography',
                            'label' => esc_html__('Heading Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-banner-box .pxl-item-heading .pxl-title',
                        ),
                        array(
                            'name' => 'bg_color_box',
                            'label' => esc_html__('Background Box', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-banner-box .pxl-item-heading , {{WRAPPER}} .pxl-banner-box .pxl-box-line' => 'background: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'pxl_animate',
                            'label' => esc_html__('Animate', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => autoev_widget_animate(),
                            'default' => '',
                        ),
                        array(
                            'name' => 'pxl_animate_delay',
                            'label' => esc_html__('Animate Delay', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '0',
                            'description' => 'Enter number. Default 0ms',
                        ),
                        array(
                            'name' => 'pxl_animate2',
                            'label' => esc_html__('Animate2', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => autoev_widget_animate(),
                            'default' => '',
                        ),
                        array(
                            'name' => 'pxl_animate_delay2',
                            'label' => esc_html__('Animate Delay2', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'default' => '0',
                            'description' => 'Enter number. Default 0ms',
                        ),
                    ),
                ),
            ),
        ),
    ),
    autoev_get_class_widget_path()
);