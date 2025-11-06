<?php
/**
 * Template per il singolo mezzo nella griglia
 *
 * @package ShaktimanB2B
 */

$disponibilita_terms = get_the_terms( get_the_ID(), 'disponibilita' );
$stato = 'disponibile';
if ( $disponibilita_terms && ! is_wp_error( $disponibilita_terms ) ) {
    $stato = $disponibilita_terms[0]->slug;
}
?>

<div class="mezzo-item stato-<?php echo esc_attr( $stato ); ?>">
    <div class="mezzo-card">
        <!-- Immagine -->
        <div class="mezzo-image">
            <a href="<?php the_permalink(); ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'medium_large' ); ?>
                <?php else : ?>
                    <div class="mezzo-placeholder">
                        <span><?php _e( 'Nessuna immagine', 'shaktiman-b2b' ); ?></span>
                    </div>
                <?php endif; ?>
                
                <!-- Badge stato -->
                <span class="mezzo-badge badge-<?php echo esc_attr( $stato ); ?>">
                    <?php
                    if ( $disponibilita_terms && ! is_wp_error( $disponibilita_terms ) ) {
                        echo esc_html( $disponibilita_terms[0]->name );
                    }
                    ?>
                </span>
            </a>
        </div>
        
        <!-- Contenuto -->
        <div class="mezzo-content">
            <h3 class="mezzo-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            
            <!-- Meta info -->
            <div class="mezzo-meta-info">
                <?php
                // Marchio
                $marchio_terms = get_the_terms( get_the_ID(), 'marchio' );
                if ( $marchio_terms && ! is_wp_error( $marchio_terms ) ) {
                    echo '<span class="mezzo-marchio">';
                    echo '<strong>' . __( 'Marchio:', 'shaktiman-b2b' ) . '</strong> ';
                    echo esc_html( $marchio_terms[0]->name );
                    echo '</span>';
                }
                
                // Categoria
                $categoria_terms = get_the_terms( get_the_ID(), 'categoria_mezzo' );
                if ( $categoria_terms && ! is_wp_error( $categoria_terms ) ) {
                    echo '<span class="mezzo-categoria">';
                    echo '<strong>' . __( 'Categoria:', 'shaktiman-b2b' ) . '</strong> ';
                    echo esc_html( $categoria_terms[0]->name );
                    echo '</span>';
                }
                
                // Ubicazione
                $ubicazione_terms = get_the_terms( get_the_ID(), 'ubicazione' );
                if ( $ubicazione_terms && ! is_wp_error( $ubicazione_terms ) ) {
                    echo '<span class="mezzo-ubicazione">';
                    echo '<strong>' . __( 'Ubicazione:', 'shaktiman-b2b' ) . '</strong> ';
                    echo esc_html( $ubicazione_terms[0]->name );
                    echo '</span>';
                }
                
                // Stato magazzino
                $stato_magazzino_terms = get_the_terms( get_the_ID(), 'stato_magazzino' );
                if ( $stato_magazzino_terms && ! is_wp_error( $stato_magazzino_terms ) ) {
                    echo '<span class="mezzo-stato-magazzino">';
                    echo esc_html( $stato_magazzino_terms[0]->name );
                    echo '</span>';
                }
                ?>
            </div>
            
            <!-- Excerpt -->
            <?php if ( has_excerpt() ) : ?>
                <div class="mezzo-excerpt">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
            
            <!-- Cliente (se riservato o venduto) -->
            <?php
            $nome_cliente = get_post_meta( get_the_ID(), '_nome_cliente', true );
            if ( $nome_cliente && ( 'riservato' === $stato || 'venduto' === $stato ) ) {
                echo '<div class="mezzo-cliente">';
                echo '<strong>' . __( 'Cliente:', 'shaktiman-b2b' ) . '</strong> ';
                echo esc_html( $nome_cliente );
                echo '</div>';
            }
            ?>
            
            <!-- Footer card -->
            <div class="mezzo-footer">
                <a href="<?php the_permalink(); ?>" class="btn-dettagli">
                    <?php _e( 'Vedi Dettagli', 'shaktiman-b2b' ); ?>
                </a>
            </div>
        </div>
    </div>
</div>
