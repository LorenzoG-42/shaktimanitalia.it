/**
 * JavaScript Admin per Shaktiman B2B
 */

(function($) {
    'use strict';
    
    /**
     * Gestione Meta Boxes
     */
    const MetaBoxes = {
        
        /**
         * Inizializza
         */
        init: function() {
            this.handleDisponibilitaChange();
            this.handleFieldValidation();
        },
        
        /**
         * Gestisce il cambio di disponibilità
         */
        handleDisponibilitaChange: function() {
            // Monitora i cambiamenti nella tassonomia disponibilità
            $(document).on('change', 'input[name="tax_input[disponibilita][]"]', function() {
                const selectedValue = $('input[name="tax_input[disponibilita][]"]:checked').val();
                MetaBoxes.toggleClienteField(selectedValue);
            });
            
            // Check iniziale
            const initialValue = $('input[name="tax_input[disponibilita][]"]:checked').val();
            if (initialValue) {
                MetaBoxes.toggleClienteField(initialValue);
            }
        },
        
        /**
         * Abilita/disabilita campo cliente in base allo stato
         */
        toggleClienteField: function(stato) {
            const nomeClienteField = $('#nome_cliente');
            
            if (stato === 'disponibile') {
                nomeClienteField.prop('disabled', true).val('');
            } else {
                nomeClienteField.prop('disabled', false);
            }
        },
        
        /**
         * Validazione campi
         */
        handleFieldValidation: function() {
            // Validazione al submit del form
            $('#post').on('submit', function(e) {
                const stato = $('input[name="tax_input[disponibilita][]"]:checked').val();
                const nomeCliente = $('#nome_cliente').val();
                
                // Se è riservato o venduto, il nome cliente è obbligatorio
                if ((stato === 'riservato' || stato === 'venduto') && !nomeCliente) {
                    e.preventDefault();
                    alert('Il nome del cliente è obbligatorio quando il mezzo è riservato o venduto.');
                    $('#nome_cliente').focus();
                    return false;
                }
                
                // Se è venduto, verifica i dati del contratto (solo per utenti autorizzati)
                if (stato === 'venduto' && $('#numero_contratto').length > 0) {
                    const numeroContratto = $('#numero_contratto').val();
                    if (!numeroContratto) {
                        const confirmProceed = confirm('Non hai inserito il numero di contratto. Vuoi procedere comunque?');
                        if (!confirmProceed) {
                            e.preventDefault();
                            $('#numero_contratto').focus();
                            return false;
                        }
                    }
                }
            });
        }
    };
    
    /**
     * Utility per gestire le colonne admin
     */
    const AdminColumns = {
        
        /**
         * Inizializza
         */
        init: function() {
            this.addStateIndicators();
        },
        
        /**
         * Aggiunge indicatori visivi agli stati
         */
        addStateIndicators: function() {
            // Trova tutte le celle con lo stato
            $('.column-disponibilita').each(function() {
                const $cell = $(this);
                const text = $cell.text().trim().toLowerCase();
                
                // Aggiungi classe per lo styling
                if (text.includes('disponibile')) {
                    $cell.addClass('stato-disponibile');
                } else if (text.includes('riservato')) {
                    $cell.addClass('stato-riservato');
                } else if (text.includes('venduto')) {
                    $cell.addClass('stato-venduto');
                }
            });
        }
    };
    
    /**
     * Inizializza quando il documento è pronto
     */
    $(document).ready(function() {
        MetaBoxes.init();
        AdminColumns.init();
        
        // Conferma eliminazione
        $('.submitdelete').on('click', function(e) {
            if (!confirm('Sei sicuro di voler eliminare questo mezzo agricolo?')) {
                e.preventDefault();
                return false;
            }
        });
    });
    
})(jQuery);
