<?php
/**
 * Widgets Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Widgets {
    
    public function __construct() {
        add_action('widgets_init', array($this, 'register_widgets'));
    }
    
    /**
     * Register widgets
     */
    public function register_widgets() {
        register_widget('TS_Filters_Widget');
        register_widget('TS_Recent_Sheets_Widget');
        register_widget('TS_Taxonomy_List_Widget');
    }
}

/**
 * Technical Sheets Filters Widget
 */
class TS_Filters_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'ts_filters_widget',
            __('Technical Sheets Filters', 'technical-sheets'),
            array(
                'description' => __('Filter technical sheets by type, brand, and search.', 'technical-sheets'),
                'classname' => 'ts-filters-widget'
            )
        );
    }
    
    /**
     * Widget front-end display
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Filter Technical Sheets', 'technical-sheets');
        $title = apply_filters('widget_title', $title);
        
        $show_type = isset($instance['show_type']) ? $instance['show_type'] : true;
        $show_brand = isset($instance['show_brand']) ? $instance['show_brand'] : true;
        $show_search = isset($instance['show_search']) ? $instance['show_search'] : true;
        $auto_submit = isset($instance['auto_submit']) ? $instance['auto_submit'] : true;
        $redirect_to = !empty($instance['redirect_to']) ? $instance['redirect_to'] : get_post_type_archive_link('technical_sheet');
        
        // Ensure scripts are loaded
        wp_enqueue_script('ts-frontend-script');
        wp_enqueue_style('ts-frontend-style');
        
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        // Get current values
        $selected_type = isset($_GET['technical_sheet_type']) ? sanitize_text_field($_GET['technical_sheet_type']) : '';
        $selected_brand = isset($_GET['technical_sheet_brand']) ? sanitize_text_field($_GET['technical_sheet_brand']) : '';
        $search_query = get_search_query();
        
        echo '<div class="ts-filters-widget-content">';
        echo '<form method="get" action="' . esc_url($redirect_to) . '" class="ts-filters-form ts-widget-form">';
        
                
        if ($show_brand) {
            echo '<div class="ts-filter-group">';
            echo '<label for="ts_brand_filter_widget">' . __('Marchio:', 'technical-sheets') . '</label>';
            echo '<select name="technical_sheet_brand" id="ts_brand_filter_widget" class="ts-filter-select">';
            echo '<option value="">' . __('Tutti i Marchi', 'technical-sheets') . '</option>';
            
            $brands = get_terms(array(
                'taxonomy' => 'technical_sheet_brand',
                'hide_empty' => true,
            ));
            
            if (!is_wp_error($brands) && !empty($brands)) {
                foreach ($brands as $brand) {
                    $selected = selected($selected_brand, $brand->slug, false);
                    printf(
                        '<option value="%s"%s>%s (%d)</option>',
                        esc_attr($brand->slug),
                        $selected,
                        esc_html($brand->name),
                        $brand->count
                    );
                }
            }
            
            echo '</select>';
            echo '</div>';
        }

        if ($show_type) {
            echo '<div class="ts-filter-group">';
            echo '<label for="ts_type_filter_widget">' . __('Tipologia:', 'technical-sheets') . '</label>';
            echo '<select name="technical_sheet_type" id="ts_type_filter_widget" class="ts-filter-select">';
            echo '<option value="">' . __('Mostra tutto', 'technical-sheets') . '</option>';
            
            $types = get_terms(array(
                'taxonomy' => 'technical_sheet_type',
                'hide_empty' => true,
            ));
            
            if (!is_wp_error($types) && !empty($types)) {
                foreach ($types as $type) {
                    $selected = selected($selected_type, $type->slug, false);
                    printf(
                        '<option value="%s"%s>%s (%d)</option>',
                        esc_attr($type->slug),
                        $selected,
                        esc_html($type->name),
                        $type->count
                    );
                }
            }
            
            echo '</select>';
            echo '</div>';
        }
        
        if ($show_search) {
            echo '<div class="ts-filter-group">';
            echo '<label for="ts_search_filter_widget">' . __('Cerca:', 'technical-sheets') . '</label>';
            echo '<input type="text" name="s" id="ts_search_filter_widget" class="ts-filter-input" ';
            echo 'value="' . esc_attr($search_query) . '" ';
            echo 'placeholder="' . __('Cerca...', 'technical-sheets') . '">';
            echo '</div>';
        }
        
        echo '<div class="ts-filter-actions">';
        echo '<button type="submit" class="ts-filter-button ts-apply-button">';
        echo __('Filtra', 'technical-sheets');
        echo '</button>';
        echo '<a href="' . esc_url($redirect_to) . '" class="ts-filter-button ts-clear-button">';
        echo __('Pulisci', 'technical-sheets');
        echo '</a>';
        //echo '<button type="button" class="ts-filter-button ts-reset-button" onclick="this.closest(\'form\').reset(); this.closest(\'form\').submit();">';
        //echo __('Ripristina', 'technical-sheets');
        echo '</button>';
        echo '</div>';
        
        echo '</form>';
        
        // Show active filters count
        $active_count = 0;
        if (!empty($selected_type)) $active_count++;
        if (!empty($selected_brand)) $active_count++;
        if (!empty($search_query)) $active_count++;
        
        if ($active_count > 0) {
            echo '<div class="ts-active-filters-count">';
            echo sprintf(
                _n('%d filtro attivo', '%d filtri attivi', $active_count, 'technical-sheets'),
                $active_count
            );
            echo '</div>';
        }
        
        echo '</div>';
        
        // Add auto-submit functionality if enabled
        if ($auto_submit) {
            echo '<script>
                jQuery(document).ready(function($) {
                    $(".ts-filters-widget-content .ts-filter-select").on("change", function() {
                        $(this).closest("form").submit();
                    });
                    
                    $(".ts-filters-widget-content .ts-filter-input").on("keypress", function(e) {
                        if (e.which === 13) {
                            $(this).closest("form").submit();
                        }
                    });
                });
            </script>';
        }
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget back-end form
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Filtri', 'technical-sheets');
        $show_type = isset($instance['show_type']) ? $instance['show_type'] : true;
        $show_brand = isset($instance['show_brand']) ? $instance['show_brand'] : true;
        $show_search = isset($instance['show_search']) ? $instance['show_search'] : true;
        $auto_submit = isset($instance['auto_submit']) ? $instance['auto_submit'] : true;
        $redirect_to = isset($instance['redirect_to']) ? $instance['redirect_to'] : '';
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'technical-sheets'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_type); ?> id="<?php echo $this->get_field_id('show_type'); ?>" name="<?php echo $this->get_field_name('show_type'); ?>">
            <label for="<?php echo $this->get_field_id('show_type'); ?>"><?php _e('Show Type Filter', 'technical-sheets'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_brand); ?> id="<?php echo $this->get_field_id('show_brand'); ?>" name="<?php echo $this->get_field_name('show_brand'); ?>">
            <label for="<?php echo $this->get_field_id('show_brand'); ?>"><?php _e('Show Brand Filter', 'technical-sheets'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_search); ?> id="<?php echo $this->get_field_id('show_search'); ?>" name="<?php echo $this->get_field_name('show_search'); ?>">
            <label for="<?php echo $this->get_field_id('show_search'); ?>"><?php _e('Show Search Field', 'technical-sheets'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($auto_submit); ?> id="<?php echo $this->get_field_id('auto_submit'); ?>" name="<?php echo $this->get_field_name('auto_submit'); ?>">
            <label for="<?php echo $this->get_field_id('auto_submit'); ?>"><?php _e('Auto-submit on change', 'technical-sheets'); ?></label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('redirect_to'); ?>"><?php _e('Redirect to (optional):', 'technical-sheets'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('redirect_to'); ?>" name="<?php echo $this->get_field_name('redirect_to'); ?>" type="url" value="<?php echo esc_attr($redirect_to); ?>" placeholder="<?php echo esc_attr(get_post_type_archive_link('technical_sheet')); ?>">
            <small><?php _e('Leave empty to use the technical sheets archive page.', 'technical-sheets'); ?></small>
        </p>
        
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_type'] = !empty($new_instance['show_type']) ? true : false;
        $instance['show_brand'] = !empty($new_instance['show_brand']) ? true : false;
        $instance['show_search'] = !empty($new_instance['show_search']) ? true : false;
        $instance['auto_submit'] = !empty($new_instance['auto_submit']) ? true : false;
        $instance['redirect_to'] = !empty($new_instance['redirect_to']) ? esc_url_raw($new_instance['redirect_to']) : '';
        
        return $instance;
    }
}

/**
 * Recent Technical Sheets Widget
 */
