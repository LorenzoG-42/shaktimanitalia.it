<?php
/**
 * Classe helper per gestire i tipi di post e le tassonomie
 */

namespace Elementor_Post_Loop\Includes;

if (!defined('ABSPATH')) {
    exit;
}

class Post_Helper {

    /**
     * Ottiene tutti i tipi di post pubblici disponibili
     *
     * @return array
     */
    public static function get_post_types() {
        $post_types = get_post_types([
            'public' => true,
        ], 'objects');

        $excluded_post_types = [
            'attachment',
            'revision',
            'nav_menu_item',
            'custom_css',
            'customize_changeset',
            'oembed_cache',
            'elementor_library',
            'e-landing-page'
        ];

        $options = [];
        foreach ($post_types as $post_type) {
            if (!in_array($post_type->name, $excluded_post_types)) {
                $options[$post_type->name] = $post_type->label;
            }
        }

        return $options;
    }

    /**
     * Ottiene le tassonomie per un tipo di post specifico
     *
     * @param string $post_type
     * @return array
     */
    public static function get_taxonomies_by_post_type($post_type) {
        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $options = [];

        foreach ($taxonomies as $taxonomy) {
            if ($taxonomy->public && $taxonomy->show_ui) {
                $options[$taxonomy->name] = $taxonomy->label;
            }
        }

        return $options;
    }

    /**
     * Ottiene i termini di una tassonomia specifica
     *
     * @param string $taxonomy
     * @return array
     */
    public static function get_terms_by_taxonomy($taxonomy) {
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);

        $options = [];
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
        }

        return $options;
    }

    /**
     * Ottiene tutti i post di un tipo specifico
     *
     * @param string $post_type
     * @param int $limit
     * @return array
     */
    public static function get_posts_by_type($post_type, $limit = -1) {
        $posts = get_posts([
            'post_type' => $post_type,
            'post_status' => 'publish',
            'numberposts' => $limit,
            'suppress_filters' => false,
        ]);

        $options = [];
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title ?: sprintf(__('(ID: %d)', 'elementor-post-loop'), $post->ID);
        }

        return $options;
    }

    /**
     * Ottiene tutti gli autori del sito
     *
     * @return array
     */
    public static function get_authors() {
        $authors = get_users([
            'who' => 'authors',
            'has_published_posts' => true,
            'fields' => ['ID', 'display_name'],
        ]);

        $options = [];
        foreach ($authors as $author) {
            $options[$author->ID] = $author->display_name;
        }

        return $options;
    }

    /**
     * Ottiene le opzioni di ordinamento
     *
     * @return array
     */
    public static function get_order_by_options() {
        return [
            'date' => __('Data', 'elementor-post-loop'),
            'title' => __('Titolo', 'elementor-post-loop'),
            'menu_order' => __('Ordine Menu', 'elementor-post-loop'),
            'modified' => __('Data Modifica', 'elementor-post-loop'),
            'author' => __('Autore', 'elementor-post-loop'),
            'comment_count' => __('Numero Commenti', 'elementor-post-loop'),
            'ID' => __('ID Post', 'elementor-post-loop'),
            'rand' => __('Casuale', 'elementor-post-loop'),
        ];
    }

    /**
     * Ottiene le opzioni di direzione ordinamento
     *
     * @return array
     */
    public static function get_order_options() {
        return [
            'DESC' => __('Decrescente', 'elementor-post-loop'),
            'ASC' => __('Crescente', 'elementor-post-loop'),
        ];
    }

    /**
     * Ottiene i metabox personalizzati per un tipo di post
     *
     * @param string $post_type
     * @return array
     */
    public static function get_custom_fields_by_post_type($post_type) {
        global $wpdb;

        $meta_keys = $wpdb->get_col($wpdb->prepare("
            SELECT DISTINCT meta_key 
            FROM {$wpdb->postmeta} pm 
            INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
            WHERE p.post_type = %s 
            AND meta_key NOT LIKE '\_%' 
            AND meta_key != '' 
            ORDER BY meta_key
        ", $post_type));

        $options = [];
        foreach ($meta_keys as $meta_key) {
            $options[$meta_key] = $meta_key;
        }

        return $options;
    }

    /**
     * Verifica se un tipo di post supporta una caratteristica
     *
     * @param string $post_type
     * @param string $feature
     * @return bool
     */
    public static function post_type_supports($post_type, $feature) {
        return post_type_supports($post_type, $feature);
    }

    /**
     * Ottiene le opzioni per i formati di data
     *
     * @return array
     */
    public static function get_date_formats() {
        return [
            'default' => __('Default WordPress', 'elementor-post-loop'),
            'F j, Y' => __('Mese Giorno, Anno', 'elementor-post-loop'),
            'Y-m-d' => __('Anno-Mese-Giorno', 'elementor-post-loop'),
            'd/m/Y' => __('Giorno/Mese/Anno', 'elementor-post-loop'),
            'd.m.Y' => __('Giorno.Mese.Anno', 'elementor-post-loop'),
            'j F Y' => __('Giorno Mese Anno', 'elementor-post-loop'),
            'custom' => __('Formato Personalizzato', 'elementor-post-loop'),
        ];
    }

    /**
     * Ottiene lo stato dei post disponibili
     *
     * @return array
     */
    public static function get_post_status_options() {
        $post_statuses = get_post_stati();
        $options = [];

        foreach ($post_statuses as $status => $label) {
            if ($status !== 'auto-draft') {
                $options[$status] = $label;
            }
        }

        return $options;
    }

    /**
     * Verifica se un tipo di post è gerarchico
     *
     * @param string $post_type
     * @return bool
     */
    public static function is_post_type_hierarchical($post_type) {
        return is_post_type_hierarchical($post_type);
    }

    /**
     * Ottiene i post padre per un tipo di post gerarchico
     *
     * @param string $post_type
     * @return array
     */
    public static function get_parent_posts($post_type) {
        if (!self::is_post_type_hierarchical($post_type)) {
            return [];
        }

        $posts = get_posts([
            'post_type' => $post_type,
            'post_status' => 'publish',
            'numberposts' => -1,
            'post_parent' => 0,
        ]);

        $options = [];
        foreach ($posts as $post) {
            $options[$post->ID] = $post->post_title;
        }

        return $options;
    }
}