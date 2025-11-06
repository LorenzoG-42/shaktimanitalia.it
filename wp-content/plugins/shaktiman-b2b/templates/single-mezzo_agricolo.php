<?php
/**
 * Template per il singolo mezzo agricolo
 *
 * @package ShaktimanB2B
 */

get_header();
?>

<div class="shaktiman-b2b-single">
    <div class="container">
        <?php
        while ( have_posts() ) {
            the_post();
            ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'mezzo-single' ); ?>>
                
                <!-- Header con stato e pulsanti -->
                <div class="mezzo-status-header">
                    
                    <!-- Stato e info vendita (sinistra) -->
                    <div>
                        <?php
                        // Disponibilità
                        $disponibilita_terms = get_the_terms( get_the_ID(), 'disponibilita' );
                        $stato = $disponibilita_terms && ! is_wp_error( $disponibilita_terms ) ? $disponibilita_terms[0]->slug : 'disponibile';
                        $nome_cliente = get_post_meta( get_the_ID(), '_nome_cliente', true );
                        
                        if ( $stato === 'disponibile' ) {
                            echo '<h2 class="stato-vendita-title">Stato vendita: <span class="stato-disponibile">Disponibile</span></h2>';
                            
                            // Ubicazione
                            $ubicazione_terms = get_the_terms( get_the_ID(), 'ubicazione' );
                            if ( $ubicazione_terms && ! is_wp_error( $ubicazione_terms ) ) {
                                echo '<p class="stato-ubicazione">' . esc_html( $ubicazione_terms[0]->name ) . '</p>';
                            }
                        } elseif ( $stato === 'riservato' ) {
                            $data_riservato = get_post_meta( get_the_ID(), '_data_riservato', true );
                            $data_formatted = $data_riservato ? date_i18n( 'd/m/Y', strtotime( $data_riservato ) ) : '';
                            $riservato_da = get_post_meta( get_the_ID(), '_riservato_da_user_name', true );
                            $numero_contratto = get_post_meta( get_the_ID(), '_numero_contratto', true );
                            
                            // Formato: Riservato da [Utente] il [Data] A [Cliente] [Numero Protocollo]
                            $testo = 'Riservato';
                            if ( $riservato_da ) {
                                $testo .= ' da ' . esc_html( $riservato_da );
                            }
                            $testo .= ' il ' . $data_formatted;
                            if ( $nome_cliente ) {
                                $testo .= ' A ' . esc_html( $nome_cliente );
                            }
                            if ( $numero_contratto ) {
                                $testo .= ' [' . esc_html( $numero_contratto ) . ']';
                            }
                            
                            echo '<h2 class="stato-vendita-title">Stato vendita: <span class="stato-riservato">' . $testo . '</span></h2>';
                            
                            // Ubicazione
                            $ubicazione_terms = get_the_terms( get_the_ID(), 'ubicazione' );
                            if ( $ubicazione_terms && ! is_wp_error( $ubicazione_terms ) ) {
                                echo '<p class="stato-ubicazione">' . esc_html( $ubicazione_terms[0]->name ) . '</p>';
                            }
                        } elseif ( $stato === 'venduto' ) {
                            $data_venduto = get_post_meta( get_the_ID(), '_data_venduto', true );
                            $data_formatted = $data_venduto ? date_i18n( 'd/m/Y', strtotime( $data_venduto ) ) : '';
                            $venduto_da = get_post_meta( get_the_ID(), '_venduto_da_user_name', true );
                            $numero_contratto = get_post_meta( get_the_ID(), '_numero_contratto', true );
                            
                            // Recupera il ragione sociale se esiste
                            $ragione_sociale = get_post_meta( get_the_ID(), '_ragione_sociale', true );
                            
                            // Ubicazione
                            $ubicazione_terms = get_the_terms( get_the_ID(), 'ubicazione' );
                            $ubicazione_text = '';
                            if ( $ubicazione_terms && ! is_wp_error( $ubicazione_terms ) ) {
                                $ubicazione_text = esc_html( $ubicazione_terms[0]->name );
                            }
                            
                            // Formato: Venduto da [Utente] il [Data] a [Cliente] [Numero Protocollo]
                            $testo = 'Venduto';
                            if ( $venduto_da ) {
                                $testo .= ' da ' . esc_html( $venduto_da );
                            }
                            $testo .= ' il ' . $data_formatted;
                            if ( $nome_cliente ) {
                                $testo .= ' a ' . esc_html( $nome_cliente );
                            }
                            if ( $numero_contratto ) {
                                $testo .= ' [' . esc_html( $numero_contratto ) . ']';
                            }
                            
                            echo '<h2 class="stato-vendita-title">Stato vendita: <span class="stato-venduto">' . $testo . '</span></h2>';
                            
                            // Mostra ubicazione in seconda riga
                            if ( $ubicazione_text ) {
                                echo '<p class="stato-ubicazione">' . $ubicazione_text . '</p>';
                            }
                        }
                        ?>
                    </div>
                    
                    <!-- Pulsanti azione (destra) -->
                    <?php if ( is_user_logged_in() ) : ?>
                        <div class="mezzo-actions-container">
                            <?php
                            // Mostra i pulsanti in base allo stato
                            if ( $stato === 'disponibile' ) :
                                ?>
                                <button class="btn-stato btn-riserva" data-post-id="<?php echo get_the_ID(); ?>" data-action="riserva">
                                    <?php _e( 'RISERVA', 'shaktiman-b2b' ); ?>
                                </button>
                                <button class="btn-stato btn-venduto" data-post-id="<?php echo get_the_ID(); ?>" data-action="venduto">
                                    <?php _e( 'VENDUTO', 'shaktiman-b2b' ); ?>
                                </button>
                                <?php if ( current_user_can( 'edit_others_posts' ) ) : ?>
                                    <button class="btn-stato btn-cambia-ubicazione" data-post-id="<?php echo get_the_ID(); ?>">
                                        <?php _e( 'CAMBIA UBICAZIONE', 'shaktiman-b2b' ); ?>
                                    </button>
                                <?php endif; ?>
                            <?php elseif ( $stato === 'riservato' ) : ?>
                                <button class="btn-stato btn-libera" data-post-id="<?php echo get_the_ID(); ?>" data-action="libera">
                                    <?php _e( 'LIBERA', 'shaktiman-b2b' ); ?>
                                </button>
                                <button class="btn-stato btn-venduto" data-post-id="<?php echo get_the_ID(); ?>" data-action="venduto" data-cliente-esistente="<?php echo esc_attr( $nome_cliente ); ?>">
                                    <?php _e( 'VENDUTO', 'shaktiman-b2b' ); ?>
                                </button>
                                <?php if ( current_user_can( 'edit_others_posts' ) ) : ?>
                                    <button class="btn-stato btn-cambia-ubicazione" data-post-id="<?php echo get_the_ID(); ?>">
                                        <?php _e( 'CAMBIA UBICAZIONE', 'shaktiman-b2b' ); ?>
                                    </button>
                                <?php endif; ?>
                            <?php elseif ( $stato === 'venduto' ) : ?>
                                <?php if ( current_user_can( 'edit_others_posts' ) ) : ?>
                                    <button class="btn-stato btn-libera venduto-state" data-post-id="<?php echo get_the_ID(); ?>" data-action="libera">
                                        <?php _e( 'LIBERA', 'shaktiman-b2b' ); ?>
                                    </button>
                                    <button class="btn-stato btn-contratto" data-post-id="<?php echo get_the_ID(); ?>">
                                        <?php _e( 'CONTRATTO', 'shaktiman-b2b' ); ?>
                                    </button>
                                    <button class="btn-stato btn-cambia-ubicazione" data-post-id="<?php echo get_the_ID(); ?>">
                                        <?php _e( 'CAMBIA UBICAZIONE', 'shaktiman-b2b' ); ?>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="entry-content">
                    <div class="mezzo-layout">
                        <div class="mezzo-images">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="mezzo-thumbnail">
                                    <?php the_post_thumbnail( 'large' ); ?>
                                </div>
                            <?php else : ?>
                                <div class="mezzo-thumbnail-placeholder">
                                    <span><?php _e( 'Nessuna immagine disponibile', 'shaktiman-b2b' ); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mezzo-details">
                            <div class="mezzo-description">
                                <h2><?php _e( 'Descrizione', 'shaktiman-b2b' ); ?></h2>
                                <?php the_content(); ?>
                            </div>
                            <!-- Tabella attributi prodotto con sfondo grigio alternato -->
                            <div class="mezzo-attributes-table">
                                <?php
                                $attributes = array();
                                
                                // Modello (dal titolo o custom field)
                                $modello = get_post_meta( get_the_ID(), '_modello', true );
                                if ( $modello ) {
                                    $attributes[] = array( 'label' => 'MODELLO:', 'value' => $modello );
                                }
                                
                                // CODE (custom field)
                                $code = get_post_meta( get_the_ID(), '_code', true );
                                if ( $code ) {
                                    $attributes[] = array( 'label' => 'CODE:', 'value' => $code );
                                }
                                
                                // Marchio
                                $marchio_terms = get_the_terms( get_the_ID(), 'marchio' );
                                if ( $marchio_terms && ! is_wp_error( $marchio_terms ) ) {
                                    $attributes[] = array( 'label' => 'MARCA:', 'value' => $marchio_terms[0]->name );
                                }
                                
                                // Listino (prezzo)
                                $listino = get_post_meta( get_the_ID(), '_listino', true );
                                if ( $listino ) {
                                    $attributes[] = array( 'label' => 'LISTINO:', 'value' => '€ ' . number_format( floatval( $listino ), 2, '.', '.' ) );
                                }
                                
                                // Note
                                $note = get_post_meta( get_the_ID(), '_note', true );
                                if ( $note ) {
                                    $attributes[] = array( 'label' => 'NOTE:', 'value' => $note );
                                }
                                
                                // Optional
                                $optional = get_post_meta( get_the_ID(), '_optional', true );
                                if ( $optional ) {
                                    $attributes[] = array( 'label' => 'OPTIONAL:', 'value' => $optional );
                                }
                                
                                // Stampa gli attributi con sfondo alternato
                                $count = 0;
                                foreach ( $attributes as $attr ) {
                                    $bg_color = $count % 2 === 0 ? '#f5f5f5' : '#ffffff';
                                    echo '<div style="display: flex; padding: 12px 20px; background: ' . $bg_color . ';">';
                                    echo '<strong style="min-width: 120px; color: #666; font-size: 14px;">' . esc_html( $attr['label'] ) . '</strong>';
                                    echo '<span style="color: #333; font-size: 14px;">' . esc_html( $attr['value'] ) . '</span>';
                                    echo '</div>';
                                    $count++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <footer class="entry-footer">
                    <a href="<?php echo get_post_type_archive_link( 'mezzo_agricolo' ); ?>" class="btn-back">
                        &larr; <?php _e( 'Torna all\'elenco', 'shaktiman-b2b' ); ?>
                    </a>
                </footer>
            </article>
            
        <?php } ?>
    </div>
