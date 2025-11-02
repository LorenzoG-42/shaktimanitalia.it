<?php
/**
 * Technical Sheets Debug File
 * Remove this file in production
 */

// This file helps debug the plugin loading

if (!defined('ABSPATH')) {
    exit;
}

// Debug function to check plugin status
function ts_debug_plugin_status() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    echo '<div class="notice notice-info"><p>';
    echo '<strong>Technical Sheets Debug:</strong><br>';
    
    // Check if classes are loaded
    $classes = array(
        'TS_Post_Type',
        'TS_Taxonomies', 
        'TS_Meta_Boxes',
        'TS_Admin',
        'TS_Frontend',
        'TS_Shortcodes',
        'TS_PDF_Export'
    );
    
    foreach ($classes as $class) {
        if (class_exists($class)) {
            echo "✓ Class $class loaded<br>";
        } else {
            echo "✗ Class $class NOT loaded<br>";
        }
    }
    
    // Check if post type exists
    if (post_type_exists('technical_sheet')) {
        echo "✓ Post type 'technical_sheet' registered<br>";
    } else {
        echo "✗ Post type 'technical_sheet' NOT registered<br>";
    }
    
    // Check if taxonomies exist
    if (taxonomy_exists('technical_sheet_type')) {
        echo "✓ Taxonomy 'technical_sheet_type' registered<br>";
    } else {
        echo "✗ Taxonomy 'technical_sheet_type' NOT registered<br>";
    }
    
    if (taxonomy_exists('technical_sheet_brand')) {
        echo "✓ Taxonomy 'technical_sheet_brand' registered<br>";
    } else {
        echo "✗ Taxonomy 'technical_sheet_brand' NOT registered<br>";
    }
    
    // Check current action and hook info
    echo '<br>Current action: ' . current_action() . '<br>';
    echo 'Current hook: ' . current_filter() . '<br>';
    
    // Check if init action has been called
    if (did_action('init')) {
        echo '✓ Init action has been called (' . did_action('init') . ' times)<br>';
    } else {
        echo '✗ Init action has NOT been called<br>';
    }
    
    // Check WordPress version
    echo 'WordPress version: ' . get_bloginfo('version') . '<br>';
    
    // Check if WordPress is fully loaded
    if (function_exists('get_option') && function_exists('register_post_type')) {
        echo '✓ WordPress functions available<br>';
    } else {
        echo '✗ WordPress functions NOT available<br>';
    }
    
    // Check global variables
    global $wp_post_types, $wp_taxonomies;
    if (isset($wp_post_types['technical_sheet'])) {
        echo '✓ technical_sheet found in global $wp_post_types<br>';
    } else {
        echo '✗ technical_sheet NOT found in global $wp_post_types<br>';
    }
    
    if (isset($wp_taxonomies['technical_sheet_type'])) {
        echo '✓ technical_sheet_type found in global $wp_taxonomies<br>';
    } else {
        echo '✗ technical_sheet_type NOT found in global $wp_taxonomies<br>';
    }
    
    echo '</p></div>';
}

// Only show debug in admin area
if (is_admin()) {
   // add_action('admin_notices', 'ts_debug_plugin_status');
}
