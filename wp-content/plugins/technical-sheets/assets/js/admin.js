/**
 * Technical Sheets Admin JavaScript
 */

jQuery(document).ready(function($) {
    
    // Debug: Check if jQuery UI Sortable is available
    if (typeof $.fn.sortable === 'undefined') {
        console.error('jQuery UI Sortable is not loaded! Using manual fallback.');
        initManualDrag();
        return;
    }
    
    console.log('Technical Sheets Admin JS loaded');
    
    // Initialize sortable functionality - make it global
    window.tsInitSortable = function initSortable() {
        console.log('Initializing sortable...');
        
        // Basic Info sortable
        var basicInfoContainer = $('#ts-basic-info-rows');
        if (basicInfoContainer.length > 0) {
            console.log('Initializing basic info sortable');
            basicInfoContainer.sortable({
                handle: '.ts-drag-handle',
                placeholder: 'ts-sortable-placeholder',
                axis: 'y',
                tolerance: 'pointer',
                cursor: 'move',
                opacity: 0.8,
                disabled: false,
                start: function(event, ui) {
                    console.log('Basic info drag started');
                },
                update: function(event, ui) {
                    console.log('Basic info order updated');
                    updateBasicInfoIndexes();
                }
            });
        }
        
        // Accordion sections sortable
        var accordionContainer = $('#ts-accordion-sections');
        if (accordionContainer.length > 0) {
            console.log('Initializing accordion sortable');
            accordionContainer.sortable({
                handle: '.ts-drag-handle',
                placeholder: 'ts-sortable-placeholder',
                axis: 'y',
                tolerance: 'pointer',
                cursor: 'move',
                opacity: 0.8,
                disabled: false,
                start: function(event, ui) {
                    console.log('Accordion drag started');
                },
                update: function(event, ui) {
                    console.log('Accordion order updated');
                    updateAccordionIndexes();
                }
            });
        }
        
        // Gallery images sortable
        var galleryContainer = $('#ts-gallery-images');
        if (galleryContainer.length > 0) {
            console.log('Initializing gallery sortable');
            galleryContainer.sortable({
                handle: '.ts-drag-handle',
                placeholder: 'ts-sortable-placeholder',
                tolerance: 'pointer',
                cursor: 'move',
                opacity: 0.8,
                disabled: false,
                start: function(event, ui) {
                    console.log('Gallery drag started');
                },
                update: function(event, ui) {
                    console.log('Gallery order updated');
                    updateGalleryIndexes();
                }
            });
        }
    }
    
    // Update basic info indexes after sorting
    function updateBasicInfoIndexes() {
        $('#ts-basic-info-rows .ts-basic-info-row').each(function(index) {
            $(this).attr('data-index', index);
            $(this).find('input[name*="[label]"]').attr('name', 'ts_basic_info[' + index + '][label]');
            $(this).find('input[name*="[value]"]').attr('name', 'ts_basic_info[' + index + '][value]');
        });
    }
    
    // Update accordion indexes after sorting
    function updateAccordionIndexes() {
        $('#ts-accordion-sections .ts-accordion-section').each(function(index) {
            $(this).attr('data-index', index);
            $(this).find('.ts-section-number').text('Section ' + (index + 1));
            $(this).find('input[name*="[title]"]').attr('name', 'ts_accordion_sections[' + index + '][title]');
            $(this).find('input[name*="[image]"]').attr('name', 'ts_accordion_sections[' + index + '][image]');
            $(this).find('textarea[name*="[content]"]').attr('name', 'ts_accordion_sections[' + index + '][content]');
            
            // Update data-section attributes
            $(this).find('.ts-accordion-toggle').attr('data-section', index);
            $(this).find('.ts-accordion-section-content').attr('data-section', index);
        });
    }
    
    // Update gallery indexes after sorting
    function updateGalleryIndexes() {
        $('#ts-gallery-images .ts-gallery-item').each(function(index) {
            $(this).attr('data-index', index);
        });
    }
    
    // Initialize sortable on page load with delay
    setTimeout(function() {
        window.tsInitSortable();
        initArrowControls();
        initAccordionCollapse();
    }, 500);
    
    // Re-initialize sortable when content is dynamically added
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).hasClass('ts-sortable-item')) {
            setTimeout(function() {
                window.tsInitSortable();
            }, 100);
        }
    });
    
    // Gallery management
    var mediaUploader;
    var galleryContainer = $('#ts-gallery-images');
    var galleryIndex = galleryContainer.children().length;
    
    // Add gallery image
    $('#add-gallery-image').on('click', function(e) {
        e.preventDefault();
        
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Select Images',
            button: {
                text: 'Add Images'
            },
            multiple: true
        });
        
        mediaUploader.on('select', function() {
            var attachments = mediaUploader.state().get('selection').toJSON();
            
            $.each(attachments, function(index, attachment) {
                var imageHtml = '<div class="ts-gallery-item ts-sortable-item" data-index="' + galleryIndex + '">';
                imageHtml += '<div class="ts-controls">';
                imageHtml += '<div class="ts-arrow-controls">';
                imageHtml += '<div class="ts-drag-handle" title="Drag to reorder">';
                imageHtml += '<span class="dashicons dashicons-menu"></span>';
                imageHtml += '</div>';
                imageHtml += '<button type="button" class="ts-move-up" title="Move up">';
                imageHtml += '<span class="dashicons dashicons-arrow-up-alt2"></span>';
                imageHtml += '</button>';
                imageHtml += '<button type="button" class="ts-move-down" title="Move down">';
                imageHtml += '<span class="dashicons dashicons-arrow-down-alt2"></span>';
                imageHtml += '</button>';
                imageHtml += '</div>';
                imageHtml += '</div>';
                imageHtml += '<img src="' + attachment.sizes.full.url + '" alt="">';
                imageHtml += '<input type="hidden" name="ts_gallery_images[]" value="' + attachment.id + '">';
                imageHtml += '<button type="button" class="remove-gallery-image">×</button>';
                imageHtml += '</div>';
                
                galleryContainer.append(imageHtml);
                galleryIndex++;
            });
            
            // Refresh sortable
            galleryContainer.sortable('refresh');
            updateArrowStates();
        });
        
        mediaUploader.open();
    });
    
    // Remove gallery image
    $(document).on('click', '.remove-gallery-image', function(e) {
        e.preventDefault();
        $(this).parent().remove();
    });
    
    // Basic info management
    var basicInfoContainer = $('#ts-basic-info-rows');
    var basicInfoIndex = basicInfoContainer.children().length;
    
    // Add basic info row
    $('#add-basic-info-row').on('click', function(e) {
        e.preventDefault();
        
        var rowHtml = '<div class="ts-basic-info-row ts-sortable-item" data-index="' + basicInfoIndex + '">';
        rowHtml += '<div class="ts-controls">';
        rowHtml += '<div class="ts-arrow-controls">';
        rowHtml += '<div class="ts-drag-handle" title="Drag to reorder">';
        rowHtml += '<span class="dashicons dashicons-menu"></span>';
        rowHtml += '</div>';
        rowHtml += '<button type="button" class="ts-move-up" title="Move up">';
        rowHtml += '<span class="dashicons dashicons-arrow-up-alt2"></span>';
        rowHtml += '</button>';
        rowHtml += '<button type="button" class="ts-move-down" title="Move down">';
        rowHtml += '<span class="dashicons dashicons-arrow-down-alt2"></span>';
        rowHtml += '</button>';
        rowHtml += '</div>';
        rowHtml += '</div>';
        rowHtml += '<div class="ts-field-inputs">';
        rowHtml += '<input type="text" name="ts_basic_info[' + basicInfoIndex + '][label]" placeholder="Label">';
        rowHtml += '<input type="text" name="ts_basic_info[' + basicInfoIndex + '][value]" placeholder="Value">';
        rowHtml += '</div>';
        rowHtml += '<button type="button" class="remove-basic-info-row">×</button>';
        rowHtml += '</div>';
        
        basicInfoContainer.append(rowHtml);
        basicInfoIndex++;
        
        // Refresh sortable
        basicInfoContainer.sortable('refresh');
        updateArrowStates();
    });
    
    // Remove basic info row
    $(document).on('click', '.remove-basic-info-row', function(e) {
        e.preventDefault();
        $(this).parent().remove();
        updateBasicInfoIndexes();
        updateArrowStates();
    });
    
    // Accordion management
    var accordionContainer = $('#ts-accordion-sections');
    var accordionIndex = accordionContainer.children().length;
    
    // Add accordion section
    $('#add-accordion-section').on('click', function(e) {
        e.preventDefault();
        
        var sectionHtml = '<div class="ts-accordion-section ts-sortable-item" data-index="' + accordionIndex + '">';
        sectionHtml += '<div class="ts-accordion-section-header">';
        sectionHtml += '<div class="ts-controls">';
        sectionHtml += '<div class="ts-arrow-controls">';
        sectionHtml += '<div class="ts-drag-handle" title="Drag to reorder">';
        sectionHtml += '<span class="dashicons dashicons-menu"></span>';
        sectionHtml += '</div>';
        sectionHtml += '<button type="button" class="ts-move-up" title="Move up">';
        sectionHtml += '<span class="dashicons dashicons-arrow-up-alt2"></span>';
        sectionHtml += '</button>';
        sectionHtml += '<button type="button" class="ts-move-down" title="Move down">';
        sectionHtml += '<span class="dashicons dashicons-arrow-down-alt2"></span>';
        sectionHtml += '</button>';
        sectionHtml += '</div>';
        sectionHtml += '</div>';
        sectionHtml += '<h4 class="ts-accordion-toggle" data-section="' + accordionIndex + '">';
        sectionHtml += '<span class="ts-section-number">Section ' + (accordionIndex + 1) + '</span>';
        sectionHtml += '<span class="ts-section-title"></span>';
        sectionHtml += '<span class="ts-collapse-icon dashicons dashicons-arrow-down-alt2"></span>';
        sectionHtml += '</h4>';
        sectionHtml += '<button type="button" class="remove-accordion-section button">Remove Section</button>';
        sectionHtml += '</div>';
        sectionHtml += '<div class="ts-accordion-section-content" data-section="' + accordionIndex + '">';
        sectionHtml += '<p>';
        sectionHtml += '<label>Title:</label>';
        sectionHtml += '<input type="text" name="ts_accordion_sections[' + accordionIndex + '][title]" class="ts-section-title-input">';
        sectionHtml += '</p>';
        sectionHtml += '<p>';
        sectionHtml += '<label>Image:</label>';
        sectionHtml += '<input type="hidden" name="ts_accordion_sections[' + accordionIndex + '][image]">';
        sectionHtml += '<button type="button" class="select-accordion-image button">Select Image</button>';
        sectionHtml += '</p>';
        sectionHtml += '<p>';
        sectionHtml += '<label>Content:</label>';
        sectionHtml += '<textarea name="ts_accordion_sections[' + accordionIndex + '][content]" rows="5"></textarea>';
        sectionHtml += '</p>';
        sectionHtml += '</div>';
        sectionHtml += '</div>';
        
        accordionContainer.append(sectionHtml);
        accordionIndex++;
        
        // Refresh sortable
        accordionContainer.sortable('refresh');
        updateArrowStates();
    });
    
    // Remove accordion section
    $(document).on('click', '.remove-accordion-section', function(e) {
        e.preventDefault();
        $(this).closest('.ts-accordion-section').remove();
        
        // Update accordion indexes
        updateAccordionIndexes();
        updateArrowStates();
    });
    
    // Select accordion image
    $(document).on('click', '.select-accordion-image', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var hiddenInput = button.siblings('input[type="hidden"]');
        var imagePreview = button.siblings('.accordion-image-preview');
        var removeButton = button.siblings('.remove-accordion-image');
        
        var imageUploader = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use Image'
            },
            multiple: false
        });
        
        imageUploader.on('select', function() {
            var attachment = imageUploader.state().get('selection').first().toJSON();
            
            hiddenInput.val(attachment.id);
            
            if (imagePreview.length) {
                imagePreview.attr('src', attachment.sizes.full.url);
            } else {
                var previewHtml = '<img src="' + attachment.sizes.full.url + '" class="accordion-image-preview" alt="">';
                previewHtml += '<button type="button" class="remove-accordion-image button">Remove Image</button>';
                button.after(previewHtml);
            }
        });
        
        imageUploader.open();
    });
    
    // Remove accordion image
    $(document).on('click', '.remove-accordion-image', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var hiddenInput = button.siblings('input[type="hidden"]');
        var imagePreview = button.siblings('.accordion-image-preview');
        
        hiddenInput.val('');
        imagePreview.remove();
        button.remove();
    });
    
    // Make gallery sortable
    if (galleryContainer.length) {
        galleryContainer.sortable({
            items: '.ts-gallery-item',
            cursor: 'move',
            tolerance: 'pointer',
            handle: '.ts-basic-info-row',
            placeholder: 'ts-basic-info-placeholder'
        });
    }
    
    // Make basic info sortable
    if (basicInfoContainer.length) {
        basicInfoContainer.sortable({
            items: '.ts-basic-info-row',
            cursor: 'move',
            tolerance: 'pointer',
            handle: '.ts-basic-info-row',
            placeholder: 'ts-basic-info-placeholder'
        });
    }
    
    // Make accordion sortable
    if (accordionContainer.length) {
        accordionContainer.sortable({
            items: '.ts-accordion-section',
            cursor: 'move',
            tolerance: 'pointer',
            handle: 'h4',
            placeholder: 'ts-accordion-placeholder',
            start: function(e, ui) {
                ui.placeholder.height(ui.item.height());
            },
            update: function(e, ui) {
                // Update section numbers after sorting
                $('#ts-accordion-sections .ts-accordion-section').each(function(index) {
                    $(this).find('h4').text('Section ' + (index + 1));
                });
            }
        });
    }
    
    // Arrow controls for moving items up/down
    function initArrowControls() {
        console.log('Initializing arrow controls...');
        
        // Move up functionality
        $(document).on('click', '.ts-move-up', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var item = $(this).closest('.ts-sortable-item');
            var prev = item.prev('.ts-sortable-item');
            
            if (prev.length) {
                item.insertBefore(prev);
                
                // Update indexes based on container type
                var container = item.parent();
                if (container.is('#ts-basic-info-rows')) {
                    updateBasicInfoIndexes();
                } else if (container.is('#ts-accordion-sections')) {
                    updateAccordionIndexes();
                } else if (container.is('#ts-gallery-images')) {
                    updateGalleryIndexes();
                }
                
                updateArrowStates();
            }
        });
        
        // Move down functionality
        $(document).on('click', '.ts-move-down', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var item = $(this).closest('.ts-sortable-item');
            var next = item.next('.ts-sortable-item');
            
            if (next.length) {
                item.insertAfter(next);
                
                // Update indexes based on container type
                var container = item.parent();
                if (container.is('#ts-basic-info-rows')) {
                    updateBasicInfoIndexes();
                } else if (container.is('#ts-accordion-sections')) {
                    updateAccordionIndexes();
                } else if (container.is('#ts-gallery-images')) {
                    updateGalleryIndexes();
                }
                
                updateArrowStates();
            }
        });
        
        // Initialize arrow states
        updateArrowStates();
    }
    
    // Update arrow button states (disable first up and last down)
    function updateArrowStates() {
        $('.ts-sortable-container').each(function() {
            var items = $(this).find('.ts-sortable-item');
            
            items.each(function(index) {
                var item = $(this);
                var upBtn = item.find('.ts-move-up');
                var downBtn = item.find('.ts-move-down');
                
                // Disable up button for first item
                if (index === 0) {
                    upBtn.prop('disabled', true);
                } else {
                    upBtn.prop('disabled', false);
                }
                
                // Disable down button for last item
                if (index === items.length - 1) {
                    downBtn.prop('disabled', true);
                } else {
                    downBtn.prop('disabled', false);
                }
            });
        });
    }
    
    // Accordion collapsible functionality
    function initAccordionCollapse() {
        console.log('Initializing accordion collapse...');
        
        // Inizializza lo stato delle sezioni: se non hanno la classe collapsed, aggiungila
        $('.ts-accordion-toggle').each(function() {
            var toggle = $(this);
            var content = toggle.closest('.ts-accordion-section').find('.ts-accordion-section-content');
            
            // Se la sezione non ha la classe collapsed, aggiungila per inizializzarla come collassata
            if (!toggle.hasClass('collapsed')) {
                toggle.addClass('collapsed');
                content.addClass('collapsed');
            }
        });
        
        // Toggle accordion sections
        $(document).on('click', '.ts-accordion-toggle', function(e) {
            e.preventDefault();
            
            var toggle = $(this);
            var content = toggle.closest('.ts-accordion-section').find('.ts-accordion-section-content');
            
            if (toggle.hasClass('collapsed')) {
                // Expand
                toggle.removeClass('collapsed');
                content.removeClass('collapsed');
            } else {
                // Collapse
                toggle.addClass('collapsed');
                content.addClass('collapsed');
            }
        });
        
        // Update section titles in real-time
        $(document).on('input', '.ts-section-title-input', function() {
            var input = $(this);
            var value = input.val().trim();
            var section = input.closest('.ts-accordion-section');
            var titleSpan = section.find('.ts-section-title');
            
            if (value) {
                titleSpan.text(' - ' + value);
            } else {
                titleSpan.text('');
            }
        });
    }
    
    // Update section numbers in real-time
    $(document).on('input', '.ts-section-number-input', function() {
        var input = $(this);
        var value = input.val().trim();
        var section = input.closest('.ts-accordion-section');
        var numberSpan = section.find('.ts-section-number');
        
        if (value) {
            numberSpan.text(value);
        } else {
            numberSpan.text('');
        }
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        var hasErrors = false;
        var errorMessages = [];
        
        // Check if at least one basic info row is complete
        var basicInfoRows = $('.ts-basic-info-row');
        var hasCompleteBasicInfo = false;
        
        basicInfoRows.each(function() {
            var label = $(this).find('input[name*="[label]"]').val();
            var value = $(this).find('input[name*="[value]"]').val();
            
            if (label && value) {
                hasCompleteBasicInfo = true;
            }
        });
        
        // Check if at least one accordion section is complete
        var accordionSections = $('.ts-accordion-section');
        var hasCompleteAccordion = false;
        
        accordionSections.each(function() {
            var title = $(this).find('input[name*="[title]"]').val();
            var content = $(this).find('textarea[name*="[content]"]').val();
            
            if (title && content) {
                hasCompleteAccordion = true;
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            alert('Please fix the following errors:\n' + errorMessages.join('\n'));
        }
    });
    
    // Auto-save functionality
    var autoSaveTimeout;
    
    $('input, textarea').on('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            // Auto-save logic here if needed
        }, 5000);
    });
    
    // Show/hide sections based on content
    function toggleEmptySections() {
        $('.ts-section').each(function() {
            var section = $(this);
            var hasContent = false;
            
            // Check if section has any content
            if (section.find('input[type="hidden"]').length > 0) {
                section.find('input[type="hidden"]').each(function() {
                    if ($(this).val()) {
                        hasContent = true;
                        return false;
                    }
                });
            }
            
            if (section.find('input[type="text"], textarea').length > 0) {
                section.find('input[type="text"], textarea').each(function() {
                    if ($(this).val()) {
                        hasContent = true;
                        return false;
                    }
                });
            }
            
            if (hasContent) {
                section.show();
            } else {
                section.hide();
            }
        });
    }
    
    // Initial check
    toggleEmptySections();
    
    // Check on input change
    $('input, textarea').on('input', function() {
        setTimeout(toggleEmptySections, 100);
    });
    
    // PDF attachment management
    var pdfMediaUploader;
    
    // Toggle between upload and link sections
    $('input[name="ts_pdf_type"]').on('change', function() {
        var selectedType = $(this).val();
        
        if (selectedType === 'upload') {
            $('#ts-pdf-upload-section').show();
            $('#ts-pdf-link-section').hide();
        } else if (selectedType === 'link') {
            $('#ts-pdf-upload-section').hide();
            $('#ts-pdf-link-section').show();
        }
    });
    
    $('#select-pdf-attachment').on('click', function(e) {
        e.preventDefault();
        
        if (pdfMediaUploader) {
            pdfMediaUploader.open();
            return;
        }
        
        pdfMediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Select PDF File',
            button: {
                text: 'Select PDF'
            },
            multiple: false,
            library: {
                type: 'application/pdf'
            }
        });
        
        pdfMediaUploader.on('select', function() {
            var attachment = pdfMediaUploader.state().get('selection').first().toJSON();
            
            if (attachment.mime === 'application/pdf') {
                $('#ts_pdf_attachment').val(attachment.id);
                
                var previewHtml = '<p><strong>Current PDF:</strong></p>';
                previewHtml += '<p><a href="' + attachment.url + '" target="_blank">' + attachment.filename + '</a></p>';
                
                $('#ts-pdf-preview').html(previewHtml);
                
                // Show remove button
                if ($('#remove-pdf-attachment').length === 0) {
                    $('#select-pdf-attachment').after('<button type="button" id="remove-pdf-attachment" class="button">Remove PDF</button>');
                }
            } else {
                alert('Please select a PDF file.');
            }
        });
        
        pdfMediaUploader.open();
    });
    
    // Remove PDF attachment
    $(document).on('click', '#remove-pdf-attachment', function(e) {
        e.preventDefault();
        
        $('#ts_pdf_attachment').val('');
        $('#ts-pdf-preview').html('<p>No PDF selected</p>');
        $(this).remove();
    });
    
    // Fallback manual drag implementation if jQuery UI is not available
    function initManualDrag() {
        console.log('Using manual drag fallback');
        
        $('.ts-drag-handle').each(function() {
            var handle = $(this);
            var item = handle.closest('.ts-sortable-item');
            var container = item.parent();
            var isDragging = false;
            var startY = 0;
            var startTop = 0;
            
            handle.on('mousedown', function(e) {
                e.preventDefault();
                isDragging = true;
                startY = e.clientY;
                startTop = item.position().top;
                
                item.addClass('dragging');
                $('body').addClass('dragging-active');
                
                $(document).on('mousemove.drag', function(e) {
                    if (!isDragging) return;
                    
                    var deltaY = e.clientY - startY;
                    var newTop = startTop + deltaY;
                    
                    // Find the position to insert
                    var siblings = container.children('.ts-sortable-item').not(item);
                    var insertBefore = null;
                    
                    siblings.each(function() {
                        var sibling = $(this);
                        var siblingTop = sibling.position().top;
                        var siblingHeight = sibling.outerHeight();
                        
                        if (newTop < siblingTop + siblingHeight / 2) {
                            insertBefore = sibling;
                            return false;
                        }
                    });
                    
                    if (insertBefore) {
                        item.insertBefore(insertBefore);
                    } else {
                        container.append(item);
                    }
                });
                
                $(document).on('mouseup.drag', function() {
                    if (!isDragging) return;
                    
                    isDragging = false;
                    item.removeClass('dragging');
                    $('body').removeClass('dragging-active');
                    $(document).off('.drag');
                    
                    // Update indexes
                    if (container.is('#ts-basic-info-rows')) {
                        updateBasicInfoIndexes();
                    } else if (container.is('#ts-accordion-sections')) {
                        updateAccordionIndexes();
                    } else if (container.is('#ts-gallery-images')) {
                        updateGalleryIndexes();
                    }
                });
            });
        });
    }

});
