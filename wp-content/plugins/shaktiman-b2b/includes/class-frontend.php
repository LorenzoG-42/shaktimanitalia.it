<?php
/**
 * Gestione Frontend
 *
 * @package ShaktimanB2B
 */

// Se questo file viene chiamato direttamente, esce
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Classe per il Frontend
 */
class Shaktiman_B2B_Frontend {
    
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
        // Template loader
        add_filter( 'template_include', array( $this, 'template_loader' ) );
        
        // Shortcode per visualizzare la griglia
        add_shortcode( 'mezzi_agricoli_grid', array( $this, 'render_grid_shortcode' ) );
        
        // AJAX per i filtri
        add_action( 'wp_ajax_filter_mezzi_agricoli', array( $this, 'ajax_filter_mezzi' ) );
        add_action( 'wp_ajax_nopriv_filter_mezzi_agricoli', array( $this, 'ajax_filter_mezzi' ) );
        
        // AJAX per ottenere opzioni filtrate
        add_action( 'wp_ajax_get_filtered_options', array( $this, 'ajax_get_filtered_options' ) );
        add_action( 'wp_ajax_nopriv_get_filtered_options', array( $this, 'ajax_get_filtered_options' ) );
        
        // AJAX per cambiare stato
        add_action( 'wp_ajax_cambia_stato_mezzo', array( $this, 'ajax_cambia_stato' ) );
        
        // AJAX per cambiare ubicazione
        add_action( 'wp_ajax_cambia_ubicazione_mezzo', array( $this, 'ajax_cambia_ubicazione' ) );
        
        // AJAX per generare contratto
        add_action( 'wp_ajax_genera_contratto_mezzo', array( $this, 'ajax_genera_contratto' ) );
        
