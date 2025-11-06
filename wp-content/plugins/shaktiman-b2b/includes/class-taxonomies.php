<?php
/**
 * Gestione delle Tassonomie
 *
 * @package ShaktimanB2B
 */

// Se questo file viene chiamato direttamente, esce
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Classe per le Tassonomie
 */
class Shaktiman_B2B_Taxonomies {
    
    /**
     * Istanza singleton
     */
    private static $instance = null;
    
    /**
     * Restituisce l'istanza singleton
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Costruttore
     */
    private function __construct() {
        add_action( 'init', array( $this, 'register_taxonomies' ) );
    }
    
    /**
     * Registra tutte le tassonomie
     */
    public function register_taxonomies() {
        $this->register_disponibilita();
        $this->register_categoria();
        $this->register_marchio();
        $this->register_ubicazione();
        $this->register_stato_magazzino();
    }
    
    /**
     * Tassonomia Disponibilità
     */
    private function register_disponibilita() {
        $labels = array(
            'name'                       => _x( 'Disponibilità', 'Taxonomy General Name', 'shaktiman-b2b' ),
            'singular_name'              => _x( 'Disponibilità', 'Taxonomy Singular Name', 'shaktiman-b2b' ),
            'menu_name'                  => __( 'Disponibilità', 'shaktiman-b2b' ),
            'all_items'                  => __( 'Tutte le Disponibilità', 'shaktiman-b2b' ),
            'parent_item'                => __( 'Disponibilità Padre', 'shaktiman-b2b' ),
            'parent_item_colon'          => __( 'Disponibilità Padre:', 'shaktiman-b2b' ),
            'new_item_name'              => __( 'Nuova Disponibilità', 'shaktiman-b2b' ),
            'add_new_item'               => __( 'Aggiungi Nuova Disponibilità', 'shaktiman-b2b' ),
            'edit_item'                  => __( 'Modifica Disponibilità', 'shaktiman-b2b' ),
            'update_item'                => __( 'Aggiorna Disponibilità', 'shaktiman-b2b' ),
            'view_item'                  => __( 'Visualizza Disponibilità', 'shaktiman-b2b' ),
            'separate_items_with_commas' => __( 'Separa le disponibilità con virgole', 'shaktiman-b2b' ),
            'add_or_remove_items'        => __( 'Aggiungi o rimuovi disponibilità', 'shaktiman-b2b' ),
            'choose_from_most_used'      => __( 'Scegli dalle più usate', 'shaktiman-b2b' ),
            'popular_items'              => __( 'Disponibilità Popolari', 'shaktiman-b2b' ),
            'search_items'               => __( 'Cerca Disponibilità', 'shaktiman-b2b' ),
            'not_found'                  => __( 'Non Trovato', 'shaktiman-b2b' ),
            'no_terms'                   => __( 'Nessuna Disponibilità', 'shaktiman-b2b' ),
            'items_list'                 => __( 'Elenco Disponibilità', 'shaktiman-b2b' ),
            'items_list_navigation'      => __( 'Navigazione Elenco Disponibilità', 'shaktiman-b2b' ),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'show_in_rest'               => true,
            'rewrite'                    => array( 'slug' => 'disponibilita' ),
        );
        
        register_taxonomy( 'disponibilita', array( 'mezzo_agricolo' ), $args );
        
        // Crea i termini predefiniti per la disponibilità
        $this->create_default_disponibilita_terms();
    }
    
    /**
     * Crea i termini predefiniti per la disponibilità
     */
    private function create_default_disponibilita_terms() {
        $terms = array(
            'disponibile' => __( 'Disponibile', 'shaktiman-b2b' ),
            'riservato'   => __( 'Riservato', 'shaktiman-b2b' ),
            'venduto'     => __( 'Venduto', 'shaktiman-b2b' ),
        );
        
        foreach ( $terms as $slug => $name ) {
            if ( ! term_exists( $slug, 'disponibilita' ) ) {
                wp_insert_term( $name, 'disponibilita', array( 'slug' => $slug ) );
            }
        }
    }
    
