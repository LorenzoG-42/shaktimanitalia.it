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
            'name'                  => _x('Schede Tecniche', 'Post Type General Name', 'technical-sheets'),
            'singular_name'         => _x('Scheda Tecnica', 'Post Type Singular Name', 'technical-sheets'),
            'menu_name'             => __('Schede Tecniche', 'technical-sheets'),
            'name_admin_bar'        => __('Scheda Tecnica', 'technical-sheets'),
            'archives'              => __('Archivio Schede Tecniche', 'technical-sheets'),
            'attributes'            => __('Attributi Scheda Tecnica', 'technical-sheets'),
            'parent_item_colon'     => __('Scheda Tecnica Padre:', 'technical-sheets'),
            'all_items'             => __('Tutte le Schede Tecniche', 'technical-sheets'),
            'add_new_item'          => __('Aggiungi Nuova Scheda Tecnica', 'technical-sheets'),
            'add_new'               => __('Aggiungi Nuova', 'technical-sheets'),
            'new_item'              => __('Nuova Scheda Tecnica', 'technical-sheets'),
            'edit_item'             => __('Modifica Scheda Tecnica', 'technical-sheets'),
            'update_item'           => __('Aggiorna Scheda Tecnica', 'technical-sheets'),
            'view_item'             => __('Visualizza Scheda Tecnica', 'technical-sheets'),
            'view_items'            => __('Visualizza Schede Tecniche', 'technical-sheets'),
            'search_items'          => __('Cerca Schede Tecniche', 'technical-sheets'),
            'not_found'             => __('Non trovato', 'technical-sheets'),
            'not_found_in_trash'    => __('Non trovato nel cestino', 'technical-sheets'),
            'featured_image'        => __('Immagine in evidenza', 'technical-sheets'),
            'set_featured_image'    => __('Imposta immagine in evidenza', 'technical-sheets'),
            'remove_featured_image' => __('Rimuovi immagine in evidenza', 'technical-sheets'),
            'use_featured_image'    => __('Usa come immagine in evidenza', 'technical-sheets'),
            'insert_into_item'      => __('Inserisci nella scheda tecnica', 'technical-sheets'),
            'uploaded_to_this_item' => __('Caricato in questa scheda tecnica', 'technical-sheets'),
            'items_list'            => __('Elenco Schede Tecniche', 'technical-sheets'),
            'items_list_navigation' => __('Navigazione Elenco Schede Tecniche', 'technical-sheets'),
            'filter_items_list'     => __('Filtra elenco schede tecniche', 'technical-sheets'),
        );
        
        $args = array(
            'label'                 => __('Scheda Tecnica', 'technical-sheets'),
            'description'           => __('Schede Tecniche per prodotti', 'technical-sheets'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
            'taxonomies'            => array('technical_sheet_category', 'technical_sheet_model', 'technical_sheet_version'),
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
            'rewrite'               => array('slug' => 'schede-tecniche'),
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
