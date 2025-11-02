<?php
/**
 * Frontend Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Frontend {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'add_print_styles'));
        add_action('pre_get_posts', array($this, 'modify_archive_query'));
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        if (is_singular('technical_sheet') || is_post_type_archive('technical_sheet')) {
            wp_enqueue_script('ts-frontend-script', TECHNICAL_SHEETS_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), TECHNICAL_SHEETS_VERSION, true);
            wp_enqueue_style('ts-frontend-style', TECHNICAL_SHEETS_PLUGIN_URL . 'assets/css/frontend.css', array(), TECHNICAL_SHEETS_VERSION);
            
            // Enqueue Font Awesome
            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
            
            // Enqueue WordPress native media scripts for lightbox
            wp_enqueue_script('wp-dom-ready');
            wp_enqueue_script('wp-a11y');
            
            // Localize script for AJAX
            wp_localize_script('ts-frontend-script', 'ts_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ts_ajax_nonce')
            ));
        }
    }
    
    /**
     * Add print styles
     */
    public function add_print_styles() {
        $options = get_option('technical_sheets_options');
        $enable_print_styles = isset($options['enable_print_styles']) ? $options['enable_print_styles'] : 1;
        
        if ($enable_print_styles && is_singular('technical_sheet')) {
            ?>
            <style type="text/css" media="print">
                h1, h2, h3, h4, h5, h6 {
                    margin: 5px;
                }
                .ts-basic-info-table{
                    margin-top: 5px;
                }
                .ts-column-50 {
                    width: 50%;
                }
                table {
                    border: none !important;
                }
                .no-print, .site-header, .site-footer, .sidebar, .comments-area, .ts-download-section,.fusion-header, .fusion-footer, .fusion-page-title-bar, .fusion-sticky-header-wrapper {
                    display: none !important;
                }
                
                .ts-container {
                    width: 100% !important;
                    max-width: none !important;
                }
                
                .ts-section {
                    page-break-inside: avoid;
                    margin-bottom: 20px;
                }
                .ts-gallery-slider {
                    display:none;
                }
                
                .ts-gallery img {
                    display: none;
                    max-width: 100%;
                    height: auto;
                }
                
                .ts-basic-info-table {
                    border-collapse: collapse;
                    width: 100%;
                }
                
                .ts-basic-info-table th,
                .ts-basic-info-table td {
                    /*border: 1px solid #000;*/
                    padding: 8px;
                    text-align: left;
                }
                
                .ts-accordion{
                    border: none;
                }
                .ts-accordion-item{
                    border-bottom: 1px solid #e9ecef;
                    page-break-inside: avoid;
                }
                .ts-accordion-header{
                    padding: 10px;    
                }
                .ts-accordion-content {
                    padding: 10px;
                }
                .ts-accordion-text p {
                    margin: 0;
                }
                p{
                    font-size: 12px;
                }
                .avada-footer-scripts{
                    display: none;
                }
                .to-top-container{
                    display: none;
                }
                .ts-accordion-title {
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                html:not(.avada-has-site-width-percent) #main, html:not(.avada-has-site-width-percent) .fusion-footer-copyright-area, html:not(.avada-has-site-width-percent) .fusion-footer-widget-area, html:not(.avada-has-site-width-percent) .fusion-sliding-bar-position-bottom .fusion-sliding-bar, html:not(.avada-has-site-width-percent) .fusion-sliding-bar-position-top .fusion-sliding-bar, html:not(.avada-has-site-width-percent) .tfs-slider .slide-content-container{
                    padding:0 !important;
                }
                @page { margin: 0; }
                .no-print,.fusion-secondary-header { display: none !important; }
                .ts-accordion-content { display: block !important; }
                .ts-accordion-header { background: none !important; }
                .ts-accordion-icon { display: none !important; }
                .ts-gallery { display: grid !important; grid-template-columns: repeat(2, 1fr) !important; }
                .ts-basic-info-table { font-size: 12px !important; }
                .ts-section { page-break-inside: avoid; }
                h1, h2, h3 { page-break-after: avoid; }
            </style>
            <?php
        }
    }
    
    /**
     * Modify archive query to support filters
     */
    public function modify_archive_query($query) {
        // Only modify main query on archive pages and not in admin
        if (is_admin() || !$query->is_main_query()) {
            return;
        }
        
        // Only for technical sheets archive
        if (!is_post_type_archive('technical_sheet') && !is_tax(array('technical_sheet_type', 'technical_sheet_brand'))) {
            return;
        }
        
        // Handle taxonomy filters
        $tax_query = array();
        
        // Filter by type
        if (isset($_GET['technical_sheet_type']) && !empty($_GET['technical_sheet_type'])) {
            $type_slug = sanitize_text_field($_GET['technical_sheet_type']);
            $tax_query[] = array(
                'taxonomy' => 'technical_sheet_type',
                'field'    => 'slug',
                'terms'    => $type_slug,
            );
        }
        
        // Filter by brand
        if (isset($_GET['technical_sheet_brand']) && !empty($_GET['technical_sheet_brand'])) {
            $brand_slug = sanitize_text_field($_GET['technical_sheet_brand']);
            $tax_query[] = array(
                'taxonomy' => 'technical_sheet_brand',
                'field'    => 'slug',
                'terms'    => $brand_slug,
            );
        }
        
        // Apply taxonomy query if we have filters
        if (!empty($tax_query)) {
            if (count($tax_query) > 1) {
                $tax_query['relation'] = 'AND';
            }
            $query->set('tax_query', $tax_query);
        }
        
        // Handle search query
        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $search_query = sanitize_text_field($_GET['s']);
            $query->set('s', $search_query);
        }
        
        // Set posts per page for better performance
        $query->set('posts_per_page', 12);
    }
}
