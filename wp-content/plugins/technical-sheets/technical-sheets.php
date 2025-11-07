<?php
/**
 * Plugin Name: Schede Tecniche
 * Plugin URI: https://shacktimanitalia.it
 * Description: Plugin per la gestione delle schede tecniche con custom post type, campi personalizzabili, categorie e tassonomie.
 * Version: 1.0.0
 * Author: Lorenzo G.
 * License: GPL v2 or later
 * Text Domain: technical-sheets
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}


// Define plugin constants
define('TECHNICAL_SHEETS_VERSION', '1.0.2');
define('TECHNICAL_SHEETS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TECHNICAL_SHEETS_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Main plugin class
class TechnicalSheets {
    
    /**
     * Plugin components
     */
    private $post_type;
    private $taxonomies;
    private $meta_boxes;
    private $admin;
    private $frontend;
    private $shortcodes;
    private $widgets;
    private $pdf_export;
    
    public function __construct() {
        // Include required files first
        $this->includes();
        
        // Initialize classes immediately
        $this->init();
        
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize classes - store instances in properties
        $this->post_type = new TS_Post_Type();
        $this->taxonomies = new TS_Taxonomies();
        $this->meta_boxes = new TS_Meta_Boxes();
        $this->admin = new TS_Admin();
        $this->frontend = new TS_Frontend();
        $this->shortcodes = new TS_Shortcodes();
        $this->widgets = new TS_Widgets();
        $this->pdf_export = new TS_PDF_Export();
    }
    
    /**
     * Load plugin text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain('technical-sheets', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Include required files
     */
    private function includes() {
        require_once TECHNICAL_SHEETS_PLUGIN_PATH . 'includes/class-ts-post-type.php';
        require_once TECHNICAL_SHEETS_PLUGIN_PATH . 'includes/class-ts-taxonomies.php';
        require_once TECHNICAL_SHEETS_PLUGIN_PATH . 'includes/class-ts-meta-boxes.php';
        require_once TECHNICAL_SHEETS_PLUGIN_PATH . 'includes/class-ts-admin.php';
        require_once TECHNICAL_SHEETS_PLUGIN_PATH . 'includes/class-ts-frontend.php';
        require_once TECHNICAL_SHEETS_PLUGIN_PATH . 'includes/class-ts-shortcodes.php';
        require_once TECHNICAL_SHEETS_PLUGIN_PATH . 'includes/class-ts-widgets.php';
        require_once TECHNICAL_SHEETS_PLUGIN_PATH . 'includes/class-ts-pdf-export.php';
        require_once TECHNICAL_SHEETS_PLUGIN_PATH . 'includes/ts-functions.php';
        
        // Debug file (remove in production)
        if (file_exists(TECHNICAL_SHEETS_PLUGIN_PATH . 'debug.php')) {
            require_once TECHNICAL_SHEETS_PLUGIN_PATH . 'debug.php';
        }
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Register post type and taxonomies for flush_rewrite_rules
        $post_type = new TS_Post_Type();
        $taxonomies = new TS_Taxonomies();
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Create database tables if needed
        $this->create_tables();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Create database tables
     */
    private function create_tables() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'technical_sheets_meta';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            post_id mediumint(9) NOT NULL,
            meta_key varchar(255) NOT NULL,
            meta_value longtext,
            meta_order int(11) DEFAULT 0,
            PRIMARY KEY (id),
            KEY post_id (post_id),
            KEY meta_key (meta_key)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// Initialize the plugin
new TechnicalSheets();
