/**
 * Technical Sheets Frontend JavaScript
 */

jQuery(document).ready(function($) {
    
    // Initialize Select2 for filter selects
    if (typeof $.fn.select2 !== 'undefined') {
        $('.ts-filter-select').select2({
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
    
    // Accordion functionality
    function initAccordion() {
        // NON fare nulla automaticamente - lascia tutto come sta nel markup
        // Solo setup degli event handlers per il click
        
        // Remove any existing event handlers to prevent duplicates
        $('.ts-accordion-header').off('click.accordion');
        
        // Handle click events with delegation for dynamic content
        $(document).off('click.accordion', '.ts-accordion-header').on('click.accordion', '.ts-accordion-header', function(e) {
            e.preventDefault();
            
            var header = $(this);
            var targetId = header.data('target');
            var content = $('#' + targetId);
            var icon = header.find('.ts-accordion-icon');
            
            // Check if target content exists
            if (!content.length) {
                console.warn('Accordion target not found:', targetId);
                return;
            }
            
            // Check if this accordion is currently open
            var isOpen = header.hasClass('active');
            
            // Close all accordion items in the same accordion group first
            var accordionContainer = header.closest('.ts-accordion');
            accordionContainer.find('.ts-accordion-header').removeClass('active');
            accordionContainer.find('.ts-accordion-content').removeClass('active').addClass('collapsed').slideUp(300);
            accordionContainer.find('.ts-accordion-icon').text('>').css('transform', 'rotate(0deg)');
            
            // If this accordion wasn't open, open it
            if (!isOpen) {
                header.addClass('active');
                content.removeClass('collapsed').addClass('active').slideDown(300);
                icon.text('>').css('transform', 'rotate(90deg)');
            }
        });
    }
    
    // Initialize accordion - solo una volta
    initAccordion();
    
    // Re-initialize accordion when new content is loaded (for shortcodes, AJAX, etc.)
    $(document).on('ts:content-loaded', function() {
        initAccordion();
    });
    
    // Gallery lightbox functionality - WordPress native
    function initWordPressLightbox() {
        // Check if WordPress lightbox is available
        if (typeof wp !== 'undefined' && wp.blocks && wp.blocks.registerBlockType) {
            // WordPress 6.4+ native lightbox support
            $('.ts-slide-link, .ts-featured-image').on('click', function(e) {
                // Let WordPress handle the lightbox
                // The data-type="image" attribute triggers the native lightbox
            });
        } else {
            // Fallback for basic lightbox functionality
            $('.ts-slide-link[data-type="image"], .ts-featured-image').on('click', function(e) {
                e.preventDefault();
                
                console.log('Lightbox clicked on:', this);
                
                var imageUrl, alt;
                
                if ($(this).is('a')) {
                    // È un link - prendi l'href
                    imageUrl = $(this).attr('href');
                    alt = $(this).find('img').attr('alt') || '';
                    console.log('Link detected - href:', imageUrl);
                } else if ($(this).hasClass('ts-featured-image')) {
                    // È un contenitore ts-featured-image - prendi il src dell'img figlio
                    var childImg = $(this).find('img').first();
                    if (childImg.length) {
                        imageUrl = childImg.attr('src');
                        alt = childImg.attr('alt') || '';
                        console.log('Featured image container detected - child img src:', imageUrl);
                    }
                } else {
                    // È un'immagine - prendi il src
                    imageUrl = $(this).attr('src');
                    alt = $(this).attr('alt') || '';
                    console.log('Image detected - src:', imageUrl);
                }
                
                if (!imageUrl) {
                    console.error('No image URL found!');
                    return;
                }
                
                console.log('Opening lightbox with image:', imageUrl);
                
                // Raccogli tutte le immagini disponibili mantenendo l'ordine DOM
                var allImages = [];
                var currentIndex = 0;
                var foundCurrentImage = false;
                
                // Prima raccogli TUTTE le immagini nell'ordine in cui appaiono nel DOM
                $('.ts-featured-image img, .ts-gallery img, .ts-slide-link img').each(function() {
                    var imgSrc = $(this).attr('src');
                    var imgAlt = $(this).attr('alt') || '';
                    
                    // Evita duplicati basandosi sul src
                    var isDuplicate = allImages.some(function(img) {
                        return img.src === imgSrc;
                    });
                    
                    if (!isDuplicate && imgSrc) {
                        // Se questa è l'immagine cliccata, salva l'indice
                        if (imgSrc === imageUrl && !foundCurrentImage) {
                            currentIndex = allImages.length;
                            foundCurrentImage = true;
                            console.log('Found clicked image at index:', currentIndex);
                        }
                        
                        allImages.push({
                            src: imgSrc,
                            alt: imgAlt
                        });
                    }
                });
                
                // Se non abbiamo trovato l'immagine esatta, proviamo a cercare per href nei link
                if (!foundCurrentImage) {
                    $('.ts-slide-link').each(function() {
                        var linkHref = $(this).attr('href');
                        if (linkHref === imageUrl) {
                            // Trova l'indice di questa immagine nell'array
                            var linkImg = $(this).find('img').first();
                            if (linkImg.length) {
                                var linkImgSrc = linkImg.attr('src');
                                for (var i = 0; i < allImages.length; i++) {
                                    if (allImages[i].src === linkImgSrc) {
                                        currentIndex = i;
                                        foundCurrentImage = true;
                                        console.log('Found clicked image via link href at index:', currentIndex);
                                        break;
                                    }
                                }
                            }
                        }
                    });
                }
                
                // Come fallback finale, se ancora non troviamo l'immagine, aggiungila
                if (!foundCurrentImage) {
                    console.log('Image not found in existing collection, adding it');
                    currentIndex = allImages.length;
                    allImages.push({
                        src: imageUrl,
                        alt: alt
                    });
                }
                
                console.log('Found', allImages.length, 'images total, starting at index', currentIndex);
                
                // Crea lightbox avanzata con navigazione
                var modal = $('<div class="ts-lightbox-modal">')
                    .css({
                        'position': 'fixed',
                        'top': '0',
                        'left': '0',
                        'width': '100%',
                        'height': '100%',
                        'background': 'rgba(0, 0, 0, 0.9)',
                        'z-index': '99999',
                        'display': 'flex',
                        'align-items': 'center',
                        'justify-content': 'center'
                    });
                
                var lightboxContent = $('<div class="ts-lightbox-content">')
                    .css({
                        'position': 'relative',
                        'max-width': '90%',
                        'max-height': '90%',
                        'text-align': 'center'
                    });
                
                var img = $('<img class="ts-lightbox-image">')
                    .attr('src', allImages[currentIndex].src)
                    .attr('alt', allImages[currentIndex].alt)
                    .css({
                        'max-width': '100%',
                        'max-height': '100vh',
                        'object-fit': 'contain'
                    });
                
                // Controlli FISSI - non si muovono mai
                var closeBtn = $('<button class="ts-lightbox-close-fixed">×</button>')
                    .css({
                        'position': 'fixed',
                        'top': '20px',
                        'right': '20px',
                        'background': 'rgba(255, 255, 255, 0.9)',
                        'border': 'none',
                        'border-radius': '50%',
                        'width': '50px',
                        'height': '50px',
                        'font-size': '24px',
                        'cursor': 'pointer',
                        'color': '#333',
                        'z-index': '100000',
                        'transition': 'background 0.3s ease'
                    });
                
                var counter = $('<div class="ts-lightbox-counter-fixed">')
                    .text((currentIndex + 1) + ' / ' + allImages.length)
                var prevBtn = $('<button class="ts-lightbox-prev-fixed">‹</button>')
                    .css({
                        'position': 'fixed',
                        'left': '20px',
                        'top': '50%',
                        'transform': 'translateY(-50%)',
                        'background': 'rgba(255, 255, 255, 0.9)',
                        'border': 'none',
                        'border-radius': '50%',
                        'width': '60px',
                        'height': '60px',
                        'font-size': '30px',
                        'cursor': 'pointer',
                        'color': '#333',
                        'z-index': '100000',
                        'transition': 'background 0.3s ease',
                        'display': allImages.length > 1 ? 'block' : 'none'
                    });
                
                var nextBtn = $('<button class="ts-lightbox-next-fixed">›</button>')
                    .css({
                        'position': 'fixed',
                        'right': '20px',
                        'top': '50%',
                        'transform': 'translateY(-50%)',
                        'background': 'rgba(255, 255, 255, 0.9)',
                        'border': 'none',
                        'border-radius': '50%',
                        'width': '60px',
                        'height': '60px',
                        'font-size': '30px',
                        'cursor': 'pointer',
                        'color': '#333',
                        'z-index': '100000',
                        'transition': 'background 0.3s ease',
                        'display': allImages.length > 1 ? 'block' : 'none'
                    });
                
                // Funzione per aggiornare l'immagine
                function updateImage() {
                    img.attr('src', allImages[currentIndex].src);
                    img.attr('alt', allImages[currentIndex].alt);
                    counter.text((currentIndex + 1) + ' / ' + allImages.length);
                }
                
                // Eventi di navigazione
                prevBtn.on('click', function(e) {
                    e.stopPropagation();
                    currentIndex = (currentIndex - 1 + allImages.length) % allImages.length;
                    updateImage();
                });
                
                nextBtn.on('click', function(e) {
                    e.stopPropagation();
                    currentIndex = (currentIndex + 1) % allImages.length;
                    updateImage();
                });
                
                // Chiudi lightbox
                closeBtn.on('click', function(e) {
                    e.stopPropagation();
                    modal.remove();
                    closeBtn.remove();
                    counter.remove();
                    prevBtn.remove();
                    nextBtn.remove();
                    $(document).off('keyup.lightbox');
                });
                
                // Assembla la lightbox
                lightboxContent.append(img);
                modal.append(lightboxContent);
                
                // Aggiungi controlli fissi direttamente al body
                $('body').append(modal);
                $('body').append(closeBtn);
                $('body').append(counter);
                if (allImages.length > 1) {
                    $('body').append(prevBtn);
                    $('body').append(nextBtn);
                }
                
                // Chiudi cliccando fuori dall'immagine
                modal.on('click', function(e) {
                    if (e.target === modal[0]) {
                        modal.remove();
                        closeBtn.remove();
                        counter.remove();
                        prevBtn.remove();
                        nextBtn.remove();
                    }
                });
                
                // Navigazione da tastiera
                $(document).on('keyup.lightbox', function(e) {
                    if (e.keyCode === 27) { // ESC
                        modal.remove();
                        closeBtn.remove();
                        counter.remove();
                        prevBtn.remove();
                        nextBtn.remove();
                        $(document).off('keyup.lightbox');
                    } else if (e.keyCode === 37 && allImages.length > 1) { // Freccia sinistra
                        currentIndex = (currentIndex - 1 + allImages.length) % allImages.length;
                        updateImage();
                    } else if (e.keyCode === 39 && allImages.length > 1) { // Freccia destra
                        currentIndex = (currentIndex + 1) % allImages.length;
                        updateImage();
                    }
                });
                
                // Rimuovi event listener quando la modal viene chiusa
                modal.on('remove', function() {
                    $(document).off('keyup.lightbox');
                });
            });
        }
    }
    
    // Initialize WordPress lightbox
    initWordPressLightbox();
    
    // Gallery Slider functionality
    function initGallerySlider() {
        $('.ts-gallery-slider').each(function() {
            var slider = $(this);
            var slides = slider.find('.ts-slide');
            var dots = slider.find('.ts-slider-dot');
            var prevBtn = slider.find('.ts-slider-prev');
            var nextBtn = slider.find('.ts-slider-next');
            var currentSlide = 0;
            var totalSlides = slides.length;
            
            if (totalSlides <= 1) return;
            
            // Auto slide functionality
            var autoSlideInterval = null;
            var autoSlideTimeout = null;
            var autoSlideDelay = 5000; // 5 seconds
            var isHovered = false;
            var isUserInteracting = false;
            
            function showSlide(index) {
                slides.removeClass('active');
                dots.removeClass('active');
                
                slides.eq(index).addClass('active');
                dots.eq(index).addClass('active');
                
                currentSlide = index;
            }
            
            function nextSlide() {
                var next = (currentSlide + 1) % totalSlides;
                showSlide(next);
            }
            
            function prevSlide() {
                var prev = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(prev);
            }
            
            function startAutoSlide() {
                // Clear any existing intervals/timeouts
                clearInterval(autoSlideInterval);
                clearTimeout(autoSlideTimeout);
                
                // Only start if not hovered and not user interacting
                if (!isHovered && !isUserInteracting) {
                    autoSlideInterval = setInterval(nextSlide, autoSlideDelay);
                }
            }
            
            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
                clearTimeout(autoSlideTimeout);
                autoSlideInterval = null;
                autoSlideTimeout = null;
            }
            
            function restartAutoSlideAfterDelay() {
                stopAutoSlide();
                isUserInteracting = true;
                
                // Restart after 3 seconds of no interaction
                autoSlideTimeout = setTimeout(function() {
                    isUserInteracting = false;
                    startAutoSlide();
                }, 3000);
            }
            
            // Navigation events
            nextBtn.on('click', function(e) {
                e.preventDefault();
                nextSlide();
                restartAutoSlideAfterDelay();
            });
            
            prevBtn.on('click', function(e) {
                e.preventDefault();
                prevSlide();
                restartAutoSlideAfterDelay();
            });
            
            // Dots navigation
            dots.on('click', function(e) {
                e.preventDefault();
                var slideIndex = parseInt($(this).data('slide'));
                showSlide(slideIndex);
                restartAutoSlideAfterDelay();
            });
            
            // Keyboard navigation
            $(document).on('keydown.slider', function(e) {
                if (e.keyCode === 37) { // Left arrow
                    prevSlide();
                    restartAutoSlideAfterDelay();
                } else if (e.keyCode === 39) { // Right arrow
                    nextSlide();
                    restartAutoSlideAfterDelay();
                }
            });
            
            // Pause auto slide on hover
            slider.hover(
                function() {
                    isHovered = true;
                    stopAutoSlide();
                },
                function() {
                    isHovered = false;
                    if (!isUserInteracting) {
                        startAutoSlide();
                    }
                }
            );
            
            // Touch/swipe support for mobile
            var startX = 0;
            var endX = 0;
            
            slider.on('touchstart', function(e) {
                startX = e.originalEvent.touches[0].clientX;
            });
            
            slider.on('touchend', function(e) {
                endX = e.originalEvent.changedTouches[0].clientX;
                var deltaX = endX - startX;
                
                if (Math.abs(deltaX) > 50) { // Minimum swipe distance
                    if (deltaX > 0) {
                        prevSlide();
                    } else {
                        nextSlide();
                    }
                    restartAutoSlideAfterDelay();
                }
            });
            
            // Start auto slide initially
            startAutoSlide();
            
            // Clean up on page unload
            $(window).on('beforeunload', function() {
                stopAutoSlide();
            });
        });
    }
    
    // Initialize gallery slider
    initGallerySlider();
    
    // PDF export functionality
    /*
    $('.ts-pdf-export-button').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var originalText = button.text();
        var postId = button.data('post-id') || $('article').attr('id').replace('post-', '');
        
        // Show loading state
        button.text('Generating PDF...');
        button.prop('disabled', true);
        
        $.ajax({
            url: ts_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'ts_export_pdf',
                post_id: postId,
                nonce: ts_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Create download link
                    var link = document.createElement('a');
                    link.href = response.data.pdf_url;
                    link.download = '';
                    link.target = '_blank';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    alert('Error generating PDF: ' + response.data);
                }
            },
            error: function() {
                alert('Error generating PDF. Please try again.');
            },
            complete: function() {
                // Restore button state
                button.text(originalText);
                button.prop('disabled', false);
            }
        });
    });
    */
    
    // Smooth scrolling for internal links
    $('a[href^="#"]').on('click', function(e) {
        var target = $(this.getAttribute('href'));
        
        if (target.length) {
            e.preventDefault();
            
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 800);
        }
    });
    
    // Back to top button
    if ($('.ts-single-sheet').length && 1==2) {
        var backToTop = $('<button class="ts-back-to-top" title="Back to top">↑</button>');
        $('body').append(backToTop);
        
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 500) {
                backToTop.addClass('show');
            } else {
                backToTop.removeClass('show');
            }
        });
        
        backToTop.on('click', function() {
            $('html, body').animate({
                scrollTop: 0
            }, 800);
        });
    }
    
    // Lazy loading for images
    function lazyLoadImages() {
        var images = $('.ts-gallery img, .ts-accordion-image, .ts-featured-image img');
        
        images.each(function() {
            var img = $(this);
            var dataSrc = img.data('src');
            
            if (dataSrc && isElementInViewport(img[0])) {
                img.attr('src', dataSrc);
                img.removeData('src');
            }
        });
    }
    
    function isElementInViewport(el) {
        var rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
    
    // Initial lazy load
    lazyLoadImages();
    
    // Lazy load on scroll
    $(window).on('scroll', function() {
        lazyLoadImages();
    });
    
    // Responsive table functionality
    $('.ts-basic-info-table').each(function() {
        var table = $(this);
        var wrapper = $('<div class="ts-table-wrapper">');
        
        table.wrap(wrapper);
        
        // Add responsive scroll indicators
        var scrollIndicator = $('<div class="ts-scroll-indicator">← Scroll to see more →</div>');
        table.parent().append(scrollIndicator);
        
        // Show/hide scroll indicator
        function checkScrollIndicator() {
            if (table.parent()[0].scrollWidth > table.parent()[0].clientWidth) {
                scrollIndicator.show();
            } else {
                scrollIndicator.hide();
            }
        }
        
        checkScrollIndicator();
        $(window).on('resize', checkScrollIndicator);
        
        // Hide indicator when scrolling
        table.parent().on('scroll', function() {
            scrollIndicator.fadeOut();
        });
    });
    
    // Social sharing functionality
    if ($('.ts-single-sheet-dis').length) {
        var shareButtons = $('<div class="ts-social-share">');
        var shareTitle = $('<h4>Share this technical sheet:</h4>');
        var shareList = $('<div class="ts-share-buttons">');
        
        var currentUrl = encodeURIComponent(window.location.href);
        var currentTitle = encodeURIComponent(document.title);
        
        var shareItems = [
            {
                name: 'Facebook',
                url: 'https://www.facebook.com/sharer/sharer.php?u=' + currentUrl,
                class: 'facebook'
            },
            {
                name: 'Twitter',
                url: 'https://twitter.com/intent/tweet?url=' + currentUrl + '&text=' + currentTitle,
                class: 'twitter'
            },
            {
                name: 'LinkedIn',
                url: 'https://www.linkedin.com/sharing/share-offsite/?url=' + currentUrl,
                class: 'linkedin'
            },
            {
                name: 'Email',
                url: 'mailto:?subject=' + currentTitle + '&body=' + currentUrl,
                class: 'email'
            }
        ];
        
        $.each(shareItems, function(index, item) {
            var shareLink = $('<a href="' + item.url + '" target="_blank" rel="noopener noreferrer" class="ts-share-button ts-share-' + item.class + '">' + item.name + '</a>');
            shareList.append(shareLink);
        });
        
        shareButtons.append(shareTitle);
        shareButtons.append(shareList);
        
        $('.ts-footer').prepend(shareButtons);
        
        // Track share clicks
        $('.ts-share-button').on('click', function() {
            var platform = $(this).attr('class').split(' ').pop().replace('ts-share-', '');
            
            // You can add analytics tracking here
            console.log('Shared on: ' + platform);
        });
    }
    
    // Print functionality enhancements
    $('.ts-print-button').on('click', function(e) {
        e.preventDefault();
        /*
        // Add print-specific styles
        var printCSS = `
            <style type="text/css" media="print">
                @page { margin: 1in; }
                .no-print { display: none !important; }
                .ts-accordion-content { display: block !important; }
                .ts-accordion-header { background: none !important; }
                .ts-accordion-icon { display: none !important; }
                .ts-gallery { display: grid !important; grid-template-columns: repeat(2, 1fr) !important; }
                .ts-basic-info-table { font-size: 12px !important; }
                .ts-section { page-break-inside: avoid; }
                h1, h2, h3 { page-break-after: avoid; }
            </style>
        `;
        
        // Add print styles to head
        $('head').append(printCSS);
        */
        // Expand all accordion sections for printing
        $('.ts-accordion-content').addClass('active').show();
        
        // Wait a bit for styles to apply, then print
        setTimeout(function() {
            window.print();
        }, 500);
    });
    
    // Keyboard navigation
    $(document).on('keydown', function(e) {
        // Navigate between accordion sections with arrow keys
        if (e.target.classList.contains('ts-accordion-header') || $(e.target).parents('.ts-accordion-header').length) {
            var currentHeader = $(e.target).closest('.ts-accordion-header');
            var allHeaders = $('.ts-accordion-header');
            var currentIndex = allHeaders.index(currentHeader);
            
            if (e.keyCode === 40) { // Down arrow
                e.preventDefault();
                var nextIndex = (currentIndex + 1) % allHeaders.length;
                allHeaders.eq(nextIndex).focus();
            } else if (e.keyCode === 38) { // Up arrow
                e.preventDefault();
                var prevIndex = (currentIndex - 1 + allHeaders.length) % allHeaders.length;
                allHeaders.eq(prevIndex).focus();
            } else if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                e.preventDefault();
                currentHeader.click();
            }
        }
    });
    
    // Make accordion headers focusable
    $('.ts-accordion-header').attr('tabindex', '0');
    
    // Intersection Observer for animations - DISABLED
    // Uncomment the code below if you want to re-enable page animations
    /*
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('ts-animate-in');
                }
            });
        }, {
            threshold: 0.1
        });
        
        $('.ts-section').each(function() {
            observer.observe(this);
        });
    }
    */
    
