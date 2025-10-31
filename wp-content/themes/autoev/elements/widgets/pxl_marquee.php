<?php
pxl_add_custom_widget(
    array(
        'name' => 'pxl_marquee',
        'title' => esc_html__('BR Marquee', 'autoev'),
        'icon' => 'eicon-testimonial',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'autoev'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                        array(
                            'name' => 'label',
                            'label' => esc_html__('Label', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'description' => 'Highlight text with shortcode: [highlight text="Text"]',
                        ),
                        array(
                            'name' => 'items',
                            'label' => esc_html__('Items', 'autoev'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'type',
                                    'label' => esc_html__('Type', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => array(
                                        'icon' => esc_html__('Icon', 'autoev'),
                                        'image' => esc_html__('Image', 'autoev'),
                                        'text' => esc_html__('Text', 'autoev'),
                                    ),
                                    'default' => 'icon',
                                ),
                                array(
                                    'name' => 'pxl_icon',
                                    'label' => esc_html__('Icon', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::ICONS,
                                    'fa4compatibility' => 'icon',
                                    'condition' => array(
                                        'type' => 'icon',
                                    ),
                                ),
                                array(
                                    'name' => 'image',
                                    'label' => esc_html__('Image', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::MEDIA,
                                    'condition' => [
                                        'type' => 'image',
                                    ],
                                ),
                                array(
                                    'name' => 'image_type',
                                    'label' => esc_html__('Image Type', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::SELECT,
                                    'options' => array(
                                        'image' => esc_html__('Image', 'autoev'),
                                        'bg' => esc_html__('Background', 'autoev'),
                                    ),
                                    'default' => 'image',
                                    'condition' => [
                                        'type' => 'image',
                                    ],
                                ),
                                array(
                                    'name' => 'img_size',
                                    'label' => esc_html__('Image Size', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'condition' => array(
                                        'image_type' => 'image',
                                    ),
                                ),
                                array(
                                    'name' => 'text',
                                    'label' => esc_html__('Text', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'condition' => array(
                                        'type' => 'text',
                                    ),
                                ),
                              
                            ),
                        ),
                    ),
                ),

                array(
                    'name' => 'section_settings_carousel',
                    'label' => esc_html__('Carousel', 'autoev'),
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                    'controls' => array(
                        array(
                            'name' => 'infinite',
                            'label' => esc_html__('Infinite Loop', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => 'true',
                        ),
                        array(
                            'name' => 'direction',
                            'label' => esc_html__('Direction', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'ltr',
                            'options' => [
                                'ltr' => esc_html__('Left to Right', 'autoev'), 
                                'rtl' => esc_html__('Right to Left', 'autoev'), 
                            ]
                        ),
                        array(
                            'name' => 'duration',
                            'label' => esc_html__('Duration', 'autoev'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 1,
                            'description' => esc_html__('Enter number. Unit is ms', 'autoev'),
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--list' => 'animation-duration: {{VALUE}}ms',
                            ],
                        ),
                        array(
                            'name' => 'delay',
                            'label' => esc_html__('Delay', 'autoev'),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 1,
                            'description' => esc_html__('Enter number. Unit is ms', 'autoev'),
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--list' => 'animation-delay: {{VALUE}}ms',
                            ],
                        ),
                        array(
                            'name' => 'pause_on_hover',
                            'label' => esc_html__('Pause On Hover', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => '',
                        ),

                         
                        // array(
                        //     'name' => 'symmetry',
                        //     'label' => esc_html__('Symmetry', 'autoev'),
                        //     'type' => \Elementor\Controls_Manager::SWITCHER,
                        //     'default' => '',
                        // ),

                        array(
                            'name' => 'layout_gap',
                            'label' => esc_html__('Gap', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner' => '--gap: {{SIZE}}{{UNIT}};',
                                
                            ],
                        ),

                    ),
                ),
                array(
                    'name' => 'section_style_general',
                    'label' => esc_html__('General', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Styles', 'autoev'),
                            'type' => 'layoutcontrol',
                            'default' => 'pxl-marquee-style1',
                            'options' => array( 
                                'pxl-marquee-style2' => array(
                                    'label' => esc_html__('Style 2', 'autoev'),
                                ),

                            ),
                        ),
                        array(
                            'name' => 'inner',
                            'label' => esc_html__('INNER', 'autoev'),
                            'type' => \Elementor\Controls_Manager::HEADING,
                        ),
                        array(
                            'name' => 'spacing_x',
                            'label' => esc_html__('Spacer X', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner' => '--x: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        
                        array(
                            'name' => 'max_width',
                            'label' => esc_html__('Max Width', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--container' => 'max-width: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                    ),
                ),

                array(
                    'name' => 'tab_style_label',
                    'label' => esc_html__('Label', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'label_normal',
                            'label' => esc_html__('NORMAL', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'after', 
                        ),
                        array(
                            'name' => 'label_color',
                            'label' => esc_html__('Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--label' => 'color: {{VALUE}}'
                            ],
                        ),
                        array(
                            'name' => 'label_typography',
                            'label' => esc_html__('Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-marquee .pxl-item--label',
                        ),
                        array(
                            'name' => 'label_spacer_bottom',
                            'label' => esc_html__('Spacing Bottom', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'label_padding',
                            'label' => esc_html__( 'Padding', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ),

                        array(
                            'name' => 'divider12',
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                        ),
                        array(
                            'name' => 'label_highlight',
                            'label' => esc_html__('HIGHLIGHT', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'after' ,
                        ),
                        array(
                            'name' => 'label_highlight_color',
                            'label' => esc_html__('Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--label .pxl-title--highlight' => 'color: {{VALUE}}'
                            ],
                        ),
                        array(
                            'name' => 'label_highlight_typography',
                            'label' => esc_html__('Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-marquee .pxl-item--label .pxl-title--highlight',
                        ),
                        array(
                            'name' => 'divider1',
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                        ),
                        array(
                            'name' => 'label_line',
                            'label' => esc_html__('LINE', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'after' ,
                        ),
                        array(
                            'name' => 'label_line_show',
                            'label' => esc_html__('Show', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SWITCHER,
                            'default' => '' ,
                        ),
                        array(
                            'name' => 'label_line_color',
                            'label' => esc_html__('Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-heading .pxl-item--label, ' => '--bg-color: {{VALUE}}'
                            ],
                        ),
                        array(
                            'name' => 'label_line_max_width',
                            'label' => esc_html__('Max Width', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--label' => '--width: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'divider3',
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                        ),
                        array(
                            'name' => 'label_animation',
                            'label' => esc_html__('ANIMATION', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'after' ,
                        ),
                        array(
                            'name' => 'label_animate',
                            'label' => esc_html__('Animate', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => autoev_widget_animate(),
                            'default' => '',
                        ),
                        array(
                            'name' => 'label_animate_delay',
                            'label' => esc_html__('Delay', 'autoev'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => 'Ex: 500',
                        ),

                    ),
                ),
                                
                array(
                    'name' => 'tab_style_img',
                    'label' => esc_html__('Image', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'normal',
                            'label' => esc_html__('NORMAL', 'autoev'),
                            'type' => Elementor\Controls_Manager::HEADING,
                            'separator' => 'after',
                        ),
                        array(
                            'name' => 'filters',
                            'label' => esc_html__('Filters', 'autoev'),
                            'type' => \Elementor\Group_Control_Css_Filter::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--image img, 
                            {{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--bg' 
                        ),
                        array(
                            'name'         => 'box_shadow',
                            'label' => esc_html__( 'Box Shadow', 'autoev' ),
                            'type'         => \Elementor\Group_Control_Box_Shadow::get_type(),
                            'control_type' => 'group',
                            'selector'     => '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--image img, 
                            {{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--bg'
                        ),
                        array(
                            'name' => 'divider',
                            'type' => Elementor\Controls_Manager::DIVIDER,
                        ),
                        array(
                            'name' => 'dimension',
                            'label' => esc_html__('DIMENSION', 'autoev'),
                            'type' => Elementor\Controls_Manager::HEADING,
                            'separator' => 'after',
                        ),
                        array(
                            'name' => 'image_height',
                            'label' => esc_html__('Height', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'unit' => ['px', '%'],  
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'separator' => 'before',
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--image img,
                                {{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--bg' => 'height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'image_min_height',
                            'label' => esc_html__('Min Height', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--image img,
                                {{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--bg' => 'min-height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'image_max_height',
                            'label' => esc_html__('Max Height', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 3000,
                                ],
                            ],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--image img,
                                {{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--bg' => 'max-height: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                    ),
                ),

                array(
                    'name' => 'section_style_icon',
                    'label' => esc_html__('Icon', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(   
                        array(
                            'name' => 'icon_normal_options',
                            'label' => esc_html__( 'NORMAL', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'after',
                        ),    
                        array(
                            'name' => 'icon_color',
                            'label' => esc_html__( 'Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon svg path:not(.not-fill)' => 'fill: {{VALUE}}',
                            ],
                        ),
                        array(
                            'name' => 'icon_opacity',
                            'label' => esc_html__( 'Opacity', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => 0,
                            'max' => 1,
                            'step' => 0.1,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon' => 'opacity: {{VALUE}}',
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon svg path' => 'fill-opacity: {{VALUE}}',
                            ],
                        ),
                        array(
                            'name' => 'icon_bg_color',
                            'label' => esc_html__( 'Background Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon' => 'background-color: {{VALUE}}',
                            ],
                        ),
                        array(
                            'name' => 'icon_box_border',
                            'label' => esc_html__('Border', 'autoev'),
                            'type' => \Elementor\Group_Control_Border::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon',
                        ),
                        array(
                            'name' => 'icon_size',
                            'label' => esc_html__('Font Size', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                            'control_type' => 'responsive',
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon' => 'font-size: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'icon_box_size',
                            'label' => esc_html__('Box Size', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                            'control_type' => 'responsive',
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon' => '--size: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'icon_border_radius',
                            'label' => esc_html__( 'Border Radius', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                            'control_type' => 'responsive',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'divider2',
                            'type' => \Elementor\Controls_Manager::DIVIDER,
                        ),
                        array(
                            'name' => 'svg_options',
                            'label' => esc_html__( 'SVG', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::HEADING,
                            'separator' => 'after',
                        ),
                        array(
                            'name' => 'svg_width',
                            'label' => esc_html__('Width', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                            'control_type' => 'responsive',
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon svg' => 'width: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'svg_height',
                            'label' => esc_html__('Height', 'autoev'),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'size_units' => ['px', '%', 'rem', 'em', 'custom'],
                            'control_type' => 'responsive',
                            'range' => [
                                'px' => [
                                    'min' => 0,
                                    'max' => 1000,
                                ],
                            ],
                            'selectors' => [
                                '{{WRAPPER}} .pxl-marquee .pxl-item--inner .pxl-item--icon svg' => 'height: {{SIZE}}{{UNIT}};',
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