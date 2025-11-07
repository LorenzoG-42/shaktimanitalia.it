<?php
/**
 * Gestione Logging e Storico
 *
 * @package ShaktimanB2B
 */

// Se questo file viene chiamato direttamente, esce
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Classe per il logging delle attività sui mezzi
 */
class Shaktiman_B2B_Logger {
    
    /**
     * Nome della tabella per i log dei mezzi
     */
    const TABLE_MEZZO_LOG = 'shaktiman_mezzo_log';
    
    /**
     * Nome della tabella per le statistiche rivenditori
     */
    const TABLE_RIVENDITORE_STATS = 'shaktiman_rivenditore_stats';
    
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
        // AJAX handlers
        add_action( 'wp_ajax_get_mezzo_history', array( $this, 'ajax_get_mezzo_history' ) );
        add_action( 'wp_ajax_get_rivenditore_stats', array( $this, 'ajax_get_rivenditore_stats' ) );
    }
    
    /**
     * Crea le tabelle del database
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Tabella log mezzi
        $table_mezzo_log = $wpdb->prefix . self::TABLE_MEZZO_LOG;
        $sql_mezzo_log = "CREATE TABLE IF NOT EXISTS $table_mezzo_log (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            mezzo_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED NOT NULL,
            user_name varchar(255) NOT NULL,
            action_type varchar(50) NOT NULL,
            old_value text DEFAULT NULL,
            new_value text DEFAULT NULL,
            nome_cliente varchar(255) DEFAULT NULL,
            note text DEFAULT NULL,
            created_at datetime NOT NULL,
            PRIMARY KEY (id),
            KEY mezzo_id (mezzo_id),
            KEY user_id (user_id),
            KEY action_type (action_type),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Tabella statistiche rivenditori
        $table_rivenditore_stats = $wpdb->prefix . self::TABLE_RIVENDITORE_STATS;
        $sql_rivenditore_stats = "CREATE TABLE IF NOT EXISTS $table_rivenditore_stats (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(20) UNSIGNED NOT NULL,
            mezzo_id bigint(20) UNSIGNED NOT NULL,
            action_type varchar(50) NOT NULL,
            stato varchar(50) NOT NULL,
            nome_cliente varchar(255) DEFAULT NULL,
            has_contratto tinyint(1) DEFAULT 0,
            numero_contratto varchar(255) DEFAULT NULL,
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY user_mezzo_unique (user_id, mezzo_id, action_type),
            KEY user_id (user_id),
            KEY mezzo_id (mezzo_id),
            KEY action_type (action_type),
            KEY stato (stato),
            KEY has_contratto (has_contratto)
        ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_mezzo_log );
        dbDelta( $sql_rivenditore_stats );
    }
    
    /**
     * Registra un'azione nel log
     *
     * @param int    $mezzo_id      ID del mezzo
     * @param string $action_type   Tipo di azione (riserva, venduto, libera, cambio_ubicazione, contratto)
     * @param array  $data          Dati aggiuntivi
     */
    public function log_action( $mezzo_id, $action_type, $data = array() ) {
        global $wpdb;
        
        $current_user = wp_get_current_user();
        $user_id = isset( $data['user_id'] ) ? $data['user_id'] : $current_user->ID;
        $user_name = isset( $data['user_name'] ) ? $data['user_name'] : $current_user->display_name;
        
        $table_name = $wpdb->prefix . self::TABLE_MEZZO_LOG;
        
        $insert_data = array(
            'mezzo_id'     => $mezzo_id,
            'user_id'      => $user_id,
            'user_name'    => $user_name,
            'action_type'  => $action_type,
            'old_value'    => isset( $data['old_value'] ) ? maybe_serialize( $data['old_value'] ) : null,
            'new_value'    => isset( $data['new_value'] ) ? maybe_serialize( $data['new_value'] ) : null,
            'nome_cliente' => isset( $data['nome_cliente'] ) ? $data['nome_cliente'] : null,
            'note'         => isset( $data['note'] ) ? $data['note'] : null,
            'created_at'   => current_time( 'mysql' ),
        );
        
        $wpdb->insert( $table_name, $insert_data );
        
        // Aggiorna statistiche rivenditore
        $this->update_rivenditore_stats( $mezzo_id, $user_id, $action_type, $data );
    }
    
    /**
     * Aggiorna le statistiche del rivenditore
     */
    private function update_rivenditore_stats( $mezzo_id, $user_id, $action_type, $data = array() ) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::TABLE_RIVENDITORE_STATS;
        
        // Determina lo stato corrente
        $disponibilita_terms = get_the_terms( $mezzo_id, 'disponibilita' );
        $stato = $disponibilita_terms && ! is_wp_error( $disponibilita_terms ) ? $disponibilita_terms[0]->slug : 'disponibile';
        
        // Verifica se c'è un contratto
        $numero_contratto = get_post_meta( $mezzo_id, '_numero_contratto', true );
        $has_contratto = ! empty( $numero_contratto ) ? 1 : 0;
        
        // Nome cliente
        $nome_cliente = isset( $data['nome_cliente'] ) ? $data['nome_cliente'] : get_post_meta( $mezzo_id, '_nome_cliente', true );
        
        // Se l'azione è "libera", rimuovi il record
        if ( $action_type === 'libera' ) {
            $wpdb->delete(
                $table_name,
                array(
                    'user_id'  => $user_id,
                    'mezzo_id' => $mezzo_id,
                ),
                array( '%d', '%d' )
            );
            return;
        }
        
        // Inserisci o aggiorna il record
        $existing = $wpdb->get_row( $wpdb->prepare(
            "SELECT id FROM $table_name WHERE user_id = %d AND mezzo_id = %d AND action_type = %s",
            $user_id,
            $mezzo_id,
            $action_type
        ) );
        
        $record_data = array(
            'user_id'          => $user_id,
            'mezzo_id'         => $mezzo_id,
            'action_type'      => $action_type,
            'stato'            => $stato,
            'nome_cliente'     => $nome_cliente,
            'has_contratto'    => $has_contratto,
            'numero_contratto' => $numero_contratto,
            'updated_at'       => current_time( 'mysql' ),
        );
        
        if ( $existing ) {
            // Aggiorna record esistente
            $wpdb->update(
                $table_name,
                $record_data,
                array( 'id' => $existing->id ),
                array( '%d', '%d', '%s', '%s', '%s', '%d', '%s', '%s' ),
                array( '%d' )
            );
        } else {
            // Inserisci nuovo record
            $record_data['created_at'] = current_time( 'mysql' );
            $wpdb->insert(
                $table_name,
                $record_data,
                array( '%d', '%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s' )
            );
        }
    }
    
    /**
     * Ottieni lo storico di un mezzo
     *
     * @param int $mezzo_id ID del mezzo
     * @return array Array di log
     */
    public function get_mezzo_history( $mezzo_id ) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::TABLE_MEZZO_LOG;
        
        $results = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $table_name WHERE mezzo_id = %d ORDER BY created_at DESC",
            $mezzo_id
        ) );
        
        return $results;
    }
    
    /**
     * Ottieni le statistiche di un rivenditore
     *
     * @param int $user_id ID del rivenditore
     * @return array Statistiche
     */
    public function get_rivenditore_stats( $user_id ) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::TABLE_RIVENDITORE_STATS;
        
        // Totali per stato
        $totals = $wpdb->get_results( $wpdb->prepare(
            "SELECT 
                stato,
                COUNT(*) as count,
                SUM(has_contratto) as count_with_contratto
            FROM $table_name 
            WHERE user_id = %d 
            GROUP BY stato",
            $user_id
        ) );
        
        // Dettaglio mezzi
        $mezzi = $wpdb->get_results( $wpdb->prepare(
            "SELECT 
                s.*,
                p.post_title as mezzo_title
            FROM $table_name s
            LEFT JOIN {$wpdb->posts} p ON s.mezzo_id = p.ID
            WHERE s.user_id = %d
            ORDER BY s.updated_at DESC",
            $user_id
        ) );
        
        return array(
            'totals' => $totals,
            'mezzi'  => $mezzi,
        );
    }
    
    /**
     * AJAX: Ottieni storico mezzo
     */
    public function ajax_get_mezzo_history() {
        // Verifica nonce
        check_ajax_referer( 'shaktiman_b2b_nonce', 'nonce' );
        
        // Verifica che l'utente sia loggato
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'Devi essere loggato.', 'shaktiman-b2b' ) ) );
        }
        
        $mezzo_id = intval( $_POST['mezzo_id'] );
        
        if ( ! $mezzo_id ) {
            wp_send_json_error( array( 'message' => __( 'ID mezzo non valido.', 'shaktiman-b2b' ) ) );
        }
        
        $history = $this->get_mezzo_history( $mezzo_id );
        
        // Formatta i dati per la visualizzazione
        $formatted_history = array();
        foreach ( $history as $log ) {
            $formatted_history[] = array(
                'id'           => $log->id,
                'user_name'    => $log->user_name,
                'action_type'  => $this->get_action_label( $log->action_type ),
                'nome_cliente' => $log->nome_cliente,
                'note'         => $log->note,
                'old_value'    => maybe_unserialize( $log->old_value ),
                'new_value'    => maybe_unserialize( $log->new_value ),
                'created_at'   => date_i18n( 'd/m/Y H:i', strtotime( $log->created_at ) ),
            );
        }
        
        wp_send_json_success( array( 'history' => $formatted_history ) );
    }
    
    /**
     * AJAX: Ottieni statistiche rivenditore
     */
    public function ajax_get_rivenditore_stats() {
        // Verifica nonce
        check_ajax_referer( 'shaktiman_b2b_nonce', 'nonce' );
        
        // Verifica che l'utente sia loggato
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'Devi essere loggato.', 'shaktiman-b2b' ) ) );
        }
        
        $user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : get_current_user_id();
        
        // Solo admin possono vedere le stats di altri utenti
        if ( $user_id !== get_current_user_id() && ! current_user_can( 'edit_others_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Non hai i permessi.', 'shaktiman-b2b' ) ) );
        }
        
        $stats = $this->get_rivenditore_stats( $user_id );
        
        wp_send_json_success( array( 'stats' => $stats ) );
    }
    
    /**
     * Ottieni l'etichetta di un'azione
     */
    private function get_action_label( $action_type ) {
        $labels = array(
            'riserva'           => __( 'Riservato', 'shaktiman-b2b' ),
            'venduto'           => __( 'Venduto', 'shaktiman-b2b' ),
            'libera'            => __( 'Liberato', 'shaktiman-b2b' ),
            'cambio_ubicazione' => __( 'Cambio Ubicazione', 'shaktiman-b2b' ),
            'contratto'         => __( 'Contratto Generato', 'shaktiman-b2b' ),
        );
        
        return isset( $labels[ $action_type ] ) ? $labels[ $action_type ] : $action_type;
    }
}
