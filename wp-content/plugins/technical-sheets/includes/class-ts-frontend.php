<?php
/**
 * Frontend Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Frontend {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'add_print_styles'));
        add_action('pre_get_posts', array($this, 'modify_archive_query'));
        
        // AJAX per ottenere opzioni filtrate
        add_action('wp_ajax_get_ts_filtered_options', array($this, 'ajax_get_filtered_options'));
        add_action('wp_ajax_nopriv_get_ts_filtered_options', array($this, 'ajax_get_filtered_options'));
        
        // AJAX per ottenere risultati filtrati
        add_action('wp_ajax_get_ts_filtered_results', array($this, 'ajax_get_filtered_results'));
        add_action('wp_ajax_nopriv_get_ts_filtered_results', array($this, 'ajax_get_filtered_results'));
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        if (is_singular('technical_sheet') || is_post_type_archive('technical_sheet')) {
            // Select2 CSS
            wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0');
            
            // Plugin CSS
            wp_enqueue_style('ts-frontend-style', TECHNICAL_SHEETS_PLUGIN_URL . 'assets/css/frontend.css', array('select2'), TECHNICAL_SHEETS_VERSION);
            
            // Enqueue Font Awesome
            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
            
            // Select2 JS
            wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0', true);
            
            // Plugin JS
            wp_enqueue_script('ts-frontend-script', TECHNICAL_SHEETS_PLUGIN_URL . 'assets/js/frontend.js', array('jquery', 'select2'), TECHNICAL_SHEETS_VERSION, true);
            
            // Enqueue WordPress native media scripts for lightbox
            wp_enqueue_script('wp-dom-ready');
            wp_enqueue_script('wp-a11y');
            
            // Localize script for AJAX
            wp_localize_script('ts-frontend-script', 'ts_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ts_ajax_nonce')
            ));
        }
    }
    
    /**
     * Add print styles
     */
    public function add_print_styles() {
        $options = get_option('technical_sheets_options');
        $enable_print_styles = isset($options['enable_print_styles']) ? $options['enable_print_styles'] : 1;
        
        if ($enable_print_styles && is_singular('technical_sheet')) {
            ?>
            <style type="text/css" media="print">
                h1, h2, h3, h4, h5, h6 {
                    margin: 5px;
                }
                .ts-basic-info-table{
                    margin-top: 5px;
                }
                .ts-column-50 {
                    width: 50%;
                }
                table {
                    border: none !important;
                }
                .no-print, .site-header, .site-footer, .sidebar, .comments-area, .ts-download-section,.fusion-header, .fusion-footer, .fusion-page-title-bar, .fusion-sticky-header-wrapper {
                    display: none !important;
                }
                
                .ts-container {
                    width: 100% !important;
                    max-width: none !important;
                }
                
                .ts-section {
                    page-break-inside: avoid;
                    margin-bottom: 20px;
                }
                .ts-gallery-slider {
                    display:none;
                }
                
                .ts-gallery img {
                    display: none;
                    max-width: 100%;
                    height: auto;
                }
                
                .ts-basic-info-table {
                    border-collapse: collapse;
                    width: 100%;
                }
                
                .ts-basic-info-table th,
                .ts-basic-info-table td {
                    /*border: 1px solid #000;*/
                    padding: 8px;
                    text-align: left;
                }
                
                .ts-accordion{
                    border: none;
                }
                .ts-accordion-item{
                    border-bottom: 1px solid #e9ecef;
                    page-break-inside: avoid;
                }
                .ts-accordion-header{
                    padding: 10px;    
                }
                .ts-accordion-content {
                    padding: 10px;
                }
                .ts-accordion-text p {
                    margin: 0;
                }
                p{
                    font-size: 12px;
                }
                .avada-footer-scripts{
                    display: none;
                }
                .to-top-container{
                    display: none;
                }
                .ts-accordion-title {
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                html:not(.avada-has-site-width-percent) #main, html:not(.avada-has-site-width-percent) .fusion-footer-copyright-area, html:not(.avada-has-site-width-percent) .fusion-footer-widget-area, html:not(.avada-has-site-width-percent) .fusion-sliding-bar-position-bottom .fusion-sliding-bar, html:not(.avada-has-site-width-percent) .fusion-sliding-bar-position-top .fusion-sliding-bar, html:not(.avada-has-site-width-percent) .tfs-slider .slide-content-container{
                    padding:0 !important;
                }
                @page { margin: 0; }
                .no-print,.fusion-secondary-header { display: none !important; }
                .ts-accordion-content { display: block !important; }
                .ts-accordion-header { background: none !important; }
                .ts-accordion-icon { display: none !important; }
                .ts-gallery { display: grid !important; grid-template-columns: repeat(2, 1fr) !important; }
                .ts-basic-info-table { font-size: 12px !important; }
                .ts-section { page-break-inside: avoid; }
                h1, h2, h3 { page-break-after: avoid; }
            </style>
            <?php
        }
    }
    
    /**
     * Modify archive query to support filters
     */
    public function modify_archive_query($query) {
        // Only modify main query on archive pages and not in admin
        if (is_admin() || !$query->is_main_query()) {
            return;
        }
        
        // Only for technical sheets archive
        if (!is_post_type_archive('technical_sheet') && !is_tax(array('technical_sheet_category', 'technical_sheet_model', 'technical_sheet_version'))) {
            return;
        }
        
        // Handle taxonomy filters
        $tax_query = array();
        
        // Filter by category
        if (isset($_GET['technical_sheet_category']) && !empty($_GET['technical_sheet_category'])) {
            $category_slug = sanitize_text_field($_GET['technical_sheet_category']);
            $tax_query[] = array(
                'taxonomy' => 'technical_sheet_category',
                'field'    => 'slug',
                'terms'    => $category_slug,
            );
        }
        
        // Filter by model
        if (isset($_GET['technical_sheet_model']) && !empty($_GET['technical_sheet_model'])) {
            $model_slug = sanitize_text_field($_GET['technical_sheet_model']);
            $tax_query[] = array(
                'taxonomy' => 'technical_sheet_model',
                'field'    => 'slug',
                'terms'    => $model_slug,
            );
        }
        
        // Filter by version
        if (isset($_GET['technical_sheet_version']) && !empty($_GET['technical_sheet_version'])) {
            $version_slug = sanitize_text_field($_GET['technical_sheet_version']);
            $tax_query[] = array(
                'taxonomy' => 'technical_sheet_version',
                'field'    => 'slug',
                'terms'    => $version_slug,
            );
        }
        
        // Apply taxonomy query if we have filters
        if (!empty($tax_query)) {
            if (count($tax_query) > 1) {
                $tax_query['relation'] = 'AND';
            }
            $query->set('tax_query', $tax_query);
        }
        
        // Handle search query
        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $search_query = sanitize_text_field($_GET['s']);
            $query->set('s', $search_query);
        }
        
        // Set posts per page for better performance
        $query->set('posts_per_page', 12);
    }
    
    /**
     * AJAX: Get filtered options for taxonomies
     */
    public function ajax_get_filtered_options() {
        check_ajax_referer('ts_ajax_nonce', 'nonce');
        
        // Build base query with current filters
        $args = array(
            'post_type' => 'technical_sheet',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'tax_query' => array(),
        );
        
        // Apply existing filters
        $taxonomies = array('technical_sheet_category', 'technical_sheet_model', 'technical_sheet_version');
        
        foreach ($taxonomies as $taxonomy) {
            if (!empty($_POST[$taxonomy])) {
                $args['tax_query'][] = array(
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_POST[$taxonomy]),
                );
            }
        }
        
        // Execute query to get filtered post IDs
        $query = new WP_Query($args);
        $post_ids = $query->posts;
        
        // Get available options for each taxonomy
        $available_options = array();
        
        foreach ($taxonomies as $taxonomy) {
            $terms = get_terms(array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            ));
            
            $available_terms = array();
            
            foreach ($terms as $term) {
                // Count how many posts have this term among filtered posts
                $term_post_count = 0;
                
                if (!empty($post_ids)) {
                    $term_args = array(
                        'post_type' => 'technical_sheet',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                        'post__in' => $post_ids,
                        'tax_query' => array(
                            array(
                                'taxonomy' => $taxonomy,
                                'field' => 'term_id',
                                'terms' => $term->term_id,
                            ),
                        ),
                    );
                    
                    $term_query = new WP_Query($term_args);
                    $term_post_count = $term_query->found_posts;
                } else {
                    // If no filters, use original count
                    $term_post_count = $term->count;
                }
                
                if ($term_post_count > 0) {
                    $available_terms[] = array(
                        'slug' => $term->slug,
                        'name' => $term->name,
                        'count' => $term_post_count,
                    );
                }
            }
            
            $available_options[$taxonomy] = $available_terms;
        }
        
        wp_send_json_success(array(
            'options' => $available_options,
        ));
    }
    
    /**
     * AJAX handler per ottenere risultati filtrati
     */
    public function ajax_get_filtered_results() {
        check_ajax_referer('ts_ajax_nonce', 'nonce');
        
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $model = isset($_POST['model']) ? sanitize_text_field($_POST['model']) : '';
        $version = isset($_POST['version']) ? sanitize_text_field($_POST['version']) : '';
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;
        
        // Build query args
        $args = array(
            'post_type' => 'technical_sheet',
            'posts_per_page' => get_option('posts_per_page', 10),
            'paged' => $paged,
            'post_status' => 'publish',
        );
        
        $tax_query = array('relation' => 'AND');
        
        if (!empty($category)) {
            $tax_query[] = array(
                'taxonomy' => 'technical_sheet_category',
                'field' => 'slug',
                'terms' => $category,
            );
        }
        
        if (!empty($model)) {
            $tax_query[] = array(
                'taxonomy' => 'technical_sheet_model',
                'field' => 'slug',
                'terms' => $model,
            );
        }
        
        if (!empty($version)) {
            $tax_query[] = array(
                'taxonomy' => 'technical_sheet_version',
                'field' => 'slug',
                'terms' => $version,
            );
        }
        
        if (count($tax_query) > 1) {
            $args['tax_query'] = $tax_query;
        }
        
        if (!empty($search)) {
            $args['s'] = $search;
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        
        if ($query->have_posts()): ?>
            <div class="ts-archive-grid">
                <?php while ($query->have_posts()): $query->the_post(); ?>
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
            
            <?php 
            // Pagination
            $pagination = '';
            if ($query->max_num_pages > 1) {
                $pagination = paginate_links(array(
                    'total' => $query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => __('Precedente', 'technical-sheets'),
                    'next_text' => __('Successivo', 'technical-sheets'),
                    'before_page_number' => '<span class="screen-reader-text">' . __('Pagina', 'technical-sheets') . ' </span>',
                    'type' => 'list',
                ));
            }
            ?>
            
        <?php else: ?>
            <div class="ts-no-posts">
                <p><?php _e('Nessuna scheda tecnica trovata.', 'technical-sheets'); ?></p>
            </div>
        <?php endif;
        
        $results_html = ob_get_clean();
        
        // Build active filters HTML
        $active_filters = array();
        if (!empty($category)) {
            $category_term = get_term_by('slug', $category, 'technical_sheet_category');
            if ($category_term) {
                $active_filters[] = sprintf(__('Categoria: %s', 'technical-sheets'), $category_term->name);
            }
        }
        if (!empty($model)) {
            $model_term = get_term_by('slug', $model, 'technical_sheet_model');
            if ($model_term) {
                $active_filters[] = sprintf(__('Modello: %s', 'technical-sheets'), $model_term->name);
            }
        }
        if (!empty($version)) {
            $version_term = get_term_by('slug', $version, 'technical_sheet_version');
            if ($version_term) {
                $active_filters[] = sprintf(__('Versione: %s', 'technical-sheets'), $version_term->name);
            }
        }
        if (!empty($search)) {
            $active_filters[] = sprintf(__('Ricerca: "%s"', 'technical-sheets'), $search);
        }
        
        $active_filters_html = '';
        if (!empty($active_filters)) {
            $active_filters_html = '<div class="ts-active-filters">';
            $active_filters_html .= '<span class="ts-active-filters-label">' . __('Filtri attivi:', 'technical-sheets') . '</span>';
            $active_filters_html .= implode(' | ', $active_filters);
            $active_filters_html .= '</div>';
        }
        
        wp_reset_postdata();
        
        wp_send_json_success(array(
            'results_html' => $results_html,
            'pagination_html' => isset($pagination) ? $pagination : '',
            'active_filters_html' => $active_filters_html,
            'found_posts' => $query->found_posts,
        ));
    }
}

