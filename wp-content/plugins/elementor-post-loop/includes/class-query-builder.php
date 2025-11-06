<?php
/**
 * Classe per costruire le query WP_Query personalizzate
 */

namespace Elementor_Post_Loop\Includes;

if (!defined('ABSPATH')) {
    exit;
}

class Query_Builder {

    /**
     * Costruisce gli argomenti per WP_Query basati sulle impostazioni del widget
     *
     * @param array $settings
     * @return array
     */
    public static function build_query_args($settings) {
        $args = [
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
        ];

        // Tipo di post
        $args['post_type'] = !empty($settings['post_type']) ? $settings['post_type'] : 'post';

        // Numero di post
        $args['posts_per_page'] = !empty($settings['posts_per_page']) ? (int) $settings['posts_per_page'] : 6;

        // Paginazione - IMPORTANTE per AJAX
        if (!empty($settings['paged'])) {
            $args['paged'] = (int) $settings['paged'];
            
            // Se stiamo paginando (paged > 1), dobbiamo gestire l'offset manualmente
            // perché WordPress non gestisce correttamente offset + paged insieme
            if ($args['paged'] > 1 && !empty($settings['offset'])) {
                $offset = (int) $settings['offset'];
                $calculated_offset = $offset + (($args['paged'] - 1) * $args['posts_per_page']);
                $args['offset'] = $calculated_offset;
                // Non impostare 'paged' quando usiamo offset manuale
                unset($args['paged']);
            }
        } else {
            $args['paged'] = max(1, get_query_var('paged', 1));
        }

        // Offset (solo per la prima pagina se non c'è paginazione)
        if (!empty($settings['offset']) && (!isset($settings['paged']) || $settings['paged'] <= 1)) {
            $args['offset'] = (int) $settings['offset'];
        }

        // Ordinamento
        $args['orderby'] = !empty($settings['orderby']) ? $settings['orderby'] : 'date';
        $args['order'] = !empty($settings['order']) ? $settings['order'] : 'DESC';

        // Meta query per ordinamento personalizzato
        if ($args['orderby'] === 'meta_value' || $args['orderby'] === 'meta_value_num') {
            if (!empty($settings['meta_key'])) {
                $args['meta_key'] = $settings['meta_key'];
            }
        }

        // Includi/escludi post specifici
        if (!empty($settings['include_posts'])) {
            $args['post__in'] = $settings['include_posts'];
        }

        if (!empty($settings['exclude_posts'])) {
            $args['post__not_in'] = $settings['exclude_posts'];
        }

        // Filtri per tassonomie
        $tax_query = self::build_tax_query($settings);
        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        // Filtri per meta fields
        $meta_query = self::build_meta_query($settings);
        if (!empty($meta_query)) {
            $args['meta_query'] = $meta_query;
        }

        // Filtri per autore
        if (!empty($settings['author'])) {
            $args['author__in'] = $settings['author'];
        }

        if (!empty($settings['author_exclude'])) {
            $args['author__not_in'] = $settings['author_exclude'];
        }

        // Filtri per data
        $date_query = self::build_date_query($settings);
        if (!empty($date_query)) {
            $args['date_query'] = $date_query;
        }

        // Filtri per post parent (per tipi di post gerarchici)
        if (!empty($settings['post_parent'])) {
            $args['post_parent__in'] = $settings['post_parent'];
        }

        // Esclusione sticky posts se richiesto
        if (!empty($settings['ignore_sticky_posts'])) {
            $args['ignore_sticky_posts'] = true;
        }

        return apply_filters('elementor_post_loop_query_args', $args, $settings);
    }

    /**
     * Costruisce la tax_query
     *
     * @param array $settings
     * @return array
     */
    private static function build_tax_query($settings) {
        $tax_query = [];

        // Gestione taxonomy query dinamica per ogni tipo di post
        $post_type = !empty($settings['post_type']) ? $settings['post_type'] : 'post';
        $taxonomies = get_object_taxonomies($post_type, 'names');

        foreach ($taxonomies as $taxonomy) {
            // Include terms
            $include_key = "include_{$taxonomy}";
            if (!empty($settings[$include_key])) {
                $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => $settings[$include_key],
                    'operator' => 'IN',
                ];
            }

            // Exclude terms
            $exclude_key = "exclude_{$taxonomy}";
            if (!empty($settings[$exclude_key])) {
                $tax_query[] = [
                    'taxonomy' => $taxonomy,
                    'field' => 'term_id',
                    'terms' => $settings[$exclude_key],
                    'operator' => 'NOT IN',
                ];
            }
        }