</div>

<!-- Modale per cambio stato -->
<div id="shaktiman-modal" class="shaktiman-modal-overlay">
    <div class="shaktiman-modal">
        <div class="modal-header">
            <h3 id="modal-title"><?php _e( 'Cambia Stato', 'shaktiman-b2b' ); ?></h3>
            <button class="modal-close" data-modal="shaktiman">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label for="modal-nome-cliente"><?php _e( 'Nome Cliente:', 'shaktiman-b2b' ); ?> <span class="label-required">*</span></label>
                <input type="text" id="modal-nome-cliente" placeholder="<?php _e( 'Inserisci il nome del cliente', 'shaktiman-b2b' ); ?>" required>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" data-modal="shaktiman">
                <?php _e( 'Annulla', 'shaktiman-b2b' ); ?>
            </button>
            <button class="modal-btn modal-btn-confirm" id="modal-confirm-btn">
                <?php _e( 'Conferma', 'shaktiman-b2b' ); ?>
            </button>
        </div>
    </div>
</div>

<!-- Modale per cambio ubicazione -->
<div id="ubicazione-modal" class="shaktiman-modal-overlay">
    <div class="shaktiman-modal">
        <div class="modal-header">
            <h3><?php _e( 'Cambia Ubicazione', 'shaktiman-b2b' ); ?></h3>
            <button class="modal-close" data-modal="ubicazione">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label for="modal-ubicazione"><?php _e( 'Seleziona Ubicazione:', 'shaktiman-b2b' ); ?> <span class="label-required">*</span></label>
                <select id="modal-ubicazione" required>
                    <option value=""><?php _e( 'Seleziona...', 'shaktiman-b2b' ); ?></option>
                    <?php
                    $ubicazioni = get_terms( array(
                        'taxonomy' => 'ubicazione',
                        'hide_empty' => false,
                    ) );
                    if ( ! empty( $ubicazioni ) && ! is_wp_error( $ubicazioni ) ) :
                        foreach ( $ubicazioni as $ubicazione ) :
                            ?>
                            <option value="<?php echo esc_attr( $ubicazione->term_id ); ?>">
                                <?php echo esc_html( $ubicazione->name ); ?>
                            </option>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" data-modal="ubicazione">
                <?php _e( 'Annulla', 'shaktiman-b2b' ); ?>
            </button>
            <button class="modal-btn modal-btn-confirm" id="ubicazione-confirm-btn">
                <?php _e( 'Conferma', 'shaktiman-b2b' ); ?>
            </button>
        </div>
    </div>
