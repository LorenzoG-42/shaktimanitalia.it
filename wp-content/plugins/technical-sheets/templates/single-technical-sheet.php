<?php
/**
 * Single Technical Sheet Template - New Modern Layout
 */

get_header(); 

// Get hero background image from settings
$options = get_option('technical_sheets_options');
$hero_bg_id = isset($options['hero_background_image']) ? $options['hero_background_image'] : '';
$hero_bg_url = $hero_bg_id ? wp_get_attachment_image_url($hero_bg_id, 'full') : '';
?>

<?php while (have_posts()) : the_post(); 
    $post_id = get_the_ID();
    $basic_info = ts_get_basic_info($post_id);
?>

<!-- Hero Section -->
<section class="ts-hero-section" <?php if ($hero_bg_url) echo 'style="background-image: url(' . esc_url($hero_bg_url) . ');"'; ?>>
    <div class="ts-hero-overlay"></div>
    <div class="ts-hero-container">
        <div class="ts-hero-content">
            <div class="ts-hero-text">
                <h1 class="ts-hero-title"><?php the_title(); ?></h1>
                
                <!-- Specifiche chiave -->
                <?php if (!empty($basic_info) && count($basic_info) > 0) : ?>
                    <div class="ts-hero-specs">
                        <?php 
                        // Mostra le prime 3 specifiche
                        $specs_to_show = array_slice($basic_info, 0, 3);
                        foreach ($specs_to_show as $spec) : 
                            if (!empty($spec['label']) && !empty($spec['value'])) :
                        ?>
                            <div class="ts-hero-spec-item">
                                <div class="ts-hero-spec-label"><?php echo esc_html($spec['label']); ?></div>
                                <div class="ts-hero-spec-value"><?php echo esc_html($spec['value']); ?></div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (has_post_thumbnail()) : ?>
                <div class="ts-hero-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<div class="ts-main-content-section">
    <div class="ts-main-container">
        <div class="ts-content-grid">
            <!-- Left Column: Gallery -->
            <div class="ts-content-left">
                <div class="ts-main-image-wrapper">
                    <?php 
                    // Display gallery with lightbox if available
                    $gallery_images = ts_get_gallery_images($post_id);
                    if (!empty($gallery_images)) {
                        ts_display_gallery($post_id);
                    } elseif (has_post_thumbnail()) {
                        // Fallback to featured image
                        echo '<div class="ts-featured-image">';
                        the_post_thumbnail('large');
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Right Column: Description and Specs -->
            <div class="ts-content-right">
                <?php if (get_the_content()) : ?>
                    <div class="ts-description-section">
                        <h2><?php _e('Descrizione', 'technical-sheets'); ?></h2>
                        <div class="ts-description-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($basic_info)) : ?>
                    <div class="ts-specifications-section">
                        <h2><?php _e('Specifiche Tecniche Principali', 'technical-sheets'); ?></h2>
                        <table class="ts-specs-table">
                            <?php foreach ($basic_info as $spec) : 
                                if (!empty($spec['label']) && !empty($spec['value'])) :
                            ?>
                                <tr>
                                    <th><?php echo esc_html($spec['label']); ?></th>
                                    <td><?php echo esc_html($spec['value']); ?></td>
                                </tr>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Accordion and Download Section -->
<div class="ts-details-section">
    <div class="ts-details-container">
        <?php 
        $accordion_sections = ts_get_accordion_sections($post_id);
        if (!empty($accordion_sections)) : 
        ?>
            <div class="ts-accordion-wrapper">
                <h2><?php _e('Specifiche Dettagliate', 'technical-sheets'); ?></h2>
                <?php ts_display_accordion($post_id); ?>
            </div>
        <?php endif; ?>
        
        <!-- Download Buttons -->
        <div class="ts-download-wrapper">
            <h2><?php _e('Scarica', 'technical-sheets'); ?></h2>
            <div class="ts-download-buttons">
                <?php 
                // Print button
                echo '<button class="ts-print-button">';
                echo '<i class="fas fa-print"></i> ' . __('Stampa', 'technical-sheets');
                echo '</button>';
                
                // PDF download button
                $pdf_type = get_post_meta($post_id, '_ts_pdf_type', true) ?: 'upload';
                $pdf_url = '';
                
                if ($pdf_type === 'upload') {
                    $pdf_attachment_id = get_post_meta($post_id, '_ts_pdf_attachment', true);
                    if ($pdf_attachment_id) {
                        $pdf_url = wp_get_attachment_url($pdf_attachment_id);
                    }
                } elseif ($pdf_type === 'link') {
                    $pdf_url = get_post_meta($post_id, '_ts_pdf_external_link', true);
                }
                
                if ($pdf_url) {
                    echo '<a href="' . esc_url($pdf_url) . '" class="ts-pdf-export-button" target="_blank" download>';
                    echo '<i class="fas fa-file-pdf"></i> ' . __('Scheda Tecnica', 'technical-sheets');
                    echo '</a>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Disclaimer Section -->
<div class="ts-disclaimer-section">
    <div class="ts-disclaimer-container">
        <h3><?php _e('Disclaimer', 'technical-sheets'); ?></h3>
        <div class="ts-disclaimer-content">
            <p><?php _e('Le informazioni e le schede tecniche presenti su questo sito sono fornite a titolo puramente gratuito e indicativo. Non viene fornita alcuna garanzia, espressa o implicita, circa l\'accuratezza o l\'aggiornamento dei dati.', 'technical-sheets'); ?></p>
            <p><?php _e('La Bruno Agricoltura non si assume alcuna responsabilità per eventuali errori, omissioni o incongruenze nei contenuti. L\'utilizzo delle informazioni è a esclusivo rischio e responsabilità dell\'utente.', 'technical-sheets'); ?></p>
            <p><?php _e('Si consiglia di verificare sempre i dati tecnici direttamente con i responsabili commerciali della Bruno Agricoltura prima di qualsiasi utilizzo operativo o decisione d\'acquisto.', 'technical-sheets'); ?></p>
            <p><?php _e('Continuando la navigazione o l\'utilizzo dei contenuti, l\'utente accetta espressamente i termini di questa limitazione di responsabilità.', 'technical-sheets'); ?></p>
        </div>
    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
