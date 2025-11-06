/**
 * Frontend JavaScript per Elementor Post Loop
 * Gestisce la paginazione AJAX per Load More e paginazione numerata
 */

(function($) {
    'use strict';

    /**
     * Classe principale per gestire la paginazione AJAX
     */
    class ElementorPostLoopAjax {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
        }

        /**
         * Associa gli eventi ai pulsanti
         */
        bindEvents() {
            // Load More Button - usa delegation su document
            $(document).on('click', '.elementor-post-loop-load-more', this.handleLoadMore.bind(this));
            
            // Paginazione numerata
            $(document).on('click', '.elementor-post-loop-pagination .page-numbers', this.handlePagination.bind(this));
            
            // Fallback: bind diretto se i pulsanti esistono già
            this.bindDirectEvents();
        }
        
        /**
         * Binding diretto agli elementi esistenti (fallback)
         */
        bindDirectEvents() {
            const $loadMoreButtons = $('.elementor-post-loop-load-more');
            
            if ($loadMoreButtons.length > 0) {
                $loadMoreButtons.off('click.direct').on('click.direct', this.handleLoadMore.bind(this));
            }
        }

        /**
         * Gestisce il click sul pulsante Load More
         */
        handleLoadMore(e) {
            e.preventDefault();
            
            const $button = $(e.currentTarget);
            const $widget = $button.closest('.elementor-widget-elementor-post-loop');
            const currentPage = parseInt($button.data('page')) || 1;
            const maxPages = parseInt($button.data('max-pages')) || 1;
            
            // Previeni click multipli
            if ($button.hasClass('loading')) {
                return;
            }

            // Se siamo all'ultima pagina, nascondi il pulsante
            if (currentPage >= maxPages) {
                $button.hide();
                return;
            }

            const nextPage = currentPage + 1;
            
            // Aggiungi stato di caricamento
            this.setLoadingState($button, true);
            
            // Fai la richiesta AJAX
            this.makeAjaxRequest($widget, nextPage, 'load_more')
                .done((response) => {
                    if (response.success && response.data.html) {
                        // Trova il container dei post e del layout
                        const $postsContainer = $widget.find('.elementor-post-loop-posts');
                        const $layoutContainer = $postsContainer.find('.elementor-post-loop-grid, .elementor-post-loop-list, .elementor-post-loop-masonry').first();
                        
                        // Crea un elemento jQuery temporaneo per parsare l'HTML ricevuto
                        const $tempDiv = $('<div>').html(response.data.html);
                        
                        // Prova a estrarre gli items dal container di layout ricevuto
                        let $newItems = $tempDiv.find('.elementor-post-loop-item');
                        
                        // Se non ci sono items nel wrapper, potrebbe essere che l'HTML sia direttamente gli items
                        if ($newItems.length === 0) {
                            // Prova a vedere se l'HTML stesso contiene direttamente gli items
                            $newItems = $(response.data.html).filter('.elementor-post-loop-item');
                            // Se ancora nessun item, prova a vedere se è un singolo item
                            if ($newItems.length === 0) {
                                $newItems = $(response.data.html);
                            }
                        }
                        
                        if ($newItems.length === 0) {
                            return;
                        }
                        
                        // Prepara i nuovi post per l'animazione
                        $newItems.css({
                            'opacity': '0',
                            'transform': 'translateY(30px)',
                            'transition': 'all 0.4s ease-out'
                        });
                        
                        // Aggiungili al container del layout (non al posts container)
                        if ($layoutContainer.length > 0) {
                            $layoutContainer.append($newItems);
                        } else {
                            // Fallback: appendi al posts container
                            $postsContainer.append($newItems);
                        }
                        
                        // Forza un reflow per assicurare che il CSS sia applicato
                        if ($newItems.length > 0) {
                            $newItems[0].offsetHeight;
                        }
                        
                        // Animazione di apparizione staggered
                        $newItems.each(function(index) {
                            const $item = $(this);
                            setTimeout(() => {
                                $item.css({
                                    'opacity': '1',
                                    'transform': 'translateY(0px)'
                                });
                            }, index * 150);
                        });
                        
                        // Aggiorna il numero di pagina
                        $button.data('page', nextPage);
                        
                        // Se abbiamo raggiunto l'ultima pagina, nascondi il pulsante
                        if (nextPage >= maxPages) {
                            $button.fadeOut();
                        }
                        
                        // Trigger evento personalizzato
                        $widget.trigger('elementor-post-loop:loaded', {
                            page: nextPage,
                            type: 'load_more'
                        });
                    } else {
                        console.error('Errore nel caricamento dei post:', response.data?.message || 'Errore sconosciuto');
                    }
                })
                .fail((xhr, status, error) => {
                    console.error('Errore AJAX:', error);
                    alert('Errore nel caricamento dei post. Riprova.');
                })
                .always(() => {
                    this.setLoadingState($button, false);
                });
        }

        /**
         * Gestisce il click sulla paginazione numerata
         */
        handlePagination(e) {
            e.preventDefault();
            
            const $link = $(e.currentTarget);
            const $widget = $link.closest('.elementor-widget-elementor-post-loop');
            
            // Ignora click su pagina corrente e dots
            if ($link.hasClass('current') || $link.hasClass('dots')) {
                return;
            }

            // Estrai il numero di pagina dall'URL o dal data attribute
            let page = 1;
            
            // Prima controlla se c'è un attributo data-page
            if ($link.data('page')) {
                page = parseInt($link.data('page'));
            } else {
                // Prova a estrarre dall'href
                const href = $link.attr('href');
                if (href) {
                    // Cerca pattern come #page-2, /page/2/, o ?paged=2
                    const patterns = [
                        /#page-(\d+)/,           // #page-2
                        /\/page\/(\d+)/,         // /page/2/
                        /[?&]paged=(\d+)/        // ?paged=2
                    ];
                    
                    for (let pattern of patterns) {
                        const matches = href.match(pattern);
                        if (matches) {
                            page = parseInt(matches[1]);
                            break;
                        }
                    }
                }
                
                // Ultimo fallback: prova a leggere il testo del link (per i numeri)
                if (page === 1 && $link.text().match(/^\d+$/)) {
                    page = parseInt($link.text());
                }
            }
            
            // Aggiungi loading overlay
            this.setWidgetLoading($widget, true);
            
            // Fai la richiesta AJAX
            this.makeAjaxRequest($widget, page, 'pagination')
                .done((response) => {
                    if (response.success && response.data.html) {
                        const $postsContainer = $widget.find('.elementor-post-loop-posts');
                        
                        // Fade out dei contenuti esistenti
                        $postsContainer.css('opacity', '0.3');
                        
                        setTimeout(() => {
                            // Trova il container del layout esistente (grid/list/masonry)
                            let $layoutContainer = $postsContainer.find('.elementor-post-loop-grid, .elementor-post-loop-list, .elementor-post-loop-masonry').first();
                            
                            // Se esiste un container di layout, sostituiscilo
                            if ($layoutContainer.length > 0) {
                                $layoutContainer.replaceWith(response.data.html);
                            } else {
                                // Altrimenti sostituisci tutto il contenuto
                                $postsContainer.html(response.data.html);
                            }
                            
                            // Aggiorna la paginazione
                            if (response.data.pagination) {
                                const $paginationContainer = $widget.find('.elementor-post-loop-pagination');
                                $paginationContainer.html(response.data.pagination);
                            }
                            
                            // Rimuovi tutti gli stili inline e assicurati della visibilità
                            $postsContainer.css({
                                'opacity': '',
                                'display': ''
                            });
                            
                            // IMPORTANTE: Ri-trova il postsContainer dopo la sostituzione
                            // perché replaceWith può aver cambiato la struttura DOM
                            const $updatedPostsContainer = $widget.find('.elementor-post-loop-posts');
                            
                            // Anima i nuovi post items
                            const $postItems = $updatedPostsContainer.find('.elementor-post-loop-item');
                            
                            if ($postItems.length === 0) {
                                return;
                            }
                            
                            // Imposta lo stato iniziale per l'animazione
                            $postItems.css({
                                'opacity': '0',
                                'transform': 'translateY(20px)',
                                'transition': 'all 0.4s ease-out'
                            });
                            
                            // Forza un reflow
                            if ($postItems.length > 0) {
                                $postItems[0].offsetHeight;
                            }
                            
                            // Anima gli elementi uno per uno
                            $postItems.each(function(index) {
                                const $item = $(this);
                                setTimeout(() => {
                                    $item.css({
                                        'opacity': '1',
                                        'transform': 'translateY(0px)'
                                    });
                                }, index * 100);
                            });
                            
                            // Scroll alla sezione
                            this.scrollToWidget($widget);
                        }, 200);
                        
                        // Trigger evento personalizzato
                        $widget.trigger('elementor-post-loop:loaded', {
                            page: page,
                            type: 'pagination'
                        });
                    } else {
                        console.error('Errore nel caricamento della pagina:', response.data?.message || 'Errore sconosciuto');
                    }
                })
                .fail((xhr, status, error) => {
                    console.error('Errore AJAX:', error);
                    console.error('XHR:', xhr);
                    alert('Errore nel caricamento della pagina. Riprova.');
                })
                .always(() => {
                    this.setWidgetLoading($widget, false);
                });
        }

        /**
         * Fa la richiesta AJAX al server
         */
        makeAjaxRequest($widget, page, type) {
            // Raccogli i settings del widget
            const settings = this.getWidgetSettings($widget);
            
            return $.ajax({
                url: elementor_post_loop_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'elementor_post_loop_load_posts',
                    nonce: elementor_post_loop_ajax.nonce,
                    page: page,
                    type: type,
                    settings: settings,
                    widget_id: $widget.data('id')
                },
                timeout: 30000
            });
        }

        /**
         * Estrae i settings del widget dal DOM
         */
        getWidgetSettings($widget) {
            // Prima prova a ottenere i settings completi dal data-settings JSON
            const $postLoop = $widget.find('.elementor-post-loop');
            let settings = {};
            
            if ($postLoop.length) {
                const settingsData = $postLoop.attr('data-settings');
                
                if (settingsData) {
                    try {
                        settings = JSON.parse(settingsData);
                        return settings;
                    } catch (e) {
                        // Parsing error, use fallback
                    }
                }
                
                // Fallback: leggi dai singoli data attributes
                settings = {
                    post_type: $postLoop.data('post-type') || 'post',
                    posts_per_page: $postLoop.data('posts-per-page') || 6,
                    layout: $postLoop.data('layout') || 'grid',
                    orderby: $postLoop.data('orderby') || 'date',
                    order: $postLoop.data('order') || 'DESC'
                };
            }

            // Aggiungi defaults per il contenuto dei post se non già presenti
            const defaults = {
                show_image: 'yes',
                show_title: 'yes',
                show_excerpt: 'yes',
                show_meta: 'yes',
                show_read_more: 'yes',
                title_tag: 'h3',
                excerpt_length: 20,
                read_more_text: 'Leggi di più',
                link_title: 'yes',
                link_image: 'yes',
                image_size: 'medium',
                template_style: 'card'
            };

            settings = Object.assign(defaults, settings);
            
            return settings;
        }

        /**
         * Imposta lo stato di caricamento per il pulsante Load More
         */
        setLoadingState($button, loading) {
            if (loading) {
                $button.addClass('loading');
                const originalText = $button.text();
                $button.data('original-text', originalText);
                $button.text('Caricamento...');
                $button.prop('disabled', true);
            } else {
                $button.removeClass('loading');
                const originalText = $button.data('original-text') || 'Carica altri';
                $button.text(originalText);
                $button.prop('disabled', false);
            }
        }

        /**
         * Imposta lo stato di caricamento per l'intero widget
         */
        setWidgetLoading($widget, loading) {
            if (loading) {
                // Aggiungi overlay di caricamento
                const $overlay = $('<div class="elementor-post-loop-loading-overlay"><div class="elementor-post-loop-spinner"></div></div>');
                $widget.append($overlay);
                $widget.addClass('elementor-post-loop-loading');
            } else {
                // Rimuovi overlay
                $widget.find('.elementor-post-loop-loading-overlay').remove();
                $widget.removeClass('elementor-post-loop-loading');
            }
        }

        /**
         * Scroll smooth al widget dopo il caricamento
         */
        scrollToWidget($widget) {
            $('html, body').animate({
                scrollTop: $widget.offset().top - 50
            }, 500);
        }
    }

    /**
     * Inizializza quando il DOM è pronto
     */
    $(document).ready(function() {
        window.elementorPostLoopAjaxInstance = new ElementorPostLoopAjax();
    });

    /**
     * Re-inizializza quando Elementor carica nuovi elementi (per preview e frontend)
     */
    $(window).on('elementor/frontend/init', function() {
        if (window.elementorFrontend) {
            // Hook per quando il widget viene renderizzato
            elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope) {
                if ($scope.hasClass('elementor-widget-elementor-post-loop')) {
                    // Trova il pulsante Load More in questo scope
                    const $loadMoreBtn = $scope.find('.elementor-post-loop-load-more');
                    if ($loadMoreBtn.length > 0) {
                        // Re-bind degli eventi diretti
                        if (window.elementorPostLoopAjaxInstance) {
                            window.elementorPostLoopAjaxInstance.bindDirectEvents();
                        }
                    }
                }
            });
        }
    });

})(jQuery);