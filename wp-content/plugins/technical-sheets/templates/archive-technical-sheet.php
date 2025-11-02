<?php
/**
 * Archive Technical Sheets Template
 */

get_header(); ?>

<div class="ts-archive-container">
    <header class="ts-archive-header">
        <h1 class="ts-archive-title"><?php _e('Technical Sheets', 'technical-sheets'); ?></h1>
        
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
                <label for="ts_type_filter"><?php _e('Filter by Type:', 'technical-sheets'); ?></label>
                <select name="technical_sheet_type" id="ts_type_filter" class="ts-filter-select">
                    <option value=""><?php _e('All Types', 'technical-sheets'); ?></option>
                    <?php
                    $types = get_terms(array(
                        'taxonomy' => 'technical_sheet_type',
                        'hide_empty' => true,
                    ));
                    
                    $selected_type = isset($_GET['technical_sheet_type']) ? sanitize_text_field($_GET['technical_sheet_type']) : '';
                    
                    if (!is_wp_error($types) && !empty($types)) {
                        foreach ($types as $type) {
                            printf(
                                '<option value="%s"%s>%s (%d)</option>',
                                esc_attr($type->slug),
                                selected($selected_type, $type->slug, false),
                                esc_html($type->name),
                                $type->count
                            );
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="ts-filter-group">
                <label for="ts_brand_filter"><?php _e('Filter by Brand:', 'technical-sheets'); ?></label>
                <select name="technical_sheet_brand" id="ts_brand_filter" class="ts-filter-select">
                    <option value=""><?php _e('All Brands', 'technical-sheets'); ?></option>
                    <?php
                    $brands = get_terms(array(
                        'taxonomy' => 'technical_sheet_brand',
                        'hide_empty' => true,
                    ));
                    
                    $selected_brand = isset($_GET['technical_sheet_brand']) ? sanitize_text_field($_GET['technical_sheet_brand']) : '';
                    
                    if (!is_wp_error($brands) && !empty($brands)) {
                        foreach ($brands as $brand) {
                            printf(
                                '<option value="%s"%s>%s (%d)</option>',
                                esc_attr($brand->slug),
                                selected($selected_brand, $brand->slug, false),
                                esc_html($brand->name),
                                $brand->count
                            );
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="ts-filter-group">
                <label for="ts_search_filter"><?php _e('Search:', 'technical-sheets'); ?></label>
                <input type="text" name="s" id="ts_search_filter" class="ts-filter-input" 
                       value="<?php echo get_search_query(); ?>" 
                       placeholder="<?php _e('Search technical sheets...', 'technical-sheets'); ?>">
            </div>

            <div class="ts-filter-actions">
                <button type="submit" class="ts-filter-button ts-apply-button">
                    <?php _e('Apply Filters', 'technical-sheets'); ?>
                </button>
                <a href="<?php echo get_post_type_archive_link('technical_sheet'); ?>" class="ts-filter-button ts-clear-button">
                    <?php _e('Clear Filters', 'technical-sheets'); ?>
                </a>
            </div>

        </form>

        <?php
        // Mostra filtri attivi
        $active_filters = array();
        if (!empty($selected_type)) {
            $type_term = get_term_by('slug', $selected_type, 'technical_sheet_type');
            if ($type_term) {
                $active_filters[] = sprintf(__('Type: %s', 'technical-sheets'), $type_term->name);
            }
        }
        if (!empty($selected_brand)) {
            $brand_term = get_term_by('slug', $selected_brand, 'technical_sheet_brand');
            if ($brand_term) {
                $active_filters[] = sprintf(__('Brand: %s', 'technical-sheets'), $brand_term->name);
            }
        }
        if (get_search_query()) {
            $active_filters[] = sprintf(__('Search: "%s"', 'technical-sheets'), get_search_query());
        }

        if (!empty($active_filters)): ?>
            <div class="ts-active-filters">
                <span class="ts-active-filters-label"><?php _e('Active filters:', 'technical-sheets'); ?></span>
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
                                    <?php _e('No Image', 'technical-sheets'); ?>
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
                                <?php _e('View Details', 'technical-sheets'); ?>
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
                'prev_text' => __('Previous', 'technical-sheets'),
                'next_text' => __('Next', 'technical-sheets'),
                'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'technical-sheets') . ' </span>',
            ));
            ?>
        </div>
        
    <?php else: ?>
        <div class="ts-no-posts">
            <p><?php _e('No technical sheets found.', 'technical-sheets'); ?></p>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
