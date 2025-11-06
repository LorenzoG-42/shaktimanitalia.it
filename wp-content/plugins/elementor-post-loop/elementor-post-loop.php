<?php
/**
 * Plugin Name: Elementor Post Loop
 * Description: Widget Elementor avanzato per creare loop di post personalizzati con supporto per tutti i tipi di post, pagine e Custom Post Types.
 * Version: 1.0.0
 * Author: Shaktiman Italia
 * Author URI: https://shaktimanitalia.it
 * Text Domain: elementor-post-loop
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Previene l'accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

// Definisce le costanti del plugin
define('ELEMENTOR_POST_LOOP_VERSION', '1.0.0');
define('ELEMENTOR_POST_LOOP_PLUGIN_FILE', __FILE__);
define('ELEMENTOR_POST_LOOP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ELEMENTOR_POST_LOOP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ELEMENTOR_POST_LOOP_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Classe principale del plugin
 */
final class Elementor_Post_Loop {

    /**
     * Istanza singola del plugin
     *
     * @var Elementor_Post_Loop
     */
    private static $_instance = null;

    /**
     * Costruttore del plugin
     */
    private function __construct() {
        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
    }

    /**
     * Restituisce l'istanza singola del plugin
     *
     * @return Elementor_Post_Loop
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Inizializza il plugin quando tutti i plugin sono caricati
     */
    public function on_plugins_loaded() {
        // Verifica se Elementor è attivo
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Verifica la versione minima di Elementor
        if (!version_compare(ELEMENTOR_VERSION, '3.0.0', '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Verifica la versione PHP
        if (version_compare(PHP_VERSION, '7.4', '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Inizializza il plugin
        $this->init();
    }

    /**
     * Inizializza i componenti del plugin
     */
    private function init() {
        // Carica i file necessari
        $this->includes();

        // Registra i widget con Elementor
        add_action('elementor/widgets/register', [$this, 'register_widgets']);

        // Carica gli script e stili
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 20); // Caricamento tardivo

        // Carica il text domain per le traduzioni
        add_action('init', [$this, 'load_textdomain']);

        // Registra le azioni AJAX
        add_action('wp_ajax_elementor_post_loop_load_posts', [$this, 'ajax_load_posts']);
        add_action('wp_ajax_nopriv_elementor_post_loop_load_posts', [$this, 'ajax_load_posts']);
        
        // Mantieni compatibilità con handler esistente (se presente)
        add_action('wp_ajax_elementor_post_loop_load_more', [$this, 'ajax_load_more']);
        add_action('wp_ajax_nopriv_elementor_post_loop_load_more', [$this, 'ajax_load_more']);
    }

    /**
     * Include i file necessari
     */
    public function includes() {
        require_once ELEMENTOR_POST_LOOP_PLUGIN_PATH . 'includes/class-query-builder.php';
        require_once ELEMENTOR_POST_LOOP_PLUGIN_PATH . 'includes/class-template-loader.php';
        require_once ELEMENTOR_POST_LOOP_PLUGIN_PATH . 'includes/class-post-helper.php';
    }

    /**
     * Registra i widget con Elementor
     */
    public function register_widgets($widgets_manager) {
        require_once ELEMENTOR_POST_LOOP_PLUGIN_PATH . 'widgets/class-post-loop-widget.php';
        $widgets_manager->register(new \Elementor_Post_Loop\Widgets\Post_Loop_Widget());
    }

    /**
     * Carica script e stili
     */
    public function enqueue_scripts() {
        // CSS unificato
        wp_enqueue_style(
            'elementor-post-loop',
            ELEMENTOR_POST_LOOP_PLUGIN_URL . 'assets/css/elementor-post-loop.css',
            [],
            ELEMENTOR_POST_LOOP_VERSION
        );

        // JavaScript frontend con AJAX
        wp_enqueue_script(
            'elementor-post-loop-frontend',
            ELEMENTOR_POST_LOOP_PLUGIN_URL . 'assets/js/frontend.js',
            ['jquery'],
            ELEMENTOR_POST_LOOP_VERSION,
            true
        );

        // JavaScript base (se esiste)
        if (file_exists(ELEMENTOR_POST_LOOP_PLUGIN_PATH . 'assets/js/elementor-post-loop.js')) {
            wp_enqueue_script(
                'elementor-post-loop',
                ELEMENTOR_POST_LOOP_PLUGIN_URL . 'assets/js/elementor-post-loop.js',
                ['jquery'],
                ELEMENTOR_POST_LOOP_VERSION,
                true
            );
        }

        // Script specifico per l'editor Elementor
        if (did_action('elementor/loaded')) {
            wp_enqueue_script(
                'elementor-post-loop-editor',
                ELEMENTOR_POST_LOOP_PLUGIN_URL . 'assets/js/elementor-editor.js',
                ['jquery', 'elementor-frontend'],
                ELEMENTOR_POST_LOOP_VERSION,
                true
            );
        }

        // Localizza lo script frontend per AJAX
        wp_localize_script('elementor-post-loop-frontend', 'elementor_post_loop_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('elementor_post_loop_ajax_nonce'),
        ]);

        // Mantieni compatibilità con script esistente
        if (wp_script_is('elementor-post-loop', 'enqueued')) {
            wp_localize_script('elementor-post-loop', 'elementorPostLoop', [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('elementor_post_loop_nonce'),
            ]);
        }
    }

    /**
     * Carica il text domain per le traduzioni
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'elementor-post-loop',
            false,
            dirname(ELEMENTOR_POST_LOOP_PLUGIN_BASENAME) . '/languages'
        );
    }

    /**
     * Avviso admin: Elementor mancante
     */
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" richiede "%2$s" per funzionare.', 'elementor-post-loop'),
            '<strong>' . esc_html__('Elementor Post Loop', 'elementor-post-loop') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-post-loop') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Avviso admin: versione Elementor troppo vecchia
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" richiede "%2$s" versione %3$s o superiore.', 'elementor-post-loop'),
            '<strong>' . esc_html__('Elementor Post Loop', 'elementor-post-loop') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-post-loop') . '</strong>',
            '3.0.0'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Avviso admin: versione PHP troppo vecchia
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" richiede "%2$s" versione %3$s o superiore.', 'elementor-post-loop'),
            '<strong>' . esc_html__('Elementor Post Loop', 'elementor-post-loop') . '</strong>',
            '<strong>' . esc_html__('PHP', 'elementor-post-loop') . '</strong>',
            '7.4'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Gestisce la richiesta AJAX per il caricamento di più post
     */
    public function ajax_load_more() {
        // Verifica il nonce
        if (!wp_verify_nonce($_POST['nonce'], 'elementor_post_loop_nonce')) {
            wp_die('Nonce verification failed');
        }

        // Assicurati che le classi necessarie siano caricate
        if (!class_exists('\Elementor_Post_Loop\Includes\Query_Builder')) {
            $this->includes();
        }

        $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
        $settings = isset($_POST['settings']) ? $_POST['settings'] : [];

        // Aggiorna la paginazione nelle impostazioni
        $settings['paged'] = $page;

        // Ottieni la query
        $query = \Elementor_Post_Loop\Includes\Query_Builder::get_query($settings);

        if ($query->have_posts()) {
            ob_start();

            while ($query->have_posts()) {
                $query->the_post();
                \Elementor_Post_Loop\Includes\Template_Loader::render_post_item($settings);
            }

            wp_reset_postdata();

            $html = ob_get_clean();

            wp_send_json_success([
                'html' => $html,
                'found_posts' => $query->found_posts,
                'max_num_pages' => $query->max_num_pages,
            ]);
        } else {
            wp_send_json_error('No more posts found');
        }
    }

    /**
     * Handler AJAX principale per caricamento post
     * Supporta sia Load More che paginazione numerata
     */
    public function ajax_load_posts() {
        // Verifica il nonce
        if (!wp_verify_nonce($_POST['nonce'], 'elementor_post_loop_ajax_nonce')) {
            wp_send_json_error('Nonce verification failed');
            return;
        }

        // Assicurati che le classi necessarie siano caricate
        if (!class_exists('\Elementor_Post_Loop\Includes\Query_Builder')) {
            $this->includes();
        }

        // Ottieni i parametri
        $page = intval($_POST['page'] ?? 1);
        $type = sanitize_text_field($_POST['type'] ?? 'load_more');
        $settings = $_POST['settings'] ?? [];
        $widget_id = sanitize_text_field($_POST['widget_id'] ?? '');

        // Sanifica i settings
        if (is_array($settings)) {
            $settings = array_map('sanitize_text_field', $settings);
        }

        // Validazione parametri
        if ($page < 1) {
            wp_send_json_error('Invalid page number');
            return;
        }

        // Aggiungi la pagina ai settings
        $settings['paged'] = $page;

        // Ottieni la query
        $query = \Elementor_Post_Loop\Includes\Query_Builder::get_query($settings);

        if (!$query || !$query->have_posts()) {
            wp_send_json_error('No posts found');
            return;
        }

        // Genera l'HTML dei post
        ob_start();
        
        // Per la paginazione numerata, includiamo anche i container del layout
        if ($type === 'pagination') {
            $layout = !empty($settings['layout']) ? $settings['layout'] : 'grid';
            $columns = !empty($settings['columns']) ? $settings['columns'] : 3;
            
            if ($layout === 'grid') {
                echo '<div class="elementor-post-loop-grid elementor-post-loop-columns-' . esc_attr($columns) . '">';
            } elseif ($layout === 'list') {
                echo '<div class="elementor-post-loop-list">';
            } elseif ($layout === 'masonry') {
                echo '<div class="elementor-post-loop-masonry elementor-post-loop-columns-' . esc_attr($columns) . '">';
            }
        }
        
        while ($query->have_posts()) {
            $query->the_post();
            \Elementor_Post_Loop\Includes\Template_Loader::render_post_item($settings);
        }
        
        if ($type === 'pagination') {
            echo '</div>'; // Chiude container layout
        }
        
        wp_reset_postdata();
        $posts_html = ob_get_clean();

        // Prepara la risposta base
        $response = [
            'html' => $posts_html,
            'page' => $page,
            'found_posts' => $query->found_posts,
            'max_num_pages' => $query->max_num_pages,
            'type' => $type
        ];

        // Per la paginazione numerata, genera anche la nuova paginazione
        if ($type === 'pagination') {
            $response['pagination'] = $this->generate_ajax_pagination($query, $settings);
        }

        wp_send_json_success($response);
    }

    /**
     * Genera l'HTML della paginazione per AJAX
     */
    private function generate_ajax_pagination($query, $settings) {
        if ($query->max_num_pages <= 1) {
            return '';
        }

        $current_page = max(1, get_query_var('paged', 1));
        if (isset($settings['paged'])) {
            $current_page = intval($settings['paged']);
        }

        $pagination_args = [
            'base' => '#page-%#%',
            'format' => '',
            'current' => $current_page,
            'total' => $query->max_num_pages,
            'prev_text' => '&laquo; ' . __('Precedente', 'elementor-post-loop'),
            'next_text' => __('Successivo', 'elementor-post-loop') . ' &raquo;',
            'type' => 'array',
            'end_size' => 2,
            'mid_size' => 1,
        ];

        $links = paginate_links($pagination_args);
        
        if (!$links) {
            return '';
        }

        ob_start();
        foreach ($links as $link) {
            // Estrai il numero di pagina dal link usando una regex più robusta
            if (preg_match('/#page-(\d+)/', $link, $matches)) {
                $page_num = $matches[1];
                // Sostituisci l'href con # e aggiungi data-page con il numero
                $link = preg_replace('/href=["\']#page-\d+["\']/', 'href="#" data-page="' . $page_num . '"', $link);
            }
            echo $link;
        }
        
        return ob_get_clean();
    }
}

// Inizializza il plugin
Elementor_Post_Loop::instance();