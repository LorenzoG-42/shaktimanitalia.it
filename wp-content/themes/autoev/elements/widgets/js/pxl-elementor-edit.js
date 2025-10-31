(function($) {
    "use strict";
    function autoev_section_start_render() {
        var _elementor = typeof elementor !== 'undefined' ? elementor : elementorFrontend;

        _elementor.hooks.addFilter('pxl_section_start_render', function(html, settings, el) {
            if (typeof settings.pxl_parallax_bg_img !== 'undefined' && settings.pxl_parallax_bg_img.url !== '') {
                var parallaxClass = settings.pxl_parallax_bg_style ? settings.pxl_parallax_bg_style : '';
                html += '<div class="pxl-section-bg-parallax ' + parallaxClass + '"></div>';
            }

            return html;
        });
    }

    function autoev_section_before_render() {
        var _elementor = typeof elementor !== 'undefined' ? elementor : elementorFrontend;

        _elementor.hooks.addFilter('pxl_custom_section/before_render', function(html, settings, el) {
            return html;
        });
    }

    $(window).on('elementor/frontend/init', function() {
        autoev_section_start_render();
        autoev_section_before_render();
    });
})(jQuery);