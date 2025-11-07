/**
 * JavaScript Frontend per Shaktiman B2B
 */

(function($) {
    'use strict';
    
    /**
     * Gestione filtri mezzi agricoli
     */
    const MezziFiltri = {
        
        /**
         * Inizializza
         */
        init: function() {
            this.initSelect2();
            this.bindEvents();
        },
        
        /**
         * Inizializza Select2
         */
        initSelect2: function() {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.filter-select').select2({
                    placeholder: 'Seleziona...',
                    allowClear: true,
                    width: '100%',
                    language: {
                        noResults: function() {
                            return 'Nessun risultato trovato';
                        },
                        searching: function() {
                            return 'Ricerca...';
                        }
                    }
                });
            }
        },
        
        /**
         * Associa eventi
         */
        bindEvents: function() {
            const self = this;
            
            // Filtri su change
            $(document).on('change', '.filter-select', function() {
                self.applyFilters();
            });
            
            // Ricerca con debounce
            let searchTimeout;
            $(document).on('keyup', '.filter-input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    self.applyFilters();
                }, 500);
            });
            
            // Reset filtri
            $(document).on('click', '.btn-reset, #reset-filters', function(e) {
                e.preventDefault();
                self.resetFilters();
            });
            
            // Gestione form submit (previene invio)
            $(document).on('submit', '#mezzi-filter-form, #mezzi-filter-form-shortcode', function(e) {
                e.preventDefault();
                self.applyFilters();
            });
        },
        
        /**
         * Applica filtri
         */
        applyFilters: function() {
            const form = $('#mezzi-filter-form, #mezzi-filter-form-shortcode');
            const grid = $('#mezzi-grid, .mezzi-grid');
            const overlay = $('.loading-overlay');
            
            // Mostra loading
            overlay.fadeIn(200);
            
            // Prepara dati
            const data = {
                action: 'filter_mezzi_agricoli',
                nonce: shaktimanB2B.nonce,
                search: form.find('[name="search"]').val(),
                disponibilita: form.find('[name="disponibilita"]').val(),
                categoria_mezzo: form.find('[name="categoria_mezzo"]').val(),
                modello: form.find('[name="modello"]').val(),
                versione: form.find('[name="versione"]').val(),
                ubicazione: form.find('[name="ubicazione"]').val(),
                stato_magazzino: form.find('[name="stato_magazzino"]').val(),
                per_page: 12,
                paged: 1
            };
            
            // AJAX request
            $.ajax({
                url: shaktimanB2B.ajaxUrl,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        // Aggiorna griglia
                        grid.fadeOut(200, function() {
                            $(this).html(response.data.html).fadeIn(200);
                        });
                        
                        // Aggiorna contatore risultati
                        const resultsInfo = $('.results-info, #results-info');
                        if (response.data.found_posts !== undefined) {
                            const text = response.data.found_posts === 1 
                                ? response.data.found_posts + ' mezzo trovato'
                                : response.data.found_posts + ' mezzi trovati';
                            resultsInfo.text(text);
                        }
                        
                        // Aggiorna le opzioni dei filtri
                        MezziFiltri.updateFilterOptions();
                        
                        // Scroll to top risultati
                        $('html, body').animate({
                            scrollTop: $('.mezzi-results').offset().top - 100
                        }, 400);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Errore nel filtraggio:', error);
                    if (typeof NotifyModal !== 'undefined') {
                        NotifyModal.show('Si è verificato un errore durante il filtraggio. Riprova.', 'Errore');
                    }
                },
                complete: function() {
                    overlay.fadeOut(200);
                }
            });
        },
        
        /**
         * Reset filtri
         */
        resetFilters: function() {
            const form = $('#mezzi-filter-form, #mezzi-filter-form-shortcode');
            
            // Reset campi
            form.find('input[type="text"]').val('');
            form.find('select').prop('selectedIndex', 0);
            
            // Riapplica filtri (che ora sono vuoti)
            this.applyFilters();
        },
        
        /**
         * Aggiorna le opzioni dei filtri in base ai filtri attuali
         */
        updateFilterOptions: function() {
            const form = $('#mezzi-filter-form, #mezzi-filter-form-shortcode');
            
            // Prepara dati dei filtri attuali
            const data = {
                action: 'get_filtered_options',
                nonce: shaktimanB2B.nonce,
                disponibilita: form.find('[name="disponibilita"]').val(),
                categoria_mezzo: form.find('[name="categoria_mezzo"]').val(),
                modello: form.find('[name="modello"]').val(),
                versione: form.find('[name="versione"]').val(),
                ubicazione: form.find('[name="ubicazione"]').val(),
                stato_magazzino: form.find('[name="stato_magazzino"]').val()
            };
            
            $.ajax({
                url: shaktimanB2B.ajaxUrl,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success && response.data.options) {
                        const options = response.data.options;
                        
                        // Aggiorna ogni select
                        $.each(options, function(taxonomy, terms) {
                            const select = form.find('[name="' + taxonomy + '"]');
                            if (select.length > 0) {
                                const currentValue = select.val();
                                const isSelect2 = select.hasClass('select2-hidden-accessible');
                                
                                // Salva la prima opzione (placeholder)
                                const firstOption = select.find('option:first').clone();
                                
                                // Svuota la select
                                select.empty();
                                
                                // Aggiungi la prima opzione
                                select.append(firstOption);
                                
                                // Aggiungi le opzioni disponibili
                                $.each(terms, function(index, term) {
                                    const option = $('<option></option>')
                                        .attr('value', term.slug)
                                        .text(term.name + ' (' + term.count + ')');
                                    select.append(option);
                                });
                                
                                // Ripristina il valore se ancora disponibile
                                if (currentValue && select.find('option[value="' + currentValue + '"]').length > 0) {
                                    select.val(currentValue);
                                }
                                
                                // Aggiorna Select2 se attivo
                                if (isSelect2 && typeof $.fn.select2 !== 'undefined') {
                                    select.trigger('change.select2');
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Errore aggiornamento opzioni filtri:', error);
                }
            });
        }
    };
    
    /**
     * Gestione cambio stato mezzi
     */
    const CambioStato = {
        
        currentPostId: null,
        currentAction: null,
        
        /**
         * Inizializza
         */
        init: function() {
            this.bindEvents();
        },
        
        /**
         * Associa eventi
         */
        bindEvents: function() {
            const self = this;
            
            // Click sui pulsanti di stato
            $(document).on('click', '.btn-stato', function(e) {
                e.preventDefault();
                const $btn = $(this);
                const postId = $btn.data('post-id');
                const action = $btn.data('action');
                const clienteEsistente = $btn.data('cliente-esistente') || '';
                
                if (postId && action) {
                    self.cambiaStato(postId, action, clienteEsistente);
                }
            });
        },
        
        /**
         * Cambia stato del mezzo
         */
        cambiaStato: function(postId, action, clienteEsistente) {
            this.currentPostId = postId;
            this.currentAction = action;
            const self = this;
            
            // Se l'azione è "venduto" e c'è già un nome cliente (riservato->venduto), usa direttamente quello
            if (action === 'venduto' && clienteEsistente && typeof clienteEsistente === 'string' && clienteEsistente.trim() !== '') {
                ConfirmModal.show(
                    'Confermi la vendita a: ' + clienteEsistente + '?',
                    function() {
                        self.eseguiCambioStato(postId, action, clienteEsistente);
                    },
                    'Conferma Vendita'
                );
            }
            // Se l'azione richiede il nome cliente e non è già presente, mostra modale
            else if (action === 'riserva' || action === 'venduto') {
                const title = action === 'riserva' ? 'Riserva Mezzo' : 'Vendi Mezzo';
                ShaktimanModal.open(title, postId, action);
            } else if (action === 'libera') {
                // Conferma diretta per liberare
                ConfirmModal.show(
                    'Sei sicuro di voler liberare questo mezzo?',
                    function() {
                        self.eseguiCambioStato(postId, action, '');
                    },
                    'Conferma Liberazione'
                );
            }
        },
        
        /**
         * Esegue il cambio stato via AJAX
         */
        eseguiCambioStato: function(postId, action, nomeCliente) {
            const $confirmBtn = $('#modal-confirm-btn');
            $confirmBtn.prop('disabled', true).text('Elaborazione...');
            
            // Ottieni l'ID del rivenditore selezionato (solo se admin)
            const rivenditoreId = $('#modal-rivenditore').length ? $('#modal-rivenditore').val() : '';
            
            const data = {
                action: 'cambia_stato_mezzo',
                nonce: shaktimanB2B.nonce,
                post_id: postId,
                stato_action: action,
                nome_cliente: nomeCliente,
                rivenditore_id: rivenditoreId
            };
            
            $.ajax({
                url: shaktimanB2B.ajaxUrl,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        ShaktimanModal.close();
                        // Mostra messaggio di successo
                        NotifyModal.show(response.data.message, 'Successo');
                        // Ricarica la pagina dopo 1.5 secondi
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        NotifyModal.show(response.data.message || 'Errore durante il cambio di stato', 'Errore');
                        $confirmBtn.prop('disabled', false).text('Conferma');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Errore AJAX:', error);
                    NotifyModal.show('Si è verificato un errore. Riprova.', 'Errore');
                    $confirmBtn.prop('disabled', false).text('Conferma');
                }
            });
        }
    };
    
    /**
     * Gestione modale
     */
    const ShaktimanModal = {
        
        currentPostId: null,
        currentAction: null,
        
        /**
         * Apri modale
         */
        open: function(title, postId, action) {
            this.currentPostId = postId;
            this.currentAction = action;
            
            $('#modal-title').text(title);
            $('#modal-nome-cliente').val('');
            $('#shaktiman-modal').addClass('active');
            
            // Carica i dati esistenti del mezzo
            const self = this;
            $.ajax({
                url: shaktimanB2B.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_mezzo_data',
                    nonce: shaktimanB2B.nonce,
                    post_id: postId
                },
                success: function(response) {
                    if (response.success && response.data.nome_cliente) {
                        $('#modal-nome-cliente').val(response.data.nome_cliente);
                    }
                }
            });
            
            // Inizializza Select2 sul campo rivenditore (se presente)
            if ($('#modal-rivenditore').length && typeof $.fn.select2 !== 'undefined') {
                $('#modal-rivenditore').select2({
                    placeholder: 'Cerca rivenditore...',
                    allowClear: true,
                    width: '100%'
                });
            }
            
            // Focus sul campo input
            setTimeout(function() {
                $('#modal-nome-cliente').focus();
            }, 100);
            
            // Bind confirm button
            $('#modal-confirm-btn').off('click').on('click', function() {
                self.confirm();
            });
            
            // Bind enter key
            $('#modal-nome-cliente').off('keypress').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    self.confirm();
                }
            });
        },
        
        /**
         * Chiudi modale
         */
        close: function() {
            // Distruggi Select2 se presente
            if ($('#modal-rivenditore').length && $('#modal-rivenditore').hasClass('select2-hidden-accessible')) {
                $('#modal-rivenditore').select2('destroy');
            }
            
            $('#shaktiman-modal').removeClass('active');
            $('#modal-nome-cliente').val('');
            $('#modal-rivenditore').val('');
            this.currentPostId = null;
            this.currentAction = null;
        },
        
        /**
         * Conferma azione
         */
        confirm: function() {
            const nomeCliente = $('#modal-nome-cliente').val().trim();
            
            if (!nomeCliente) {
                NotifyModal.show('Il nome del cliente è obbligatorio', 'Attenzione');
                return;
            }
            
            CambioStato.eseguiCambioStato(this.currentPostId, this.currentAction, nomeCliente);
        }
    };
    
    /**
     * Modale di notifica generica (sostituisce alert)
     */
    const NotifyModal = {
        
        /**
         * Mostra notifica
         */
        show: function(message, title = 'Notifica') {
            $('#notify-title').text(title);
            $('#notify-message').text(message);
            $('#notify-modal').addClass('active');
            
            // Focus sul pulsante OK
            setTimeout(function() {
                $('#notify-btn-ok').focus();
            }, 100);
        },
        
        /**
         * Chiudi notifica
         */
        close: function() {
            $('#notify-modal').removeClass('active');
        }
    };
    
    /**
     * Modale di conferma generica (sostituisce confirm)
     */
    const ConfirmModal = {
        
        confirmCallback: null,
        
        /**
         * Mostra conferma
         */
        show: function(message, callback, title = 'Conferma') {
            this.confirmCallback = callback;
            $('#confirm-title').text(title);
            $('#confirm-message').text(message);
            $('#confirm-modal').addClass('active');
            
            // Focus sul pulsante conferma
            setTimeout(function() {
                $('#confirm-btn-ok').focus();
            }, 100);
        },
        
        /**
         * Chiudi conferma
         */
        close: function() {
            $('#confirm-modal').removeClass('active');
            this.confirmCallback = null;
        },
        
        /**
         * Conferma azione
         */
        confirm: function() {
            if (this.confirmCallback && typeof this.confirmCallback === 'function') {
                this.confirmCallback();
            }
            this.close();
        }
    };
    
    // Click sui pulsanti OK dei modali
    $(document).on('click', '#notify-btn-ok', function() {
        NotifyModal.close();
    });
    
    $(document).on('click', '#confirm-btn-ok', function() {
        ConfirmModal.confirm();
    });
    
    // Chiudi modale cliccando sull'overlay
    $(document).on('click', '.shaktiman-modal-overlay', function(e) {
        if (e.target === this) {
            ShaktimanModal.close();
            UbicazioneModal.close();
            ContrattoModal.close();
            NotifyModal.close();
            ConfirmModal.close();
        }
    });
    
    // Chiudi/Annulla modale con pulsanti
    $(document).on('click', '.modal-close, .modal-btn-cancel', function(e) {
        e.preventDefault();
        const modalType = $(this).data('modal');
        
        switch(modalType) {
            case 'shaktiman':
                ShaktimanModal.close();
                break;
            case 'ubicazione':
                UbicazioneModal.close();
                break;
            case 'contratto':
                ContrattoModal.close();
                break;
            case 'notify':
                NotifyModal.close();
                break;
            case 'confirm':
                ConfirmModal.close();
                break;
        }
    });
    
    // Chiudi modale con ESC
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            if ($('#shaktiman-modal').hasClass('active')) {
                ShaktimanModal.close();
            }
            if ($('#ubicazione-modal').hasClass('active')) {
                UbicazioneModal.close();
            }
            if ($('#contratto-modal').hasClass('active')) {
                ContrattoModal.close();
            }
            if ($('#notify-modal').hasClass('active')) {
                NotifyModal.close();
            }
            if ($('#confirm-modal').hasClass('active')) {
                ConfirmModal.close();
            }
        }
    });
    
    /**
     * Gestione modale Ubicazione
     */
    const UbicazioneModal = {
        
        currentPostId: null,
        
        /**
         * Apri modale
         */
        open: function(postId) {
            this.currentPostId = postId;
            $('#modal-ubicazione').val('');
            $('#ubicazione-modal').addClass('active');
            
            // Carica i dati esistenti del mezzo
            $.ajax({
                url: shaktimanB2B.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_mezzo_data',
                    nonce: shaktimanB2B.nonce,
                    post_id: postId
                },
                success: function(response) {
                    if (response.success && response.data.ubicazione_id) {
                        $('#modal-ubicazione').val(response.data.ubicazione_id);
                    }
                }
            });
            
            // Focus sul select
            setTimeout(function() {
                $('#modal-ubicazione').focus();
            }, 100);
            
            // Bind confirm button
            const self = this;
            $('#ubicazione-confirm-btn').off('click').on('click', function() {
                self.confirm();
            });
        },
        
        /**
         * Chiudi modale
         */
        close: function() {
            $('#ubicazione-modal').removeClass('active');
            this.currentPostId = null;
        },
        
        /**
         * Conferma cambio ubicazione
         */
        confirm: function() {
            const ubicazioneId = $('#modal-ubicazione').val();
            
            if (!ubicazioneId) {
                NotifyModal.show('Seleziona un\'ubicazione', 'Attenzione');
                return;
            }
            
            const $confirmBtn = $('#ubicazione-confirm-btn');
            $confirmBtn.prop('disabled', true).text('Elaborazione...');
            
            const data = {
                action: 'cambia_ubicazione_mezzo',
                nonce: shaktimanB2B.nonce,
                post_id: this.currentPostId,
                ubicazione_id: ubicazioneId
            };
            
            $.ajax({
                url: shaktimanB2B.ajaxUrl,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        UbicazioneModal.close();
                        NotifyModal.show(response.data.message, 'Successo');
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        NotifyModal.show(response.data.message || 'Errore durante il cambio di ubicazione', 'Errore');
                        $confirmBtn.prop('disabled', false).text('Conferma');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Errore AJAX:', error);
                    NotifyModal.show('Si è verificato un errore. Riprova.', 'Errore');
                    $confirmBtn.prop('disabled', false).text('Conferma');
                }
            });
        }
    };
    
    /**
     * Gestione modale Contratto
     */
    const ContrattoModal = {
        
        currentPostId: null,
        
        /**
         * Apri modale
         */
        open: function(postId) {
            this.currentPostId = postId;
            $('#modal-numero-contratto').val('');
            $('#modal-ragione-sociale').val('');
            $('#contratto-modal').addClass('active');
            
            // Carica i dati esistenti del mezzo
            $.ajax({
                url: shaktimanB2B.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_mezzo_data',
                    nonce: shaktimanB2B.nonce,
                    post_id: postId
                },
                success: function(response) {
                    if (response.success) {
                        if (response.data.numero_contratto) {
                            $('#modal-numero-contratto').val(response.data.numero_contratto);
                        }
                        if (response.data.ragione_sociale) {
                            $('#modal-ragione-sociale').val(response.data.ragione_sociale);
                        }
                    }
                }
            });
            
            // Focus sul campo input
            setTimeout(function() {
                $('#modal-numero-contratto').focus();
            }, 100);
            
            // Bind confirm button
            const self = this;
            $('#contratto-confirm-btn').off('click').on('click', function() {
                self.confirm();
            });
        },
        
        /**
         * Chiudi modale
         */
        close: function() {
            $('#contratto-modal').removeClass('active');
            this.currentPostId = null;
        },
        
        /**
         * Conferma e genera contratto
         */
        confirm: function() {
            const numeroContratto = $('#modal-numero-contratto').val().trim();
            const ragioneSociale = $('#modal-ragione-sociale').val().trim();
            
            if (!numeroContratto) {
                NotifyModal.show('Il numero di contratto è obbligatorio', 'Attenzione');
                return;
            }
            
            const $confirmBtn = $('#contratto-confirm-btn');
            $confirmBtn.prop('disabled', true).text('Generazione...');
            
            const data = {
                action: 'genera_contratto_mezzo',
                nonce: shaktimanB2B.nonce,
                post_id: this.currentPostId,
                numero_contratto: numeroContratto,
                ragione_sociale: ragioneSociale
            };
            
            $.ajax({
                url: shaktimanB2B.ajaxUrl,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        ContrattoModal.close();
                        NotifyModal.show(response.data.message, 'Successo');
                        
                        // Se c'è un PDF, aprilo in nuova finestra
                        if (response.data.pdf_url) {
                            window.open(response.data.pdf_url, '_blank');
                        }
                        
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        NotifyModal.show(response.data.message || 'Errore durante la generazione del contratto', 'Errore');
                        $confirmBtn.prop('disabled', false).text('Salva');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Errore AJAX:', error);
                    NotifyModal.show('Si è verificato un errore. Riprova.', 'Errore');
                    $confirmBtn.prop('disabled', false).text('Salva');
                }
            });
        }
    };
    
    /**
     * Click handler per pulsante cambia ubicazione
     */
    $(document).on('click', '.btn-cambia-ubicazione', function(e) {
        e.preventDefault();
        const postId = $(this).data('post-id');
        if (postId) {
            UbicazioneModal.open(postId);
        }
    });
    
    /**
     * Click handler per pulsante contratto
     */
    $(document).on('click', '.btn-contratto', function(e) {
        e.preventDefault();
        const postId = $(this).data('post-id');
        if (postId) {
            ContrattoModal.open(postId);
        }
    });
    
    /**
     * Inizializza quando il documento è pronto
     */
    $(document).ready(function() {
        // Inizializza filtri se la griglia esiste
        if ($('#mezzi-grid, .mezzi-grid').length > 0) {
            MezziFiltri.init();
        }
        
        // Inizializza cambio stato
        CambioStato.init();
        
        // Smooth scroll per i link interni
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 100
                }, 600);
            }
        });
        
        // Tooltip informativi (se esistono)
        if ($.fn.tooltip) {
            $('[data-tooltip]').tooltip();
        }
    });
    
})(jQuery);