        // Relazione tra le query di tassonomia
        if (count($tax_query) > 1) {
            $tax_query['relation'] = !empty($settings['tax_query_relation']) ? $settings['tax_query_relation'] : 'AND';
        }

        return $tax_query;
    }

    /**
     * Costruisce la meta_query
     *
     * @param array $settings
     * @return array
     */
    private static function build_meta_query($settings) {
        $meta_query = [];

        // Meta query personalizzate
        if (!empty($settings['meta_queries'])) {
            foreach ($settings['meta_queries'] as $meta_query_item) {
                if (!empty($meta_query_item['meta_key'])) {
                    $query_item = [
                        'key' => $meta_query_item['meta_key'],
                        'compare' => !empty($meta_query_item['meta_compare']) ? $meta_query_item['meta_compare'] : '=',
                    ];

                    if (!empty($meta_query_item['meta_value'])) {
                        $query_item['value'] = $meta_query_item['meta_value'];
                    }

                    if (!empty($meta_query_item['meta_type'])) {
                        $query_item['type'] = $meta_query_item['meta_type'];
                    }

                    $meta_query[] = $query_item;
                }
            }
        }

        // Relazione tra le meta query
        if (count($meta_query) > 1) {
            $meta_query['relation'] = !empty($settings['meta_query_relation']) ? $settings['meta_query_relation'] : 'AND';
        }

        return $meta_query;
    }

    /**
     * Costruisce la date_query
     *
     * @param array $settings
     * @return array
     */
    private static function build_date_query($settings) {
        $date_query = [];

        // Filtro per data di inizio
        if (!empty($settings['date_after'])) {
            $date_query['after'] = $settings['date_after'];
        }

        // Filtro per data di fine
        if (!empty($settings['date_before'])) {
            $date_query['before'] = $settings['date_before'];
        }

        // Filtro per ultimo periodo
        if (!empty($settings['date_period'])) {
            switch ($settings['date_period']) {
                case 'last_week':
                    $date_query['after'] = '1 week ago';
                    break;
                case 'last_month':
                    $date_query['after'] = '1 month ago';
                    break;
                case 'last_year':
                    $date_query['after'] = '1 year ago';
                    break;
                case 'today':
                    $date_query['after'] = 'today';
                    break;
                case 'yesterday':
                    $date_query['after'] = 'yesterday';
                    $date_query['before'] = 'yesterday';
                    break;
            }
        }

        return $date_query;
    }

    /**
     * Esegue la query e restituisce i risultati
     *
     * @param array $settings
     * @return \WP_Query
     */
    public static function get_query($settings) {
        $args = self::build_query_args($settings);
        
        // Se siamo nell'editor, modifica alcuni parametri per un'anteprima migliore
        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            // Limita il numero di post per l'anteprima per non appesantire l'editor
            $original_posts_per_page = $args['posts_per_page'];
            if ($args['posts_per_page'] > 8) {
                $args['posts_per_page'] = 8;
            }
            
            // Aggiungi un commento nella query per debugging
            $args['meta_query'] = isset($args['meta_query']) ? $args['meta_query'] : [];
            $args['meta_query']['_elementor_preview'] = [
                'key' => '_elementor_preview_mode',
                'compare' => 'NOT EXISTS' // Questo non dovrebbe mai matchare, è solo per debug
            ];
        }
        
        return new \WP_Query($args);
    }

    /**
     * Ottiene i post correlati
     *
     * @param int $post_id
     * @param array $settings
     * @return \WP_Query
     */
    public static function get_related_posts($post_id, $settings) {
        $args = self::build_query_args($settings);
        
        // Escludi il post corrente
        if (!isset($args['post__not_in'])) {
            $args['post__not_in'] = [];
        }
        $args['post__not_in'][] = $post_id;

        // Trova post correlati per tassonomie
        $post_type = get_post_type($post_id);
        $taxonomies = get_object_taxonomies($post_type, 'names');
        
        if (!empty($taxonomies)) {
            $terms = wp_get_object_terms($post_id, $taxonomies, ['fields' => 'ids']);
            
            if (!empty($terms) && !is_wp_error($terms)) {
                $args['tax_query'] = [
                    [
                        'taxonomy' => $taxonomies,
                        'field' => 'term_id',
                        'terms' => $terms,
                        'operator' => 'IN',
                    ],
                ];
            }
        }

        return new \WP_Query($args);
    }

    /**
     * Ottiene statistiche sulla query
     *
     * @param \WP_Query $query
     * @return array
     */
    public static function get_query_stats($query) {
        return [
            'found_posts' => $query->found_posts,
            'post_count' => $query->post_count,
            'max_num_pages' => $query->max_num_pages,
            'current_page' => max(1, get_query_var('paged')),
        ];
    }
}