</div>

<!-- Modale per contratto -->
<div id="contratto-modal" class="shaktiman-modal-overlay">
    <div class="shaktiman-modal">
        <div class="modal-header">
            <h3><?php _e( 'Genera Contratto', 'shaktiman-b2b' ); ?></h3>
            <button class="modal-close" data-modal="contratto">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label for="modal-numero-contratto"><?php _e( 'Numero Contratto:', 'shaktiman-b2b' ); ?> <span class="label-required">*</span></label>
                <input type="text" id="modal-numero-contratto" placeholder="<?php _e( 'Inserisci il numero di protocollo', 'shaktiman-b2b' ); ?>" required>
            </div>
            <div class="modal-field">
                <label for="modal-ragione-sociale"><?php _e( 'Ragione Sociale:', 'shaktiman-b2b' ); ?></label>
                <input type="text" id="modal-ragione-sociale" placeholder="<?php _e( 'Inserisci la ragione sociale', 'shaktiman-b2b' ); ?>">
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" data-modal="contratto">
                <?php _e( 'Annulla', 'shaktiman-b2b' ); ?>
            </button>
            <button class="modal-btn modal-btn-confirm" id="contratto-confirm-btn">
                <?php _e( 'Salva e Genera PDF', 'shaktiman-b2b' ); ?>
            </button>
        </div>
    </div>
