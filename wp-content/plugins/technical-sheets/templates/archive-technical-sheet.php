<?php
/**
 * Archive Technical Sheets Template
 */

get_header(); ?>

<div class="ts-archive-container">
    <header class="ts-archive-header">
        <h1 class="ts-archive-title"><?php _e('Schede Tecniche', 'technical-sheets'); ?></h1>
        
        <?php if (is_tax()): ?>
            <div class="ts-taxonomy-description">
                <?php the_archive_description(); ?>
            </div>
        <?php endif; ?>
    </header>

    <!-- Filtri per categorie e tipologie -->
    <div class="ts-filters-container">
        <form method="get" action="<?php echo get_post_type_archive_link('technical_sheet'); ?>" class="ts-filters-form">
            
            
            <div class="ts-filter-group">
                <label for="ts_search_filter"><?php _e('Ricerca:', 'technical-sheets'); ?></label>
                <input type="text" name="s" id="ts_search_filter" class="ts-filter-input" 
                       value="<?php echo get_search_query(); ?>" 
                       placeholder="<?php _e('Cerca schede tecniche...', 'technical-sheets'); ?>">
            </div>
            
            <div class="ts-filter-group">
                <label for="ts_category_filter"><?php _e('Filtra per Categoria:', 'technical-sheets'); ?></label>
                <select name="technical_sheet_category" id="ts_category_filter" class="ts-filter-select">
                    <option value=""><?php _e('Tutte le Categorie', 'technical-sheets'); ?></option>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'technical_sheet_category',
                        'hide_empty' => true,
                    ));
                    
                    $selected_category = isset($_GET['technical_sheet_category']) ? sanitize_text_field($_GET['technical_sheet_category']) : '';
                    
                    if (!is_wp_error($categories) && !empty($categories)) {
                        foreach ($categories as $category) {
                            printf(
                                '<option value="%s"%s>%s (%d)</option>',
                                esc_attr($category->slug),
                                selected($selected_category, $category->slug, false),
                                esc_html($category->name),
                                $category->count
                            );
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="ts-filter-group">
                <label for="ts_model_filter"><?php _e('Filtra per Modello:', 'technical-sheets'); ?></label>
                <select name="technical_sheet_model" id="ts_model_filter" class="ts-filter-select">
                    <option value=""><?php _e('Tutti i Modelli', 'technical-sheets'); ?></option>
                    <?php
                    $models = get_terms(array(
                        'taxonomy' => 'technical_sheet_model',
                        'hide_empty' => true,
                    ));
                    
                    $selected_model = isset($_GET['technical_sheet_model']) ? sanitize_text_field($_GET['technical_sheet_model']) : '';
                    
                    if (!is_wp_error($models) && !empty($models)) {
                        foreach ($models as $model) {
                            printf(
                                '<option value="%s"%s>%s (%d)</option>',
                                esc_attr($model->slug),
                                selected($selected_model, $model->slug, false),
                                esc_html($model->name),
                                $model->count
                            );
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="ts-filter-group">
                <label for="ts_version_filter"><?php _e('Filtra per Versione:', 'technical-sheets'); ?></label>
                <select name="technical_sheet_version" id="ts_version_filter" class="ts-filter-select">
                    <option value=""><?php _e('Tutte le Versioni', 'technical-sheets'); ?></option>
                    <?php
                    $versions = get_terms(array(
                        'taxonomy' => 'technical_sheet_version',
                        'hide_empty' => true,
                    ));
                    
                    $selected_version = isset($_GET['technical_sheet_version']) ? sanitize_text_field($_GET['technical_sheet_version']) : '';
                    
                    if (!is_wp_error($versions) && !empty($versions)) {
                        foreach ($versions as $version) {
                            printf(
                                '<option value="%s"%s>%s (%d)</option>',
                                esc_attr($version->slug),
                                selected($selected_version, $version->slug, false),
                                esc_html($version->name),
                                $version->count
                            );
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="ts-filter-actions">
                <button type="submit" class="ts-filter-button ts-apply-button">
                    <?php _e('Cerca', 'technical-sheets'); ?>
                </button>
                <a href="<?php echo get_post_type_archive_link('technical_sheet'); ?>" class="ts-filter-button ts-clear-button">
                    <?php _e('Reset', 'technical-sheets'); ?>
                </a>
            </div>

        </form>

        <?php
        // Mostra filtri attivi
        $active_filters = array();
        if (!empty($selected_category)) {
            $category_term = get_term_by('slug', $selected_category, 'technical_sheet_category');
            if ($category_term) {
                $active_filters[] = sprintf(__('Categoria: %s', 'technical-sheets'), $category_term->name);
            }
        }
        if (!empty($selected_model)) {
            $model_term = get_term_by('slug', $selected_model, 'technical_sheet_model');
            if ($model_term) {
                $active_filters[] = sprintf(__('Modello: %s', 'technical-sheets'), $model_term->name);
            }
        }
        if (!empty($selected_version)) {
            $version_term = get_term_by('slug', $selected_version, 'technical_sheet_version');
            if ($version_term) {
                $active_filters[] = sprintf(__('Versione: %s', 'technical-sheets'), $version_term->name);
            }
        }
        if (get_search_query()) {
            $active_filters[] = sprintf(__('Ricerca: "%s"', 'technical-sheets'), get_search_query());
        }

        if (!empty($active_filters)): ?>
            <div class="ts-active-filters">
                <span class="ts-active-filters-label"><?php _e('Filtri attivi:', 'technical-sheets'); ?></span>
                <?php echo implode(' | ', $active_filters); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if (have_posts()): ?>
        <div class="ts-archive-grid">
            <?php while (have_posts()): the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('ts-archive-item'); ?>>
                    
                    <div class="ts-item-thumbnail">
                        <?php if (has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        <?php else: ?>
                            <div class="ts-no-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php _e('Nessuna Immagine', 'technical-sheets'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="ts-item-content">
                        <h2 class="ts-item-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        
                        <?php ts_display_sheet_meta(get_the_ID(), 'ts-item-meta'); ?>
                        
                        <?php if (has_excerpt()): ?>
                            <div class="ts-item-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="ts-item-actions">
                            <a href="<?php the_permalink(); ?>" class="ts-view-button">
                                <?php _e('Vedi Dettagli', 'technical-sheets'); ?>
                            </a>
                            
                            <?php if (ts_get_pdf_export_url(get_the_ID())): ?>
                                <a href="<?php echo ts_get_pdf_export_url(get_the_ID()); ?>" class="ts-pdf-button" target="_blank">
                                    <?php _e('Scarica', 'technical-sheets'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                </article>
            <?php endwhile; ?>
        </div>
        
        <div class="ts-pagination">
            <?php
            the_posts_pagination(array(
                'prev_text' => __('Precedente', 'technical-sheets'),
                'next_text' => __('Successivo', 'technical-sheets'),
                'before_page_number' => '<span class="screen-reader-text">' . __('Pagina', 'technical-sheets') . ' </span>',
            ));
            ?>
        </div>
        
    <?php else: ?>
        <div class="ts-no-posts">
            <p><?php _e('Nessuna scheda tecnica trovata.', 'technical-sheets'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
