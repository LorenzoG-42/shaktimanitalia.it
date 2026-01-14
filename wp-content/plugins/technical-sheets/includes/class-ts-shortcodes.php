<?php
/**
 * Shortcodes Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Shortcodes {
    
    public function __construct() {
        add_shortcode('technical_sheets', array($this, 'technical_sheets_shortcode'));
        add_shortcode('technical_sheet', array($this, 'single_technical_sheet_shortcode'));
        add_shortcode('ts_filters', array($this, 'filters_shortcode'));
    }
    
    /**
     * Technical sheets archive shortcode
     */
    public function technical_sheets_shortcode($atts) {
        $atts = shortcode_atts(array(
            'posts_per_page' => 12,
            'type' => '',
            'brand' => '',
            'orderby' => 'date',
            'order' => 'DESC',
            'columns' => 3,
            'show_excerpt' => 'yes',
            'show_meta' => 'yes'
        ), $atts);
        
        $args = array(
            'post_type' => 'technical_sheet',
            'posts_per_page' => intval($atts['posts_per_page']),
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'post_status' => 'publish'
        );
        
        // Add taxonomy filters
        if (!empty($atts['type'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'technical_sheet_type',
                'field' => 'slug',
                'terms' => $atts['type']
            );
        }
        
        if (!empty($atts['brand'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'technical_sheet_brand',
                'field' => 'slug',
                'terms' => $atts['brand']
            );
        }
        
        if (isset($args['tax_query']) && count($args['tax_query']) > 1) {
            $args['tax_query']['relation'] = 'AND';
        }
        
        $query = new WP_Query($args);
        
        if (!$query->have_posts()) {
            return '<p>' . __('No technical sheets found.', 'technical-sheets') . '</p>';
        }
        
        $output = '<div class="ts-shortcode-container ts-columns-' . intval($atts['columns']) . '">';
        
        while ($query->have_posts()) {
            $query->the_post();
            
            $output .= '<div class="ts-shortcode-item">';
            $output .= '<div class="ts-item-thumbnail">';
            
            if (has_post_thumbnail()) {
                $output .= '<a href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</a>';
            } else {
                $output .= '<div class="ts-no-image"><a href="' . get_permalink() . '">' . __('No Image', 'technical-sheets') . '</a></div>';
            }
            
            $output .= '</div>';
            $output .= '<div class="ts-item-content">';
            $output .= '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            
            if ($atts['show_meta'] === 'yes') {
                $types = get_the_terms(get_the_ID(), 'technical_sheet_type');
                $brands = get_the_terms(get_the_ID(), 'technical_sheet_brand');
                
                if ($types || $brands) {
                    $output .= '<div class="ts-item-meta">';
                    
                    if ($brands) {
                        $output .= '<span class="ts-brand">' . __('Marchio:', 'technical-sheets') . ' ' . implode(', ', wp_list_pluck($brands, 'name')) . '</span>';
                    }

                    if ($types) {
                        $output .= '<span class="ts-type">' . __('Tipologia:', 'technical-sheets') . ' ' . implode(', ', wp_list_pluck($types, 'name')) . '</span>';
                    }
                    
                    $output .= '</div>';
                }
            }
            
            if ($atts['show_excerpt'] === 'yes') {
                $output .= '<div class="ts-item-excerpt">' . get_the_excerpt() . '</div>';
            }
            
            $output .= '<div class="ts-item-actions">';
            $output .= '<a href="' . get_permalink() . '" class="ts-view-button">' . __('View Details', 'technical-sheets') . '</a>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        wp_reset_postdata();
        
        return $output;
    }
    
    /**
     * Single technical sheet shortcode
     */
    public function single_technical_sheet_shortcode($atts) {
        $atts = shortcode_atts(array(
            'id' => 0,
            'sections' => 'all' // all, gallery, basic_info, content, accordion
        ), $atts);
        
        if (empty($atts['id'])) {
            return '<p>' . __('Technical sheet ID is required.', 'technical-sheets') . '</p>';
        }
        
        $post = get_post($atts['id']);
        
        if (!$post || $post->post_type !== 'technical_sheet') {
            return '<p>' . __('Technical sheet not found.', 'technical-sheets') . '</p>';
        }
        
        $sections = explode(',', $atts['sections']);
        $sections = array_map('trim', $sections);
        
        $output = '<div class="ts-single-shortcode">';
        $output .= '<h2>' . get_the_title($post) . '</h2>';
        $output .= '<div class="ts-container">';
        
        // Gallery section
        if (in_array('all', $sections) || in_array('gallery', $sections)) {
            $output .= $this->render_gallery_section($post->ID);
        }
        
        // Basic info section
        if (in_array('all', $sections) || in_array('basic_info', $sections)) {
            $output .= $this->render_basic_info_section($post->ID);
        }
        
        // Content section
        if (in_array('all', $sections) || in_array('content', $sections)) {
            $output .= $this->render_content_section($post);
        }
        
        // Accordion section
        if (in_array('all', $sections) || in_array('accordion', $sections)) {
            $output .= $this->render_accordion_section($post->ID);
        }
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Render gallery section
     */
    private function render_gallery_section($post_id) {
        $gallery_images = get_post_meta($post_id, '_ts_gallery_images', true);
        if (empty($gallery_images)) {
            return '';
        }
        
        $output = '<div class="ts-section ts-gallery-section">';
        $output .= '<div class="ts-gallery">';
        
        foreach ($gallery_images as $image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'large');
            $thumb_url = wp_get_attachment_image_url($image_id, 'medium');
            
            if ($image_url) {
                $output .= '<div class="ts-gallery-item">';
                $output .= '<a href="' . $image_url . '" class="ts-gallery-link">';
                $output .= '<img src="' . $thumb_url . '" alt="' . get_post_meta($image_id, '_wp_attachment_image_alt', true) . '">';
                $output .= '</a>';
                $output .= '</div>';
            }
        }
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Render basic info section
     */
    private function render_basic_info_section($post_id) {
        $basic_info = get_post_meta($post_id, '_ts_basic_info', true);
        if (empty($basic_info)) {
            return '';
        }
        
        $output = '<div class="ts-section ts-basic-info-section">';
        $output .= '<h3>' . __('Informazioni Generali', 'technical-sheets') . '</h3>';
        $output .= '<table class="ts-basic-info-table">';
        
        foreach ($basic_info as $row) {
            if (!empty($row['label']) && !empty($row['value'])) {
                $output .= '<tr>';
                $output .= '<th>' . esc_html($row['label']) . '</th>';
                $output .= '<td>' . esc_html($row['value']) . '</td>';
                $output .= '</tr>';
            }
        }
        
        $output .= '</table>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Render content section
     */
    private function render_content_section($post) {
        if (empty($post->post_content)) {
            return '';
        }
        
        $output = '<div class="ts-section ts-content-section">';
        $output .= '<div class="ts-content">';
        $output .= apply_filters('the_content', $post->post_content);
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Render accordion section
     */
    private function render_accordion_section($post_id) {
        $accordion_sections = get_post_meta($post_id, '_ts_accordion_sections', true);
        if (empty($accordion_sections)) {
            return '';
        }
        
        $output = '<div class="ts-section ts-accordion-section">';
        $output .= '<div class="ts-accordion">';
        
        foreach ($accordion_sections as $index => $section) {
            if (!empty($section['title']) && !empty($section['content'])) {
                $output .= '<div class="ts-accordion-item">';
                $output .= '<div class="ts-accordion-header" data-target="ts-accordion-content-' . $index . '">';
                $output .= '<h4>' . esc_html($section['title']) . '</h4>';
                $output .= '<span class="ts-accordion-icon">></span>';
                $output .= '</div>';
                $output .= '<div class="ts-accordion-content" id="ts-accordion-content-' . $index . '">';
                
                if (!empty($section['image'])) {
                    $image_url = wp_get_attachment_image_url($section['image'], 'medium');
                    if ($image_url) {
                        $output .= '<img src="' . $image_url . '" alt="' . esc_attr($section['title']) . '" class="ts-accordion-image">';
                    }
                }
                
                $output .= '<div class="ts-accordion-text">' . wpautop(esc_html($section['content'])) . '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }
        }
        
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Filters shortcode
     */
    public function filters_shortcode($atts) {
        $atts = shortcode_atts(array(
            'show_type' => 'yes',
            'show_brand' => 'yes',
            'show_search' => 'yes',
            'redirect_to' => '', // URL to redirect to (default: archive page)
            'style' => 'default', // default, compact, inline
            'auto_submit' => 'yes' // Auto submit on change
        ), $atts);
        
        // Ensure scripts are loaded
        wp_enqueue_script('ts-frontend-script');
        wp_enqueue_style('ts-frontend-style');
        
        // Get redirect URL
        $redirect_url = !empty($atts['redirect_to']) ? $atts['redirect_to'] : get_post_type_archive_link('technical_sheet');
        
        // Get current values
        $selected_type = isset($_GET['technical_sheet_type']) ? sanitize_text_field($_GET['technical_sheet_type']) : '';
        $selected_brand = isset($_GET['technical_sheet_brand']) ? sanitize_text_field($_GET['technical_sheet_brand']) : '';
        $search_query = get_search_query();
        
        $output = '<div class="ts-filters-shortcode ts-filters-' . esc_attr($atts['style']) . '">';
        $output .= '<form method="get" action="' . esc_url($redirect_url) . '" class="ts-filters-form">';
        
        if ($atts['show_type'] === 'yes') {
            $output .= '<div class="ts-filter-group">';
            $output .= '<label for="ts_type_filter_sc">' . __('Filtra per Tipologia:', 'technical-sheets') . '</label>';
            $output .= '<select name="technical_sheet_type" id="ts_type_filter_sc" class="ts-filter-select">';
            $output .= '<option value="">' . __('Tutte le Tipologie', 'technical-sheets') . '</option>';

            $types = get_terms(array(
                'taxonomy' => 'technical_sheet_type',
                'hide_empty' => true,
            ));
            
            if (!is_wp_error($types) && !empty($types)) {
                foreach ($types as $type) {
                    $selected = selected($selected_type, $type->slug, false);
                    $output .= sprintf(
                        '<option value="%s"%s>%s (%d)</option>',
                        esc_attr($type->slug),
                        $selected,
                        esc_html($type->name),
                        $type->count
                    );
                }
            }
            
            $output .= '</select>';
            $output .= '</div>';
        }
        
        if ($atts['show_brand'] === 'yes') {
            $output .= '<div class="ts-filter-group">';
            $output .= '<label for="ts_brand_filter_sc">' . __('Filter by Brand:', 'technical-sheets') . '</label>';
            $output .= '<select name="technical_sheet_brand" id="ts_brand_filter_sc" class="ts-filter-select">';
            $output .= '<option value="">' . __('All Brands', 'technical-sheets') . '</option>';
            
            $brands = get_terms(array(
                'taxonomy' => 'technical_sheet_brand',
                'hide_empty' => true,
            ));
            
            if (!is_wp_error($brands) && !empty($brands)) {
                foreach ($brands as $brand) {
                    $selected = selected($selected_brand, $brand->slug, false);
                    $output .= sprintf(
                        '<option value="%s"%s>%s (%d)</option>',
                        esc_attr($brand->slug),
                        $selected,
                        esc_html($brand->name),
                        $brand->count
                    );
                }
            }
            
            $output .= '</select>';
            $output .= '</div>';
        }
        
        if ($atts['show_search'] === 'yes') {
            $output .= '<div class="ts-filter-group">';
            $output .= '<label for="ts_search_filter_sc">' . __('Ricerca:', 'technical-sheets') . '</label>';
            $output .= '<input type="text" name="s" id="ts_search_filter_sc" class="ts-filter-input" ';
            $output .= 'value="' . esc_attr($search_query) . '" ';
            $output .= 'placeholder="' . __('Cerca schede tecniche...', 'technical-sheets') . '">';
            $output .= '</div>';
        }
        
        $output .= '<div class="ts-filter-actions">';
        $output .= '<button type="submit" class="ts-filter-button ts-apply-button">';
        $output .= __('Cerca', 'technical-sheets');
        $output .= '</button>';
        $output .= '<a href="' . esc_url($redirect_url) . '" class="ts-filter-button ts-clear-button">';
        $output .= __('Reset', 'technical-sheets');
        $output .= '</a>';
        $output .= '<button type="button" class="ts-filter-button ts-reset-button" onclick="this.closest(\'form\').reset(); this.closest(\'form\').submit();">';
        $output .= __('Reset', 'technical-sheets');
        $output .= '</button>';
        $output .= '</div>';
        
        $output .= '</form>';
        
        // Show active filters
        $active_filters = array();
        if (!empty($selected_type)) {
            $type_term = get_term_by('slug', $selected_type, 'technical_sheet_type');
            if ($type_term) {
                $active_filters[] = sprintf(__('Tipologia: %s', 'technical-sheets'), $type_term->name);
            }
        }
        if (!empty($selected_brand)) {
            $brand_term = get_term_by('slug', $selected_brand, 'technical_sheet_brand');
            if ($brand_term) {
                $active_filters[] = sprintf(__('Brand: %s', 'technical-sheets'), $brand_term->name);
            }
        }
        if (!empty($search_query)) {
            $active_filters[] = sprintf(__('Search: "%s"', 'technical-sheets'), $search_query);
        }
        
        if (!empty($active_filters)) {
            $output .= '<div class="ts-active-filters">';
            $output .= '<span class="ts-active-filters-label">' . __('Active filters:', 'technical-sheets') . '</span> ';
            $output .= implode(' | ', $active_filters);
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        // Add auto-submit functionality if enabled
        if ($atts['auto_submit'] === 'yes') {
            $output .= '<script>
                jQuery(document).ready(function($) {
                    $(".ts-filters-shortcode .ts-filter-select").on("change", function() {
                        $(this).closest("form").submit();
                    });
                    
                    $(".ts-filters-shortcode .ts-filter-input").on("keypress", function(e) {
                        if (e.which === 13) {
                            $(this).closest("form").submit();
                        }
                    });
                });
            </script>';
        }
        
        return $output;
    }
}
