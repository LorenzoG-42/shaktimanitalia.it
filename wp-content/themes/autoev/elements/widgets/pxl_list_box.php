<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_list_box',
        'title' => esc_html__('BR List Box', 'autoev'),
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
                                    'image' => get_template_directory_uri() . '/elements/widgets/img-layout/pxl_list_box/layout1.jpg'
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
                                  '{{WRAPPER}} .pxl-list-box' => 'text-align: {{VALUE}};',
                                  '{{WRAPPER}} .pxl-list-box .pxl--item','{{WRAPPER}} .pxl-list-box .pxl-item--icon i' => 'justify-content: {{VALUE}};',
                              ],
                          ),
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-default' => 'Default',
                                'style-2' => 'Style 2',
                                'style-3' => 'Style 3',
                            ],
                            'default' => 'style-default',
                        ),
                        array(
                            'name' => 'lists',
                            'label' => esc_html__('Content', 'autoev'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'icon_type',
                                    'label' => esc_html__('Type', 'autoev' ),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => [
                                        'icon' => 'Icon',
                                        'image' => 'Image',
                                    ],
                                    'default' => 'icon',
                                ),
                                array(
                                    'name' => 'icon_image',
                                    'label' => esc_html__( 'Image', 'autoev' ),
                                    'type' => \Elementor\Controls_Manager::MEDIA,
                                    'condition' => [
                                        'icon_type' => 'image',
                                    ],
                                ),
                                array(
                                    'name' => 'pxl_icon',
                                    'label' => esc_html__('Icon', 'autoev' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                    'condition' => [
                                        'icon_type' => 'icon',
                                    ],
                                ),
                                array(
                                    'name' => 'text',
                                    'label' => esc_html__('Text', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'sub_text',
                                    'label' => esc_html__('Sub Text', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
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
                            'name' => 'item_padding',
                            'label' => esc_html__('Item Padding', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-box .pxl--item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'control_type' => 'responsive',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_text',
                    'label' => esc_html__('Text', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'text_color',
                            'label' => esc_html__('Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-box .pxl--item .pxl-item-content .pxl-item--text' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'text_typography',
                            'label' => esc_html__('Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-list-box .pxl--item .pxl-item-content .pxl-item--text',
                        ),

                        array(
                            'name' => 'sub_text_color',
                            'label' => esc_html__('Sub Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-box .pxl--item .pxl-item-content .pxl-sub-text a' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'sub_text_color_hover',
                            'label' => esc_html__('Sub Color Hover', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-box .pxl--item .pxl-item-content .pxl-sub-text a:hover' => 'color: {{VALUE}} !important;',
                            ],
                        ),
                        array(
                            'name' => 'sub_text_typography',
                            'label' => esc_html__('Sub Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-list-box .pxl--item .pxl-item-content .pxl-sub-text a',
                        ),
                    ),
                ),
                array(
                    'name' => 'section_style_icon',
                    'label' => esc_html__('Icon', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'icon_color',
                            'label' => esc_html__('Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-box .pxl-item--icon i' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .pxl-list-box .pxl-item--icon svg' => 'fill: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'icon_size',
                            'label' => esc_html__('Size', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'control_type' => 'responsive',
                            'size_units' => [ 'px' ],
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-box .pxl-item--icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                                '{{WRAPPER}} .pxl-list-box .pxl-item--icon svg' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                            'separator' => 'after',
                           
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
                        array(
                            'name' => 'icon_space',
                            'label' => esc_html__('Space', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-box .pxl-item--icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'control_type' => 'responsive',
                        ),
                    ),
                ),
                autoev_widget_animation_settings(),
            ),
        ),
    ),
    autoev_get_class_widget_path()
);