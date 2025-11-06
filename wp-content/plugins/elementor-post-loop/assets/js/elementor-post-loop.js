/**
 * JavaScript per Elementor Post Loop
 */

(function($) {
    'use strict';

    /**
     * Inizializza il plugin quando il DOM è pronto
     */
    $(document).ready(function() {
        // Inizializza masonry se presente
        initMasonry();
        
        // Inizializza load more
        initLoadMore();
        
        // Inizializza animazioni
        initAnimations();
    });

    /**
     * Inizializza il layout masonry
     */
    function initMasonry() {
        if (typeof $.fn.masonry !== 'undefined') {
            $('.elementor-post-loop-masonry').each(function() {
                $(this).masonry({
                    itemSelector: '.elementor-post-loop-item',
                    columnWidth: '.elementor-post-loop-item',
                    gutter: 20,
                    percentPosition: true
                });
            });
        }
    }

    /**
     * Inizializza la funzionalità "Carica altri"
     */
    function initLoadMore() {
        $(document).on('click', '.elementor-post-loop-load-more', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var $container = $button.closest('.elementor-post-loop-container');
            var currentPage = parseInt($button.data('page'));
            var maxPages = parseInt($button.data('max-pages'));
            var nextPage = currentPage + 1;
            
            // Se abbiamo raggiunto l'ultima pagina, nascondi il pulsante
            if (nextPage > maxPages) {
                $button.hide();
                return;
            }
            
            // Mostra loading
            $button.addClass('loading').prop('disabled', true).text('Caricamento...');
            
            // Effettua la richiesta AJAX
            $.ajax({
                url: elementorPostLoop.ajaxurl,
                type: 'POST',
                data: {
                    action: 'elementor_post_loop_load_more',
                    page: nextPage,
                    settings: $container.data('settings'),
                    nonce: elementorPostLoop.nonce
                },
                success: function(response) {
                    if (response.success && response.data.html) {
                        // Aggiungi i nuovi post
                        var $newItems = $(response.data.html);
                        var $grid = $container.find('.elementor-post-loop-grid, .elementor-post-loop-masonry, .elementor-post-loop-list');
                        
                        if ($grid.hasClass('elementor-post-loop-masonry') && typeof $.fn.masonry !== 'undefined') {
                            $grid.append($newItems).masonry('appended', $newItems);
                        } else {
                            $grid.append($newItems);
                        }
                        
                        // Aggiorna il numero di pagina
                        $button.data('page', nextPage);
                        
                        // Se questa era l'ultima pagina, nascondi il pulsante
                        if (nextPage >= maxPages) {
                            $button.hide();
                        }
                        
                        // Anima i nuovi elementi
                        animateNewItems($newItems);
                    }
                },
                error: function() {
                    console.log('Errore nel caricamento dei post');
                },
                complete: function() {
                    $button.removeClass('loading').prop('disabled', false).text($button.data('original-text') || 'Carica altri');
                }
            });
        });
    }

    /**
     * Inizializza le animazioni
     */
    function initAnimations() {
        // Animazione di entrata per gli elementi visibili
        if (typeof IntersectionObserver !== 'undefined') {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('animate-in');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            $('.elementor-post-loop-item').each(function() {
                observer.observe(this);
            });
        }
    }

    /**
     * Anima i nuovi elementi caricati
     */
    function animateNewItems($items) {
        $items.each(function(index) {
            var $item = $(this);
            setTimeout(function() {
                $item.addClass('animate-in');
            }, index * 100);
        });
    }

    /**
     * Gestisce il ridimensionamento della finestra
     */
    $(window).on('resize', function() {
        // Ricalcola masonry
        if (typeof $.fn.masonry !== 'undefined') {
            $('.elementor-post-loop-masonry').masonry('layout');
        }
    });

    /**
     * Gestisce il caricamento delle immagini per masonry
     */
    $(window).on('load', function() {
        if (typeof $.fn.masonry !== 'undefined') {
            $('.elementor-post-loop-masonry').masonry('layout');
        }
    });

    /**
     * Funzione per aggiornare dinamicamente i controlli Elementor
     */
    window.elementorPostLoopUpdateControls = function(panel) {
        // Aggiorna le opzioni delle tassonomie quando cambia il post type
        panel.elements.$element.on('change', '.elementor-control-post_type select', function() {
            var postType = $(this).val();
            // Qui potresti aggiungere logica per aggiornare dinamicamente
            // le opzioni delle tassonomie tramite AJAX
        });
    };

})(jQuery);

/**
 * CSS per le animazioni
 */
var animationCSS = `
    .elementor-post-loop-item {
        /*opacity: 0;*/
        transform: translateY(30px);
        transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .elementor-post-loop-item.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    .elementor-post-loop-load-more.loading {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .elementor-post-loop-load-more.loading::after {
        content: '';
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid transparent;
        border-top-color: currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 8px;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
`;

// Aggiungi gli stili delle animazioni
var style = document.createElement('style');
style.textContent = animationCSS;
document.head.appendChild(style);