class TS_Recent_Sheets_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'ts_recent_sheets_widget',
            __('Recent Technical Sheets', 'technical-sheets'),
            array(
                'description' => __('Display a list of recent technical sheets.', 'technical-sheets'),
                'classname' => 'ts-recent-sheets-widget'
            )
        );
    }
    
    /**
     * Widget front-end display
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Ultime schede tecniche', 'technical-sheets');
        $title = apply_filters('widget_title', $title);
        
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $show_thumbnail = isset($instance['show_thumbnail']) ? $instance['show_thumbnail'] : true;
        $show_excerpt = isset($instance['show_excerpt']) ? $instance['show_excerpt'] : false;
        $type_filter = !empty($instance['type_filter']) ? $instance['type_filter'] : '';
        $brand_filter = !empty($instance['brand_filter']) ? $instance['brand_filter'] : '';
        
        $query_args = array(
            'post_type' => 'technical_sheet',
            'posts_per_page' => $number,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        );
        
        // Add taxonomy filters
        $tax_query = array();
        if (!empty($type_filter)) {
            $tax_query[] = array(
                'taxonomy' => 'technical_sheet_type',
                'field' => 'slug',
                'terms' => $type_filter
            );
        }
        if (!empty($brand_filter)) {
            $tax_query[] = array(
                'taxonomy' => 'technical_sheet_brand',
                'field' => 'slug',
                'terms' => $brand_filter
            );
        }
        if (!empty($tax_query)) {
            if (count($tax_query) > 1) {
                $tax_query['relation'] = 'AND';
            }
            $query_args['tax_query'] = $tax_query;
        }
        
        $recent_sheets = new WP_Query($query_args);
        
        if ($recent_sheets->have_posts()) {
            echo $args['before_widget'];
            
            if (!empty($title)) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            
            echo '<div class="ts-recent-sheets-list">';
            
            while ($recent_sheets->have_posts()) {
                $recent_sheets->the_post();
                
                echo '<div class="ts-recent-sheet-item">';
                
                if ($show_thumbnail && has_post_thumbnail()) {
                    echo '<div class="ts-recent-sheet-thumbnail">';
                    echo '<a href="' . get_permalink() . '">';
                    the_post_thumbnail('thumbnail');
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<div class="ts-recent-sheet-content">';
                echo '<h4 class="ts-recent-sheet-title">';
                echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                echo '</h4>';
                
                if ($show_excerpt && has_excerpt()) {
                    echo '<div class="ts-recent-sheet-excerpt">';
                    echo wp_trim_words(get_the_excerpt(), 15);
                    echo '</div>';
                }
                
                echo '<div class="ts-recent-sheet-meta">';
                echo '<span class="ts-recent-sheet-date">' . get_the_date() . '</span>';
                echo '</div>';
                
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>';
            
            // Add "View All" link
            echo '<div class="ts-recent-sheets-footer">';
            echo '<a href="' . get_post_type_archive_link('technical_sheet') . '" class="ts-view-all-link">';
            echo __('View All Technical Sheets', 'technical-sheets');
            echo '</a>';
            echo '</div>';
            
            echo $args['after_widget'];
            
            wp_reset_postdata();
        }
    }
    
    /**
     * Widget back-end form
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Recent Technical Sheets', 'technical-sheets');
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_thumbnail = isset($instance['show_thumbnail']) ? $instance['show_thumbnail'] : true;
        $show_excerpt = isset($instance['show_excerpt']) ? $instance['show_excerpt'] : false;
        $type_filter = isset($instance['type_filter']) ? $instance['type_filter'] : '';
        $brand_filter = isset($instance['brand_filter']) ? $instance['brand_filter'] : '';
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'technical-sheets'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of sheets to show:', 'technical-sheets'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3">
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_thumbnail); ?> id="<?php echo $this->get_field_id('show_thumbnail'); ?>" name="<?php echo $this->get_field_name('show_thumbnail'); ?>">
            <label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php _e('Show Thumbnails', 'technical-sheets'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_excerpt); ?> id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>">
            <label for="<?php echo $this->get_field_id('show_excerpt'); ?>"><?php _e('Show Excerpt', 'technical-sheets'); ?></label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('type_filter'); ?>"><?php _e('Filter by Type:', 'technical-sheets'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('type_filter'); ?>" name="<?php echo $this->get_field_name('type_filter'); ?>">
                <option value=""><?php _e('Tutte le tipologie', 'technical-sheets'); ?></option>
                <?php
                $types = get_terms(array(
                    'taxonomy' => 'technical_sheet_type',
                    'hide_empty' => false,
                ));
                
                if (!is_wp_error($types) && !empty($types)) {
                    foreach ($types as $type) {
                        printf(
                            '<option value="%s"%s>%s</option>',
                            esc_attr($type->slug),
                            selected($type_filter, $type->slug, false),
                            esc_html($type->name)
                        );
                    }
                }
                ?>
            </select>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('brand_filter'); ?>"><?php _e('Filter by Brand:', 'technical-sheets'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('brand_filter'); ?>" name="<?php echo $this->get_field_name('brand_filter'); ?>">
                <option value=""><?php _e('All Brands', 'technical-sheets'); ?></option>
                <?php
                $brands = get_terms(array(
                    'taxonomy' => 'technical_sheet_brand',
                    'hide_empty' => false,
                ));
                
                if (!is_wp_error($brands) && !empty($brands)) {
                    foreach ($brands as $brand) {
                        printf(
                            '<option value="%s"%s>%s</option>',
                            esc_attr($brand->slug),
                            selected($brand_filter, $brand->slug, false),
                            esc_html($brand->name)
                        );
                    }
                }
                ?>
            </select>
        </p>
        
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = !empty($new_instance['number']) ? absint($new_instance['number']) : 5;
        $instance['show_thumbnail'] = !empty($new_instance['show_thumbnail']) ? true : false;
        $instance['show_excerpt'] = !empty($new_instance['show_excerpt']) ? true : false;
        $instance['type_filter'] = !empty($new_instance['type_filter']) ? sanitize_text_field($new_instance['type_filter']) : '';
        $instance['brand_filter'] = !empty($new_instance['brand_filter']) ? sanitize_text_field($new_instance['brand_filter']) : '';
        
        return $instance;
    }
}

/**
 * Technical Sheets Taxonomy List Widget
 */
