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
            <form id="mezzi-filter-form" class="filter-form">
                <div class="filter-row">
                    <!-- Ricerca -->
                    <div class="filter-item filter-search">
                        <label for="mezzi-search"><?php _e( 'Ricerca:', 'shaktiman-b2b' ); ?></label>
                        <input type="text" 
                               name="search" 
                               id="mezzi-search" 
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
                            foreach ( $disponibilita_terms as $term ) {
                                echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
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
                            foreach ( $categoria_terms as $term ) {
                                echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
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
                            foreach ( $modello_terms as $term ) {
                                echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
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
                            foreach ( $versione_terms as $term ) {
                                echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
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
                            foreach ( $ubicazione_terms as $term ) {
                                echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
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
                            foreach ( $stato_magazzino_terms as $term ) {
                                echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Bottone Reset -->
                    <div class="filter-item">
                        <button type="button" id="reset-filters" class="btn-reset">
                            <?php _e( 'Resetta Filtri', 'shaktiman-b2b' ); ?>
                        </button>
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
            if ( $wp_query->max_num_pages > 1 ) :
                $current_page = max( 1, get_query_var( 'paged' ) );
                $total_pages = $wp_query->max_num_pages;
            ?>
            <nav class="navigation pagination" role="navigation" aria-label="<?php esc_attr_e( 'Articoli', 'shaktiman-b2b' ); ?>">
                <h2 class="screen-reader-text"><?php _e( 'Navigazione articoli', 'shaktiman-b2b' ); ?></h2>
                <div class="nav-links">
                    <?php
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
                    ?>
                </div>
            </nav>
            <?php endif; ?>
        </div>
        
        <div id="loading-overlay" class="loading-overlay" style="display: none;">
            <div class="loading-spinner"></div>
        </div>
    </div>
</div>

<?php
get_footer();
