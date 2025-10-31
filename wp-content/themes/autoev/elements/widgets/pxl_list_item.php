<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_list_item',
        'title' => esc_html__('BR List Item', 'autoev'),
        'icon' => 'eicon-alert',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'autoev'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'icons',
                            'label' => esc_html__('Icons', 'autoev'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'pxl_icon',
                                    'label' => esc_html__('Icon', 'autoev' ),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                ),
                              
                                array(
                                    'name' => 'title',
                                    'label' => esc_html__('Title', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'sub_title',
                                    'label' => esc_html__('Sub Title', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'text_button',
                                    'label' => esc_html__('Text Buttom', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                                array(
                                    'name' => 'item_link',
                                    'label' => esc_html__('Link Details', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::URL,
                                    'label_block' => true,
                                ),
                            ),
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
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item .pxl-description' => 'text-align: {{VALUE}};',
                            ],
                        ),
                    ),
                ),
                
                array(
                    'name' => 'section_style',
                    'label' => esc_html__('Style', 'autoev'),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style-default' => 'Default',
                                'style-box1' => 'Box 1',
                                'style-1' => 'Style 1',
                                'style-2' => 'Style 2',
                                'style-3' => 'Style 3',
                                'style-4' => 'Style 4',
                                'style-5' => 'Style 5',
                                'style-6' => 'Style 6',
                                'style-7' => 'Style 7',
                                'style-8' => 'Style 8',
                                'style-9' => 'Style 9',
                            ],
                            'default' => 'style-default',
                        ),
                       
                        
                        array(
                            'name' => 'icon_font_size',
                            'label' => esc_html__('Icon Size', 'autoev' ),
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
                                '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-icon-content i' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'box_width',
                            'label' => esc_html__('Box Width', 'autoev' ),
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
                                '{{WRAPPER}} .pxl-list-item.style-box1 .pxl-content' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                        array(
                            'name' => 'box_height',
                            'label' => esc_html__('Box Height', 'autoev' ),
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
                                '{{WRAPPER}}.pxl-list-item.style-box1 .pxl-content' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                            ],
                            'condition' => [
                                'style' => ['style-box1'],
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
                                '{{WRAPPER}} .pxl-list-item .pxl-item-content' => 'border-style: {{VALUE}} ;',
                            ],
                        ),
                        array(
                            'name' => 'border_width',
                            'label' => esc_html__( 'Border Width', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item .pxl-item-content' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                            ],
                            'condition' => [
                                'border_type!' => '',
                            ],
                            'responsive' => true,
                        ),
                        array(
                            'name' => 'border_color',
                            'label' => esc_html__( 'Border Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item .pxl-item-content' => 'border-color: {{VALUE}} ;',
                            ],
                            'condition' => [
                                'border_type!' => '',
                            ],
                        ),
                       
                        array(
                            'name' => 'box_radius',
                            'label' => esc_html__('Border Radius', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px' ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-icon-list a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                        array(
                            'name' => 'item_background',
                            'label' => esc_html__('Box background', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item.style-box1 .pxl-content' => 'background: {{VALUE}};',
                            ],
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                        array(
                            'name' => 'content_background',
                            'label' => esc_html__('Content background', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item .pxl-item-content' => 'background: {{VALUE}};',
                            ],
                            'condition' => [
                                'style' => ['style-5'],
                            ],
                        ),
                        array(
                            'name' => 'color_icon',
                            'label' => esc_html__('Icon Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item .pxl-content .pxl-icon-content i' => 'color: {{VALUE}};',
                            ],
                           
                        ),
                        array(
                            'name' => 'bg_icon',
                            'label' => esc_html__('Icon Background', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-content .pxl-icon-content' => 'background: {{VALUE}};',
                            ],
                          
                        ),
                        array(
                            'name' => 'bg_btnr',
                            'label' => esc_html__('Button Background', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-item-button .pxl-item-right' => 'background-color: {{VALUE}};',
                            ],
                            'condition' => [
                                'style' => ['style-default'],
                            ],
                           
                        ),
                        array(
                            'name' => 'color_btn',
                            'label' => esc_html__('Btn Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-item-button .pxl-item-right' => 'color: {{VALUE}};',
                            ],
                           'condition' => [
                                'style' => ['style-default'],
                            ],
                        ),
                        array(
                            'name' => 'btn_typography',
                            'label' => esc_html__('Btn Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-item-button .pxl-item-right',
                            'condition' => [
                                'style' => ['style-default'],
                            ],
                        ),
                        array(
                            'name'         => 'box_shadow',
                            'label' => esc_html__( 'Box Shadow', 'autoev' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .pxl-icon-list a',
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                     
                        array(
                            'name'         => 'box_shadow_hover',
                            'label' => esc_html__( 'Hover - Box Shadow', 'autoev' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .pxl-icon-list a:hover',
                            'condition' => [
                                'style' => ['style-box1'],
                            ],
                        ),
                       
                        array(
                            'name' => 'title_typography',
                            'label' => esc_html__('Title Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-description .pxl-title',
                        ),
                        array(
                            'name' => 'title_color',
                            'label' => esc_html__('Title Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-description .pxl-title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'sub_typography',
                            'label' => esc_html__('Sub Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-description .pxl-sub--title',
                        ),
                        array(
                            'name' => 'button_typography',
                            'label' => esc_html__('Button Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-item-right a',
                        ),
                       
                        array(
                            'name' => 'sb_color',
                            'label' => esc_html__('Sub Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-description .pxl-sub--title' => 'color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name'         => 'button_shadow',
                            'label' => esc_html__( 'Button Shadow', 'autoev' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .pxl-list-item .pxl-item-content .pxl-item-right:hover',
                        ),
                    ),
                ),
                autoev_widget_animation_settings(),
            ),
        ),
    ),
    autoev_get_class_widget_path()
);