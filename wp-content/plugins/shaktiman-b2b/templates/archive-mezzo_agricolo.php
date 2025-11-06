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
                        <input type="text" 
                               name="search" 
                               id="mezzi-search" 
                               placeholder="<?php _e( 'Cerca mezzo...', 'shaktiman-b2b' ); ?>"
                               class="filter-input">
                    </div>
                    
                    <!-- Filtro Disponibilità -->
                    <div class="filter-item">
                        <select name="disponibilita" id="filter-disponibilita" class="filter-select">
                            <option value=""><?php _e( 'Tutte le disponibilità', 'shaktiman-b2b' ); ?></option>
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
                        <select name="categoria_mezzo" id="filter-categoria" class="filter-select">
                            <option value=""><?php _e( 'Tutte le categorie', 'shaktiman-b2b' ); ?></option>
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
                    
                    <!-- Filtro Marchio -->
                    <div class="filter-item">
                        <select name="marchio" id="filter-marchio" class="filter-select">
                            <option value=""><?php _e( 'Tutti i marchi', 'shaktiman-b2b' ); ?></option>
                            <?php
                            $marchio_terms = get_terms( array(
                                'taxonomy' => 'marchio',
                                'hide_empty' => false,
                            ) );
                            foreach ( $marchio_terms as $term ) {
                                echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Filtro Ubicazione -->
                    <div class="filter-item">
                        <select name="ubicazione" id="filter-ubicazione" class="filter-select">
                            <option value=""><?php _e( 'Tutte le ubicazioni', 'shaktiman-b2b' ); ?></option>
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
                        <select name="stato_magazzino" id="filter-stato-magazzino" class="filter-select">
                            <option value=""><?php _e( 'Tutti gli stati', 'shaktiman-b2b' ); ?></option>
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
            the_posts_pagination( array(
                'mid_size' => 2,
                'prev_text' => __( '&laquo; Precedente', 'shaktiman-b2b' ),
                'next_text' => __( 'Successivo &raquo;', 'shaktiman-b2b' ),
            ) );
            ?>
        </div>
        
        <div id="loading-overlay" class="loading-overlay" style="display: none;">
            <div class="loading-spinner"></div>
        </div>
    </div>
</div>

<?php
get_footer();