// Add CSS for animations and lightbox
var additionalCSS = `
    <style>
        .ts-featured-image {
            cursor: pointer;
        }
        
        .ts-featured-image:hover {
            transform: scale(1.02);
        }
        
        .ts-featured-image img {
            transition: inherit;
            pointer-events: none;
        }
        
        .ts-lightbox {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }
        
        .ts-lightbox-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
        }
        
        .ts-lightbox-content img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
        }
        
        /* Controlli fissi - non si muovono mai */
        .ts-lightbox-close-fixed {
            position: fixed !important;
            bottom: 20px !important;
            right: 20px !important;
            background: rgba(255, 255, 255, 0.9) !important;
            border: none !important;
            border-radius: 50% !important;
            width: 50px !important;
            height: 50px !important;
            font-size: 24px !important;
            cursor: pointer !important;
            color: #333 !important;
            z-index: 100000 !important;
            transition: background 0.3s ease !important;
        }
        
        .ts-lightbox-close-fixed:hover {
            background: rgba(255, 255, 255, 1) !important;
        }
        
        .ts-lightbox-prev-fixed,
        .ts-lightbox-next-fixed {
            position: fixed !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            background: rgba(255, 255, 255, 0.9) !important;
            border: none !important;
            border-radius: 50% !important;
            width: 60px !important;
            height: 60px !important;
            font-size: 30px !important;
            cursor: pointer !important;
            color: #333 !important;
            z-index: 100000 !important;
            transition: background 0.3s ease !important;
        }
        
        .ts-lightbox-prev-fixed {
            left: 20px !important;
        }
        
        .ts-lightbox-next-fixed {
            right: 20px !important;
        }
        
        .ts-lightbox-prev-fixed:hover,
        .ts-lightbox-next-fixed:hover {
            background: rgba(255, 255, 255, 1) !important;
        }
        
        .ts-lightbox-counter-fixed {
            position: fixed !important;
            bottom: 20px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            color: white !important;
            background: rgba(0, 0, 0, 0.7) !important;
            padding: 8px 16px !important;
            border-radius: 25px !important;
            font-size: 16px !important;
            font-weight: bold !important;
            z-index: 100000 !important;
        }
        
        @media (max-width: 768px) {
            .ts-lightbox-prev-fixed {
                left: 10px !important;
                width: 50px !important;
                height: 50px !important;
                font-size: 24px !important;
            }
            
            .ts-lightbox-next-fixed {
                right: 10px !important;
                width: 50px !important;
                height: 50px !important;
                font-size: 24px !important;
            }
            
            .ts-lightbox-close-fixed {
                top: 10px !important;
                right: 10px !important;
                width: 40px !important;
                height: 40px !important;
                font-size: 20px !important;
            }
            
            .ts-lightbox-counter-fixed {
                top: 10px !important;
                font-size: 14px !important;
                padding: 6px 12px !important;
            }
        }
        
        .ts-back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #007cba;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 20px;
            cursor: pointer;
            z-index: 1000;
            opacity: 0;
            transform: translateY(100px);
            transition: all 0.3s ease;
        }
        
        .ts-back-to-top.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .ts-back-to-top:hover {
            background: #005a87;
        }
        
        .ts-table-wrapper {
            position: relative;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .ts-scroll-indicator {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 10;
        }
        
        .ts-social-share {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .ts-social-share h4 {
            margin: 0 0 15px 0;
            font-size: 1.1rem;
        }
        
        .ts-share-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .ts-share-button {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .ts-share-facebook { background: #3b5998; }
        .ts-share-twitter { background: #1da1f2; }
        .ts-share-linkedin { background: #0077b5; }
        .ts-share-email { background: #666; }
        
        .ts-share-button:hover {
            color: white;
            transform: translateY(-2px);
        }
        
        .ts-section {
            /* Animation effects disabled for instant loading */
            opacity: 1;
            transform: none;
        }
        
        .ts-section.ts-animate-in {
            /* Keep this for backwards compatibility but no animation */
            opacity: 1;
            transform: none;
        }
        
        @media (max-width: 768px) {
            .ts-share-buttons {
                flex-direction: column;
            }
            
            .ts-share-button {
                text-align: center;
            }
        }
    </style>
`;

