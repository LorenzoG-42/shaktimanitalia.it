<?php
/**
 * Gestione dei ruoli personalizzati
 *
 * @package ShaktimanB2B
 */

// Se questo file viene chiamato direttamente, esce
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Classe per la gestione dei ruoli
 */
class Shaktiman_B2B_Roles {
    
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
        // Hook per eventuali modifiche ai ruoli
        add_action( 'init', array( $this, 'maybe_update_roles' ) );
    }
    
    /**
     * Crea i ruoli personalizzati
     */
    public static function create_roles() {
        // Ruolo Rivenditore - come subscriber, solo lettura frontend
        add_role(
            'rivenditore',
            __( 'Rivenditore', 'shaktiman-b2b' ),
            array(
                'read' => true,
            )
        );
        
        // Ruolo Reparto Vendite - accesso completo ai mezzi agricoli
        add_role(
            'reparto_vendite',
            __( 'Reparto Vendite', 'shaktiman-b2b' ),
            array(
                'read' => true,
            )
        );
    }
    
    /**
     * Rimuove i ruoli personalizzati
     */
    public static function remove_roles() {
        remove_role( 'rivenditore' );
        remove_role( 'reparto_vendite' );
    }
    
    /**
     * Aggiorna i ruoli se necessario
     */
    public function maybe_update_roles() {
        $version = get_option( 'shaktiman_b2b_roles_version' );
        
        if ( version_compare( $version, SHAKTIMAN_B2B_VERSION, '<' ) ) {
            self::create_roles();
            update_option( 'shaktiman_b2b_roles_version', SHAKTIMAN_B2B_VERSION );
        }
    }
    
    /**
     * Verifica se l'utente corrente è un rivenditore
     */
    public static function is_rivenditore( $user_id = null ) {
        if ( null === $user_id ) {
            $user_id = get_current_user_id();
        }
        
        $user = get_userdata( $user_id );
        
        if ( ! $user ) {
            return false;
        }
        
        return in_array( 'rivenditore', (array) $user->roles, true ) || 
               in_array( 'reparto_vendite', (array) $user->roles, true ) ||
               in_array( 'administrator', (array) $user->roles, true );
    }
    
    /**
     * Verifica se l'utente può gestire i contratti
     */
    public static function can_manage_contracts( $user_id = null ) {
        if ( null === $user_id ) {
            $user_id = get_current_user_id();
        }
        
        $user = get_userdata( $user_id );
        
        if ( ! $user ) {
            return false;
        }
        
        return in_array( 'reparto_vendite', (array) $user->roles, true ) ||
               in_array( 'administrator', (array) $user->roles, true );
    }
}
