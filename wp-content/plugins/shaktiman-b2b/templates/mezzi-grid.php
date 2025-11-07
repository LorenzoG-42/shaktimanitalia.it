<?php
/**
 * Template griglia mezzi agricoli (shortcode)
 *
 * @package ShaktimanB2B
 */

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$args = array(
    'post_type' => 'mezzo_agricolo',
    'posts_per_page' => $atts['per_page'],
    'paged' => $paged,
    'orderby' => $atts['orderby'],
    'order' => $atts['order'],
);

$query = new WP_Query( $args );
?>

<div class="shaktiman-b2b-grid-wrapper">
    <!-- Filtri -->
    <div class="mezzi-filters">
        <form id="mezzi-filter-form-shortcode" class="filter-form">
            <div class="filter-row">
                <!-- Ricerca -->
                <div class="filter-item filter-search">
                    <label for="search-input"><?php _e( 'Ricerca:', 'shaktiman-b2b' ); ?></label>
                    <input type="text" 
                           id="search-input"
                           name="search" 
                           placeholder="<?php _e( 'Cerca mezzo...', 'shaktiman-b2b' ); ?>"
                           class="filter-input">
                </div>
                
                <!-- Filtro Disponibilità -->
                <div class="filter-item">
                    <label for="disponibilita-filter"><?php _e( 'Filtra per Disponibilità:', 'shaktiman-b2b' ); ?></label>
                    <select name="disponibilita" id="disponibilita-filter" class="filter-select">
                        <option value=""><?php _e( 'Tutte le Disponibilità', 'shaktiman-b2b' ); ?></option>
                        <?php
                        $disponibilita_terms = get_terms( array(
                            'taxonomy' => 'disponibilita',
                            'hide_empty' => true,
                        ) );
                        foreach ( $disponibilita_terms as $term ) {
                            echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . ' (' . $term->count . ')</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Filtro Categoria -->
                <div class="filter-item">
                    <label for="categoria-filter"><?php _e( 'Filtra per Categoria:', 'shaktiman-b2b' ); ?></label>
                    <select name="categoria_mezzo" id="categoria-filter" class="filter-select">
                        <option value=""><?php _e( 'Tutte le Categorie', 'shaktiman-b2b' ); ?></option>
                        <?php
                        $categoria_terms = get_terms( array(
                            'taxonomy' => 'categoria_mezzo',
                            'hide_empty' => true,
                        ) );
                        foreach ( $categoria_terms as $term ) {
                            echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . ' (' . $term->count . ')</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Filtro Modello -->
                <div class="filter-item">
                    <label for="modello-filter"><?php _e( 'Filtra per Modello:', 'shaktiman-b2b' ); ?></label>
                    <select name="modello" id="modello-filter" class="filter-select">
                        <option value=""><?php _e( 'Tutti i Modelli', 'shaktiman-b2b' ); ?></option>
                        <?php
                        $modello_terms = get_terms( array(
                            'taxonomy' => 'modello',
                            'hide_empty' => true,
                        ) );
                        foreach ( $modello_terms as $term ) {
                            echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . ' (' . $term->count . ')</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Filtro Versione -->
                <div class="filter-item">
                    <label for="versione-filter"><?php _e( 'Filtra per Versione:', 'shaktiman-b2b' ); ?></label>
                    <select name="versione" id="versione-filter" class="filter-select">
                        <option value=""><?php _e( 'Tutte le Versioni', 'shaktiman-b2b' ); ?></option>
                        <?php
                        $versione_terms = get_terms( array(
                            'taxonomy' => 'versione',
                            'hide_empty' => true,
                        ) );
                        foreach ( $versione_terms as $term ) {
                            echo '<option value="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . ' (' . $term->count . ')</option>';
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Bottone Reset -->
                <div class="filter-item">
                    <button type="button" class="btn-reset">
                        <?php _e( 'Resetta', 'shaktiman-b2b' ); ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Risultati -->
    <div class="mezzi-results">
        <div class="results-count">
            <span class="results-info">
                <?php
                printf(
                    _n( '%s mezzo trovato', '%s mezzi trovati', $query->found_posts, 'shaktiman-b2b' ),
                    number_format_i18n( $query->found_posts )
                );
                ?>
            </span>
        </div>
        
        <div class="mezzi-grid columns-<?php echo esc_attr( $atts['columns'] ); ?>">
            <?php
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    include SHAKTIMAN_B2B_PLUGIN_DIR . 'templates/content-mezzo.php';
                }
                wp_reset_postdata();
            } else {
                echo '<p class="no-mezzi">' . __( 'Nessun mezzo trovato.', 'shaktiman-b2b' ) . '</p>';
            }
            ?>
        </div>
        
        <!-- Paginazione -->
        <?php
        echo paginate_links( array(
            'total' => $query->max_num_pages,
            'current' => $paged,
            'prev_text' => __( '&laquo; Precedente', 'shaktiman-b2b' ),
            'next_text' => __( 'Successivo &raquo;', 'shaktiman-b2b' ),
        ) );
        ?>
    </div>
    
    <div class="loading-overlay" style="display: none;">
        <div class="loading-spinner"></div>
    </div>
</div>
