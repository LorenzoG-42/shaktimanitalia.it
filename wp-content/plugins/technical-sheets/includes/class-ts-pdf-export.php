<?php
/**
 * PDF Export Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_PDF_Export {
    
    public function __construct() {
        add_action('init', array($this, 'handle_pdf_export'));
        add_action('wp_ajax_ts_export_pdf', array($this, 'ajax_export_pdf'));
        add_action('wp_ajax_nopriv_ts_export_pdf', array($this, 'ajax_export_pdf'));
        add_filter('post_row_actions', array($this, 'add_pdf_export_link'), 10, 2);
    }
    
    /**
     * Handle PDF export
     */
    public function handle_pdf_export() {
        if (isset($_GET['ts_export_pdf']) && isset($_GET['post_id'])) {
            $post_id = intval($_GET['post_id']);
            $post = get_post($post_id);
            
            if (!$post || $post->post_type !== 'technical_sheet') {
                wp_die(__('Invalid technical sheet.', 'technical-sheets'));
            }
            
            $this->generate_pdf($post_id);
        }
    }
    
    /**
     * AJAX export PDF
     */
    public function ajax_export_pdf() {
        check_ajax_referer('ts_ajax_nonce', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $post = get_post($post_id);
        
        if (!$post || $post->post_type !== 'technical_sheet') {
            wp_die(__('Invalid technical sheet.', 'technical-sheets'));
        }
        
        $pdf_url = $this->generate_pdf($post_id, true);
        
        wp_send_json_success(array('pdf_url' => $pdf_url));
    }
    
    /**
     * Generate PDF
     */
    private function generate_pdf($post_id, $return_url = false) {
        $options = get_option('technical_sheets_options');
        $enable_pdf_export = isset($options['enable_pdf_export']) ? $options['enable_pdf_export'] : 1;
        
        if (!$enable_pdf_export) {
            wp_die(__('PDF export is disabled.', 'technical-sheets'));
        }
        
        $post = get_post($post_id);
        
        // For now, we'll create a simple HTML to PDF conversion
        // You can later integrate with a PDF library like TCPDF, mPDF, or DomPDF
        
        $html_content = $this->generate_pdf_content($post_id);
        
        if ($return_url) {
            // For AJAX requests, return a message for now
            return 'PDF generation not fully implemented yet. HTML content generated.';
        } else {
            // For direct download, output HTML for now
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . sanitize_file_name($post->post_title) . '.html"');
            echo $html_content;
            exit;
        }
    }
    
    /**
     * Generate PDF content
     */
    private function generate_pdf_content($post_id) {
        $post = get_post($post_id);
        
        $html = '<h1>' . esc_html($post->post_title) . '</h1>';
        
        // Add featured image
        if (has_post_thumbnail($post_id)) {
            $image_url = get_the_post_thumbnail_url($post_id, 'medium');
            $html .= '<img src="' . $image_url . '" style="max-width: 300px; height: auto;">';
        }
        
        // Add basic information
        $basic_info = get_post_meta($post_id, '_ts_basic_info', true);
        if (!empty($basic_info)) {
            $html .= '<h2>' . __('Informazioni Generali', 'technical-sheets') . '</h2>';
            $html .= '<table border="1" cellpadding="5" cellspacing="0">';
            
            foreach ($basic_info as $row) {
                if (!empty($row['label']) && !empty($row['value'])) {
                    $html .= '<tr>';
                    $html .= '<td><strong>' . esc_html($row['label']) . '</strong></td>';
                    $html .= '<td>' . esc_html($row['value']) . '</td>';
                    $html .= '</tr>';
                }
            }
            
            $html .= '</table>';
        }
        
        // Add content
        if (!empty($post->post_content)) {
            $html .= '<h2>' . __('Descrizione', 'technical-sheets') . '</h2>';
            $html .= wpautop($post->post_content);
        }
        
        // Add accordion sections
        $accordion_sections = get_post_meta($post_id, '_ts_accordion_sections', true);
        if (!empty($accordion_sections)) {
            $html .= '<h2>' . __('Additional Information', 'technical-sheets') . '</h2>';
            
            foreach ($accordion_sections as $section) {
                if (!empty($section['title']) && !empty($section['content'])) {
                    $html .= '<h3>' . esc_html($section['title']) . '</h3>';
                    
                    if (!empty($section['image'])) {
                        $image_url = wp_get_attachment_image_url($section['image'], 'medium');
                        if ($image_url) {
                            $html .= '<img src="' . $image_url . '" style="max-width: 200px; height: auto; margin: 10px 0;">';
                        }
                    }
                    
                    $html .= '<p>' . nl2br(esc_html($section['content'])) . '</p>';
                }
            }
        }
        
        return $html;
    }
    
    /**
     * Add PDF export link to post row actions
     */
    public function add_pdf_export_link($actions, $post) {
        if ($post->post_type === 'technical_sheet') {
            $options = get_option('technical_sheets_options');
            $enable_pdf_export = isset($options['enable_pdf_export']) ? $options['enable_pdf_export'] : 1;
            
            if ($enable_pdf_export) {
                $export_url = add_query_arg(array(
                    'ts_export_pdf' => '1',
                    'post_id' => $post->ID
                ), admin_url());
                
                $actions['export_pdf'] = '<a href="' . $export_url . '" target="_blank">' . __('Export PDF', 'technical-sheets') . '</a>';
            }
        }
        
        return $actions;
    }
}
