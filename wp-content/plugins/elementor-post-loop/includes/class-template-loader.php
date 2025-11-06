<?php
/**
 * Classe per gestire il caricamento e rendering dei template
 */

namespace Elementor_Post_Loop\Includes;

if (!defined('ABSPATH')) {
    exit;
}

class Template_Loader {

    /**
     * Percorso base dei template
     *
     * @var string
     */
    private static $templates_path = 'templates/';

    /**
     * Renderizza il template del loop
     *
     * @param \WP_Query $query
     * @param array $settings
     * @return string
     */
    public static function render_loop($query, $settings) {
        if (!$query->have_posts()) {
            return self::render_no_posts_message($settings);
        }

        $layout = !empty($settings['layout']) ? $settings['layout'] : 'grid';
        $columns = !empty($settings['columns']) ? $settings['columns'] : 3;
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

        ob_start();

        // Salva tutti i settings come JSON per l'AJAX
        $ajax_settings = wp_json_encode($settings);

        // Build data attributes properly
        $data_attrs = 'data-post-type="' . esc_attr($settings['post_type'] ?? 'post') . '"';
        $data_attrs .= ' data-posts-per-page="' . esc_attr($settings['posts_per_page'] ?? 6) . '"';
        $data_attrs .= ' data-layout="' . esc_attr($layout) . '"';
        $data_attrs .= ' data-orderby="' . esc_attr($settings['orderby'] ?? 'date') . '"';
        $data_attrs .= ' data-order="' . esc_attr($settings['order'] ?? 'DESC') . '"';
        $data_attrs .= ' data-settings="' . esc_attr($ajax_settings) . '"';

        echo '<div class="elementor-post-loop elementor-post-loop-container elementor-post-loop-' . esc_attr($layout) . ' elementor-post-loop-columns-' . esc_attr($columns) . '" ' . $data_attrs . '>';
        
        // Aggiungi classe speciale per l'editor
        if ($is_editor) {
            echo '<!-- Anteprima Live con Dati Reali -->';
        }
        
        echo '<div class="elementor-post-loop-posts">';
        
        if ($layout === 'grid') {
            echo '<div class="elementor-post-loop-grid elementor-post-loop-columns-' . esc_attr($columns) . '">';
        } elseif ($layout === 'list') {
            echo '<div class="elementor-post-loop-list">';
        } elseif ($layout === 'masonry') {
            echo '<div class="elementor-post-loop-masonry elementor-post-loop-columns-' . esc_attr($columns) . '">';
        }

        while ($query->have_posts()) {
            $query->the_post();
            self::render_post_item($settings);
        }

        echo '</div>'; // Chiude container layout
        echo '</div>'; // Chiude elementor-post-loop-posts

        // Renderizza paginazione se abilitata e non in modalità editor
        if (!empty($settings['show_pagination']) && !$is_editor) {
            self::render_pagination($query, $settings);
        } elseif (!empty($settings['show_pagination']) && $is_editor) {
            // Mostra anteprima paginazione in modalità editor
            self::render_pagination_preview($settings);
        }

        echo '</div>'; // Chiude container principale

        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * Renderizza un singolo elemento del post
     *
     * @param array $settings
     */
    public static function render_post_item($settings) {
        global $post;

        // Aggiungi defaults per AJAX quando i settings potrebbero essere incompleti
        $defaults = [
            'template_style' => 'card',
            'show_image' => 'yes',
            'show_title' => 'yes',
            'show_excerpt' => 'yes',
            'show_meta' => 'yes',
            'show_read_more' => 'yes',
            'title_tag' => 'h3',
            'excerpt_length' => 20,
            'read_more_text' => __('Leggi di più', 'elementor-post-loop'),
            'link_title' => 'yes',
            'link_image' => 'yes',
            'image_size' => 'medium'
        ];

        $settings = wp_parse_args($settings, $defaults);
        
        $template_style = $settings['template_style'];
        $is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

        // Aggiungi classe speciale per l'editor
        $editor_class = $is_editor ? ' elementor-post-loop-editor-mode' : '';
        
        echo '<article class="elementor-post-loop-item elementor-post-loop-' . esc_attr($template_style) . $editor_class . '" data-post-id="' . esc_attr(get_the_ID()) . '">';

        // In modalità editor, aggiungi un piccolo indicatore con il tipo di post
        if ($is_editor) {
            $post_type_obj = get_post_type_object(get_post_type());
            $post_type_label = $post_type_obj ? $post_type_obj->labels->singular_name : 'Post';
            echo '<div class="elementor-post-loop-editor-badge">' . esc_html($post_type_label) . ' #' . get_the_ID() . '</div>';
        }

        // Immagine in evidenza
        if ($settings['show_image'] === 'yes' && has_post_thumbnail()) {
            self::render_post_thumbnail($settings);
        }

        echo '<div class="elementor-post-loop-content">';

        // Titolo
        if ($settings['show_title'] === 'yes') {
            self::render_post_title($settings);
        }

        // Meta informazioni
        if ($settings['show_meta'] === 'yes') {
            self::render_post_meta($settings);
        }

        // Excerpt
        if ($settings['show_excerpt'] === 'yes') {
            self::render_post_excerpt($settings);
        }

        // Link "Leggi di più"
        if ($settings['show_read_more'] === 'yes') {
            self::render_read_more_link($settings);
        }

        echo '</div>'; // Chiude content

        echo '</article>';
    }

    /**
     * Renderizza l'immagine in evidenza
     *
     * @param array $settings
     */
    private static function render_post_thumbnail($settings) {
        $image_size = !empty($settings['image_size']) ? $settings['image_size'] : 'medium';
        $link_image = !empty($settings['link_image']);

        echo '<div class="elementor-post-loop-thumbnail">';

        if ($link_image) {
            echo '<a href="' . esc_url(get_permalink()) . '">';
        }

        the_post_thumbnail($image_size, [
            'class' => 'elementor-post-loop-image',
            'alt' => get_the_title(),
        ]);

        if ($link_image) {
            echo '</a>';
        }

        echo '</div>';
    }

    /**
     * Renderizza il titolo del post
     *
     * @param array $settings
     */
    private static function render_post_title($settings) {
        $title_tag = !empty($settings['title_tag']) ? $settings['title_tag'] : 'h3';
        $title_length = !empty($settings['title_length']) ? (int) $settings['title_length'] : 0;
        $link_title = !empty($settings['link_title']);

        $title = get_the_title();
        if ($title_length > 0 && strlen($title) > $title_length) {
            $title = substr($title, 0, $title_length) . '...';
        }

        echo '<' . esc_attr($title_tag) . ' class="elementor-post-loop-title">';

        if ($link_title) {
            echo '<a href="' . esc_url(get_permalink()) . '">';
        }

        echo esc_html($title);

        if ($link_title) {
            echo '</a>';
        }

        echo '</' . esc_attr($title_tag) . '>';
    }

    /**
     * Renderizza le meta informazioni
     *
     * @param array $settings
     */
    private static function render_post_meta($settings) {
        $meta_items = !empty($settings['meta_items']) ? $settings['meta_items'] : ['date', 'author'];

        echo '<div class="elementor-post-loop-meta">';

        foreach ($meta_items as $meta_item) {
            switch ($meta_item) {
                case 'date':
                    self::render_meta_date($settings);
                    break;
                case 'author':
                    self::render_meta_author($settings);
                    break;
                case 'comments':
                    self::render_meta_comments($settings);
                    break;
                case 'categories':
                    self::render_meta_categories($settings);
                    break;
                case 'tags':
                    self::render_meta_tags($settings);
                    break;
            }
        }

        echo '</div>';
    }

    /**
     * Renderizza la data del post
     *
     * @param array $settings
     */
    private static function render_meta_date($settings) {
        $date_format = !empty($settings['date_format']) ? $settings['date_format'] : get_option('date_format');
        
        if ($date_format === 'custom' && !empty($settings['custom_date_format'])) {
            $date_format = $settings['custom_date_format'];
        } elseif ($date_format === 'default') {
            $date_format = get_option('date_format');
        }

        echo '<span class="elementor-post-loop-meta-item elementor-post-loop-date">';
        echo '<i class="far fa-calendar-alt"></i>';
        echo esc_html(get_the_date($date_format));
        echo '</span>';
    }

    /**
     * Renderizza l'autore del post
     *
     * @param array $settings
     */
    private static function render_meta_author($settings) {
        echo '<span class="elementor-post-loop-meta-item elementor-post-loop-author">';
        echo '<i class="far fa-user"></i>';
        echo esc_html(get_the_author());
        echo '</span>';
    }

    /**
     * Renderizza il numero di commenti
     *
     * @param array $settings
     */
    private static function render_meta_comments($settings) {
        if (!comments_open() && get_comments_number() == 0) {
            return;
        }

        echo '<span class="elementor-post-loop-meta-item elementor-post-loop-comments">';
        echo '<i class="far fa-comments"></i>';
        echo esc_html(get_comments_number());
        echo '</span>';
    }

    /**
     * Renderizza le categorie
     *
     * @param array $settings
     */
    private static function render_meta_categories($settings) {
        $categories = get_the_category();
        if (empty($categories)) {
            return;
        }

        echo '<span class="elementor-post-loop-meta-item elementor-post-loop-categories">';
        echo '<i class="far fa-folder"></i>';
        $category_names = array_map(function($cat) {
            return $cat->name;
        }, $categories);
        echo esc_html(implode(', ', $category_names));
        echo '</span>';
    }

    /**
     * Renderizza i tag
     *
     * @param array $settings
     */
    private static function render_meta_tags($settings) {
        $tags = get_the_tags();
        if (empty($tags)) {
            return;
        }

        echo '<span class="elementor-post-loop-meta-item elementor-post-loop-tags">';
        echo '<i class="far fa-tags"></i>';
        $tag_names = array_map(function($tag) {
            return $tag->name;
        }, $tags);
        echo esc_html(implode(', ', $tag_names));
        echo '</span>';
    }

    /**
     * Renderizza l'excerpt del post
     *
     * @param array $settings
     */
    private static function render_post_excerpt($settings) {
        $excerpt_length = !empty($settings['excerpt_length']) ? (int) $settings['excerpt_length'] : 20;
        
        echo '<div class="elementor-post-loop-excerpt">';
        echo esc_html(wp_trim_words(get_the_excerpt(), $excerpt_length));
        echo '</div>';
    }

    /**
     * Renderizza il link "Leggi di più"
     *
     * @param array $settings
     */
    private static function render_read_more_link($settings) {
        $read_more_text = !empty($settings['read_more_text']) ? $settings['read_more_text'] : __('Leggi di più', 'elementor-post-loop');

        echo '<div class="elementor-post-loop-read-more">';
        echo '<a href="' . esc_url(get_permalink()) . '" class="elementor-post-loop-read-more-link">';
        echo esc_html($read_more_text);
        echo '</a>';
        echo '</div>';
    }

    /**
     * Renderizza la paginazione
     *
     * @param \WP_Query $query
     * @param array $settings
     */
    private static function render_pagination($query, $settings) {
        $pagination_type = !empty($settings['pagination_type']) ? $settings['pagination_type'] : 'numbers';

        echo '<div class="elementor-post-loop-pagination">';

        switch ($pagination_type) {
            case 'numbers':
                // Genera i link con data-page attributes per AJAX
                $current_page = max(1, get_query_var('paged', 1));
                
                $pagination_args = [
                    'base' => '#page-%#%',
                    'format' => '',
                    'current' => $current_page,
                    'total' => $query->max_num_pages,
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'type' => 'array',
                    'end_size' => 2,
                    'mid_size' => 1,
                ];
                
                $links = paginate_links($pagination_args);
                
                if ($links) {
                    foreach ($links as $link) {
                        // Estrai il numero di pagina e aggiungi data-page attribute
                        if (preg_match('/#page-(\d+)/', $link, $matches)) {
                            $page_num = $matches[1];
                            $link = preg_replace('/href=["\']#page-\d+["\']/', 'href="#" data-page="' . $page_num . '"', $link);
                        }
                        echo $link;
                    }
                }
                break;

            case 'prev_next':
                previous_posts_link(__('&laquo; Precedente', 'elementor-post-loop'));
                next_posts_link(__('Successivo &raquo;', 'elementor-post-loop'), $query->max_num_pages);
                break;

            case 'load_more':
                echo '<div class="elementor-post-loop-load-more-container">';
                echo '<button class="elementor-post-loop-load-more" data-page="1" data-max-pages="' . esc_attr($query->max_num_pages) . '">';
                echo esc_html(!empty($settings['load_more_text']) ? $settings['load_more_text'] : __('Carica altri', 'elementor-post-loop'));
                echo '</button>';
                echo '</div>';
                break;
        }

        echo '</div>';
    }

    /**
     * Renderizza il messaggio quando non ci sono post
     *
     * @param array $settings
     * @return string
     */
    private static function render_no_posts_message($settings) {
        $message = !empty($settings['no_posts_message']) ? $settings['no_posts_message'] : __('Nessun post trovato.', 'elementor-post-loop');

        return '<div class="elementor-post-loop-no-posts">' . esc_html($message) . '</div>';
    }

    /**
     * Renderizza l'anteprima della paginazione per l'editor
     *
     * @param array $settings
     */
    private static function render_pagination_preview($settings) {
        $pagination_type = !empty($settings['pagination_type']) ? $settings['pagination_type'] : 'numbers';

        echo '<div class="elementor-post-loop-pagination elementor-post-loop-pagination-preview">';
        echo '<small style="opacity: 0.7; font-size: 11px; display: block; margin-bottom: 5px;">Anteprima Paginazione:</small>';

        switch ($pagination_type) {
            case 'numbers':
                echo '<div>';
                echo '<span class="page-numbers">1</span>';
                echo '<span class="page-numbers current">2</span>';
                echo '<span class="page-numbers">3</span>';
                echo '<span class="page-numbers">...</span>';
                echo '<span class="page-numbers">10</span>';
                echo '</div>';
                break;

            case 'prev_next':
                echo '<div>';
                echo '<span class="prev page-numbers">« Precedente</span>';
                echo '<span class="next page-numbers">Successivo »</span>';
                echo '</div>';
                break;

            case 'load_more':
                echo '<div class="elementor-post-loop-load-more-container">';
                echo '<button class="elementor-post-loop-load-more" disabled style="pointer-events: none;">';
                echo esc_html(!empty($settings['load_more_text']) ? $settings['load_more_text'] : __('Carica altri', 'elementor-post-loop'));
                echo '</button>';
                echo '</div>';
                break;
        }

        echo '</div>';
    }

    /**
     * Carica un template personalizzato se esiste
     *
     * @param string $template_name
     * @param array $vars
     * @return string
     */
    public static function load_template($template_name, $vars = []) {
        // Cerca nel tema prima
        $theme_template = locate_template("elementor-post-loop/{$template_name}.php");
        
        if ($theme_template) {
            $template_path = $theme_template;
        } else {
            $template_path = ELEMENTOR_POST_LOOP_PLUGIN_PATH . self::$templates_path . "{$template_name}.php";
        }

        if (file_exists($template_path)) {
            extract($vars);
            ob_start();
            include $template_path;
            return ob_get_clean();
        }

        return '';
    }
}