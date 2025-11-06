<?php
/**
 * File di test per il plugin Elementor Post Loop
 * Questo file può essere utilizzato per testare le funzionalità del plugin
 */

// Previene l'accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Funzione di test per verificare che il plugin funzioni correttamente
 */
function elementor_post_loop_test() {
    // Verifica che Elementor sia attivo
    if (!did_action('elementor/loaded')) {
        return 'Elementor non è attivo';
    }

    // Verifica che il plugin sia caricato
    if (!class_exists('Elementor_Post_Loop')) {
        return 'Plugin non caricato correttamente';
    }

    // Test delle classi helper
    if (!class_exists('Elementor_Post_Loop\Includes\Post_Helper')) {
        return 'Post_Helper non trovato';
    }

    // Test del query builder
    if (!class_exists('Elementor_Post_Loop\Includes\Query_Builder')) {
        return 'Query_Builder non trovato';
    }

    // Test del template loader
    if (!class_exists('Elementor_Post_Loop\Includes\Template_Loader')) {
        return 'Template_Loader non trovato';
    }

    // Test del widget
    if (!class_exists('Elementor_Post_Loop\Widgets\Post_Loop_Widget')) {
        return 'Post_Loop_Widget non trovato';
    }

    return 'Tutti i test superati! ✅';
}

/**
 * Funzione per testare la query builder
 */
function elementor_post_loop_test_query() {
    $settings = [
        'post_type' => 'post',
        'posts_per_page' => 3,
        'orderby' => 'date',
        'order' => 'DESC'
    ];

    $query = \Elementor_Post_Loop\Includes\Query_Builder::get_query($settings);
    
    return [
        'found_posts' => $query->found_posts,
        'post_count' => $query->post_count,
        'max_num_pages' => $query->max_num_pages
    ];
}

// Se questo file viene chiamato direttamente con parametro test, esegue i test
if (isset($_GET['elementor_post_loop_test']) && current_user_can('manage_options')) {
    echo '<h2>Test Elementor Post Loop</h2>';
    echo '<p><strong>Test Base:</strong> ' . elementor_post_loop_test() . '</p>';
    
    if (function_exists('elementor_post_loop_test_query')) {
        $query_test = elementor_post_loop_test_query();
        echo '<p><strong>Test Query:</strong></p>';
        echo '<ul>';
        echo '<li>Post trovati: ' . $query_test['found_posts'] . '</li>';
        echo '<li>Post nella query: ' . $query_test['post_count'] . '</li>';
        echo '<li>Pagine totali: ' . $query_test['max_num_pages'] . '</li>';
        echo '</ul>';
    }
    
    // Test post types
    $post_types = \Elementor_Post_Loop\Includes\Post_Helper::get_post_types();
    echo '<p><strong>Post Types disponibili:</strong></p>';
    echo '<ul>';
    foreach ($post_types as $key => $label) {
        echo '<li>' . $key . ' - ' . $label . '</li>';
    }
    echo '</ul>';
    
    exit;
}