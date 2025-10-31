<?php
$slides_to_show = range( 1, 10 );
$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

pxl_add_custom_widget(
    array(
        'name' => 'pxl_text_carousel',
        'title' => esc_html__('BR Text Slide', 'autoev'),
        'icon' => 'eicon-wordart',
        'categories' => array('pxltheme-core'),
        'params' => array(
            'sections' => array(
                array(
                    'name' => 'section_content',
                    'label' => esc_html__('Content', 'autoev'),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    'controls' => array(
                    
                        array(
                            'name' => 'items',
                            'label' => esc_html__('Items', 'autoev'),
                            'type' => \Elementor\Controls_Manager::REPEATER,
                            'controls' => array(
                                array(
                                    'name' => 'text',
                                    'label' => esc_html__('Text', 'autoev'),
                                    'type' => \Elementor\Controls_Manager::TEXT,
                                    'label_block' => true,
                                ),
                            ),
                            'title_field' => '{{{ text }}}',
                        ),
                    ),
                ),

                array(
                    'name' => 'tab_style_text',
                    'label' => esc_html__('Text', 'autoev' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'controls' => array(
                        array(
                            'name' => 'style',
                            'label' => esc_html__('Style', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'style1' => 'Style 1',
                            ],
                            'default' => 'style1',
                        ),
                        array(
                            'name' => 'text_typography',
                            'label' => esc_html__('Typography', 'autoev' ),
                            'type' => \Elementor\Group_Control_Typography::get_type(),
                            'control_type' => 'group',
                            'selector' => '{{WRAPPER}} .pxl-text-carousel .pxl-item--text',
                        ),
                        array(
                            'name' => 'text_bold_color',
                            'label' => esc_html__( 'Text Bold Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-carousel .pxl-item--text' => 'color: {{VALUE}};',
                                '{{WRAPPER}} .pxl-text-carousel .pxl--item::after' => 'background-color: {{VALUE}};',
                            ],
                        ),

                        array(
                            'name' => 'text_stroke_color',
                            'label' => esc_html__( 'Text Stroke Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-carousel .pxl--item .pxl-item--text' => '-webkit-text-stroke-color: {{VALUE}};',
                                '{{WRAPPER}} .pxl-text-carousel .pxl--item::after' => '-webkit-text-stroke-color: {{VALUE}};',
                                '{{WRAPPER}} .pxl-text-carousel .pxl--item::after' => 'border-color: {{VALUE}};',
                            ],
                        ),
                        array(
                            'name' => 'text_bold_outline',
                            'label' => esc_html__( 'Text Outline Color', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '',
                            'selectors' => [
                                '{{WRAPPER}} .pxl-text-carousel .pxl--item .pxl-item--text' => '-webkit-text-stroke-color: {{VALUE}};color: transparent;',
                                '{{WRAPPER}} .pxl-text-carousel .pxl--item::after' => '-webkit-text-stroke-color: {{VALUE}};color: transparent;',
                            ],
                        ),
                        array(
                            'name' => 'text_outline_width',
                            'label' => esc_html__('Text Outline Width', 'autoev' ),
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
                                '{{WRAPPER}} .pxl-text-carousel .pxl--item:nth-child(2n) .pxl-item--text' => '-webkit-text-stroke-width: {{SIZE}}{{UNIT}};',
                            ],
                        ),
                        array(
                            'name' => 'effect',
                            'label' => esc_html__('Effect', 'autoev' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'options' => [
                                'text-slide-to-left' => 'Slide to Left',
                                'text-slide-to-right' => 'Slide to Right',
                            ],
                            'default' => 'text-slide-to-left',
                        ),
                        array(
                            'name' => 'effect_speed',
                            'label' => esc_html__('Effect Speed', 'autoev'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                            'label_block' => true,
                            'description' => 'Default: 16000 - Unit: ms',
                        ),
                    ),
                ),
                autoev_widget_animation_settings(),
            ),
        ),
    ),
    autoev_get_class_widget_path()
);