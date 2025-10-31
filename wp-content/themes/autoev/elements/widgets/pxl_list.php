<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_list',
        'title' => esc_html__('BR List', 'autoev'),
        'icon' => 'eicon-editor-list-ul',
        'categories' => array('pxltheme-core'),
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
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_list/layout1.jpg'
                                ],
                                '2' => [
                                    'label' => esc_html__('Layout 2', 'autoev' ),
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_list/layout2.jpg'
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
                            'name' => 'title_heading',
                            'label' => esc_html__('Heading', 'autoev'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                        ),
                        array(
                            'name' => 'lists',
                            'label' => esc_html__('Content', 'autoev'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'heading',
                                    'label' => esc_html__('Heading', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'text',
                                    'label' => esc_html__('Text', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'link',
                                    'label' => esc_html__('Link', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::URL,
                                    'label_block' => true,
                                    
                                ),
                            ),
                            'title_field' => '{{{ text }}}',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_general',
                    'label' => esc_html__('General', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'heading_space',
                            'label' => esc_html__('Heading Space', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list .pxl-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'after',
                        ),
                        array(
                            'name' => 'item_space',
                            'label' => esc_html__('Item Space', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 300,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list .pxl--item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'after',
                        ),
                        
                        array(
                            'name' => 'align_items',
                            'label' => esc_html__('Align Items', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'options' => [
                                'flex-start' => [
                                    'title' => esc_html__( 'Flex Start', 'autoev' ),
                                    'icon' => 'far fa-arrow-alt-to-top',
                                ],
                                'Center' => [
                                    'title' => esc_html__( 'Center', 'autoev' ),
                                    'icon' => 'far fa-arrows-alt-v',
                                ],
                                'flex-end' => [
                                    'title' => esc_html__( 'Flex End', 'autoev' ),
                                    'icon' => 'far fa-arrow-alt-to-bottom',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list .pxl--item' => 'align-items: {{VALUE}};',
                            ],
                        ),
                        array(
                          'name' => 'align',
                            'label' => esc_html__( 'Alignment', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::CHOOSE,
                            'control_type' => 'responsive',
                            'options' => [
                                'left' => [
                                    'title' => esc_html__( 'Left', 'autoev' ),
                                    'icon' => 'eicon-text-align-left',
                                ],
                                'center' => [
                                    'title' => esc_html__( 'Center', 'autoev' ),
                                    'icon' => 'eicon-text-align-center',
                                ],
                                'right' => [
                                    'title' => esc_html__( 'Right', 'autoev' ),
                                    'icon' => 'eicon-text-align-right',
                                ],
                                'justify' => [
                                    'title' => esc_html__( 'Justified', 'autoev' ),
                                    'icon' => 'eicon-text-align-justify',
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list .pxl--item' => 'text-align: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-default' => 'Default',
                                'style-lv2' => 'Style2',
                            ],
                            'default' => 'style-default',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_text',
                    'label' => esc_html__('Text', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'heading_typography',
                            'label' => esc_html__('Heading Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-list .pxl-heading',
                            'condition' => [
			                    'layout' => '2',
		                        ],
                        ),
                        array(
                            'name' => 'heading_color',
                            'label' => esc_html__('Heading Color ', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list .pxl-heading' => 'color: {{VALUE}};',
                            ],
                                'condition' => [
			                    'layout' => '2',
		                        ],
                        ),
                        array(
                            'name' => 'fist_color',
                            'label' => esc_html__('Text First Color ', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list .pxl--item .pxl-desc .pxl-item-heading' => 'color: {{VALUE}};',
                            ],
                                'condition' => [
			                    'layout' => '2',
		                        ],
                        ),
                        array(
                            'name' => 'text_color',
                            'label' => esc_html__('Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list .pxl-item--text a , {{WRAPPER}} .pxl-list .pxl--item .pxl-desc .pxl-item-title' => 'color: {{VALUE}};',
                            ],
                        ),
                        
                        array(
                            'name' => 'color_hover',
                            'label' => esc_html__('Color Hover', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list .pxl-item--text a:hover' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'text_typography',
                            'label' => esc_html__('Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-list .pxl-item--text a, {{WRAPPER}} .pxl-list .pxl--item .pxl-desc .pxl-item-title ',
                        ),
                        array(
                            'name' => 'fist_typography',
                            'label' => esc_html__('First Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => ' {{WRAPPER}} .pxl-list .pxl--item .pxl-desc .pxl-item-heading',
                            'condition' => [
			                    'layout' => '2',
		                        ],
                        ),
                        array(
                            'name' => 'number_color',
                            'label' => esc_html__('Bg Number Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list .pxl--item .pxl-item-number' => 'background: {{VALUE}};',
                            ],
                            'condition' => [
			                    'layout' => '2',
		                        ],
                        ),
                    ),
                ),
                autoev_widget_animation_settings(),
            ),
        ),
    ),
    autoev_get_class_widget_path()
);