jQuery('head').append(additionalCSS);

    // Archive filters functionality
    function initArchiveFilters() {
        if ($('.ts-filters-form').length === 0) {
            return;
        }
        
        console.log('Initializing archive filters...');
        
        // Initialize Select2 for filter dropdowns
        if (typeof $.fn.select2 !== 'undefined') {
            $('.ts-filter-select').select2({
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
        
        var isLoading = false;
        
        // When select values change or search input changes, load results via AJAX
        $('.ts-filter-select, .ts-filter-input').on('change keyup', function(e) {
            // For search input, only trigger on Enter key or after a delay
            if ($(this).hasClass('ts-filter-input')) {
                if (e.type === 'keyup' && e.which !== 13) {
                    clearTimeout(window.tsSearchTimeout);
                    window.tsSearchTimeout = setTimeout(function() {
                        loadFilteredResults();
                    }, 500);
                    return;
                } else if (e.type === 'keyup' && e.which === 13) {
                    e.preventDefault();
                    loadFilteredResults();
                    return;
                }
                // Skip change event for search input (only use keyup)
                if (e.type === 'change') {
                    return;
                }
            }
            
            // For selects, load immediately on change
            if ($(this).hasClass('ts-filter-select')) {
                loadFilteredResults();
            }
        });
        
        // Submit form via AJAX
        $('.ts-filters-form').on('submit', function(e) {
            e.preventDefault();
            loadFilteredResults();
        });
        
        // Clear filters button
        $('.ts-clear-button').on('click', function(e) {
            e.preventDefault();
            $('.ts-filter-select').val('').trigger('change.select2');
            $('.ts-filter-input').val('');
            loadFilteredResults();
        });
        
        // Handle pagination clicks
        $(document).on('click', '.ts-pagination a.page-numbers', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var page = 1;
            
            // Extract page number from URL
            var matches = url.match(/paged=(\d+)/);
            if (matches) {
                page = parseInt(matches[1]);
            } else {
                matches = url.match(/page\/(\d+)/);
                if (matches) {
                    page = parseInt(matches[1]);
                }
            }
            
            loadFilteredResults(page);
            
            // Scroll to top of results
            $('html, body').animate({
                scrollTop: $('.ts-filters-form').offset().top - 100
            }, 300);
        });
        
        function loadFilteredResults(page) {
            if (isLoading) {
                return;
            }
            
            page = page || 1;
            isLoading = true;
            
            var $form = $('.ts-filters-form');
            var $resultsContainer = $('.ts-filters-container');
            var $submitButton = $form.find('.ts-apply-button');
            
            // Show loading state
            $submitButton.prop('disabled', true).text('Caricamento...');
            $resultsContainer.css('opacity', '0.5');
            
            // Get filter values
            var data = {
                action: 'get_ts_filtered_results',
                nonce: ts_ajax.nonce,
                category: $form.find('[name="technical_sheet_category"]').val(),
                model: $form.find('[name="technical_sheet_model"]').val(),
                version: $form.find('[name="technical_sheet_version"]').val(),
                search: $form.find('[name="s"]').val(),
                paged: page
            };
            
            // First, update filter options
            $.ajax({
                url: ts_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_ts_filtered_options',
                    nonce: ts_ajax.nonce,
                    technical_sheet_category: data.category,
                    technical_sheet_model: data.model,
                    technical_sheet_version: data.version
                },
                success: function(response) {
                    if (response.success && response.data.options) {
                        var options = response.data.options;
                        
                        // Update each select
                        $.each(options, function(taxonomy, terms) {
                            var $sel = $form.find('[name="' + taxonomy + '"]');
                            if ($sel.length) {
                                var current = $sel.val();
                                var isSelect2 = $sel.hasClass('select2-hidden-accessible');
                                
                                // Keep placeholder
                                var firstOpt = $sel.find('option:first').clone();
                                $sel.empty();
                                if (firstOpt && firstOpt.length) {
                                    $sel.append(firstOpt);
                                }
                                
                                // Append terms
                                $.each(terms, function(i, term) {
                                    var $opt = $('<option></option>').attr('value', term.slug).text(term.name + ' (' + term.count + ')');
                                    $sel.append($opt);
                                });
                                
                                // Restore selection
                                if (current && $sel.find('option[value="' + current + '"]').length) {
                                    $sel.val(current);
                                } else {
                                    $sel.val('');
                                }
                                
                                // Refresh Select2
                                if (isSelect2 && typeof $.fn.select2 !== 'undefined') {
                                    $sel.trigger('change.select2');
                                }
                            }
                        });
                    }
                }
            });
            
            // Load filtered results
            $.ajax({
                url: ts_ajax.ajax_url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        // Update active filters
                        $('.ts-active-filters').remove();
                        if (response.data.active_filters_html) {
                            $('.ts-filters-container').append(response.data.active_filters_html);
                        }
                        
                        // Update results
                        $('.ts-archive-grid').remove();
                        $('.ts-no-posts').remove();
                        $('.ts-pagination').remove();
                        
                        // Insert new content after filters container
                        $('.ts-filters-container').after(response.data.results_html);
                        
                        // Update pagination
                        if (response.data.pagination_html) {
                            $('.ts-archive-grid, .ts-no-posts').last().after('<div class="ts-pagination">' + response.data.pagination_html + '</div>');
                        }
                        
                        // Update URL without reload
                        var url = new URL(window.location);
                        url.searchParams.delete('technical_sheet_category');
                        url.searchParams.delete('technical_sheet_model');
                        url.searchParams.delete('technical_sheet_version');
                        url.searchParams.delete('s');
                        url.searchParams.delete('paged');
                        
                        if (data.category) url.searchParams.set('technical_sheet_category', data.category);
                        if (data.model) url.searchParams.set('technical_sheet_model', data.model);
                        if (data.version) url.searchParams.set('technical_sheet_version', data.version);
                        if (data.search) url.searchParams.set('s', data.search);
                        if (page > 1) url.searchParams.set('paged', page);
                        
                        window.history.pushState({}, '', url);
                    }
                },
                error: function() {
                    alert('Si è verificato un errore durante il caricamento dei risultati.');
                },
                complete: function() {
                    isLoading = false;
                    $submitButton.prop('disabled', false).text('Applica Filtri');
                    $resultsContainer.css('opacity', '1');
                }
            });
        }
    }
    
    // Update results count
    function updateResultsCount() {
        var resultsCount = $('.ts-archive-grid .ts-archive-item').length;
        var totalText = resultsCount === 1 ? 
            resultsCount + ' technical sheet found' : 
            resultsCount + ' technical sheets found';
            
        if ($('.ts-results-count').length === 0) {
            $('.ts-archive-grid').before('<div class="ts-results-count" style="margin-bottom:20px;color:#666;font-size:0.9rem;">' + totalText + '</div>');
        } else {
            $('.ts-results-count').text(totalText);
        }
    }
    
    // Initialize filters when DOM is ready
    initArchiveFilters();

}); // End jQuery(document).ready
