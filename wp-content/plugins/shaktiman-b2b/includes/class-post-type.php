<?php
/**
 * Gestione del Custom Post Type Mezzi Agricoli
 *
 * @package ShaktimanB2B
 */

// Se questo file viene chiamato direttamente, esce
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Classe per il Custom Post Type
 */
class Shaktiman_B2B_Post_Type {
    
    /**
     * Istanza singleton
     */
    private static $instance = null;
    
    /**
     * Nome del post type
     */
    const POST_TYPE = 'mezzo_agricolo';
    
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
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );
        add_action( 'admin_menu', array( $this, 'remove_add_new_for_rivenditori' ) );
    }
    
    /**
     * Registra il Custom Post Type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Mezzi Agricoli', 'Post Type General Name', 'shaktiman-b2b' ),
            'singular_name'         => _x( 'Mezzo Agricolo', 'Post Type Singular Name', 'shaktiman-b2b' ),
            'menu_name'             => __( 'Mezzi Agricoli B2B', 'shaktiman-b2b' ),
            'name_admin_bar'        => __( 'Mezzo Agricolo', 'shaktiman-b2b' ),
            'archives'              => __( 'Archivio Mezzi', 'shaktiman-b2b' ),
            'attributes'            => __( 'Attributi Mezzo', 'shaktiman-b2b' ),
            'parent_item_colon'     => __( 'Mezzo Padre:', 'shaktiman-b2b' ),
            'all_items'             => __( 'Tutti i Mezzi', 'shaktiman-b2b' ),
            'add_new_item'          => __( 'Aggiungi Nuovo Mezzo', 'shaktiman-b2b' ),
            'add_new'               => __( 'Aggiungi Nuovo', 'shaktiman-b2b' ),
            'new_item'              => __( 'Nuovo Mezzo', 'shaktiman-b2b' ),
            'edit_item'             => __( 'Modifica Mezzo', 'shaktiman-b2b' ),
            'update_item'           => __( 'Aggiorna Mezzo', 'shaktiman-b2b' ),
            'view_item'             => __( 'Visualizza Mezzo', 'shaktiman-b2b' ),
            'view_items'            => __( 'Visualizza Mezzi', 'shaktiman-b2b' ),
            'search_items'          => __( 'Cerca Mezzo', 'shaktiman-b2b' ),
            'not_found'             => __( 'Non trovato', 'shaktiman-b2b' ),
            'not_found_in_trash'    => __( 'Non trovato nel Cestino', 'shaktiman-b2b' ),
            'featured_image'        => __( 'Immagine in evidenza', 'shaktiman-b2b' ),
            'set_featured_image'    => __( 'Imposta immagine in evidenza', 'shaktiman-b2b' ),
            'remove_featured_image' => __( 'Rimuovi immagine in evidenza', 'shaktiman-b2b' ),
            'use_featured_image'    => __( 'Usa come immagine in evidenza', 'shaktiman-b2b' ),
            'insert_into_item'      => __( 'Inserisci nel mezzo', 'shaktiman-b2b' ),
            'uploaded_to_this_item' => __( 'Caricato in questo mezzo', 'shaktiman-b2b' ),
            'items_list'            => __( 'Elenco mezzi', 'shaktiman-b2b' ),
            'items_list_navigation' => __( 'Navigazione elenco mezzi', 'shaktiman-b2b' ),
            'filter_items_list'     => __( 'Filtra elenco mezzi', 'shaktiman-b2b' ),
        );
        
        $args = array(
            'label'                 => __( 'Mezzo Agricolo', 'shaktiman-b2b' ),
            'description'           => __( 'Mezzi agricoli per la sezione B2B', 'shaktiman-b2b' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
            'taxonomies'            => array(),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 26,
            'menu_icon'             => 'dashicons-admin-multisite',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => 'mezzi-agricoli-b2b',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'rewrite'               => array(
                'slug'       => 'mezzo-agricolo',
                'with_front' => false,
            ),
            'capability_type'       => 'post',
            'map_meta_cap'          => true,
            'show_in_rest'          => true,
        );
        
        register_post_type( self::POST_TYPE, $args );
    }
    
    /**
     * Messaggi personalizzati di aggiornamento
     */
    public function updated_messages( $messages ) {
        global $post, $post_ID;
        
        $messages[ self::POST_TYPE ] = array(
            0  => '', // Non utilizzato
            1  => sprintf( __( 'Mezzo agricolo aggiornato. <a href="%s">Visualizza mezzo</a>', 'shaktiman-b2b' ), esc_url( get_permalink( $post_ID ) ) ),
            2  => __( 'Campo personalizzato aggiornato.', 'shaktiman-b2b' ),
            3  => __( 'Campo personalizzato eliminato.', 'shaktiman-b2b' ),
            4  => __( 'Mezzo agricolo aggiornato.', 'shaktiman-b2b' ),
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Mezzo agricolo ripristinato dalla revisione del %s', 'shaktiman-b2b' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => sprintf( __( 'Mezzo agricolo pubblicato. <a href="%s">Visualizza mezzo</a>', 'shaktiman-b2b' ), esc_url( get_permalink( $post_ID ) ) ),
            7  => __( 'Mezzo agricolo salvato.', 'shaktiman-b2b' ),
            8  => sprintf( __( 'Mezzo agricolo inviato. <a target="_blank" href="%s">Anteprima mezzo</a>', 'shaktiman-b2b' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
            9  => sprintf( __( 'Mezzo agricolo programmato per: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Anteprima mezzo</a>', 'shaktiman-b2b' ), date_i18n( __( 'M j, Y @ G:i', 'shaktiman-b2b' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
            10 => sprintf( __( 'Bozza mezzo agricolo aggiornata. <a target="_blank" href="%s">Anteprima mezzo</a>', 'shaktiman-b2b' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
        );
        
        return $messages;
    }
    
    /**
     * Rimuove il bottone "Aggiungi nuovo" per i rivenditori (solo admin può creare)
     */
    public function remove_add_new_for_rivenditori() {
        if ( ! current_user_can( 'publish_posts' ) ) {
            global $submenu;
            unset( $submenu['edit.php?post_type=' . self::POST_TYPE][10] );
        }
    }
}
