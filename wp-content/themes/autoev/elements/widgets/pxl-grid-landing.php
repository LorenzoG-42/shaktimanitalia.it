<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_grid_landing',
        'title' => esc_html__('BR Grid Landing', 'autoev'),
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
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_showcase/layout1.jpg'
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
                            'name' => 'style',
                            'label' => esc_html__('Style', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'condition' => [
                                'layout' => ['1'],
                            ],
                            'options' => [
                                'style-1'   => 'Style1',
                                'style-2'   => 'Style2',
                            ],
                            'default' => 'style-1',
                        ),
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
                            'name' => 'image2',
                            'label' => esc_html__('Image LV2', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                        ),
                        array(
                            'name' => 'img_size2',
                            'label' => esc_html__('Image Size LV2', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'description' => 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Default: 370x300 (Width x Height)).',
                        ),

                        array(
                            'name' => 'title',
                            'label' => esc_html__('Title', 'autoev'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                        ),
                        array(
                            'name' => 'new',
                            'label' => esc_html__('New', 'autoev'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'condition' => [
                                'layout' => ['2','3'],
                            ],
                        ),
                        array(
                            'name' => 'sub_title',
                            'label' => esc_html__('Sub Title', 'autoev'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'condition' => [
                                'layout' => ['2','3','4'],
                            ],
                        ),
                        array(
                            'name' => 'start',
                            'label' => esc_html__('Start Title', 'autoev'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,  
                            'condition' => [
                                'layout' => ['3','4'],
                            ],
                        ),
                        
                        array(
                            'name' => 'money',
                            'label' => esc_html__('Money', 'autoev'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,  
                            'condition' => [
                                'layout' => ['3','4'],
                            ],
                        ),
                        
                        array(
                            'name' => 'button_text_lv1',
                            'label' => esc_html__('Button Text LV1', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'layout' => ['1','3','4'],
                            ],
                            'default' => '',
                        ),
                        array(
                            'name' => 'button_link_lv1',
                            'label' => esc_html__('Button Link LV1', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::URL,
                        ),
                        array(
                            'name' => 'button_text_lv2',
                            'label' => esc_html__('Button Text LV2', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'condition' => [
                                'layout' => ['1'],
                            ],
                            'default' => '',
                        ),
                        array(
                            'name' => 'button_link_lv2',
                            'label' => esc_html__('Button Link LV2', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::URL,
                            'condition' => [
                                'layout' => ['1'],
                            ],
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style',
                    'label' => esc_html__('Style', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'bg_section',
                            'label' => esc_html__('Bg Item', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-showcase'  => 'background-color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'layout' => '4',
                            ],
                        ),
                        array(
                            'name' => 'bg_title',
                            'label' => esc_html__('Bg Title', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-showcase .pxl-item--meta'  => 'background-color: {{VALUE}} !important;',
                            ],
                            'condition' => [
                                'layout' => '4',
                            ],
                        ),
                        
                       
                        array(
                            'name' => 'button_typography',
                            'label' => esc_html__('Button Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} pxl-showcase .pxl-item--title',
                            'condition' => [
                                'layout' => '3',
                            ],
                        ),
                       
                        // start
                        array(
                            'name' => 'start_color',
                            'label' => esc_html__('Start Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-showcase .pxl-start' => 'color: {{VALUE}} !important;',
                            ],
                            'layout' => '3',
                        ),
                        array(
                            'name' => 'start_typography',
                            'label' => esc_html__('Start Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-showcase .pxl-start',
                            'layout' => '3',
                        ),
                        //money
                        array(
                            'name' => 'money_color',
                            'label' => esc_html__('Money Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-showcase .pxl-money' => 'color: {{VALUE}} !important;',
                            ],
                            'layout' => '3',
                        ),
                        array(
                            'name' => 'money_typography',
                            'label' => esc_html__('Money Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-showcase .pxl-money',
                            'layout' => '3',
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

                    ),
                ),
                array(
                    'name' => 'section_title',
                    'label' => esc_html__('Title', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-showcase .pxl-item--title '  => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Title Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-showcase .pxl-item--title',
                        ),
                        array(
                            'name' => 'info_title_typography',
                            'label' => esc_html__('Info Title Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-showcase .pxl-item--img .pxl-item-title',
                            'layout' => '3',
                        ),
                    //    end info
                        array(
                            'name' => 'title_typography1',
                            'label' => esc_html__('Title Typography 1', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-showcase .pxl-item--title',
                        ),
                        array(
                            'name' => 'info_title_color',
                            'label' => esc_html__('Info Title Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-showcase .pxl-item--img .pxl-item-title' => 'color: {{VALUE}} !important;',
                            ],
                            'layout' => '3',
                        ),
                    )   
                ),
                array(
                    'name' => 'section_infor',
                    'label' => esc_html__('Infor', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                         //info
                         array(
                            'name' => 'info_heading_color',
                            'label' => esc_html__('Info Heading Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-showcase .pxl-item--img .pxl-item-heading' => 'color: {{VALUE}} !important;',
                            ],
                            'layout' => '3',
                        ),
                        array(
                            'name' => 'info_heading_typography',
                            'label' => esc_html__('Info Heading Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-showcase .pxl-item--img .pxl-item-heading',
                            'layout' => '3',
                        ),

                        
                    )
                ),
                array(
                    'name' => 'section_sub',
                    'label' => esc_html__('Sub', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'sub_title_color',
                            'label' => esc_html__('Sub Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-showcase .pxl-item--sub '  => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'sub_typography',
                            'label' => esc_html__('Sub Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-showcase .pxl-item--sub',
                        ),
                    )
                ),
            ),
        ),
    ),
    autoev_get_class_widget_path()
);