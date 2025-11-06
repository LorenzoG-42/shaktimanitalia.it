<?php
/**
 * Plugin Name: Shaktiman B2B
 * Plugin URI: https://shaktimanitalia.it
 * Description: Sistema B2B per la gestione dei mezzi agricoli con accesso riservato ai rivenditori
 * Version: 1.0.0
 * Author: Shaktiman Italia
 * Author URI: https://shaktimanitalia.it
 * Text Domain: shaktiman-b2b
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 *
 * @package ShaktimanB2B
 */

// Se questo file viene chiamato direttamente, esce
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Definizione delle costanti del plugin
define( 'SHAKTIMAN_B2B_VERSION', '1.0.0' );
define( 'SHAKTIMAN_B2B_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SHAKTIMAN_B2B_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SHAKTIMAN_B2B_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Classe principale del plugin
 */
class Shaktiman_B2B {
    
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
        $this->load_dependencies();
        $this->init_hooks();
    }
    
    /**
     * Carica le dipendenze del plugin
     */
    private function load_dependencies() {
        // Classi core
        require_once SHAKTIMAN_B2B_PLUGIN_DIR . 'includes/class-roles.php';
        require_once SHAKTIMAN_B2B_PLUGIN_DIR . 'includes/class-post-type.php';
        require_once SHAKTIMAN_B2B_PLUGIN_DIR . 'includes/class-taxonomies.php';
        require_once SHAKTIMAN_B2B_PLUGIN_DIR . 'includes/class-meta-boxes.php';
        require_once SHAKTIMAN_B2B_PLUGIN_DIR . 'includes/class-access-control.php';
        require_once SHAKTIMAN_B2B_PLUGIN_DIR . 'includes/class-frontend.php';
    }
    
    /**
     * Inizializza gli hooks di WordPress
     */
    private function init_hooks() {
        // Attivazione e disattivazione plugin
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
        
        // Inizializzazione
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        
        // Caricamento assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }
    
    /**
     * Inizializza il plugin
     */
    public function init() {
        // Carica traduzione
        load_plugin_textdomain( 'shaktiman-b2b', false, dirname( SHAKTIMAN_B2B_PLUGIN_BASENAME ) . '/languages' );
        
        // Inizializza le classi
        Shaktiman_B2B_Roles::get_instance();
        Shaktiman_B2B_Post_Type::get_instance();
        Shaktiman_B2B_Taxonomies::get_instance();
        Shaktiman_B2B_Meta_Boxes::get_instance();
        Shaktiman_B2B_Access_Control::get_instance();
        Shaktiman_B2B_Frontend::get_instance();
    }
    
    /**
     * Attivazione del plugin
     */
    public function activate() {
        // Crea i ruoli
        Shaktiman_B2B_Roles::create_roles();
        
        // Registra post type e tassonomie
        $post_type = Shaktiman_B2B_Post_Type::get_instance();
        $post_type->register_post_type();
        
        $taxonomies = Shaktiman_B2B_Taxonomies::get_instance();
        $taxonomies->register_taxonomies();
        
        // Flush delle rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Disattivazione del plugin
     */
    public function deactivate() {
        // Flush delle rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Carica gli asset frontend
     */
    public function enqueue_frontend_assets() {
        // CSS
        wp_enqueue_style(
            'shaktiman-b2b-frontend',
            SHAKTIMAN_B2B_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            SHAKTIMAN_B2B_VERSION
        );
        
        // JavaScript
        wp_enqueue_script(
            'shaktiman-b2b-frontend',
            SHAKTIMAN_B2B_PLUGIN_URL . 'assets/js/frontend.js',
            array( 'jquery' ),
            SHAKTIMAN_B2B_VERSION,
            true
        );
        
        // Localizzazione
        wp_localize_script(
            'shaktiman-b2b-frontend',
            'shaktimanB2B',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'shaktiman_b2b_nonce' )
            )
        );
    }
    
    /**
     * Carica gli asset admin
     */
    public function enqueue_admin_assets( $hook ) {
        global $post_type;
        
        // Carica solo sulle pagine del nostro CPT
        if ( 'mezzo_agricolo' !== $post_type ) {
            return;
        }
        
        // CSS
        wp_enqueue_style(
            'shaktiman-b2b-admin',
            SHAKTIMAN_B2B_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            SHAKTIMAN_B2B_VERSION
        );
        
        // JavaScript
        wp_enqueue_script(
            'shaktiman-b2b-admin',
            SHAKTIMAN_B2B_PLUGIN_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            SHAKTIMAN_B2B_VERSION,
            true
        );
    }
}

/**
 * Inizializza il plugin
 */
function shaktiman_b2b() {
    return Shaktiman_B2B::get_instance();
}

// Avvia il plugin
shaktiman_b2b();
