<?php
/**
 * Functions and definitions per il tema child Shaktiman Italia
 */

// Enqueue degli stili del tema padre e del tema child
function shaktiman_italia_enqueue_styles() {
    // Style del tema padre
    wp_enqueue_style( 'autoev-style', get_template_directory_uri() . '/style.css' );
    
    // Style del tema child
    wp_enqueue_style( 'shaktiman-italia-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('autoev-style'),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'shaktiman_italia_enqueue_styles' );

// Qui puoi aggiungere le tue personalizzazioni PHP