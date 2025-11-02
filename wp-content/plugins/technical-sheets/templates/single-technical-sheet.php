<?php
/**
 * Single Technical Sheet Template
 */

get_header(); ?>

<div class="ts-container">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('ts-single-sheet'); ?>>
            
            <header class="ts-header">
                <h1 class="product-title"><?php the_title(); ?></h1>
                <div class="product-border fusion-separator sep-double sep-solid"></div>
                <?php ts_display_sheet_meta(get_the_ID(), 'ts-meta-header'); ?>
            </header>
            
            <div class="ts-content-wrapper">
                <div class="ts-row">
                    <!-- Sezione 1: Gallery (50%) -->
                    <div class="ts-column ts-column-50">
                        <div class="ts -section ts-gallery-section">
                            <?php if (has_post_thumbnail()): ?>
                                <div class="ts-featured-image">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php ts_display_gallery(get_the_ID()); ?>
                        </div>
                    </div>
                    
                    <!-- Sezione 2: Basic Information (50%) -->
                    <div class="ts-column ts-column-50">


                        <div class="ts-section ts-content-section">
                            <?php if (get_the_content()): ?>
                                <h3><?php _e('Descrizione', 'technical-sheets'); ?></h3>
                                <div class="ts-post-content">
                                    <?php the_content(); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="ts-section ts-basic-info-section">
                            <?php ts_display_basic_info(get_the_ID()); ?>
                        </div>

                        <div class="ts-section ts-accordion-section">
                            <?php ts_display_accordion(get_the_ID()); ?>
                        </div>

                        <div class="ts-section ts-download-section-wrapper">
                            <?php ts_display_download_section(get_the_ID(), 'no-print'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="ts-row">
                    <!-- Sezione 3: Content (50%) -->
                    <div class="ts-column ts-column-50">

                    </div>
                    
                    <!-- Sezione 4: Accordion (50%) -->
                    <div class="ts-column ts-column-50">
                        
                    </div>
                </div>
            </div>
            
            <footer class="ts-footer">
                <div class="ts-disclaimer">
                    <h4><?php _e('Disclaimer', 'technical-sheets'); ?></h4>
                    <p><?php _e('Le informazioni e le schede tecniche presenti su questo sito sono fornite a titolo puramente gratuito e indicativo. Non viene fornita alcuna garanzia, espressa o implicita, circa l\'accuratezza o l\'aggiornamento dei dati.', 'technical-sheets'); ?></p>
                    <p><?php _e('La Bruno Agricoltura non si assume alcuna responsabilità per eventuali errori, omissioni o incongruenze nei contenuti. L\'utilizzo delle informazioni è a esclusivo rischio e responsabilità dell\'utente.', 'technical-sheets'); ?></p>
                    <p><?php _e('Si consiglia di verificare sempre i dati tecnici direttamente con i responsabili commerciali della Bruno Agricoltura prima di qualsiasi utilizzo operativo o decisione d\'acquisto.', 'technical-sheets'); ?></p>
                    <p><?php _e('Continuando la navigazione o l\'utilizzo dei contenuti, l\'utente accetta espressamente i termini di questa limitazione di responsabilità.', 'technical-sheets'); ?></p>
                </div>
                
                <div class="ts-navigation">
                    <div class="ts-nav-previous">
                        <?php previous_post_link('%link', '<span class="nav-subtitle">' . __('Previous:', 'technical-sheets') . '</span> <span class="nav-title">%title</span>', true, '', 'technical_sheet_type'); ?>
                    </div>
                    <div class="ts-nav-next">
                        <?php next_post_link('%link', '<span class="nav-subtitle">' . __('Next:', 'technical-sheets') . '</span> <span class="nav-title">%title</span>', true, '', 'technical_sheet_type'); ?>
                    </div>
                </div>
            </footer>
            
        </article>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
