<?php
/**
 * Gestione dei Meta Boxes
 *
 * @package ShaktimanB2B
 */

// Se questo file viene chiamato direttamente, esce
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Classe per i Meta Boxes
 */
class Shaktiman_B2B_Meta_Boxes {
    
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
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post_mezzo_agricolo', array( $this, 'save_meta_boxes' ), 10, 2 );
    }
    
    /**
     * Aggiunge i meta boxes
     */
    public function add_meta_boxes() {
        // Meta box per informazioni cliente
        add_meta_box(
            'mezzo_cliente_info',
            __( 'Informazioni Cliente', 'shaktiman-b2b' ),
            array( $this, 'render_cliente_meta_box' ),
            'mezzo_agricolo',
            'normal',
            'high'
        );
        
        // Meta box per informazioni contratto (solo per admin e reparto vendite)
        if ( Shaktiman_B2B_Roles::can_manage_contracts() ) {
            add_meta_box(
                'mezzo_contratto_info',
                __( 'Informazioni Contratto', 'shaktiman-b2b' ),
                array( $this, 'render_contratto_meta_box' ),
                'mezzo_agricolo',
                'normal',
                'high'
            );
        }
    }
    
    /**
     * Renderizza il meta box informazioni cliente
     */
    public function render_cliente_meta_box( $post ) {
        // Nonce per sicurezza
        wp_nonce_field( 'mezzo_cliente_meta_box', 'mezzo_cliente_meta_box_nonce' );
        
        // Recupera i valori esistenti
        $nome_cliente = get_post_meta( $post->ID, '_nome_cliente', true );
        $data_riservato = get_post_meta( $post->ID, '_data_riservato', true );
        $data_venduto = get_post_meta( $post->ID, '_data_venduto', true );
        
        // Ottieni lo stato dalla tassonomia disponibilità
        $disponibilita_terms = wp_get_post_terms( $post->ID, 'disponibilita' );
        $stato_attuale = ! empty( $disponibilita_terms ) ? $disponibilita_terms[0]->slug : 'disponibile';
        ?>
        <div class="mezzo-cliente-info">
            <p>
                <label for="nome_cliente">
                    <strong><?php _e( 'Nome Cliente:', 'shaktiman-b2b' ); ?></strong>
                </label>
                <input type="text" 
                       id="nome_cliente" 
                       name="nome_cliente" 
                       value="<?php echo esc_attr( $nome_cliente ); ?>" 
                       class="widefat"
                       <?php echo ( 'disponibile' === $stato_attuale ) ? 'disabled' : ''; ?>
                />
                <?php if ( 'disponibile' === $stato_attuale ) : ?>
                    <span class="description">
                        <?php _e( 'Disponibile solo quando il mezzo è riservato o venduto', 'shaktiman-b2b' ); ?>
                    </span>
                <?php endif; ?>
            </p>
            
            <?php if ( 'riservato' === $stato_attuale && $data_riservato ) : ?>
                <p>
                    <strong><?php _e( 'Data Prenotazione:', 'shaktiman-b2b' ); ?></strong>
                    <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $data_riservato ) ) ); ?>
                </p>
            <?php endif; ?>
            
            <?php if ( 'venduto' === $stato_attuale && $data_venduto ) : ?>
                <p>
                    <strong><?php _e( 'Data Vendita:', 'shaktiman-b2b' ); ?></strong>
                    <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $data_venduto ) ) ); ?>
                </p>
            <?php endif; ?>
            
            <p class="description">
                <?php _e( 'Il nome del cliente è obbligatorio quando il mezzo è riservato o venduto.', 'shaktiman-b2b' ); ?>
            </p>
        </div>
        
        <style>
            .mezzo-cliente-info label {
                display: block;
                margin-bottom: 5px;
            }
            .mezzo-cliente-info input[type="text"] {
                margin-bottom: 5px;
            }
            .mezzo-cliente-info p {
                margin: 15px 0;
            }
        </style>
        <?php
    }
    
    /**
     * Renderizza il meta box informazioni contratto
     */
    public function render_contratto_meta_box( $post ) {
        // Nonce per sicurezza
        wp_nonce_field( 'mezzo_contratto_meta_box', 'mezzo_contratto_meta_box_nonce' );
        
        // Recupera i valori esistenti
        $numero_contratto = get_post_meta( $post->ID, '_numero_contratto', true );
        $ragione_sociale = get_post_meta( $post->ID, '_ragione_sociale', true );
        $data_contratto = get_post_meta( $post->ID, '_data_contratto', true );
        
        // Ottieni lo stato dalla tassonomia disponibilità
        $disponibilita_terms = wp_get_post_terms( $post->ID, 'disponibilita' );
        $stato_attuale = ! empty( $disponibilita_terms ) ? $disponibilita_terms[0]->slug : 'disponibile';
        
        $is_venduto = ( 'venduto' === $stato_attuale );
        ?>
        <div class="mezzo-contratto-info">
            <?php if ( ! $is_venduto ) : ?>
                <p class="description">
                    <strong><?php _e( 'Questi campi sono disponibili solo quando il mezzo è venduto.', 'shaktiman-b2b' ); ?></strong>
                </p>
            <?php endif; ?>
            
            <p>
                <label for="numero_contratto">
                    <strong><?php _e( 'Numero Contratto:', 'shaktiman-b2b' ); ?></strong>
                </label>
                <input type="text" 
                       id="numero_contratto" 
                       name="numero_contratto" 
                       value="<?php echo esc_attr( $numero_contratto ); ?>" 
                       class="widefat"
                       <?php echo ! $is_venduto ? 'disabled' : ''; ?>
                />
            </p>
            
            <p>
                <label for="ragione_sociale">
                    <strong><?php _e( 'Ragione Sociale:', 'shaktiman-b2b' ); ?></strong>
                </label>
                <input type="text" 
                       id="ragione_sociale" 
                       name="ragione_sociale" 
                       value="<?php echo esc_attr( $ragione_sociale ); ?>" 
                       class="widefat"
                       <?php echo ! $is_venduto ? 'disabled' : ''; ?>
                />
            </p>
            
            <p>
                <label for="data_contratto">
                    <strong><?php _e( 'Data Contratto:', 'shaktiman-b2b' ); ?></strong>
                </label>
                <input type="date" 
                       id="data_contratto" 
                       name="data_contratto" 
                       value="<?php echo esc_attr( $data_contratto ); ?>" 
                       class="widefat"
                       <?php echo ! $is_venduto ? 'disabled' : ''; ?>
                />
            </p>
            
            <p class="description">
                <?php _e( 'Solo gli utenti con ruolo "Reparto Vendite" o "Amministratore" possono modificare questi campi.', 'shaktiman-b2b' ); ?>
            </p>
        </div>
        
        <style>
            .mezzo-contratto-info label {
                display: block;
                margin-bottom: 5px;
            }
            .mezzo-contratto-info input[type="text"],
            .mezzo-contratto-info input[type="date"] {
                margin-bottom: 5px;
            }
            .mezzo-contratto-info p {
                margin: 15px 0;
            }
        </style>
        <?php
    }
    
    /**
     * Salva i meta boxes
     */
    public function save_meta_boxes( $post_id, $post ) {
        // Verifica nonce per cliente info
        if ( isset( $_POST['mezzo_cliente_meta_box_nonce'] ) ) {
            if ( ! wp_verify_nonce( $_POST['mezzo_cliente_meta_box_nonce'], 'mezzo_cliente_meta_box' ) ) {
                return;
            }
            
            // Salva nome cliente
            if ( isset( $_POST['nome_cliente'] ) ) {
                update_post_meta( $post_id, '_nome_cliente', sanitize_text_field( $_POST['nome_cliente'] ) );
            }
            
            // Gestione date automatiche basate sullo stato
            $disponibilita_terms = wp_get_post_terms( $post_id, 'disponibilita' );
            if ( ! empty( $disponibilita_terms ) ) {
                $stato = $disponibilita_terms[0]->slug;
                
                // Se è riservato, salva la data di prenotazione
                if ( 'riservato' === $stato ) {
                    $data_riservato = get_post_meta( $post_id, '_data_riservato', true );
                    if ( empty( $data_riservato ) ) {
                        update_post_meta( $post_id, '_data_riservato', current_time( 'mysql' ) );
                    }
                }
                
                // Se è venduto, salva la data di vendita
                if ( 'venduto' === $stato ) {
                    $data_venduto = get_post_meta( $post_id, '_data_venduto', true );
                    if ( empty( $data_venduto ) ) {
                        update_post_meta( $post_id, '_data_venduto', current_time( 'mysql' ) );
                    }
                }
                
                // Se torna disponibile, rimuovi le date
                if ( 'disponibile' === $stato ) {
                    delete_post_meta( $post_id, '_data_riservato' );
                    delete_post_meta( $post_id, '_data_venduto' );
                    delete_post_meta( $post_id, '_nome_cliente' );
                }
            }
        }
        
        // Verifica nonce per contratto info (solo per utenti autorizzati)
        if ( isset( $_POST['mezzo_contratto_meta_box_nonce'] ) && Shaktiman_B2B_Roles::can_manage_contracts() ) {
            if ( ! wp_verify_nonce( $_POST['mezzo_contratto_meta_box_nonce'], 'mezzo_contratto_meta_box' ) ) {
                return;
            }
            
            // Salva numero contratto
            if ( isset( $_POST['numero_contratto'] ) ) {
                update_post_meta( $post_id, '_numero_contratto', sanitize_text_field( $_POST['numero_contratto'] ) );
            }
            
            // Salva ragione sociale
            if ( isset( $_POST['ragione_sociale'] ) ) {
                update_post_meta( $post_id, '_ragione_sociale', sanitize_text_field( $_POST['ragione_sociale'] ) );
            }
            
            // Salva data contratto
            if ( isset( $_POST['data_contratto'] ) ) {
                update_post_meta( $post_id, '_data_contratto', sanitize_text_field( $_POST['data_contratto'] ) );
            }
        }
    }
}
