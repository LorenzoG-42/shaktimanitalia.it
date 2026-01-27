<?php
/**
 * Template per l'archivio mezzi agricoli
 *
 * @package ShaktimanB2B
 */

get_header();
?>

<div class="shaktiman-b2b-archive">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                if ( is_tax() ) {
                    single_term_title();
                } else {
                    echo __( 'Mezzi Agricoli B2B', 'shaktiman-b2b' );
                }
                ?>
            </h1>
            
            <?php
            if ( is_tax() ) {
                $term_description = term_description();
                if ( ! empty( $term_description ) ) {
                    echo '<div class="taxonomy-description">' . $term_description . '</div>';
                }
            }
            ?>
        </header>
        
        <!-- Filtri -->
        <div class="mezzi-filters">
            <form method="get" action="<?php echo get_post_type_archive_link('mezzo_agricolo'); ?>" id="mezzi-filter-form" class="filter-form">
                <div class="filter-row">
                    <!-- Ricerca -->
                    <div class="filter-item filter-search">
                        <label for="mezzi-search"><?php _e( 'Ricerca:', 'shaktiman-b2b' ); ?></label>
                        <input type="text" 
                               name="s" 
                               id="mezzi-search" 
                               value="<?php echo get_search_query(); ?>"
                               placeholder="<?php _e( 'Cerca mezzo...', 'shaktiman-b2b' ); ?>"
                               class="filter-input">
                    </div>
                    
                    <!-- Filtro Disponibilità -->
                    <div class="filter-item">
                        <label for="filter-disponibilita"><?php _e( 'Filtra per Disponibilità:', 'shaktiman-b2b' ); ?></label>
                        <select name="disponibilita" id="filter-disponibilita" class="filter-select">
                            <option value=""><?php _e( 'Tutte le Disponibilità', 'shaktiman-b2b' ); ?></option>
                            <?php
                            $disponibilita_terms = get_terms( array(
                                'taxonomy' => 'disponibilita',
                                'hide_empty' => false,
                            ) );
                            $selected_disponibilita = isset($_GET['disponibilita']) ? sanitize_text_field($_GET['disponibilita']) : '';
                            foreach ( $disponibilita_terms as $term ) {
                                printf(
                                    '<option value="%s"%s>%s (%d)</option>',
                                    esc_attr($term->slug),
                                    selected($selected_disponibilita, $term->slug, false),
                                    esc_html($term->name),
                                    $term->count
                                );
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Filtro Categoria -->
                    <div class="filter-item">
                        <label for="filter-categoria"><?php _e( 'Filtra per Categoria:', 'shaktiman-b2b' ); ?></label>
                        <select name="categoria_mezzo" id="filter-categoria" class="filter-select">
                            <option value=""><?php _e( 'Tutte le Categorie', 'shaktiman-b2b' ); ?></option>
                            <?php
                            $categoria_terms = get_terms( array(
                                'taxonomy' => 'categoria_mezzo',
                                'hide_empty' => false,
                            ) );
                            $selected_categoria = isset($_GET['categoria_mezzo']) ? sanitize_text_field($_GET['categoria_mezzo']) : '';
                            foreach ( $categoria_terms as $term ) {
                                printf(
                                    '<option value="%s"%s>%s (%d)</option>',
                                    esc_attr($term->slug),
                                    selected($selected_categoria, $term->slug, false),
                                    esc_html($term->name),
                                    $term->count
                                );
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Filtro Modello -->
                    <div class="filter-item">
                        <label for="filter-modello"><?php _e( 'Filtra per Modello:', 'shaktiman-b2b' ); ?></label>
                        <select name="modello" id="filter-modello" class="filter-select">
                            <option value=""><?php _e( 'Tutti i Modelli', 'shaktiman-b2b' ); ?></option>
                            <?php
                            $modello_terms = get_terms( array(
                                'taxonomy' => 'modello',
                                'hide_empty' => false,
                            ) );
                            $selected_modello = isset($_GET['modello']) ? sanitize_text_field($_GET['modello']) : '';
                            foreach ( $modello_terms as $term ) {
                                printf(
                                    '<option value="%s"%s>%s (%d)</option>',
                                    esc_attr($term->slug),
                                    selected($selected_modello, $term->slug, false),
                                    esc_html($term->name),
                                    $term->count
                                );
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Filtro Versione -->
                    <div class="filter-item">
                        <label for="filter-versione"><?php _e( 'Filtra per Versione:', 'shaktiman-b2b' ); ?></label>
                        <select name="versione" id="filter-versione" class="filter-select">
                            <option value=""><?php _e( 'Tutte le Versioni', 'shaktiman-b2b' ); ?></option>
                            <?php
                            $versione_terms = get_terms( array(
                                'taxonomy' => 'versione',
                                'hide_empty' => false,
                            ) );
                            $selected_versione = isset($_GET['versione']) ? sanitize_text_field($_GET['versione']) : '';
                            foreach ( $versione_terms as $term ) {
                                printf(
                                    '<option value="%s"%s>%s (%d)</option>',
                                    esc_attr($term->slug),
                                    selected($selected_versione, $term->slug, false),
                                    esc_html($term->name),
                                    $term->count
                                );
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Filtro Ubicazione -->
                    <div class="filter-item">
                        <label for="filter-ubicazione"><?php _e( 'Filtra per Ubicazione:', 'shaktiman-b2b' ); ?></label>
                        <select name="ubicazione" id="filter-ubicazione" class="filter-select">
                            <option value=""><?php _e( 'Tutte le Ubicazioni', 'shaktiman-b2b' ); ?></option>
                            <?php
                            $ubicazione_terms = get_terms( array(
                                'taxonomy' => 'ubicazione',
                                'hide_empty' => false,
                            ) );
                            $selected_ubicazione = isset($_GET['ubicazione']) ? sanitize_text_field($_GET['ubicazione']) : '';
                            foreach ( $ubicazione_terms as $term ) {
                                printf(
                                    '<option value="%s"%s>%s (%d)</option>',
                                    esc_attr($term->slug),
                                    selected($selected_ubicazione, $term->slug, false),
                                    esc_html($term->name),
                                    $term->count
                                );
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Filtro Stato Magazzino -->
                    <div class="filter-item">
                        <label for="filter-stato-magazzino"><?php _e( 'Filtra per Stato Magazzino:', 'shaktiman-b2b' ); ?></label>
                        <select name="stato_magazzino" id="filter-stato-magazzino" class="filter-select">
                            <option value=""><?php _e( 'Tutti gli Stati', 'shaktiman-b2b' ); ?></option>
                            <?php
                            $stato_magazzino_terms = get_terms( array(
                                'taxonomy' => 'stato_magazzino',
                                'hide_empty' => false,
                            ) );
                            $selected_stato = isset($_GET['stato_magazzino']) ? sanitize_text_field($_GET['stato_magazzino']) : '';
                            foreach ( $stato_magazzino_terms as $term ) {
                                printf(
                                    '<option value="%s"%s>%s (%d)</option>',
                                    esc_attr($term->slug),
                                    selected($selected_stato, $term->slug, false),
                                    esc_html($term->name),
                                    $term->count
                                );
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Bottoni azione -->
                    <div class="filter-item filter-actions">
                        <button type="submit" class="btn-search">
                            <?php _e( 'Cerca', 'shaktiman-b2b' ); ?>
                        </button>
                        <a href="<?php echo get_post_type_archive_link('mezzo_agricolo'); ?>" class="btn-reset">
                            <?php _e( 'Reset', 'shaktiman-b2b' ); ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Risultati -->
        <div class="mezzi-results">
            <div class="results-count">
                <span id="results-info">
                    <?php
                    global $wp_query;
                    printf(
                        _n( '%s mezzo trovato', '%s mezzi trovati', $wp_query->found_posts, 'shaktiman-b2b' ),
                        number_format_i18n( $wp_query->found_posts )
                    );
                    ?>
                </span>
            </div>
            
            <div id="mezzi-grid" class="mezzi-grid">
                <?php
                if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();
                        include SHAKTIMAN_B2B_PLUGIN_DIR . 'templates/content-mezzo.php';
                    }
                } else {
                    echo '<p class="no-mezzi">' . __( 'Nessun mezzo trovato.', 'shaktiman-b2b' ) . '</p>';
                }
                ?>
            </div>
            
            <!-- Paginazione -->
            <?php
            // Mantieni i parametri di query per la paginazione
            $query_args = $_GET;
            unset($query_args['paged']);
            $query_string = http_build_query($query_args);
            $base_url = get_post_type_archive_link('mezzo_agricolo');
            $paged_format = ($query_string ? '&' : '?') . 'paged=%#%';
            
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('&laquo; Precedente', 'shaktiman-b2b'),
                'next_text' => __('Successivo &raquo;', 'shaktiman-b2b'),
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Pagina', 'shaktiman-b2b') . ' </span>',
            ));
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
