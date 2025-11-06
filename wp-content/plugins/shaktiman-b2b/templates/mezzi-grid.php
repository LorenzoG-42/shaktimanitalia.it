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
                    <input type="text" 
                           name="search" 
                           placeholder="<?php _e( 'Cerca mezzo...', 'shaktiman-b2b' ); ?>"
                           class="filter-input">
                </div>
                
                <!-- Filtro Disponibilità -->
                <div class="filter-item">
                    <select name="disponibilita" class="filter-select">
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
                    <select name="categoria_mezzo" class="filter-select">
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
                    <select name="marchio" class="filter-select">
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
