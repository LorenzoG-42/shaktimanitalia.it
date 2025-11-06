<?php
/**
 * Widget Elementor per Post Loop
 */

namespace Elementor_Post_Loop\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor_Post_Loop\Includes\Post_Helper;
use Elementor_Post_Loop\Includes\Query_Builder;
use Elementor_Post_Loop\Includes\Template_Loader;

if (!defined('ABSPATH')) {
    exit;
}

class Post_Loop_Widget extends Widget_Base {

    /**
     * Ottiene il nome del widget
     */
    public function get_name() {
        return 'elementor-post-loop';
    }

    /**
     * Ottiene il titolo del widget
     */
    public function get_title() {
        return __('Post Loop Avanzato', 'elementor-post-loop');
    }

    /**
     * Ottiene l'icona del widget
     */
    public function get_icon() {
        return 'eicon-posts-grid';
    }

    /**
     * Ottiene le categorie del widget
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Ottiene le parole chiave del widget
     */
    public function get_keywords() {
        return ['post', 'loop', 'grid', 'blog', 'custom post type', 'cpt'];
    }

    /**
     * Registra i controlli del widget
     */
    protected function register_controls() {
        // Sezione Query
        $this->start_controls_section(
            'section_query',
            [
                'label' => __('Query', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => __('Tipo di Post', 'elementor-post-loop'),
                'type' => Controls_Manager::SELECT,
                'options' => Post_Helper::get_post_types(),
                'default' => 'post',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Numero di Post', 'elementor-post-loop'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'min' => -1,
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => __('Offset', 'elementor-post-loop'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
            ]
        );

        // Controlli dinamici per le tassonomie
        $this->add_dynamic_taxonomy_controls();

        $this->add_control(
            'orderby',
            [
                'label' => __('Ordina per', 'elementor-post-loop'),
                'type' => Controls_Manager::SELECT,
                'options' => Post_Helper::get_order_by_options(),
                'default' => 'date',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => __('Direzione', 'elementor-post-loop'),
                'type' => Controls_Manager::SELECT,
                'options' => Post_Helper::get_order_options(),
                'default' => 'DESC',
            ]
        );

        $this->add_control(
            'meta_key',
            [
                'label' => __('Meta Key', 'elementor-post-loop'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'orderby' => ['meta_value', 'meta_value_num'],
                ],
            ]
        );

        $this->end_controls_section();

        // Sezione Layout
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __('Layout', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __('Layout', 'elementor-post-loop'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'grid' => __('Griglia', 'elementor-post-loop'),
                    'list' => __('Lista', 'elementor-post-loop'),
                    'masonry' => __('Masonry', 'elementor-post-loop'),
                ],
                'default' => 'grid',
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Colonne', 'elementor-post-loop'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'default' => '3',
                'condition' => [
                    'layout!' => 'list',
                ],
            ]
        );

        $this->add_control(
            'template_style',
            [
                'label' => __('Stile Template', 'elementor-post-loop'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'card' => __('Card', 'elementor-post-loop'),
                    'overlay' => __('Overlay', 'elementor-post-loop'),
                    'minimal' => __('Minimal', 'elementor-post-loop'),
                ],
                'default' => 'card',
            ]
        );

        $this->end_controls_section();

        // Sezione Contenuto
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Contenuto', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' => __('Mostra Immagine', 'elementor-post-loop'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => __('Dimensione Immagine', 'elementor-post-loop'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => __('Thumbnail', 'elementor-post-loop'),
                    'medium' => __('Medium', 'elementor-post-loop'),
                    'medium_large' => __('Medium Large', 'elementor-post-loop'),
                    'large' => __('Large', 'elementor-post-loop'),
                    'full' => __('Full', 'elementor-post-loop'),
                ],
                'default' => 'medium',
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'link_image',
            [
                'label' => __('Link Immagine', 'elementor-post-loop'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => __('Mostra Titolo', 'elementor-post-loop'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => __('Tag Titolo', 'elementor-post-loop'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                ],
                'default' => 'h3',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_length',
            [
                'label' => __('Lunghezza Titolo', 'elementor-post-loop'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'description' => __('0 = nessun limite', 'elementor-post-loop'),
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'link_title',
            [
                'label' => __('Link Titolo', 'elementor-post-loop'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => __('Mostra Meta', 'elementor-post-loop'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'meta_items',
            [
                'label' => __('Elementi Meta', 'elementor-post-loop'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'date' => __('Data', 'elementor-post-loop'),
                    'author' => __('Autore', 'elementor-post-loop'),
                    'comments' => __('Commenti', 'elementor-post-loop'),
                    'categories' => __('Categorie', 'elementor-post-loop'),
                    'tags' => __('Tag', 'elementor-post-loop'),
                ],
                'default' => ['date', 'author'],
                'condition' => [
                    'show_meta' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => __('Mostra Excerpt', 'elementor-post-loop'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => __('Lunghezza Excerpt', 'elementor-post-loop'),
                'type' => Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 1,
                'condition' => [
                    'show_excerpt' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => __('Mostra "Leggi di più"', 'elementor-post-loop'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => __('Testo "Leggi di più"', 'elementor-post-loop'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Leggi di più', 'elementor-post-loop'),
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Sezione Paginazione
        $this->start_controls_section(
            'section_pagination',
            [
                'label' => __('Paginazione', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => __('Mostra Paginazione', 'elementor-post-loop'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'pagination_type',
            [
                'label' => __('Tipo Paginazione', 'elementor-post-loop'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'numbers' => __('Numeri', 'elementor-post-loop'),
                    'prev_next' => __('Precedente/Successivo', 'elementor-post-loop'),
                    'load_more' => __('Carica Altri', 'elementor-post-loop'),
                ],
                'default' => 'numbers',
                'condition' => [
                    'show_pagination' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Stili
        $this->register_style_controls();
    }

    /**
     * Aggiunge controlli dinamici per le tassonomie
     */
    private function add_dynamic_taxonomy_controls() {
        $post_types = Post_Helper::get_post_types();

        foreach ($post_types as $post_type => $label) {
            $taxonomies = Post_Helper::get_taxonomies_by_post_type($post_type);

            foreach ($taxonomies as $taxonomy => $tax_label) {
                $this->add_control(
                    "include_{$taxonomy}",
                    [
                        'label' => sprintf(__('Includi %s', 'elementor-post-loop'), $tax_label),
                        'type' => Controls_Manager::SELECT2,
                        'multiple' => true,
                        'options' => Post_Helper::get_terms_by_taxonomy($taxonomy),
                        'condition' => [
                            'post_type' => $post_type,
                        ],
                    ]
                );

                $this->add_control(
                    "exclude_{$taxonomy}",
                    [
                        'label' => sprintf(__('Escludi %s', 'elementor-post-loop'), $tax_label),
                        'type' => Controls_Manager::SELECT2,
                        'multiple' => true,
                        'options' => Post_Helper::get_terms_by_taxonomy($taxonomy),
                        'condition' => [
                            'post_type' => $post_type,
                        ],
                    ]
                );
            }
        }
    }

    /**
     * Registra i controlli di stile
     */
    private function register_style_controls() {
        // Stile Container
        $this->start_controls_section(
            'section_style_container',
            [
                'label' => __('Container', 'elementor-post-loop'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label' => __('Gap', 'elementor-post-loop'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-grid' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-post-loop-masonry' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Stile Item
        $this->start_controls_section(
            'section_style_item',
            [
                'label' => __('Item', 'elementor-post-loop'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-post-loop-item',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .elementor-post-loop-item',
            ]
        );

        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __('Border Radius', 'elementor-post-loop'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-post-loop-item',
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __('Padding', 'elementor-post-loop'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Stile Titolo
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Titolo', 'elementor-post-loop'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-post-loop-title',
            ]
        );

        $this->start_controls_tabs('title_colors');

        $this->start_controls_tab(
            'title_normal',
            [
                'label' => __('Normale', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Colore', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-post-loop-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_hover',
            [
                'label' => __('Hover', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __('Colore', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-title:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-post-loop-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __('Spaziatura', 'elementor-post-loop'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Stile Pulsanti
        $this->start_controls_section(
            'section_style_buttons',
            [
                'label' => __('Pulsanti', 'elementor-post-loop'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'read_more_heading',
            [
                'label' => __('Pulsante "Leggi di più"', 'elementor-post-loop'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_align',
            [
                'label' => __('Allineamento', 'elementor-post-loop'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Sinistra', 'elementor-post-loop'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Centro', 'elementor-post-loop'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Destra', 'elementor-post-loop'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Giustificato', 'elementor-post-loop'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-read-more-container' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'read_more_typography',
                'selector' => '{{WRAPPER}} .elementor-post-loop-read-more-link',
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs(
            'read_more_style_tabs',
            [
                'condition' => [
                    'show_read_more' => 'yes',
                ],
            ]
        );

        $this->start_controls_tab(
            'read_more_normal',
            [
                'label' => __('Normale', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'read_more_color',
            [
                'label' => __('Colore Testo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-read-more-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'read_more_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-post-loop-read-more-link',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'read_more_border',
                'selector' => '{{WRAPPER}} .elementor-post-loop-read-more-link',
            ]
        );

        $this->add_responsive_control(
            'read_more_border_radius',
            [
                'label' => __('Border Radius', 'elementor-post-loop'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-read-more-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'read_more_padding',
            [
                'label' => __('Padding', 'elementor-post-loop'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-read-more-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'read_more_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-post-loop-read-more-link',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'read_more_hover',
            [
                'label' => __('Hover', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'read_more_hover_color',
            [
                'label' => __('Colore Testo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-read-more-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'read_more_hover_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-post-loop-read-more-link:hover',
            ]
        );

        $this->add_control(
            'read_more_hover_border_color',
            [
                'label' => __('Colore Bordo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-read-more-link:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'read_more_border_border!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'read_more_hover_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-post-loop-read-more-link:hover',
            ]
        );

        $this->add_control(
            'read_more_hover_transition',
            [
                'label' => __('Durata Transizione', 'elementor-post-loop'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-read-more-link' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'load_more_heading',
            [
                'label' => __('Pulsante "Carica Altri"', 'elementor-post-loop'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_pagination' => 'yes',
                    'pagination_type' => 'load_more',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_align',
            [
                'label' => __('Allineamento', 'elementor-post-loop'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Sinistra', 'elementor-post-loop'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Centro', 'elementor-post-loop'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Destra', 'elementor-post-loop'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-load-more-container' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'show_pagination' => 'yes',
                    'pagination_type' => 'load_more',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'load_more_typography',
                'selector' => '{{WRAPPER}} .elementor-post-loop-load-more',
                'condition' => [
                    'show_pagination' => 'yes',
                    'pagination_type' => 'load_more',
                ],
            ]
        );

        $this->start_controls_tabs(
            'load_more_style_tabs',
            [
                'condition' => [
                    'show_pagination' => 'yes',
                    'pagination_type' => 'load_more',
                ],
            ]
        );

        $this->start_controls_tab(
            'load_more_normal',
            [
                'label' => __('Normale', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'load_more_color',
            [
                'label' => __('Colore Testo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-load-more' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'load_more_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-post-loop-load-more',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'load_more_border',
                'selector' => '{{WRAPPER}} .elementor-post-loop-load-more',
            ]
        );

        $this->add_responsive_control(
            'load_more_border_radius',
            [
                'label' => __('Border Radius', 'elementor-post-loop'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_padding',
            [
                'label' => __('Padding', 'elementor-post-loop'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'load_more_margin',
            [
                'label' => __('Margin', 'elementor-post-loop'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-load-more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-post-loop-load-more',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'load_more_hover',
            [
                'label' => __('Hover', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'load_more_hover_color',
            [
                'label' => __('Colore Testo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-load-more:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'load_more_hover_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-post-loop-load-more:hover',
            ]
        );

        $this->add_control(
            'load_more_hover_border_color',
            [
                'label' => __('Colore Bordo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-load-more:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'load_more_border_border!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'load_more_hover_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-post-loop-load-more:hover',
            ]
        );

        $this->add_control(
            'load_more_hover_transition',
            [
                'label' => __('Durata Transizione', 'elementor-post-loop'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-load-more' => 'transition-duration: {{SIZE}}s;',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'load_more_loading',
            [
                'label' => __('Loading', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'load_more_loading_color',
            [
                'label' => __('Colore Testo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-load-more.loading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'load_more_loading_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-post-loop-load-more.loading',
            ]
        );

        $this->add_control(
            'load_more_loading_opacity',
            [
                'label' => __('Opacità', 'elementor-post-loop'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-load-more.loading' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'pagination_heading',
            [
                'label' => __('Paginazione Numerata', 'elementor-post-loop'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_pagination' => 'yes',
                    'pagination_type' => ['numbers', 'prev_next'],
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_align',
            [
                'label' => __('Allineamento', 'elementor-post-loop'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Sinistra', 'elementor-post-loop'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Centro', 'elementor-post-loop'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Destra', 'elementor-post-loop'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-pagination' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'show_pagination' => 'yes',
                    'pagination_type' => ['numbers', 'prev_next'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'selector' => '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers',
                'condition' => [
                    'show_pagination' => 'yes',
                    'pagination_type' => ['numbers', 'prev_next'],
                ],
            ]
        );

        $this->start_controls_tabs(
            'pagination_style_tabs',
            [
                'condition' => [
                    'show_pagination' => 'yes',
                    'pagination_type' => ['numbers', 'prev_next'],
                ],
            ]
        );

        $this->start_controls_tab(
            'pagination_normal',
            [
                'label' => __('Normale', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'pagination_color',
            [
                'label' => __('Colore Testo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pagination_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pagination_border',
                'selector' => '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers',
            ]
        );

        $this->add_responsive_control(
            'pagination_border_radius',
            [
                'label' => __('Border Radius', 'elementor-post-loop'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_padding',
            [
                'label' => __('Padding', 'elementor-post-loop'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_spacing',
            [
                'label' => __('Spaziatura tra elementi', 'elementor-post-loop'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pagination_hover',
            [
                'label' => __('Hover', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'pagination_hover_color',
            [
                'label' => __('Colore Testo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pagination_hover_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers:hover',
            ]
        );

        $this->add_control(
            'pagination_hover_border_color',
            [
                'label' => __('Colore Bordo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'pagination_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'pagination_active',
            [
                'label' => __('Attivo', 'elementor-post-loop'),
            ]
        );

        $this->add_control(
            'pagination_active_color',
            [
                'label' => __('Colore Testo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers.current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'pagination_active_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers.current',
            ]
        );

        $this->add_control(
            'pagination_active_border_color',
            [
                'label' => __('Colore Bordo', 'elementor-post-loop'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-post-loop-pagination .page-numbers.current' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'pagination_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Renderizza il widget
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Aggiungi l'ID del widget ai settings per l'AJAX
        $settings['widget_id'] = $this->get_id();
        
        // Ottieni la query con i dati reali
        $query = Query_Builder::get_query($settings);
        
        // Renderizza il template con i dati reali
        $content = Template_Loader::render_loop($query, $settings);
        
        // Se siamo nell'editor, aggiungi informazioni aggiuntive
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $content .= $this->render_editor_info($query, $settings);
        }
        
        echo $content;
    }

    /**
     * Renderizza il widget in modalità editor con dati reali
     */
    protected function content_template() {
        // Non usiamo JavaScript template, ma PHP per avere dati reali
        return;
    }

    /**
     * Renderizza informazioni aggiuntive per l'editor
     */
    private function render_editor_info($query, $settings) {
        $post_type_obj = get_post_type_object($settings['post_type'] ?? 'post');
        $post_type_label = $post_type_obj ? $post_type_obj->label : 'Post';
        
        $layout = $settings['layout'] ?? 'grid';
        $layout_labels = [
            'grid' => 'Griglia',
            'list' => 'Lista', 
            'masonry' => 'Masonry'
        ];
        $layout_label = $layout_labels[$layout] ?? 'Griglia';
        
        ob_start();
        ?>
        <div class="elementor-post-loop-editor-info">
            <small>
                <i class="eicon-info-circle-o"></i>
                <strong>Anteprima Live:</strong> 
                Mostrando <?php echo esc_html($query->post_count); ?> di <?php echo esc_html($query->found_posts); ?> 
                "<?php echo esc_html($post_type_label); ?>" in layout "<?php echo esc_html($layout_label); ?>"
                <?php if (!empty($settings['posts_per_page'])): ?>
                    (Limite: <?php echo esc_html($settings['posts_per_page']); ?>)
                <?php endif; ?>
            </small>
        </div>
        <?php
        return ob_get_clean();
    }
}