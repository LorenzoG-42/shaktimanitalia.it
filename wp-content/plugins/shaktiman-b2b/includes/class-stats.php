<?php
/**
 * Gestione Statistiche Rivenditori
 *
 * @package ShaktimanB2B
 */

// Se questo file viene chiamato direttamente, esce
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Classe per le statistiche rivenditori
 */
class Shaktiman_B2B_Stats {
    
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
        // Registra shortcode
        add_shortcode( 'rivenditore_stats', array( $this, 'render_stats_shortcode' ) );
        
        // AJAX handlers
        add_action( 'wp_ajax_get_rivenditore_detailed_stats', array( $this, 'ajax_get_detailed_stats' ) );
        
        // Widget dashboard per admin
        add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );
        
        // Menu admin
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
    }
    
    /**
     * Renderizza lo shortcode statistiche
     * Uso: [rivenditore_stats user_id="123"] o [rivenditore_stats] per l'utente corrente
     */
    public function render_stats_shortcode( $atts ) {
        // Verifica che l'utente sia loggato
        if ( ! is_user_logged_in() ) {
            return '<p class="stats-error">' . __( 'Devi essere loggato per visualizzare le statistiche.', 'shaktiman-b2b' ) . '</p>';
        }
        
        $atts = shortcode_atts( array(
            'user_id' => get_current_user_id(),
        ), $atts );
        
        $user_id = intval( $atts['user_id'] );
        $current_user_id = get_current_user_id();
        
        // Solo admin possono vedere le stats di altri utenti
        if ( $user_id !== $current_user_id && ! current_user_can( 'edit_others_posts' ) ) {
            return '<p class="stats-error">' . __( 'Non hai i permessi per visualizzare queste statistiche.', 'shaktiman-b2b' ) . '</p>';
        }
        
        // Ottieni le statistiche
        $logger = Shaktiman_B2B_Logger::get_instance();
        $stats = $logger->get_rivenditore_stats( $user_id );
        
        // Renderizza il template
        ob_start();
        $this->render_stats_template( $stats, $user_id );
        return ob_get_clean();
    }
    
    /**
     * Renderizza il template delle statistiche
     */
    private function render_stats_template( $stats, $user_id ) {
        $user = get_userdata( $user_id );
        $totals = $stats['totals'];
        $mezzi = $stats['mezzi'];
        
        // Calcola i totali
        $total_riservati = 0;
        $total_venduti = 0;
        $total_con_contratto = 0;
        $total_senza_contratto = 0;
        
        foreach ( $totals as $total ) {
            if ( $total->stato === 'riservato' ) {
                $total_riservati = $total->count;
            } elseif ( $total->stato === 'venduto' ) {
                $total_venduti = $total->count;
                $total_con_contratto = $total->count_with_contratto;
                $total_senza_contratto = $total->count - $total->count_with_contratto;
            }
        }
        
        ?>
        <div class="rivenditore-stats-container">
            <div class="stats-header">
                <h2 class="stats-title">
                    <?php echo esc_html( sprintf( __( 'Statistiche di %s', 'shaktiman-b2b' ), $user->display_name ) ); ?>
                </h2>
                <button class="btn-refresh-stats" data-user-id="<?php echo esc_attr( $user_id ); ?>">
                    🔄 <?php _e( 'Aggiorna', 'shaktiman-b2b' ); ?>
                </button>
            </div>
            
            <!-- Cards riassuntive -->
            <div class="stats-cards">
                <div class="stat-card stat-card-riservati">
                    <div class="stat-icon">📦</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo esc_html( $total_riservati ); ?></div>
                        <div class="stat-label"><?php _e( 'Mezzi Riservati', 'shaktiman-b2b' ); ?></div>
                    </div>
                </div>
                
                <div class="stat-card stat-card-venduti">
                    <div class="stat-icon">✅</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo esc_html( $total_venduti ); ?></div>
                        <div class="stat-label"><?php _e( 'Mezzi Venduti', 'shaktiman-b2b' ); ?></div>
                    </div>
                </div>
                
                <div class="stat-card stat-card-contratto">
                    <div class="stat-icon">📄</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo esc_html( $total_con_contratto ); ?></div>
                        <div class="stat-label"><?php _e( 'Con Contratto', 'shaktiman-b2b' ); ?></div>
                    </div>
                </div>
                
                <div class="stat-card stat-card-no-contratto">
                    <div class="stat-icon">⚠️</div>
                    <div class="stat-content">
                        <div class="stat-number"><?php echo esc_html( $total_senza_contratto ); ?></div>
                        <div class="stat-label"><?php _e( 'Senza Contratto', 'shaktiman-b2b' ); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Filtri -->
            <div class="stats-filters">
                <label>
                    <input type="radio" name="filter-stato" value="all" checked> 
                    <?php _e( 'Tutti', 'shaktiman-b2b' ); ?>
                </label>
                <label>
                    <input type="radio" name="filter-stato" value="riservato"> 
                    <?php _e( 'Solo Riservati', 'shaktiman-b2b' ); ?>
                </label>
                <label>
                    <input type="radio" name="filter-stato" value="venduto"> 
                    <?php _e( 'Solo Venduti', 'shaktiman-b2b' ); ?>
                </label>
                <label>
                    <input type="checkbox" name="filter-contratto" value="1"> 
                    <?php _e( 'Solo con Contratto', 'shaktiman-b2b' ); ?>
                </label>
                <label>
                    <input type="checkbox" name="filter-no-contratto" value="1"> 
                    <?php _e( 'Solo senza Contratto', 'shaktiman-b2b' ); ?>
                </label>
            </div>
            
            <!-- Tabella dettagliata -->
            <div class="stats-table-wrapper">
                <?php if ( empty( $mezzi ) ) : ?>
                    <p class="no-data"><?php _e( 'Nessun mezzo trovato.', 'shaktiman-b2b' ); ?></p>
                <?php else : ?>
                    <table class="stats-table">
                        <thead>
                            <tr>
                                <th><?php _e( 'Mezzo', 'shaktiman-b2b' ); ?></th>
                                <th><?php _e( 'Stato', 'shaktiman-b2b' ); ?></th>
                                <th><?php _e( 'Cliente', 'shaktiman-b2b' ); ?></th>
                                <th><?php _e( 'Contratto', 'shaktiman-b2b' ); ?></th>
                                <th><?php _e( 'Ultimo Aggiornamento', 'shaktiman-b2b' ); ?></th>
                                <th><?php _e( 'Azioni', 'shaktiman-b2b' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $mezzi as $mezzo ) : 
                                $has_contratto = (bool) $mezzo->has_contratto;
                                ?>
                                <tr data-stato="<?php echo esc_attr( $mezzo->stato ); ?>" data-contratto="<?php echo $has_contratto ? '1' : '0'; ?>">
                                    <td>
                                        <a href="<?php echo get_permalink( $mezzo->mezzo_id ); ?>" target="_blank">
                                            <?php echo esc_html( $mezzo->mezzo_title ); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="stato-badge stato-<?php echo esc_attr( $mezzo->stato ); ?>">
                                            <?php echo esc_html( ucfirst( $mezzo->stato ) ); ?>
                                        </span>
                                    </td>
                                    <td><?php echo esc_html( $mezzo->nome_cliente ?: '-' ); ?></td>
                                    <td>
                                        <?php if ( $has_contratto ) : ?>
                                            <span class="contratto-badge contratto-si">
                                                ✓ <?php echo esc_html( $mezzo->numero_contratto ); ?>
                                            </span>
                                        <?php else : ?>
                                            <span class="contratto-badge contratto-no">✗ No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo esc_html( date_i18n( 'd/m/Y H:i', strtotime( $mezzo->updated_at ) ) ); ?></td>
                                    <td>
                                        <a href="<?php echo get_permalink( $mezzo->mezzo_id ); ?>" class="btn-view-mezzo" target="_blank">
                                            👁️ <?php _e( 'Visualizza', 'shaktiman-b2b' ); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX: Ottieni statistiche dettagliate
     */
    public function ajax_get_detailed_stats() {
        check_ajax_referer( 'shaktiman_b2b_nonce', 'nonce' );
        
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'Non autorizzato.', 'shaktiman-b2b' ) ) );
        }
        
        $user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : get_current_user_id();
        
        // Controllo permessi
        if ( $user_id !== get_current_user_id() && ! current_user_can( 'edit_others_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Non hai i permessi.', 'shaktiman-b2b' ) ) );
        }
        
        $logger = Shaktiman_B2B_Logger::get_instance();
        $stats = $logger->get_rivenditore_stats( $user_id );
        
        wp_send_json_success( array( 'stats' => $stats ) );
    }
    
    /**
     * Aggiungi widget alla dashboard admin
     */
    public function add_dashboard_widgets() {
        if ( ! current_user_can( 'edit_others_posts' ) ) {
            return;
        }
        
        wp_add_dashboard_widget(
            'shaktiman_b2b_stats_widget',
            __( 'Statistiche B2B - Rivenditori', 'shaktiman-b2b' ),
            array( $this, 'render_dashboard_widget' )
        );
    }
    
    /**
     * Renderizza il widget dashboard
     */
    public function render_dashboard_widget() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . Shaktiman_B2B_Logger::TABLE_RIVENDITORE_STATS;
        
        // Top 5 rivenditori per mezzi venduti
        $top_venditori = $wpdb->get_results(
            "SELECT 
                u.ID,
                u.display_name,
                COUNT(DISTINCT s.mezzo_id) as total_mezzi,
                SUM(CASE WHEN s.stato = 'venduto' THEN 1 ELSE 0 END) as venduti,
                SUM(CASE WHEN s.stato = 'riservato' THEN 1 ELSE 0 END) as riservati,
                SUM(s.has_contratto) as con_contratto
            FROM {$wpdb->users} u
            INNER JOIN $table_name s ON u.ID = s.user_id
            GROUP BY u.ID
            ORDER BY venduti DESC, riservati DESC
            LIMIT 5"
        );
        
        ?>
        <div class="shaktiman-dashboard-widget">
            <h3><?php _e( 'Top 5 Rivenditori', 'shaktiman-b2b' ); ?></h3>
            
            <?php if ( empty( $top_venditori ) ) : ?>
                <p><?php _e( 'Nessun dato disponibile.', 'shaktiman-b2b' ); ?></p>
            <?php else : ?>
                <table class="widefat">
                    <thead>
                        <tr>
                            <th><?php _e( 'Rivenditore', 'shaktiman-b2b' ); ?></th>
                            <th><?php _e( 'Venduti', 'shaktiman-b2b' ); ?></th>
                            <th><?php _e( 'Riservati', 'shaktiman-b2b' ); ?></th>
                            <th><?php _e( 'Con Contratto', 'shaktiman-b2b' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $top_venditori as $rivenditore ) : ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html( $rivenditore->display_name ); ?></strong>
                                </td>
                                <td><?php echo esc_html( $rivenditore->venduti ); ?></td>
                                <td><?php echo esc_html( $rivenditore->riservati ); ?></td>
                                <td><?php echo esc_html( $rivenditore->con_contratto ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <p style="margin-top: 15px;">
                    <a href="<?php echo admin_url( 'admin.php?page=shaktiman-b2b-stats' ); ?>" class="button">
                        <?php _e( 'Vedi Report Completo', 'shaktiman-b2b' ); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Aggiungi menu admin
     */
    public function add_admin_menu() {
        if ( ! current_user_can( 'edit_others_posts' ) ) {
            return;
        }
        
        add_menu_page(
            __( 'Statistiche B2B', 'shaktiman-b2b' ),
            __( 'Statistiche B2B', 'shaktiman-b2b' ),
            'edit_others_posts',
            'shaktiman-b2b-stats',
            array( $this, 'render_admin_stats_page' ),
            'dashicons-chart-bar',
            30
        );
    }
    
    /**
     * Renderizza la pagina admin statistiche
     */
    public function render_admin_stats_page() {
        if ( ! current_user_can( 'edit_others_posts' ) ) {
            wp_die( __( 'Non hai i permessi per accedere a questa pagina.', 'shaktiman-b2b' ) );
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . Shaktiman_B2B_Logger::TABLE_RIVENDITORE_STATS;
        
        // Ottieni tutti i rivenditori con attività
        $rivenditori = $wpdb->get_results(
            "SELECT 
                u.ID,
                u.display_name,
                u.user_email,
                COUNT(DISTINCT s.mezzo_id) as total_mezzi,
                SUM(CASE WHEN s.stato = 'venduto' THEN 1 ELSE 0 END) as venduti,
                SUM(CASE WHEN s.stato = 'riservato' THEN 1 ELSE 0 END) as riservati,
                SUM(s.has_contratto) as con_contratto,
                MAX(s.updated_at) as ultimo_aggiornamento
            FROM {$wpdb->users} u
            INNER JOIN $table_name s ON u.ID = s.user_id
            GROUP BY u.ID
            ORDER BY venduti DESC, riservati DESC"
        );
        
        ?>
        <div class="wrap">
            <h1><?php _e( 'Report Statistiche B2B - Tutti i Rivenditori', 'shaktiman-b2b' ); ?></h1>
            
            <?php if ( empty( $rivenditori ) ) : ?>
                <div class="notice notice-info">
                    <p><?php _e( 'Nessun dato disponibile. Le statistiche appariranno dopo le prime attività dei rivenditori.', 'shaktiman-b2b' ); ?></p>
                </div>
            <?php else : ?>
                
                <!-- Statistiche generali -->
                <div class="stats-summary" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 20px 0;">
                    <?php
                    $total_rivenditori = count( $rivenditori );
                    $total_mezzi_venduti = array_sum( array_column( $rivenditori, 'venduti' ) );
                    $total_mezzi_riservati = array_sum( array_column( $rivenditori, 'riservati' ) );
                    $total_contratti = array_sum( array_column( $rivenditori, 'con_contratto' ) );
                    ?>
                    <div style="background: white; padding: 20px; border-left: 4px solid #2196F3; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <h3 style="margin: 0 0 10px; font-size: 14px; color: #666;">👥 Rivenditori Attivi</h3>
                        <p style="margin: 0; font-size: 32px; font-weight: bold; color: #333;"><?php echo $total_rivenditori; ?></p>
                    </div>
                    <div style="background: white; padding: 20px; border-left: 4px solid #4CAF50; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <h3 style="margin: 0 0 10px; font-size: 14px; color: #666;">✅ Totale Venduti</h3>
                        <p style="margin: 0; font-size: 32px; font-weight: bold; color: #333;"><?php echo $total_mezzi_venduti; ?></p>
                    </div>
                    <div style="background: white; padding: 20px; border-left: 4px solid #FF9800; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <h3 style="margin: 0 0 10px; font-size: 14px; color: #666;">📦 Totale Riservati</h3>
                        <p style="margin: 0; font-size: 32px; font-weight: bold; color: #333;"><?php echo $total_mezzi_riservati; ?></p>
                    </div>
                    <div style="background: white; padding: 20px; border-left: 4px solid #9C27B0; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <h3 style="margin: 0 0 10px; font-size: 14px; color: #666;">📄 Totale Contratti</h3>
                        <p style="margin: 0; font-size: 32px; font-weight: bold; color: #333;"><?php echo $total_contratti; ?></p>
                    </div>
                </div>
                
                <!-- Tabella rivenditori -->
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e( 'Rivenditore', 'shaktiman-b2b' ); ?></th>
                            <th><?php _e( 'Email', 'shaktiman-b2b' ); ?></th>
                            <th><?php _e( 'Venduti', 'shaktiman-b2b' ); ?></th>
                            <th><?php _e( 'Riservati', 'shaktiman-b2b' ); ?></th>
                            <th><?php _e( 'Con Contratto', 'shaktiman-b2b' ); ?></th>
                            <th><?php _e( 'Totale Mezzi', 'shaktiman-b2b' ); ?></th>
                            <th><?php _e( 'Ultimo Aggiornamento', 'shaktiman-b2b' ); ?></th>
                            <th><?php _e( 'Azioni', 'shaktiman-b2b' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $rivenditori as $rivenditore ) : ?>
                            <tr>
                                <td><strong><?php echo esc_html( $rivenditore->display_name ); ?></strong></td>
                                <td><?php echo esc_html( $rivenditore->user_email ); ?></td>
                                <td><span style="background: #4CAF50; color: white; padding: 4px 10px; border-radius: 12px; font-weight: 600;"><?php echo $rivenditore->venduti; ?></span></td>
                                <td><span style="background: #FF9800; color: white; padding: 4px 10px; border-radius: 12px; font-weight: 600;"><?php echo $rivenditore->riservati; ?></span></td>
                                <td><span style="background: #2196F3; color: white; padding: 4px 10px; border-radius: 12px; font-weight: 600;"><?php echo $rivenditore->con_contratto; ?></span></td>
                                <td><?php echo $rivenditore->total_mezzi; ?></td>
                                <td><?php echo date_i18n( 'd/m/Y H:i', strtotime( $rivenditore->ultimo_aggiornamento ) ); ?></td>
                                <td>
                                    <a href="<?php echo admin_url( 'user-edit.php?user_id=' . $rivenditore->ID ); ?>" class="button button-small">
                                        <?php _e( 'Profilo', 'shaktiman-b2b' ); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            <?php endif; ?>
            
            <div style="margin-top: 30px;">
                <h2><?php _e( 'Come usare le statistiche', 'shaktiman-b2b' ); ?></h2>
                <p><?php _e( 'Per mostrare le statistiche ai rivenditori, crea una pagina e inserisci lo shortcode:', 'shaktiman-b2b' ); ?></p>
                <code style="background: #f5f5f5; padding: 10px; display: inline-block; border-radius: 4px;">[rivenditore_stats]</code>
                <p style="margin-top: 10px;"><?php _e( 'Per mostrare le statistiche di un rivenditore specifico (solo admin):', 'shaktiman-b2b' ); ?></p>
                <code style="background: #f5f5f5; padding: 10px; display: inline-block; border-radius: 4px;">[rivenditore_stats user_id="123"]</code>
            </div>
        </div>
        <?php
    }
}
