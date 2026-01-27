<?php
/**
 * Template griglia mezzi agricoli (shortcode)
 *
 * @package ShaktimanB2B
 */

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

// Prepara args per la query
$args = array(
    'post_type' => 'mezzo_agricolo',
    'posts_per_page' => $atts['per_page'],
    'paged' => $paged,
    'orderby' => $atts['orderby'],
    'order' => $atts['order'],
);

// Gestione filtri tassonomie (GET parameters)
$tax_query = array();

if ( isset( $_GET['disponibilita'] ) && ! empty( $_GET['disponibilita'] ) ) {
    $tax_query[] = array(
        'taxonomy' => 'disponibilita',
        'field'    => 'slug',
        'terms'    => sanitize_text_field( $_GET['disponibilita'] ),
    );
}

if ( isset( $_GET['categoria_mezzo'] ) && ! empty( $_GET['categoria_mezzo'] ) ) {
    $tax_query[] = array(
        'taxonomy' => 'categoria_mezzo',
        'field'    => 'slug',
        'terms'    => sanitize_text_field( $_GET['categoria_mezzo'] ),
    );
}

if ( isset( $_GET['modello'] ) && ! empty( $_GET['modello'] ) ) {
    $tax_query[] = array(
        'taxonomy' => 'modello',
        'field'    => 'slug',
        'terms'    => sanitize_text_field( $_GET['modello'] ),
    );
}

if ( isset( $_GET['versione'] ) && ! empty( $_GET['versione'] ) ) {
    $tax_query[] = array(
        'taxonomy' => 'versione',
        'field'    => 'slug',
        'terms'    => sanitize_text_field( $_GET['versione'] ),
    );
}

if ( ! empty( $tax_query ) ) {
    if ( count( $tax_query ) > 1 ) {
        $tax_query['relation'] = 'AND';
    }
    $args['tax_query'] = $tax_query;
}

// Gestione ricerca
if ( isset( $_GET['s'] ) && ! empty( $_GET['s'] ) ) {
    $args['s'] = sanitize_text_field( $_GET['s'] );
}

$query = new WP_Query( $args );
?>

<div class="shaktiman-b2b-grid-wrapper">
    <!-- Filtri -->
    <div class="mezzi-filters">
        <form method="get" action="" id="mezzi-filter-form-shortcode" class="filter-form">
            <div class="filter-row">
                <!-- Ricerca -->
                <div class="filter-item filter-search">
                    <label for="search-input"><?php _e( 'Ricerca:', 'shaktiman-b2b' ); ?></label>
                    <input type="text" 
                           id="search-input"
                           name="s" 
                           value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>"
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
                    <label for="categoria-filter"><?php _e( 'Filtra per Categoria:', 'shaktiman-b2b' ); ?></label>
                    <select name="categoria_mezzo" id="categoria-filter" class="filter-select">
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
                    <label for="modello-filter"><?php _e( 'Filtra per Modello:', 'shaktiman-b2b' ); ?></label>
                    <select name="modello" id="modello-filter" class="filter-select">
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
                    <label for="versione-filter"><?php _e( 'Filtra per Versione:', 'shaktiman-b2b' ); ?></label>
                    <select name="versione" id="versione-filter" class="filter-select">
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
                
                <!-- Bottoni azione -->
                <div class="filter-item filter-actions">
                    <button type="submit" class="btn-search">
                        <?php _e( 'Cerca', 'shaktiman-b2b' ); ?>
                    </button>
                    <a href="<?php echo remove_query_arg( array('s', 'disponibilita', 'categoria_mezzo', 'modello', 'versione', 'paged') ); ?>" class="btn-reset">
                        <?php _e( 'Reset', 'shaktiman-b2b' ); ?>
                    </a>
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
        // Mantieni i parametri di query per la paginazione
        $query_args = $_GET;
        unset($query_args['paged']);
        $base_url = add_query_arg($query_args, get_permalink());
        
        echo paginate_links( array(
            'total' => $query->max_num_pages,
            'current' => $paged,
            'prev_text' => __( '&laquo; Precedente', 'shaktiman-b2b' ),
            'next_text' => __( 'Successivo &raquo;', 'shaktiman-b2b' ),
            'base' => $base_url . '%_%',
            'format' => '?paged=%#%',
            'add_args' => false,
        ) );
        ?>
    </div>
</div>
