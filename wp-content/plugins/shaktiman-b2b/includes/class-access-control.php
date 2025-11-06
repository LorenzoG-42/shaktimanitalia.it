<?php
/**
 * Gestione del Controllo Accessi
 *
 * @package ShaktimanB2B
 */

// Se questo file viene chiamato direttamente, esce
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Classe per il Controllo Accessi
 */
class Shaktiman_B2B_Access_Control {
    
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
        // Protegge l'accesso all'archivio e ai singoli mezzi
        add_action( 'template_redirect', array( $this, 'restrict_access' ) );
        
        // Nasconde i mezzi agricoli dalle ricerche pubbliche
        add_action( 'pre_get_posts', array( $this, 'exclude_from_search' ) );
        
        // Modifica la query per mostrare solo i propri mezzi ai rivenditori
        add_action( 'pre_get_posts', array( $this, 'filter_admin_list' ) );
        
        // Blocca l'accesso alla dashboard per i rivenditori
        add_action( 'admin_init', array( $this, 'restrict_admin_access' ) );
    }
    
    /**
     * Restringe l'accesso alle pagine frontend
     */
    public function restrict_access() {
        // Controlla se siamo su una pagina del nostro CPT
        if ( ! is_singular( 'mezzo_agricolo' ) && ! is_post_type_archive( 'mezzo_agricolo' ) && ! is_tax( array( 'disponibilita', 'categoria_mezzo', 'marchio', 'ubicazione', 'stato_magazzino' ) ) ) {
            return;
        }
        
        // Se l'utente non è loggato, reindirizza al login
        if ( ! is_user_logged_in() ) {
            auth_redirect();
            exit;
        }
        
        // Verifica se l'utente ha i permessi necessari
        if ( ! Shaktiman_B2B_Roles::is_rivenditore() ) {
            wp_die(
                __( 'Non hai i permessi per accedere a questa sezione.', 'shaktiman-b2b' ),
                __( 'Accesso Negato', 'shaktiman-b2b' ),
                array( 'response' => 403 )
            );
        }
    }
    
    /**
     * Esclude i mezzi agricoli dalle ricerche pubbliche
     */
    public function exclude_from_search( $query ) {
        // Solo per le ricerche frontend
        if ( ! is_admin() && $query->is_search() && $query->is_main_query() ) {
            $post_type = $query->get( 'post_type' );
            
            // Se non è specificato un post type, escludi i mezzi agricoli
            if ( empty( $post_type ) ) {
                $query->set( 'post_type', array( 'post', 'page' ) );
            }
        }
    }
    
    /**
     * Filtra la lista admin per i rivenditori
     */
    public function filter_admin_list( $query ) {
        // Solo nell'admin
        if ( ! is_admin() ) {
            return;
        }
        
        // Solo per il nostro post type
        if ( ! isset( $query->query['post_type'] ) || 'mezzo_agricolo' !== $query->query['post_type'] ) {
            return;
        }
        
        // Solo per la lista principale
        if ( ! $query->is_main_query() ) {
            return;
        }
        
        // Gli admin e reparto vendite vedono tutto
        if ( current_user_can( 'edit_others_posts' ) ) {
            return;
        }
        
        // I rivenditori vedono tutti i mezzi (possono modificarli ma non cancellarli)
        // Se in futuro si vuole limitare ai propri mezzi, decommentare:
        /*
        $current_user = wp_get_current_user();
        if ( Shaktiman_B2B_Roles::is_rivenditore() && ! current_user_can( 'manage_mezzi_agricoli_contracts' ) ) {
            $query->set( 'author', $current_user->ID );
        }
        */
    }
    
    /**
     * Blocca l'accesso alla dashboard per i rivenditori
     */
    public function restrict_admin_access() {
        // Permetti le chiamate AJAX
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return;
        }
        
        // Se l'utente è un rivenditore (ma non reparto vendite o admin)
        $user = wp_get_current_user();
        if ( in_array( 'rivenditore', (array) $user->roles, true ) ) {
            // Reindirizza al frontend
            wp_redirect( home_url( '/mezzi-agricoli-b2b/' ) );
            exit;
        }
    }
    
    /**
     * Verifica se un utente può modificare lo stato di un mezzo
     */
    public static function can_edit_stato( $post_id, $user_id = null ) {
        if ( null === $user_id ) {
            $user_id = get_current_user_id();
        }
        
        // Admin e reparto vendite possono sempre
        if ( Shaktiman_B2B_Roles::can_manage_contracts( $user_id ) ) {
            return true;
        }
        
        // Rivenditori possono modificare
        if ( Shaktiman_B2B_Roles::is_rivenditore( $user_id ) ) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Verifica se un utente può modificare i dati del contratto
     */
    public static function can_edit_contratto( $user_id = null ) {
        return Shaktiman_B2B_Roles::can_manage_contracts( $user_id );
    }
}
