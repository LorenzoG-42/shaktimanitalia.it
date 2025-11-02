<?php
/**
 * Custom Post Type Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Post_Type {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_type'), 10);
        add_filter('single_template', array($this, 'single_template'));
        add_filter('archive_template', array($this, 'archive_template'));
    }
    
    /**
     * Register Technical Sheets post type
     */
    public function register_post_type() {
        // Debug: Check if this function is called
        if (defined('WP_DEBUG') && WP_DEBUG) {
           // error_log('TS_Post_Type::register_post_type() called');
        }
        
        $labels = array(
            'name'                  => _x('Technical Sheets', 'Post Type General Name', 'technical-sheets'),
            'singular_name'         => _x('Technical Sheet', 'Post Type Singular Name', 'technical-sheets'),
            'menu_name'             => __('Technical Sheets', 'technical-sheets'),
            'name_admin_bar'        => __('Technical Sheet', 'technical-sheets'),
            'archives'              => __('Technical Sheets Archives', 'technical-sheets'),
            'attributes'            => __('Technical Sheet Attributes', 'technical-sheets'),
            'parent_item_colon'     => __('Parent Technical Sheet:', 'technical-sheets'),
            'all_items'             => __('All Technical Sheets', 'technical-sheets'),
            'add_new_item'          => __('Add New Technical Sheet', 'technical-sheets'),
            'add_new'               => __('Add New', 'technical-sheets'),
            'new_item'              => __('New Technical Sheet', 'technical-sheets'),
            'edit_item'             => __('Edit Technical Sheet', 'technical-sheets'),
            'update_item'           => __('Update Technical Sheet', 'technical-sheets'),
            'view_item'             => __('View Technical Sheet', 'technical-sheets'),
            'view_items'            => __('View Technical Sheets', 'technical-sheets'),
            'search_items'          => __('Search Technical Sheets', 'technical-sheets'),
            'not_found'             => __('Not found', 'technical-sheets'),
            'not_found_in_trash'    => __('Not found in Trash', 'technical-sheets'),
            'featured_image'        => __('Featured Image', 'technical-sheets'),
            'set_featured_image'    => __('Set featured image', 'technical-sheets'),
            'remove_featured_image' => __('Remove featured image', 'technical-sheets'),
            'use_featured_image'    => __('Use as featured image', 'technical-sheets'),
            'insert_into_item'      => __('Insert into technical sheet', 'technical-sheets'),
            'uploaded_to_this_item' => __('Uploaded to this technical sheet', 'technical-sheets'),
            'items_list'            => __('Technical Sheets list', 'technical-sheets'),
            'items_list_navigation' => __('Technical Sheets list navigation', 'technical-sheets'),
            'filter_items_list'     => __('Filter technical sheets list', 'technical-sheets'),
        );
        
        $args = array(
            'label'                 => __('Technical Sheet', 'technical-sheets'),
            'description'           => __('Technical Sheets for products', 'technical-sheets'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
            'taxonomies'            => array('technical_sheet_type', 'technical_sheet_brand'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 20,
            'menu_icon'             => 'dashicons-media-document',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => array('slug' => 'technical-sheets'),
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );
        
        $result = register_post_type('technical_sheet', $args);
        
        // Debug: Check if registration was successful
        if (defined('WP_DEBUG') && WP_DEBUG) {
            if (is_wp_error($result)) {
                //error_log('TS_Post_Type registration failed: ' . $result->get_error_message());
            } else {
                //error_log('TS_Post_Type registration successful');
            }
        }
    }
    
    /**
     * Load single template
     */
    public function single_template($single_template) {
        global $post;
        
        if ($post->post_type == 'technical_sheet') {
            $single_template = TECHNICAL_SHEETS_PLUGIN_PATH . 'templates/single-technical-sheet.php';
            if (!file_exists($single_template)) {
                $single_template = get_template_directory() . '/single-technical-sheet.php';
            }
        }
        
        return $single_template;
    }
    
    /**
     * Load archive template
     */
    public function archive_template($archive_template) {
        if (is_post_type_archive('technical_sheet')) {
            $archive_template = TECHNICAL_SHEETS_PLUGIN_PATH . 'templates/archive-technical-sheet.php';
            if (!file_exists($archive_template)) {
                $archive_template = get_template_directory() . '/archive-technical-sheet.php';
            }
        }
        
        return $archive_template;
    }
}
