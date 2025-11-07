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
        
        $this->register_category_taxonomy();
        $this->register_model_taxonomy();
        $this->register_version_taxonomy();
    }
    
    /**
     * Register Category taxonomy
     */
    private function register_category_taxonomy() {
        $labels = array(
            'name'                       => _x('Categorie', 'Taxonomy General Name', 'technical-sheets'),
            'singular_name'              => _x('Categoria', 'Taxonomy Singular Name', 'technical-sheets'),
            'menu_name'                  => __('Categorie', 'technical-sheets'),
            'all_items'                  => __('Tutte le Categorie', 'technical-sheets'),
            'parent_item'                => __('Categoria Padre', 'technical-sheets'),
            'parent_item_colon'          => __('Categoria Padre:', 'technical-sheets'),
            'new_item_name'              => __('Nuova Categoria', 'technical-sheets'),
            'add_new_item'               => __('Aggiungi Nuova Categoria', 'technical-sheets'),
            'edit_item'                  => __('Modifica Categoria', 'technical-sheets'),
            'update_item'                => __('Aggiorna Categoria', 'technical-sheets'),
            'view_item'                  => __('Visualizza Categoria', 'technical-sheets'),
            'separate_items_with_commas' => __('Separa le categorie con virgole', 'technical-sheets'),
            'add_or_remove_items'        => __('Aggiungi o rimuovi categorie', 'technical-sheets'),
            'choose_from_most_used'      => __('Scegli dalle più usate', 'technical-sheets'),
            'popular_items'              => __('Categorie Popolari', 'technical-sheets'),
            'search_items'               => __('Cerca Categoria', 'technical-sheets'),
            'not_found'                  => __('Non Trovato', 'technical-sheets'),
            'no_terms'                   => __('Nessuna Categoria', 'technical-sheets'),
            'items_list'                 => __('Elenco Categorie', 'technical-sheets'),
            'items_list_navigation'      => __('Navigazione Elenco Categorie', 'technical-sheets'),
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
            'rewrite'                    => array('slug' => 'categoria-scheda'),
        );
        
        $result = register_taxonomy('technical_sheet_category', array('technical_sheet'), $args);
        
        // Debug: Check if registration was successful
        if (defined('WP_DEBUG') && WP_DEBUG) {
            if (is_wp_error($result)) {
                //error_log('TS_Taxonomies category registration failed: ' . $result->get_error_message());
            } else {
                //error_log('TS_Taxonomies category registration successful');
            }
        }
    }
    
    /**
     * Register Model taxonomy
     */
    private function register_model_taxonomy() {
        $labels = array(
            'name'                       => _x('Modelli', 'Taxonomy General Name', 'technical-sheets'),
            'singular_name'              => _x('Modello', 'Taxonomy Singular Name', 'technical-sheets'),
            'menu_name'                  => __('Modelli', 'technical-sheets'),
            'all_items'                  => __('Tutti i Modelli', 'technical-sheets'),
            'parent_item'                => __('Modello Padre', 'technical-sheets'),
            'parent_item_colon'          => __('Modello Padre:', 'technical-sheets'),
            'new_item_name'              => __('Nuovo Modello', 'technical-sheets'),
            'add_new_item'               => __('Aggiungi Nuovo Modello', 'technical-sheets'),
            'edit_item'                  => __('Modifica Modello', 'technical-sheets'),
            'update_item'                => __('Aggiorna Modello', 'technical-sheets'),
            'view_item'                  => __('Visualizza Modello', 'technical-sheets'),
            'separate_items_with_commas' => __('Separa i modelli con virgole', 'technical-sheets'),
            'add_or_remove_items'        => __('Aggiungi o rimuovi modelli', 'technical-sheets'),
            'choose_from_most_used'      => __('Scegli dai più usati', 'technical-sheets'),
            'popular_items'              => __('Modelli Popolari', 'technical-sheets'),
            'search_items'               => __('Cerca Modello', 'technical-sheets'),
            'not_found'                  => __('Non Trovato', 'technical-sheets'),
            'no_terms'                   => __('Nessun Modello', 'technical-sheets'),
            'items_list'                 => __('Elenco Modelli', 'technical-sheets'),
            'items_list_navigation'      => __('Navigazione Elenco Modelli', 'technical-sheets'),
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
            'rewrite'                    => array('slug' => 'modello-scheda'),
        );
        
        $result = register_taxonomy('technical_sheet_model', array('technical_sheet'), $args);
        
        // Debug: Check if registration was successful
        if (defined('WP_DEBUG') && WP_DEBUG) {
            if (is_wp_error($result)) {
                //error_log('TS_Taxonomies model registration failed: ' . $result->get_error_message());
            } else {
                //error_log('TS_Taxonomies model registration successful');
            }
        }
    }
    
    /**
     * Register Version taxonomy
     */
    private function register_version_taxonomy() {
        $labels = array(
            'name'                       => _x('Versioni', 'Taxonomy General Name', 'technical-sheets'),
            'singular_name'              => _x('Versione', 'Taxonomy Singular Name', 'technical-sheets'),
            'menu_name'                  => __('Versioni', 'technical-sheets'),
            'all_items'                  => __('Tutte le Versioni', 'technical-sheets'),
            'parent_item'                => __('Versione Padre', 'technical-sheets'),
            'parent_item_colon'          => __('Versione Padre:', 'technical-sheets'),
            'new_item_name'              => __('Nuova Versione', 'technical-sheets'),
            'add_new_item'               => __('Aggiungi Nuova Versione', 'technical-sheets'),
            'edit_item'                  => __('Modifica Versione', 'technical-sheets'),
            'update_item'                => __('Aggiorna Versione', 'technical-sheets'),
            'view_item'                  => __('Visualizza Versione', 'technical-sheets'),
            'separate_items_with_commas' => __('Separa le versioni con virgole', 'technical-sheets'),
            'add_or_remove_items'        => __('Aggiungi o rimuovi versioni', 'technical-sheets'),
            'choose_from_most_used'      => __('Scegli dalle più usate', 'technical-sheets'),
            'popular_items'              => __('Versioni Popolari', 'technical-sheets'),
            'search_items'               => __('Cerca Versione', 'technical-sheets'),
            'not_found'                  => __('Non Trovato', 'technical-sheets'),
            'no_terms'                   => __('Nessuna Versione', 'technical-sheets'),
            'items_list'                 => __('Elenco Versioni', 'technical-sheets'),
            'items_list_navigation'      => __('Navigazione Elenco Versioni', 'technical-sheets'),
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
            'rewrite'                    => array('slug' => 'versione-scheda'),
        );
        
        $result = register_taxonomy('technical_sheet_version', array('technical_sheet'), $args);
        
        // Debug: Check if registration was successful
        if (defined('WP_DEBUG') && WP_DEBUG) {
            if (is_wp_error($result)) {
                //error_log('TS_Taxonomies version registration failed: ' . $result->get_error_message());
            } else {
                //error_log('TS_Taxonomies version registration successful');
            }
        }
    }
}