        // AJAX per ottenere dati mezzo
        add_action( 'wp_ajax_get_mezzo_data', array( $this, 'ajax_get_mezzo_data' ) );
    }
    
    /**
     * Carica i template personalizzati
     */
    public function template_loader( $template ) {
        // Archivio mezzi agricoli
        if ( is_post_type_archive( 'mezzo_agricolo' ) ) {
            $custom_template = $this->locate_template( 'archive-mezzo_agricolo.php' );
            if ( $custom_template ) {
                return $custom_template;
            }
        }
        
        // Singolo mezzo agricolo
        if ( is_singular( 'mezzo_agricolo' ) ) {
            $custom_template = $this->locate_template( 'single-mezzo_agricolo.php' );
            if ( $custom_template ) {
                return $custom_template;
            }
        }
        
        // Archivi tassonomie
        if ( is_tax( array( 'disponibilita', 'categoria_mezzo', 'modello', 'versione', 'ubicazione', 'stato_magazzino' ) ) ) {
            $custom_template = $this->locate_template( 'archive-mezzo_agricolo.php' );
            if ( $custom_template ) {
                return $custom_template;
            }
        }
        
        return $template;
    }
    
    /**
     * Localizza il template
     */
    private function locate_template( $template_name ) {
        // Cerca nel tema child
        $child_theme_template = get_stylesheet_directory() . '/shaktiman-b2b/' . $template_name;
        if ( file_exists( $child_theme_template ) ) {
            return $child_theme_template;
        }
        
        // Cerca nel tema parent
        $parent_theme_template = get_template_directory() . '/shaktiman-b2b/' . $template_name;
        if ( file_exists( $parent_theme_template ) ) {
            return $parent_theme_template;
        }
        
        // Usa il template del plugin
        $plugin_template = SHAKTIMAN_B2B_PLUGIN_DIR . 'templates/' . $template_name;
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
        
        return false;
    }
    
    /**
     * Shortcode per visualizzare la griglia
     */
    public function render_grid_shortcode( $atts ) {
        // Se l'utente non è loggato o non ha permessi, non mostra nulla
        if ( ! is_user_logged_in() || ! Shaktiman_B2B_Roles::is_rivenditore() ) {
            return '<p>' . __( 'Devi essere autenticato come rivenditore per visualizzare questa sezione.', 'shaktiman-b2b' ) . '</p>';
        }
        
        $atts = shortcode_atts( array(
            'per_page' => 12,
            'columns' => 3,
            'orderby' => 'date',
            'order' => 'DESC',
        ), $atts, 'mezzi_agricoli_grid' );
        
        ob_start();
        
        // Include il template della griglia
        $grid_template = $this->locate_template( 'mezzi-grid.php' );
        if ( $grid_template ) {
            include $grid_template;
        }
        
        return ob_get_clean();
    }
    
    /**
     * AJAX per filtrare i mezzi agricoli
     */
    public function ajax_filter_mezzi() {
        check_ajax_referer( 'shaktiman_b2b_nonce', 'nonce' );
        
        if ( ! is_user_logged_in() || ! Shaktiman_B2B_Roles::is_rivenditore() ) {
            wp_send_json_error( array( 'message' => __( 'Accesso negato', 'shaktiman-b2b' ) ) );
        }
        
        $args = array(
            'post_type' => 'mezzo_agricolo',
            'posts_per_page' => isset( $_POST['per_page'] ) ? intval( $_POST['per_page'] ) : 12,
            'paged' => isset( $_POST['paged'] ) ? intval( $_POST['paged'] ) : 1,
            'tax_query' => array(),
        );
        
        // Filtri tassonomie
        $taxonomies = array( 'disponibilita', 'categoria_mezzo', 'modello', 'versione', 'ubicazione', 'stato_magazzino' );
        
        foreach ( $taxonomies as $taxonomy ) {
            if ( ! empty( $_POST[ $taxonomy ] ) ) {
                $args['tax_query'][] = array(
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => sanitize_text_field( $_POST[ $taxonomy ] ),
                );
            }
        }
        
        // Ricerca per keyword
        if ( ! empty( $_POST['search'] ) ) {
            $args['s'] = sanitize_text_field( $_POST['search'] );
        }
        
        $query = new WP_Query( $args );
        
        ob_start();
        
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $content_template = $this->locate_template( 'content-mezzo.php' );
                if ( $content_template ) {
                    include $content_template;
                }
            }
            wp_reset_postdata();
        } else {
            echo '<p class="no-mezzi">' . __( 'Nessun mezzo trovato.', 'shaktiman-b2b' ) . '</p>';
        }
        
        $html = ob_get_clean();
        
        // Genera HTML paginazione manualmente per AJAX
        ob_start();
        if ( $query->max_num_pages > 1 ) {
            $current_page = $args['paged'];
            $total_pages = $query->max_num_pages;
            
            echo '<nav class="navigation pagination" role="navigation" aria-label="' . esc_attr__( 'Articoli', 'shaktiman-b2b' ) . '">';
            echo '<h2 class="screen-reader-text">' . __( 'Navigazione articoli', 'shaktiman-b2b' ) . '</h2>';
            echo '<div class="nav-links">';
            
            // Link Precedente
            if ( $current_page > 1 ) {
                echo '<a class="prev page-numbers" href="?paged=' . ( $current_page - 1 ) . '">' . __( '&laquo; Precedente', 'shaktiman-b2b' ) . '</a>';
            }
            
            // Prima pagina
            if ( $current_page > 3 ) {
                echo '<a class="page-numbers" href="?paged=1"><span class="meta-nav screen-reader-text">' . __( 'Pagina', 'shaktiman-b2b' ) . ' </span>1</a>';
                if ( $current_page > 4 ) {
                    echo '<span class="page-numbers dots">&hellip;</span>';
                }
            }
            
            // Pagine intermedie
            for ( $i = max( 1, $current_page - 2 ); $i <= min( $total_pages, $current_page + 2 ); $i++ ) {
                if ( $i == $current_page ) {
                    echo '<span aria-current="page" class="page-numbers current"><span class="meta-nav screen-reader-text">' . __( 'Pagina', 'shaktiman-b2b' ) . ' </span>' . $i . '</span>';
                } else {
                    echo '<a class="page-numbers" href="?paged=' . $i . '"><span class="meta-nav screen-reader-text">' . __( 'Pagina', 'shaktiman-b2b' ) . ' </span>' . $i . '</a>';
                }
            }
            
            // Ultima pagina
            if ( $current_page < $total_pages - 2 ) {
                if ( $current_page < $total_pages - 3 ) {
                    echo '<span class="page-numbers dots">&hellip;</span>';
                }
                echo '<a class="page-numbers" href="?paged=' . $total_pages . '"><span class="meta-nav screen-reader-text">' . __( 'Pagina', 'shaktiman-b2b' ) . ' </span>' . $total_pages . '</a>';
            }
            
            // Link Successivo
            if ( $current_page < $total_pages ) {
                echo '<a class="next page-numbers" href="?paged=' . ( $current_page + 1 ) . '">' . __( 'Successivo &raquo;', 'shaktiman-b2b' ) . '</a>';
            }
            
            echo '</div>';
            echo '</nav>';
        }
        $pagination_html = ob_get_clean();
        
        wp_send_json_success( array(
            'html' => $html,
            'pagination' => $pagination_html,
            'found_posts' => $query->found_posts,
            'max_pages' => $query->max_num_pages,
            'current_page' => $args['paged'],
        ) );
    }
    
    /**
     * AJAX per cambiare lo stato del mezzo
     */
    public function ajax_cambia_stato() {
        // Verifica nonce
        check_ajax_referer( 'shaktiman_b2b_nonce', 'nonce' );
        
        // Verifica che l'utente sia loggato
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'Devi essere loggato.', 'shaktiman-b2b' ) ) );
        }
        
        // Sanitizza i dati
        $post_id = intval( $_POST['post_id'] );
        $action = sanitize_text_field( $_POST['stato_action'] );
        
        // Verifica che il post esista
        if ( ! get_post( $post_id ) ) {
            wp_send_json_error( array( 'message' => __( 'Mezzo non trovato.', 'shaktiman-b2b' ) ) );
        }
        
        // Salva lo stato precedente per il log
        $disponibilita_terms = get_the_terms( $post_id, 'disponibilita' );
        $stato_precedente = $disponibilita_terms && ! is_wp_error( $disponibilita_terms ) ? $disponibilita_terms[0]->slug : 'disponibile';
        
        // Determina il nuovo stato e raccogli dati
        $nuovo_stato = '';
        $nome_cliente = '';
        $current_user = wp_get_current_user();
        
        // Verifica se l'admin ha selezionato un altro utente
        $rivenditore_id = isset( $_POST['rivenditore_id'] ) && ! empty( $_POST['rivenditore_id'] ) 
            ? intval( $_POST['rivenditore_id'] ) 
            : $current_user->ID;
        
        // Ottieni le informazioni dell'utente che effettua l'ordine
        $utente_ordine = get_userdata( $rivenditore_id );
        if ( ! $utente_ordine ) {
            // Fallback all'utente corrente se l'ID non è valido
            $utente_ordine = $current_user;
            $rivenditore_id = $current_user->ID;
        }
        
        switch ( $action ) {
            case 'riserva':
                $nuovo_stato = 'riservato';
                // Richiedi nome cliente
                if ( empty( $_POST['nome_cliente'] ) ) {
                    wp_send_json_error( array( 
                        'message' => __( 'Nome cliente richiesto.', 'shaktiman-b2b' ),
                        'richiedi_cliente' => true
                    ) );
                }
                $nome_cliente = sanitize_text_field( $_POST['nome_cliente'] );
                update_post_meta( $post_id, '_nome_cliente', $nome_cliente );
                update_post_meta( $post_id, '_data_riservato', current_time( 'Y-m-d H:i:s' ) );
                update_post_meta( $post_id, '_riservato_da_user_id', $rivenditore_id );
                update_post_meta( $post_id, '_riservato_da_user_name', $utente_ordine->display_name );
                break;
                
            case 'venduto':
                $nuovo_stato = 'venduto';
                // Richiedi nome cliente
                if ( empty( $_POST['nome_cliente'] ) ) {
                    wp_send_json_error( array( 
                        'message' => __( 'Nome cliente richiesto.', 'shaktiman-b2b' ),
                        'richiedi_cliente' => true
                    ) );
                }
                $nome_cliente = sanitize_text_field( $_POST['nome_cliente'] );
                update_post_meta( $post_id, '_nome_cliente', $nome_cliente );
                update_post_meta( $post_id, '_data_venduto', current_time( 'Y-m-d H:i:s' ) );
                update_post_meta( $post_id, '_venduto_da_user_id', $rivenditore_id );
                update_post_meta( $post_id, '_venduto_da_user_name', $utente_ordine->display_name );
                break;
                
            case 'libera':
                $nuovo_stato = 'disponibile';
                // Rimuovi i dati cliente e utente
                delete_post_meta( $post_id, '_nome_cliente' );
                delete_post_meta( $post_id, '_data_riservato' );
                delete_post_meta( $post_id, '_data_venduto' );
                delete_post_meta( $post_id, '_riservato_da_user_id' );
                delete_post_meta( $post_id, '_riservato_da_user_name' );
                delete_post_meta( $post_id, '_venduto_da_user_id' );
                delete_post_meta( $post_id, '_venduto_da_user_name' );
                delete_post_meta( $post_id, '_numero_contratto' );
                delete_post_meta( $post_id, '_ragione_sociale' );
                break;
                
            default:
                wp_send_json_error( array( 'message' => __( 'Azione non valida.', 'shaktiman-b2b' ) ) );
        }
        
        // Trova il term ID dello stato
        $term = get_term_by( 'slug', $nuovo_stato, 'disponibilita' );
        if ( ! $term ) {
            wp_send_json_error( array( 'message' => __( 'Stato non trovato.', 'shaktiman-b2b' ) ) );
        }
        
        // Aggiorna la tassonomia
        $result = wp_set_object_terms( $post_id, $term->term_id, 'disponibilita', false );
        
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => __( 'Errore nell\'aggiornamento dello stato.', 'shaktiman-b2b' ) ) );
        }
        
        // Log dell'azione
        $logger = Shaktiman_B2B_Logger::get_instance();
        $logger->log_action( $post_id, $action, array(
            'user_id'      => $rivenditore_id,
            'user_name'    => $utente_ordine->display_name,
            'nome_cliente' => $nome_cliente,
            'old_value'    => $stato_precedente,
            'new_value'    => $nuovo_stato,
        ) );
        
        wp_send_json_success( array( 
            'message' => __( 'Stato aggiornato con successo!', 'shaktiman-b2b' ),
            'nuovo_stato' => $nuovo_stato,
            'nome_stato' => $term->name
        ) );
    }
    
    /**
     * AJAX: Cambia ubicazione mezzo
     */
    public function ajax_cambia_ubicazione() {
        // Verifica nonce
        check_ajax_referer( 'shaktiman_b2b_nonce', 'nonce' );
        
        // Verifica permessi (solo admin)
        if ( ! current_user_can( 'edit_others_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Non hai i permessi per eseguire questa azione.', 'shaktiman-b2b' ) ) );
        }
        
        // Verifica parametri
        if ( empty( $_POST['post_id'] ) || empty( $_POST['ubicazione_id'] ) ) {
            wp_send_json_error( array( 'message' => __( 'Parametri mancanti.', 'shaktiman-b2b' ) ) );
        }
        
        $post_id = intval( $_POST['post_id'] );
        $ubicazione_id = intval( $_POST['ubicazione_id'] );
        
        // Verifica che il post esista
        if ( get_post_type( $post_id ) !== 'mezzo_agricolo' ) {
            wp_send_json_error( array( 'message' => __( 'Mezzo non trovato.', 'shaktiman-b2b' ) ) );
        }
        
        // Verifica che l'ubicazione esista
        $ubicazione = get_term( $ubicazione_id, 'ubicazione' );
        if ( ! $ubicazione || is_wp_error( $ubicazione ) ) {
            wp_send_json_error( array( 'message' => __( 'Ubicazione non valida.', 'shaktiman-b2b' ) ) );
        }
        
        // Salva l'ubicazione precedente per il log
        $ubicazione_precedente_terms = get_the_terms( $post_id, 'ubicazione' );
        $ubicazione_precedente = '';
        if ( $ubicazione_precedente_terms && ! is_wp_error( $ubicazione_precedente_terms ) ) {
            $ubicazione_precedente = $ubicazione_precedente_terms[0]->name;
        }
        
        // Aggiorna l'ubicazione
        $result = wp_set_object_terms( $post_id, $ubicazione_id, 'ubicazione', false );
        
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => __( 'Errore nell\'aggiornamento dell\'ubicazione.', 'shaktiman-b2b' ) ) );
        }
        
        // Log dell'azione
        $logger = Shaktiman_B2B_Logger::get_instance();
        $logger->log_action( $post_id, 'cambio_ubicazione', array(
            'old_value' => $ubicazione_precedente,
            'new_value' => $ubicazione->name,
        ) );
        
        wp_send_json_success( array( 
            'message' => sprintf( __( 'Ubicazione cambiata in: %s', 'shaktiman-b2b' ), $ubicazione->name ),
            'ubicazione_name' => $ubicazione->name
        ) );
    }
    
    /**
     * AJAX: Genera contratto mezzo
     */
    public function ajax_genera_contratto() {
        // Verifica nonce
        check_ajax_referer( 'shaktiman_b2b_nonce', 'nonce' );
        
        // Verifica permessi (solo admin)
        if ( ! current_user_can( 'edit_others_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Non hai i permessi per eseguire questa azione.', 'shaktiman-b2b' ) ) );
        }
        
        // Verifica parametri
        if ( empty( $_POST['post_id'] ) || empty( $_POST['numero_contratto'] ) ) {
            wp_send_json_error( array( 'message' => __( 'Numero contratto obbligatorio.', 'shaktiman-b2b' ) ) );
        }
        
        $post_id = intval( $_POST['post_id'] );
        $numero_contratto = sanitize_text_field( $_POST['numero_contratto'] );
        $ragione_sociale = ! empty( $_POST['ragione_sociale'] ) ? sanitize_text_field( $_POST['ragione_sociale'] ) : '';
        
        // Verifica che il post esista
        if ( get_post_type( $post_id ) !== 'mezzo_agricolo' ) {
            wp_send_json_error( array( 'message' => __( 'Mezzo non trovato.', 'shaktiman-b2b' ) ) );
        }
        
        // Verifica che il mezzo sia venduto
        $disponibilita_terms = get_the_terms( $post_id, 'disponibilita' );
        $stato_attuale = $disponibilita_terms && ! is_wp_error( $disponibilita_terms ) ? $disponibilita_terms[0]->slug : '';
        
        if ( $stato_attuale !== 'venduto' ) {
            wp_send_json_error( array( 'message' => __( 'Il mezzo deve essere venduto per generare un contratto.', 'shaktiman-b2b' ) ) );
        }
        
        // Salva i dati del contratto
        update_post_meta( $post_id, '_numero_contratto', $numero_contratto );
        if ( $ragione_sociale ) {
            update_post_meta( $post_id, '_ragione_sociale', $ragione_sociale );
        }
        
        // Log dell'azione
        $logger = Shaktiman_B2B_Logger::get_instance();
        $logger->log_action( $post_id, 'contratto', array(
            'new_value' => array(
                'numero_contratto' => $numero_contratto,
                'ragione_sociale'  => $ragione_sociale,
            ),
        ) );
        
        // TODO: Implementare generazione PDF
        // Per ora salviamo solo i metadati
        
        wp_send_json_success( array( 
            'message' => __( 'Dati contratto salvati con successo!', 'shaktiman-b2b' ),
            'numero_contratto' => $numero_contratto,
            'ragione_sociale' => $ragione_sociale,
            // 'pdf_url' => '' // URL del PDF quando sarà implementato
        ) );
    }
    
    /**
     * AJAX: Ottieni opzioni filtrate per le tassonomie
     */
    public function ajax_get_filtered_options() {
        check_ajax_referer( 'shaktiman_b2b_nonce', 'nonce' );
        
        if ( ! is_user_logged_in() || ! Shaktiman_B2B_Roles::is_rivenditore() ) {
            wp_send_json_error( array( 'message' => __( 'Accesso negato', 'shaktiman-b2b' ) ) );
        }
        
        // Costruisci query base con i filtri attuali
        $args = array(
            'post_type' => 'mezzo_agricolo',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'tax_query' => array(),
        );
        
        // Applica i filtri esistenti (escluso quello che stiamo aggiornando)
        $taxonomies = array( 'disponibilita', 'categoria_mezzo', 'modello', 'versione', 'ubicazione', 'stato_magazzino' );
        
        foreach ( $taxonomies as $taxonomy ) {
            if ( ! empty( $_POST[ $taxonomy ] ) ) {
                $args['tax_query'][] = array(
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => sanitize_text_field( $_POST[ $taxonomy ] ),
                );
            }
        }
        
        // Esegui la query per ottenere gli ID dei post filtrati
        $query = new WP_Query( $args );
        $post_ids = $query->posts;
        
        // Ottieni le opzioni disponibili per ogni tassonomia
        $available_options = array();
        
        foreach ( $taxonomies as $taxonomy ) {
            $terms = get_terms( array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            ) );
            
            $available_terms = array();
            
            foreach ( $terms as $term ) {
                // Conta quanti post hanno questo term tra i post filtrati
                $term_post_count = 0;
                
                if ( ! empty( $post_ids ) ) {
                    $term_args = array(
                        'post_type' => 'mezzo_agricolo',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                        'post__in' => $post_ids,
                        'tax_query' => array(
                            array(
                                'taxonomy' => $taxonomy,
                                'field' => 'term_id',
                                'terms' => $term->term_id,
                            ),
                        ),
                    );
                    
                    $term_query = new WP_Query( $term_args );
                    $term_post_count = $term_query->found_posts;
                } else {
                    // Se non ci sono filtri, usa il count originale
                    $term_post_count = $term->count;
                }
                
                if ( $term_post_count > 0 ) {
                    $available_terms[] = array(
                        'slug' => $term->slug,
                        'name' => $term->name,
                        'count' => $term_post_count,
                    );
                }
            }
            
            $available_options[ $taxonomy ] = $available_terms;
        }
        
        wp_send_json_success( array(
            'options' => $available_options,
        ) );
    }
    
    /**
     * AJAX: Ottieni dati del mezzo
     */
    public function ajax_get_mezzo_data() {
        // Verifica nonce
        check_ajax_referer( 'shaktiman_b2b_nonce', 'nonce' );
        
        // Verifica che l'utente sia loggato
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( array( 'message' => __( 'Devi essere loggato.', 'shaktiman-b2b' ) ) );
        }
        
        // Sanitizza i dati
        $post_id = intval( $_POST['post_id'] );
        
        // Verifica che il post esista
        if ( ! get_post( $post_id ) ) {
            wp_send_json_error( array( 'message' => __( 'Mezzo non trovato.', 'shaktiman-b2b' ) ) );
        }
        
        // Recupera i dati
        $nome_cliente = get_post_meta( $post_id, '_nome_cliente', true );
        $numero_contratto = get_post_meta( $post_id, '_numero_contratto', true );
        $ragione_sociale = get_post_meta( $post_id, '_ragione_sociale', true );
        
        // Recupera ubicazione corrente
        $ubicazione_terms = get_the_terms( $post_id, 'ubicazione' );
        $ubicazione_id = '';
        if ( $ubicazione_terms && ! is_wp_error( $ubicazione_terms ) ) {
            $ubicazione_id = $ubicazione_terms[0]->term_id;
        }
        
        wp_send_json_success( array(
            'nome_cliente' => $nome_cliente ? $nome_cliente : '',
            'numero_contratto' => $numero_contratto ? $numero_contratto : '',
            'ragione_sociale' => $ragione_sociale ? $ragione_sociale : '',
            'ubicazione_id' => $ubicazione_id,
        ) );
    }
}