    /**
     * Tassonomia Categoria
     */
    private function register_categoria() {
        $labels = array(
            'name'                       => _x( 'Categorie', 'Taxonomy General Name', 'shaktiman-b2b' ),
            'singular_name'              => _x( 'Categoria', 'Taxonomy Singular Name', 'shaktiman-b2b' ),
            'menu_name'                  => __( 'Categorie', 'shaktiman-b2b' ),
            'all_items'                  => __( 'Tutte le Categorie', 'shaktiman-b2b' ),
            'parent_item'                => __( 'Categoria Padre', 'shaktiman-b2b' ),
            'parent_item_colon'          => __( 'Categoria Padre:', 'shaktiman-b2b' ),
            'new_item_name'              => __( 'Nuova Categoria', 'shaktiman-b2b' ),
            'add_new_item'               => __( 'Aggiungi Nuova Categoria', 'shaktiman-b2b' ),
            'edit_item'                  => __( 'Modifica Categoria', 'shaktiman-b2b' ),
            'update_item'                => __( 'Aggiorna Categoria', 'shaktiman-b2b' ),
            'view_item'                  => __( 'Visualizza Categoria', 'shaktiman-b2b' ),
            'separate_items_with_commas' => __( 'Separa le categorie con virgole', 'shaktiman-b2b' ),
            'add_or_remove_items'        => __( 'Aggiungi o rimuovi categorie', 'shaktiman-b2b' ),
            'choose_from_most_used'      => __( 'Scegli dalle più usate', 'shaktiman-b2b' ),
            'popular_items'              => __( 'Categorie Popolari', 'shaktiman-b2b' ),
            'search_items'               => __( 'Cerca Categoria', 'shaktiman-b2b' ),
            'not_found'                  => __( 'Non Trovato', 'shaktiman-b2b' ),
            'no_terms'                   => __( 'Nessuna Categoria', 'shaktiman-b2b' ),
            'items_list'                 => __( 'Elenco Categorie', 'shaktiman-b2b' ),
            'items_list_navigation'      => __( 'Navigazione Elenco Categorie', 'shaktiman-b2b' ),
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
            'rewrite'                    => array( 'slug' => 'categoria-mezzo' ),
        );
        
        register_taxonomy( 'categoria_mezzo', array( 'mezzo_agricolo' ), $args );
    }
    
    /**
     * Tassonomia Marchio
     */
    private function register_marchio() {
        $labels = array(
            'name'                       => _x( 'Marchi', 'Taxonomy General Name', 'shaktiman-b2b' ),
            'singular_name'              => _x( 'Marchio', 'Taxonomy Singular Name', 'shaktiman-b2b' ),
            'menu_name'                  => __( 'Marchi', 'shaktiman-b2b' ),
            'all_items'                  => __( 'Tutti i Marchi', 'shaktiman-b2b' ),
            'parent_item'                => __( 'Marchio Padre', 'shaktiman-b2b' ),
            'parent_item_colon'          => __( 'Marchio Padre:', 'shaktiman-b2b' ),
            'new_item_name'              => __( 'Nuovo Marchio', 'shaktiman-b2b' ),
            'add_new_item'               => __( 'Aggiungi Nuovo Marchio', 'shaktiman-b2b' ),
            'edit_item'                  => __( 'Modifica Marchio', 'shaktiman-b2b' ),
            'update_item'                => __( 'Aggiorna Marchio', 'shaktiman-b2b' ),
            'view_item'                  => __( 'Visualizza Marchio', 'shaktiman-b2b' ),
            'separate_items_with_commas' => __( 'Separa i marchi con virgole', 'shaktiman-b2b' ),
            'add_or_remove_items'        => __( 'Aggiungi o rimuovi marchi', 'shaktiman-b2b' ),
            'choose_from_most_used'      => __( 'Scegli dai più usati', 'shaktiman-b2b' ),
            'popular_items'              => __( 'Marchi Popolari', 'shaktiman-b2b' ),
            'search_items'               => __( 'Cerca Marchio', 'shaktiman-b2b' ),
            'not_found'                  => __( 'Non Trovato', 'shaktiman-b2b' ),
            'no_terms'                   => __( 'Nessun Marchio', 'shaktiman-b2b' ),
            'items_list'                 => __( 'Elenco Marchi', 'shaktiman-b2b' ),
            'items_list_navigation'      => __( 'Navigazione Elenco Marchi', 'shaktiman-b2b' ),
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
            'rewrite'                    => array( 'slug' => 'marchio' ),
        );
        
        register_taxonomy( 'marchio', array( 'mezzo_agricolo' ), $args );
    }
    
