<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_infor_girl',
        'title' => esc_html__('Pxl Infor Grid', 'autoev'),
        'icon' => 'eicon-gallery-grid',
        'categories' => array('pxltheme-core'),
        'scripts' => [
            'imagesloaded',
            'isotope',
            'pxl-post-grid',
        ],
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_layout',
                    'label' => esc_html__('Layout', 'autoev'),
                    'tab' => \Elementor\Controls_Manager::TAB_LAYOUT,
                    'controls' => array(
                        array(
                            'name' => 'layout',
                            'label' => esc_html__('Templates', 'autoev'),
                            'type' => 'layoutcontrol',
                            'default' => '1',
                            'options' => [
                                '1' => [
                                    'label' => esc_html__('Layout 1', 'autoev'),
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_infor_grid/layout1.jpg'
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
                            'name' => 'image_list',
                            'label' => esc_html__('Image', 'autoev'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'default' => [],
                            'controls' => array(
                                
                                array(
                                    'name' => 'image',
                                    'label' => esc_html__('Image', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::MEDIA,
                                ),
                                array(
                                    'name' => 'time',
                                    'label' => esc_html__('Time', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                
                                array(
                                    'name' => 'des',
                                    'label' => esc_html__('Description', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),

                                array(
                                    'name' => 'contact',
                                    'label' => esc_html__('Mail', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),

                                array(
                                    'name' => 'phone',
                                    'label' => esc_html__('Phone', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'btn_link',
                                    'label' => esc_html__('Button Link', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'link',
                                    'label' => esc_html__('Link', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::URL,
                                    'label_block' => true,
                                    'description' => 'Input link to your page.',
                                ),
                                
                            ),
                            'title_field' => '{{{ title }}}',
                        ),
                        
                        array(
                            'name' => 'show_arrow',
                            'label' => esc_html__('Show Arrow Image', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'false',
                        ),
                        array(
                            'name' => 'pxl_icon',
                            'label' => esc_html__('Arrow Icon', 'autoev'),
                            'type' => \Elementor\Controls_Manager::ICONS,
                            'fa4compatibility' => 'icon',
                            'condition' => [
                                'show_arrow' => 'true',
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_settings',
                    'label' => esc_html__('Grid', 'autoev'),
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                    'controls' => array(
                        array(
                            'name' => 'pxl_animate',
                            'label' => esc_html__('Case Animate', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => autoev_widget_animate(),
                            'default' => '',
                        ),
                        array(
                            'name' => 'col_xs',
                            'label' => esc_html__('Columns XS Devices', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '1',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                        ),
                        array(
                            'name' => 'col_sm',
                            'label' => esc_html__('Columns SM Devices', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '2',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                        ),
                        array(
                            'name' => 'col_md',
                            'label' => esc_html__('Columns MD Devices', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '2',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                        ),
                        array(
                            'name' => 'col_lg',
                            'label' => esc_html__('Columns LG Devices', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '6' => '6',
                            ],
                        ),
                        array(
                            'name' => 'col_xl',
                            'label' => esc_html__('Columns XL Devices', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => '3',
                            'options' => [
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                            ],
                        ),
                        
                    ),
                ),
                array(
                    'name' => 'section_style',
                    'label' => esc_html__('Style', 'autoev'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        // color gradiend
                        array(
                            'name'  => 'style_bg_btn_color',
                            'label' => esc_html__('Style Background Color', 'autoev'),
                            'type'  => \Elementor\Controls_Manager::SELECT,
                            'options'   => [
                                'color_normal'  => 'Normal',
                                'gradient_color' => 'Gradient',
                            ],
                            'default'   => 'color_normal',
                            
                        ),
                        array(
                            'name'         => 'style_gradient',
                            'label' => esc_html__('Background Type', 'autoev'),
                            'type'         => \Elementor\Group_Control_Background::get_type(),
                            'control_type' => 'group',
                            'types' => ['gradient'],
                            'selector'     => '{{WRAPPER}} .pxl-infor-girl .pxl-box .pxl-gradiend',
                            'condition' => [
                                'style_bg_btn_color' => ['gradient_color'],
                            ],
                        ),

                        array(
                            'name' => 'bg_nm_color',
                            'label' => esc_html__('Style Bg Color', 'autoev'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-infor-girl .pxl-box .pxl-gradiend',
                            ],
                            'condition' => [
                                'style_bg_btn_color' => ['color_normal'],
                            ],
                        ),
                        array(
                            'name' => 'border_type',
                            'label' => esc_html__( 'Border Type', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '' => esc_html__( 'None', 'autoev' ),
                                'solid' => esc_html__( 'Solid', 'autoev' ),
                                'double' => esc_html__( 'Double', 'autoev' ),
                                'dotted' => esc_html__( 'Dotted', 'autoev' ),
                                'dashed' => esc_html__( 'Dashed', 'autoev' ),
                                'groove' => esc_html__( 'Groove', 'autoev' ),
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-infor-girl .pxl-gradiend' => 'border-style: {{VALUE}} !important;',
                            ],
                        ),
                        // end border gradiend
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'autoev'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-infor-girl .pxl-item--title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Title Typography', 'autoev'),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-infor-girl .pxl-item--title',
                        ),  
                        
                        array(
                            'name' => 'list_item_color',
                            'label' => esc_html__('List Item Color', 'autoev'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-infor-girl .pxl-wrap-content .pxl-item' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'list_item_typography',
                            'label' => esc_html__('List Item Typography', 'autoev'),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-infor-girl .pxl-wrap-content .pxl-item',
                        ),
                        array(
                            'name' => 'btn_link_color',
                            'label' => esc_html__('Button Color', 'autoev'),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-infor-girl .pxl-wrap-content .pxl-button--link' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'btn_typography',
                            'label' => esc_html__('button Typography', 'autoev'),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-infor-girl .pxl-wrap-content .pxl-button--link',
                        ),
                    
                        array(
                            'name' => 'img_effect',
                            'label' => esc_html__('Image Effect', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                '' => 'None',
                                'pxl-image-effect1' => 'Zigzag',
                                'pxl-image-tilt' => 'Tilt',
                                'pxl-image-spin-normal' => 'Spin',
                                'pxl-image-spin' => 'Spin Reverse',
                                'pxl-image-zoom' => 'Zoom',
                                'pxl-image-bounce' => 'Bounce',
                                'slide-up-down' => 'Slide Up Down',
                                'slide-top-to-bottom' => 'Slide Top To Bottom ',
                                'pxl-image-effect2' => 'Slide Bottom To Top ',
                                'slide-right-to-left' => 'Slide Right To Left ',
                                'slide-left-to-right' => 'Slide Left To Right ',
                                'pxl-hover1' => 'ZoomIn',
                                'pxl-hover2' => 'ZoomOut',
                                'pxl-animation-round' => 'Round',
                                'pxl-image-parallax' => 'Parallax',
                            ],
                            'default' => '',
                            
                        ),
                        array(
                            'name' => 'parallax_value',
                            'label' => esc_html__('Parallax Value', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'img_effect' => 'pxl-image-parallax',
                            ],
                            'default' => '40',
                            'description' => esc_html__('Enter number.', 'autoev' ),
                        ),

                    ),
                ),
            ),
        ),
    ),
    autoev_get_class_widget_path()
);
