/**
 * JavaScript per l'editor di Elementor
 */

(function($) {
    'use strict';

    // Attende che Elementor sia caricato
    $(window).on('elementor/frontend/init', function() {
        
        // Handler per l'anteprima del widget nell'editor
        elementorFrontend.hooks.addAction('frontend/element_ready/elementor-post-loop.default', function($scope) {
            
            // Se siamo nell'editor, inizializza l'anteprima
            if (elementorFrontend.isEditMode()) {
                initEditorPreview($scope);
            } else {
                // Se siamo nel frontend, inizializza le funzionalità normali
                initFrontendFeatures($scope);
            }
        });
    });

    /**
     * Inizializza l'anteprima nell'editor
     */
    function initEditorPreview($scope) {
        var $container = $scope.find('.elementor-post-loop-container');
        
        // Disabilita i link nell'anteprima
        $container.find('a').on('click', function(e) {
            e.preventDefault();
            return false;
        });

        // Aggiorna l'anteprima quando cambiano le impostazioni
        updatePreviewInfo($scope);
    }

    /**
     * Inizializza le funzionalità del frontend
     */
    function initFrontendFeatures($scope) {
        var $container = $scope.find('.elementor-post-loop-container');
        
        // Inizializza masonry se presente
        if ($container.find('.elementor-post-loop-masonry').length) {
            initMasonry($container);
        }
        
        // Inizializza load more
        initLoadMore($container);
        
        // Inizializza animazioni
        initAnimations($container);
    }

    /**
     * Aggiorna le informazioni di anteprima
     */
    function updatePreviewInfo($scope) {
        var $info = $scope.find('.elementor-post-loop-editor-info small');
        var $container = $scope.find('.elementor-post-loop-container');
        var layout = 'grid';
        var itemCount = $scope.find('.elementor-post-loop-item').length;
        
        if ($container.hasClass('elementor-post-loop-grid')) {
            layout = 'griglia';
        } else if ($container.hasClass('elementor-post-loop-list')) {
            layout = 'lista';
        } else if ($container.hasClass('elementor-post-loop-masonry')) {
            layout = 'masonry';
        }
        
        if ($info.length) {
            $info.html('<i class="eicon-info-circle"></i> Anteprima: ' + itemCount + ' elementi in layout "' + layout + '"');
        }
    }

    /**
     * Inizializza masonry
     */
    function initMasonry($container) {
        var $masonry = $container.find('.elementor-post-loop-masonry');
        
        if ($masonry.length && typeof $.fn.masonry !== 'undefined') {
            $masonry.imagesLoaded(function() {
                $masonry.masonry({
                    itemSelector: '.elementor-post-loop-item',
                    columnWidth: '.elementor-post-loop-item',
                    gutter: 20,
                    percentPosition: true
                });
            });
        }
    }

    /**
     * Inizializza load more
     */
    function initLoadMore($container) {
        $container.off('click.postloop').on('click.postloop', '.elementor-post-loop-load-more', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var currentPage = parseInt($button.data('page')) || 1;
            var maxPages = parseInt($button.data('max-pages')) || 1;
            var nextPage = currentPage + 1;
            
            if (nextPage > maxPages) {
                $button.hide();
                return;
            }
            
            // Simula il caricamento (nell'implementazione reale qui ci sarebbe AJAX)
            $button.addClass('loading').prop('disabled', true).text('Caricamento...');
            
            setTimeout(function() {
                $button.removeClass('loading').prop('disabled', false).text('Carica altri');
                $button.data('page', nextPage);
                
                if (nextPage >= maxPages) {
                    $button.hide();
                }
            }, 1000);
        });
    }

    /**
     * Inizializza animazioni
     */
    function initAnimations($container) {
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

            $container.find('.elementor-post-loop-item').each(function() {
                observer.observe(this);
            });
        }
    }

    // Gestione del panel dell'editor
    if (typeof elementor !== 'undefined') {
        elementor.hooks.addAction('panel/open_editor/widget/elementor-post-loop', function(panel, model, view) {
            // Aggiorna i controlli dinamici quando si apre il panel
            updateDynamicControls(panel, model);
        });
    }

    /**
     * Aggiorna i controlli dinamici nel panel
     */
    function updateDynamicControls(panel, model) {
        // Aggiorna le opzioni delle tassonomie quando cambia il post type
        panel.$el.on('change', '.elementor-control-post_type select', function() {
            var postType = $(this).val();
            // Qui potresti implementare la logica per aggiornare dinamicamente
            // le opzioni delle tassonomie via AJAX
            console.log('Post type changed to:', postType);
        });
    }

})(jQuery);

// CSS aggiuntivo per migliorare l'esperienza nell'editor
var editorCSS = `
    .elementor-editor-active .elementor-post-loop-container {
        min-height: 200px;
    }
    
    .elementor-editor-active .elementor-post-loop-item {
        cursor: default;
    }
    
    .elementor-editor-active .elementor-post-loop-item:hover {
        transform: none;
    }
    
    .elementor-widget-elementor-post-loop .elementor-widget-empty-icon {
        display: none;
    }
`;

// Aggiungi gli stili per l'editor
if (typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode()) {
    var style = document.createElement('style');
    style.textContent = editorCSS;
    document.head.appendChild(style);
}