    /**
     * Tassonomia Ubicazione
     */
    private function register_ubicazione() {
        $labels = array(
            'name'                       => _x( 'Ubicazioni', 'Taxonomy General Name', 'shaktiman-b2b' ),
            'singular_name'              => _x( 'Ubicazione', 'Taxonomy Singular Name', 'shaktiman-b2b' ),
            'menu_name'                  => __( 'Ubicazioni', 'shaktiman-b2b' ),
            'all_items'                  => __( 'Tutte le Ubicazioni', 'shaktiman-b2b' ),
            'parent_item'                => __( 'Ubicazione Padre', 'shaktiman-b2b' ),
            'parent_item_colon'          => __( 'Ubicazione Padre:', 'shaktiman-b2b' ),
            'new_item_name'              => __( 'Nuova Ubicazione', 'shaktiman-b2b' ),
            'add_new_item'               => __( 'Aggiungi Nuova Ubicazione', 'shaktiman-b2b' ),
            'edit_item'                  => __( 'Modifica Ubicazione', 'shaktiman-b2b' ),
            'update_item'                => __( 'Aggiorna Ubicazione', 'shaktiman-b2b' ),
            'view_item'                  => __( 'Visualizza Ubicazione', 'shaktiman-b2b' ),
            'separate_items_with_commas' => __( 'Separa le ubicazioni con virgole', 'shaktiman-b2b' ),
            'add_or_remove_items'        => __( 'Aggiungi o rimuovi ubicazioni', 'shaktiman-b2b' ),
            'choose_from_most_used'      => __( 'Scegli dalle più usate', 'shaktiman-b2b' ),
            'popular_items'              => __( 'Ubicazioni Popolari', 'shaktiman-b2b' ),
            'search_items'               => __( 'Cerca Ubicazione', 'shaktiman-b2b' ),
            'not_found'                  => __( 'Non Trovato', 'shaktiman-b2b' ),
            'no_terms'                   => __( 'Nessuna Ubicazione', 'shaktiman-b2b' ),
            'items_list'                 => __( 'Elenco Ubicazioni', 'shaktiman-b2b' ),
            'items_list_navigation'      => __( 'Navigazione Elenco Ubicazioni', 'shaktiman-b2b' ),
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
            'rewrite'                    => array( 'slug' => 'ubicazione' ),
        );
        
        register_taxonomy( 'ubicazione', array( 'mezzo_agricolo' ), $args );
    }
    
    /**
     * Tassonomia Stato Magazzino
     */
    private function register_stato_magazzino() {
        $labels = array(
            'name'                       => _x( 'Stati Magazzino', 'Taxonomy General Name', 'shaktiman-b2b' ),
            'singular_name'              => _x( 'Stato Magazzino', 'Taxonomy Singular Name', 'shaktiman-b2b' ),
            'menu_name'                  => __( 'Stato Magazzino', 'shaktiman-b2b' ),
            'all_items'                  => __( 'Tutti gli Stati', 'shaktiman-b2b' ),
            'parent_item'                => __( 'Stato Padre', 'shaktiman-b2b' ),
            'parent_item_colon'          => __( 'Stato Padre:', 'shaktiman-b2b' ),
            'new_item_name'              => __( 'Nuovo Stato', 'shaktiman-b2b' ),
            'add_new_item'               => __( 'Aggiungi Nuovo Stato', 'shaktiman-b2b' ),
            'edit_item'                  => __( 'Modifica Stato', 'shaktiman-b2b' ),
            'update_item'                => __( 'Aggiorna Stato', 'shaktiman-b2b' ),
            'view_item'                  => __( 'Visualizza Stato', 'shaktiman-b2b' ),
            'separate_items_with_commas' => __( 'Separa gli stati con virgole', 'shaktiman-b2b' ),
            'add_or_remove_items'        => __( 'Aggiungi o rimuovi stati', 'shaktiman-b2b' ),
            'choose_from_most_used'      => __( 'Scegli dai più usati', 'shaktiman-b2b' ),
            'popular_items'              => __( 'Stati Popolari', 'shaktiman-b2b' ),
            'search_items'               => __( 'Cerca Stato', 'shaktiman-b2b' ),
            'not_found'                  => __( 'Non Trovato', 'shaktiman-b2b' ),
            'no_terms'                   => __( 'Nessuno Stato', 'shaktiman-b2b' ),
            'items_list'                 => __( 'Elenco Stati', 'shaktiman-b2b' ),
            'items_list_navigation'      => __( 'Navigazione Elenco Stati', 'shaktiman-b2b' ),
        );
        
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => false,
            'show_in_rest'               => true,
            'rewrite'                    => array( 'slug' => 'stato-magazzino' ),
        );
        
        register_taxonomy( 'stato_magazzino', array( 'mezzo_agricolo' ), $args );
        
        // Crea i termini predefiniti
        $this->create_default_stato_magazzino_terms();
    }
    
    /**
     * Crea i termini predefiniti per stato magazzino
     */
    private function create_default_stato_magazzino_terms() {
        $terms = array(
            'in-arrivo'     => __( 'In Arrivo', 'shaktiman-b2b' ),
            'in-magazzino'  => __( 'In Magazzino', 'shaktiman-b2b' ),
        );
        
        foreach ( $terms as $slug => $name ) {
            if ( ! term_exists( $slug, 'stato_magazzino' ) ) {
                wp_insert_term( $name, 'stato_magazzino', array( 'slug' => $slug ) );
            }
        }
    }
}
