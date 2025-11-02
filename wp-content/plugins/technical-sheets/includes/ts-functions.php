<?php
/**
 * Helper Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get technical sheet gallery images
 */
function ts_get_gallery_images($post_id) {
    $gallery_images = get_post_meta($post_id, '_ts_gallery_images', true);
    if (empty($gallery_images)) {
        return array();
    }
    return $gallery_images;
}

/**
 * Get technical sheet basic info
 */
function ts_get_basic_info($post_id) {
    $basic_info = get_post_meta($post_id, '_ts_basic_info', true);
    if (empty($basic_info)) {
        return array();
    }
    return $basic_info;
}

/**
 * Get technical sheet accordion sections
 */
function ts_get_accordion_sections($post_id) {
    $accordion_sections = get_post_meta($post_id, '_ts_accordion_sections', true);
    if (empty($accordion_sections)) {
        return array();
    }
    return $accordion_sections;
}

/**
 * Display technical sheet gallery
 */
function ts_display_gallery($post_id, $class = '') {
    $gallery_images = ts_get_gallery_images($post_id);
    if (empty($gallery_images)) {
        return;
    }
    
    echo '<div class="ts-gallery-slider ' . esc_attr($class) . '">';
    echo '<div class="ts-slider-container">';
    echo '<div class="ts-slider-wrapper">';
    
    foreach ($gallery_images as $index => $image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'full');
        $thumb_url = wp_get_attachment_image_url($image_id, 'large');
        $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        $caption = wp_get_attachment_caption($image_id);
        
        if ($image_url) {
            $active_class = $index === 0 ? ' active' : '';
            echo '<div class="ts-slide' . $active_class . '" data-slide="' . $index . '">';
            echo '<a href="' . esc_url($image_url) . '" data-type="image" class="ts-slide-link">';
            echo wp_get_attachment_image($image_id, 'large', false, array(
                'alt' => $alt_text,
                'class' => 'ts-slide-image'
            ));
            echo '</a>';
            if ($caption) {
                echo '<div class="ts-slide-caption">' . esc_html($caption) . '</div>';
            }
            echo '</div>';
        }
    }
    
    echo '</div>'; // ts-slider-wrapper
    
    // Navigation arrows (only if more than 1 image)
    if (count($gallery_images) > 1) {
        echo '<button class="ts-slider-btn ts-slider-prev" aria-label="Previous image">';
        echo '<i class="fas fa-chevron-left"></i>';
        echo '</button>';
        echo '<button class="ts-slider-btn ts-slider-next" aria-label="Next image">';
        echo '<i class="fas fa-chevron-right"></i>';
        echo '</button>';
        
        // Dots indicator
        echo '<div class="ts-slider-dots">';
        foreach ($gallery_images as $index => $image_id) {
            $active_class = $index === 0 ? ' active' : '';
            echo '<button class="ts-slider-dot' . $active_class . '" data-slide="' . $index . '" aria-label="Go to slide ' . ($index + 1) . '"></button>';
        }
        echo '</div>';
    }
    
    echo '</div>'; // ts-slider-container
    echo '</div>'; // ts-gallery-slider
}

/**
 * Display technical sheet basic info table
 */
function ts_display_basic_info($post_id, $class = '') {
    $basic_info = ts_get_basic_info($post_id);
    if (empty($basic_info)) {
        return;
    }
    
    echo '<div class="ts-basic-info ' . esc_attr($class) . '">';
    echo '<h3>' . __('Informazioni Generali', 'technical-sheets') . '</h3>';
    echo '<table class="ts-basic-info-table">';
    
    foreach ($basic_info as $row) {
        if (!empty($row['label']) && !empty($row['value'])) {
            echo '<tr>';
            echo '<th>' . esc_html($row['label']) . '</th>';
            echo '<td>' . esc_html($row['value']) . '</td>';
            echo '</tr>';
        }
    }
    
    echo '</table>';
    echo '</div>';
}

/**
 * Display technical sheet accordion
 */