class TS_Taxonomy_List_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'ts_taxonomy_list_widget',
            __('Technical Sheets Categories', 'technical-sheets'),
            array(
                'description' => __('Display filterable lists of technical sheet types and brands.', 'technical-sheets'),
                'classname' => 'ts-taxonomy-list-widget'
            )
        );
    }
    
    /**
     * Widget front-end display
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Browse Categories', 'technical-sheets');
        $title = apply_filters('widget_title', $title);
        
        $show_types = isset($instance['show_types']) ? $instance['show_types'] : true;
        $show_brands = isset($instance['show_brands']) ? $instance['show_brands'] : true;
        $show_counts = isset($instance['show_counts']) ? $instance['show_counts'] : true;
        $show_reset = isset($instance['show_reset']) ? $instance['show_reset'] : true;
        $layout = !empty($instance['layout']) ? $instance['layout'] : 'vertical';
        
        // Get current filters
        $selected_type = isset($_GET['technical_sheet_type']) ? sanitize_text_field($_GET['technical_sheet_type']) : '';
        $selected_brand = isset($_GET['technical_sheet_brand']) ? sanitize_text_field($_GET['technical_sheet_brand']) : '';
        $search_query = get_search_query();
        
        // Get archive URL
        $archive_url = get_post_type_archive_link('technical_sheet');
        
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        echo '<div class="ts-taxonomy-list-content ts-layout-' . esc_attr($layout) . '">';
        
        // Types section
        if ($show_types) {
            $types = get_terms(array(
                'taxonomy' => 'technical_sheet_type',
                'hide_empty' => true,
                'orderby' => 'name',
                'order' => 'ASC'
            ));
            
            if (!is_wp_error($types) && !empty($types)) {
                echo '<div class="ts-taxonomy-section ts-types-section">';
                echo '<h4 class="ts-taxonomy-title">' . __('Tipologie', 'technical-sheets') . '</h4>';
                echo '<ul class="ts-taxonomy-list">';
                
                foreach ($types as $type) {
                    $current_url = add_query_arg('technical_sheet_type', $type->slug, $archive_url);
                    
                    // Preserve other filters
                    if (!empty($selected_brand)) {
                        $current_url = add_query_arg('technical_sheet_brand', $selected_brand, $current_url);
                    }
                    if (!empty($search_query)) {
                        $current_url = add_query_arg('s', $search_query, $current_url);
                    }
                    
                    $is_active = ($selected_type === $type->slug);
                    $count_display = $show_counts ? ' (' . $type->count . ')' : '';
                    
                    echo '<li class="ts-taxonomy-item' . ($is_active ? ' active' : '') . '">';
                    echo '<a href="' . esc_url($current_url) . '" class="ts-taxonomy-link">';
                    echo esc_html($type->name) . $count_display;
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
            }
        }
        
        // Brands section
        if ($show_brands) {
            $brands = get_terms(array(
                'taxonomy' => 'technical_sheet_brand',
                'hide_empty' => true,
                'orderby' => 'name',
                'order' => 'ASC'
            ));
            
            if (!is_wp_error($brands) && !empty($brands)) {
                echo '<div class="ts-taxonomy-section ts-brands-section">';
                echo '<h4 class="ts-taxonomy-title">' . __('Marchi', 'technical-sheets') . '</h4>';
                echo '<ul class="ts-taxonomy-list">';
                
                foreach ($brands as $brand) {
                    $current_url = add_query_arg('technical_sheet_brand', $brand->slug, $archive_url);
                    
                    // Preserve other filters
                    if (!empty($selected_type)) {
                        $current_url = add_query_arg('technical_sheet_type', $selected_type, $current_url);
                    }
                    if (!empty($search_query)) {
                        $current_url = add_query_arg('s', $search_query, $current_url);
                    }
                    
                    $is_active = ($selected_brand === $brand->slug);
                    $count_display = $show_counts ? ' (' . $brand->count . ')' : '';
                    
                    echo '<li class="ts-taxonomy-item' . ($is_active ? ' active' : '') . '">';
                    echo '<a href="' . esc_url($current_url) . '" class="ts-taxonomy-link">';
                    echo esc_html($brand->name) . $count_display;
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
            }
        }
        
        // Show active filters summary
        $active_filters = array();
        if (!empty($selected_type)) {
            $type_term = get_term_by('slug', $selected_type, 'technical_sheet_type');
            if ($type_term) {
                $active_filters[] = __('Type:', 'technical-sheets') . ' ' . $type_term->name;
            }
        }
        if (!empty($selected_brand)) {
            $brand_term = get_term_by('slug', $selected_brand, 'technical_sheet_brand');
            if ($brand_term) {
                $active_filters[] = __('Brand:', 'technical-sheets') . ' ' . $brand_term->name;
            }
        }
        if (!empty($search_query)) {
            $active_filters[] = __('Search:', 'technical-sheets') . ' "' . $search_query . '"';
        }
        
        if (!empty($active_filters)) {
            echo '<div class="ts-active-filters-summary">';
            echo '<h5>' . __('Filtri attivi:', 'technical-sheets') . '</h5>';
            echo '<ul>';
            foreach ($active_filters as $filter) {
                echo '<li>' . esc_html($filter) . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }

        // Show reset button if any filters are active
        if ($show_reset && (!empty($selected_type) || !empty($selected_brand) || !empty($search_query))) {
            echo '<div class="ts-taxonomy-reset">';
            echo '<a href="' . esc_url($archive_url) . '" class="ts-reset-filters-link">';
            echo '<span class="dashicons dashicons-dismiss"></span> ' . __('Cancella tutti i filtri', 'technical-sheets');
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div>';
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget back-end form
     */
    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : __('Browse Categories', 'technical-sheets');
        $show_types = isset($instance['show_types']) ? $instance['show_types'] : true;
        $show_brands = isset($instance['show_brands']) ? $instance['show_brands'] : true;
        $show_counts = isset($instance['show_counts']) ? $instance['show_counts'] : true;
        $show_reset = isset($instance['show_reset']) ? $instance['show_reset'] : true;
        $layout = isset($instance['layout']) ? $instance['layout'] : 'vertical';
        ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'technical-sheets'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_types); ?> id="<?php echo $this->get_field_id('show_types'); ?>" name="<?php echo $this->get_field_name('show_types'); ?>">
            <label for="<?php echo $this->get_field_id('show_types'); ?>"><?php _e('Mostra Tipologie', 'technical-sheets'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_brands); ?> id="<?php echo $this->get_field_id('show_brands'); ?>" name="<?php echo $this->get_field_name('show_brands'); ?>">
            <label for="<?php echo $this->get_field_id('show_brands'); ?>"><?php _e('Mostra Marchi', 'technical-sheets'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_counts); ?> id="<?php echo $this->get_field_id('show_counts'); ?>" name="<?php echo $this->get_field_name('show_counts'); ?>">
            <label for="<?php echo $this->get_field_id('show_counts'); ?>"><?php _e('Mostra Conteggi Post', 'technical-sheets'); ?></label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_reset); ?> id="<?php echo $this->get_field_id('show_reset'); ?>" name="<?php echo $this->get_field_name('show_reset'); ?>">
            <label for="<?php echo $this->get_field_id('show_reset'); ?>"><?php _e('Mostra Pulsante Reset', 'technical-sheets'); ?></label>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Layout:', 'technical-sheets'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
                <option value="vertical" <?php selected($layout, 'vertical'); ?>><?php _e('Vertical', 'technical-sheets'); ?></option>
                <option value="horizontal" <?php selected($layout, 'horizontal'); ?>><?php _e('Horizontal', 'technical-sheets'); ?></option>
                <option value="compact" <?php selected($layout, 'compact'); ?>><?php _e('Compact', 'technical-sheets'); ?></option>
            </select>
        </p>
        
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['show_types'] = !empty($new_instance['show_types']) ? true : false;
        $instance['show_brands'] = !empty($new_instance['show_brands']) ? true : false;
        $instance['show_counts'] = !empty($new_instance['show_counts']) ? true : false;
        $instance['show_reset'] = !empty($new_instance['show_reset']) ? true : false;
        $instance['layout'] = !empty($new_instance['layout']) ? sanitize_text_field($new_instance['layout']) : 'vertical';
        
        return $instance;
    }
}