</div>

<!-- Modale di conferma generica -->
<div id="confirm-modal" class="shaktiman-modal-overlay">
    <div class="shaktiman-modal modal-small">
        <div class="modal-header">
            <h3 id="confirm-title"><?php _e( 'Conferma', 'shaktiman-b2b' ); ?></h3>
            <button class="modal-close" data-modal="confirm">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirm-message" class="modal-message"></p>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" data-modal="confirm">
                <?php _e( 'Annulla', 'shaktiman-b2b' ); ?>
            </button>
            <button class="modal-btn modal-btn-confirm" id="confirm-btn-ok">
                <?php _e( 'Conferma', 'shaktiman-b2b' ); ?>
            </button>
        </div>
    </div>
</div>

<!-- Modale di notifica generica -->
<div id="notify-modal" class="shaktiman-modal-overlay">
    <div class="shaktiman-modal modal-small">
        <div class="modal-header">
            <h3 id="notify-title"><?php _e( 'Notifica', 'shaktiman-b2b' ); ?></h3>
            <button class="modal-close" data-modal="notify">&times;</button>
        </div>
        <div class="modal-body">
            <p id="notify-message" class="modal-message"></p>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-confirm" id="notify-btn-ok">
                <?php _e( 'OK', 'shaktiman-b2b' ); ?>
            </button>
        </div>
    </div>
</div>

<?php
get_footer();