function ts_display_accordion($post_id, $class = '') {
    $accordion_sections = ts_get_accordion_sections($post_id);
    if (empty($accordion_sections)) {
        return;
    }
    
    echo '<div class="ts-accordion ' . esc_attr($class) . '">';
    
    foreach ($accordion_sections as $index => $section) {
        if (!empty($section['title']) && !empty($section['content'])) {
            echo '<div class="ts-accordion-item">';
            echo '<div class="ts-accordion-header" data-target="ts-accordion-content-' . $index . '">';
            echo '<h4>' . esc_html($section['title']) . '</h4>';
            echo '<span class="ts-accordion-icon">></span>';
            echo '</div>';
            echo '<div class="ts-accordion-content collapsed" id="ts-accordion-content-' . $index . '">';
            
            if (!empty($section['image'])) {
                $image_url = wp_get_attachment_image_url($section['image'], 'medium');
                if ($image_url) {
                    echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($section['title']) . '" class="ts-accordion-image">';
                }
            }
            
            // Process content to make headers bold
            $content = $section['content'];
            $lines = explode("\n", $content);
            $processed_content = '';
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line) && strpos($line, ':') !== false) {
                    $parts = explode(':', $line, 2);
                    if (count($parts) === 2) {
                        $header = trim($parts[0]);
                        $description = trim($parts[1]);
                        $processed_content .= '<strong>' . esc_html($header) . ':</strong> ' . esc_html($description) . "<br>\n";
                    } else {
                        $processed_content .= esc_html($line) . "<br>\n";
                    }
                } else {
                    $processed_content .= esc_html($line) . "<br>\n";
                }
            }
            
            echo '<div class="ts-accordion-text">' . $processed_content . '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
    
    echo '</div>';
}

/**
 * Get PDF export URL
 */
function ts_get_pdf_export_url($post_id) {
    $options = get_option('technical_sheets_options');
    $enable_pdf_export = isset($options['enable_pdf_export']) ? $options['enable_pdf_export'] : 1;
    
    if (!$enable_pdf_export) {
        return false;
    }
    
    return add_query_arg(array(
        'ts_export_pdf' => '1',
        'post_id' => $post_id
    ), home_url());
}

/**
 * Display PDF export button
 */
function ts_display_pdf_export_button($post_id, $class = '') {
    $pdf_attachment_id = get_post_meta($post_id, '_ts_pdf_attachment', true);
    
    if (!$pdf_attachment_id) {
        return;
    }
    
    $pdf_url = wp_get_attachment_url($pdf_attachment_id);
    if (!$pdf_url) {
        return;
    }
    
    echo '<div class="ts-pdf-export ' . esc_attr($class) . '">';
    echo '<a href="' . esc_url($pdf_url) . '" class="ts-pdf-export-button p" target="_blank" download>';
    echo '<i class="fas fa-file-pdf"></i> ' . __('Scarica', 'technical-sheets');
    echo '</a>';
    echo '</div>';
}

/**
 * Display print button
 */
function ts_display_print_button($class = '') {
    echo '<div class="ts-print ' . esc_attr($class) . '">';
    echo '<button class="ts-print-button">';
    echo '<i class="fas fa-print"></i> ' . __('Stampa', 'technical-sheets');
    echo '</button>';
    echo '</div>';
}

/**
 * Get technical sheet types
 */
function ts_get_sheet_types($post_id) {
    return get_the_terms($post_id, 'technical_sheet_type');
}

/**
 * Get technical sheet brands
 */
function ts_get_sheet_brands($post_id) {
    return get_the_terms($post_id, 'technical_sheet_brand');
}

/**
 * Display technical sheet meta
 */
function ts_display_sheet_meta($post_id, $class = '') {
    $types = ts_get_sheet_types($post_id);
    $brands = ts_get_sheet_brands($post_id);
    
    if (!$types && !$brands) {
        return;
    }
    
    echo '<div class="ts-sheet-meta ' . esc_attr($class) . '">';
    
    if ($types) {
        echo '<div class="ts-sheet-types">';
        echo '<strong>' . __('Tipologia:', 'technical-sheets') . '</strong> ';
        echo implode(', ', wp_list_pluck($types, 'name'));
        echo '</div>';
    }
    
    if ($brands) {
        echo '<div class="ts-sheet-brands">';
        echo '<strong>' . __('Marchio:', 'technical-sheets') . '</strong> ';
        echo implode(', ', wp_list_pluck($brands, 'name'));
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Display download section with print and PDF buttons
 */
function ts_display_download_section($post_id, $class = '') {
    $pdf_attachment_id = get_post_meta($post_id, '_ts_pdf_attachment', true);
    
    echo '<div class="ts-download-section ' . esc_attr($class) . '">';
    echo '<h3>' . __('Scarica', 'technical-sheets') . '</h3>';
    echo '<div class="ts-download-buttons">';
    
    // Print button
    echo '<button class="ts-print-button">';
    echo '<i class="fas fa-print"></i> ' . __('Stampa', 'technical-sheets');
    echo '</button>';
    
    // PDF download button (only if PDF is attached)
    if ($pdf_attachment_id) {
        $pdf_url = wp_get_attachment_url($pdf_attachment_id);
        if ($pdf_url) {
            echo '<a href="' . esc_url($pdf_url) . '" class="ts-pdf-export-button" target="_blank" download>';
            echo '<i class="fas fa-file-pdf"></i> ' . __('Scheda Tecnica', 'technical-sheets');
            echo '</a>';
        }
    }
    
    echo '</div>';
    echo '</div>';
}
