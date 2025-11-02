<?php
/**
 * Taxonomies Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Taxonomies {
    
    public function __construct() {
        add_action('init', array($this, 'register_taxonomies'), 10);
    }
    
    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Debug: Check if this function is called
        if (defined('WP_DEBUG') && WP_DEBUG) {
            //error_log('TS_Taxonomies::register_taxonomies() called');
        }
        
        $this->register_type_taxonomy();
        $this->register_brand_taxonomy();
    }
    
    /**
     * Register Type taxonomy
     */
    private function register_type_taxonomy() {
        $labels = array(
            'name'                       => _x('Types', 'Taxonomy General Name', 'technical-sheets'),
            'singular_name'              => _x('Type', 'Taxonomy Singular Name', 'technical-sheets'),
            'menu_name'                  => __('Types', 'technical-sheets'),
            'all_items'                  => __('All Types', 'technical-sheets'),
            'parent_item'                => __('Parent Type', 'technical-sheets'),
            'parent_item_colon'          => __('Parent Type:', 'technical-sheets'),
            'new_item_name'              => __('New Type Name', 'technical-sheets'),
            'add_new_item'               => __('Add New Type', 'technical-sheets'),
            'edit_item'                  => __('Edit Type', 'technical-sheets'),
            'update_item'                => __('Update Type', 'technical-sheets'),
            'view_item'                  => __('View Type', 'technical-sheets'),
            'separate_items_with_commas' => __('Separate types with commas', 'technical-sheets'),
            'add_or_remove_items'        => __('Add or remove types', 'technical-sheets'),
            'choose_from_most_used'      => __('Choose from the most used', 'technical-sheets'),
            'popular_items'              => __('Popular Types', 'technical-sheets'),
            'search_items'               => __('Search Types', 'technical-sheets'),
            'not_found'                  => __('Not Found', 'technical-sheets'),
            'no_terms'                   => __('No types', 'technical-sheets'),
            'items_list'                 => __('Types list', 'technical-sheets'),
            'items_list_navigation'      => __('Types list navigation', 'technical-sheets'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'technical-sheet-type'),
        );
        
        $result = register_taxonomy('technical_sheet_type', array('technical_sheet'), $args);
        
        // Debug: Check if registration was successful
        if (defined('WP_DEBUG') && WP_DEBUG) {
            if (is_wp_error($result)) {
                //error_log('TS_Taxonomies type registration failed: ' . $result->get_error_message());
            } else {
                //error_log('TS_Taxonomies type registration successful');
            }
        }
    }
    
    /**
     * Register Brand taxonomy
     */
    private function register_brand_taxonomy() {
        $labels = array(
            'name'                       => _x('Brands', 'Taxonomy General Name', 'technical-sheets'),
            'singular_name'              => _x('Brand', 'Taxonomy Singular Name', 'technical-sheets'),
            'menu_name'                  => __('Brands', 'technical-sheets'),
            'all_items'                  => __('All Brands', 'technical-sheets'),
            'parent_item'                => __('Parent Brand', 'technical-sheets'),
            'parent_item_colon'          => __('Parent Brand:', 'technical-sheets'),
            'new_item_name'              => __('New Brand Name', 'technical-sheets'),
            'add_new_item'               => __('Add New Brand', 'technical-sheets'),
            'edit_item'                  => __('Edit Brand', 'technical-sheets'),
            'update_item'                => __('Update Brand', 'technical-sheets'),
            'view_item'                  => __('View Brand', 'technical-sheets'),
            'separate_items_with_commas' => __('Separate brands with commas', 'technical-sheets'),
            'add_or_remove_items'        => __('Add or remove brands', 'technical-sheets'),
            'choose_from_most_used'      => __('Choose from the most used', 'technical-sheets'),
            'popular_items'              => __('Popular Brands', 'technical-sheets'),
            'search_items'               => __('Search Brands', 'technical-sheets'),
            'not_found'                  => __('Not Found', 'technical-sheets'),
            'no_terms'                   => __('No brands', 'technical-sheets'),
            'items_list'                 => __('Brands list', 'technical-sheets'),
            'items_list_navigation'      => __('Brands list navigation', 'technical-sheets'),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
            'rewrite'                    => array('slug' => 'technical-sheet-brand'),
        );
        
        $result = register_taxonomy('technical_sheet_brand', array('technical_sheet'), $args);
        
        // Debug: Check if registration was successful
        if (defined('WP_DEBUG') && WP_DEBUG) {
            if (is_wp_error($result)) {
                //error_log('TS_Taxonomies brand registration failed: ' . $result->get_error_message());
            } else {
                //error_log('TS_Taxonomies brand registration successful');
            }
        }
    }
}
