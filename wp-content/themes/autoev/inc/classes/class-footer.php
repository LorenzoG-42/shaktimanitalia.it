<?php

if (!class_exists('Autoev_Footer')) {

    class Autoev_Footer
    {
        public function getFooter()
        {
            if(is_singular('elementor_library')) return;
            
            $footer_layout = (int)autoev()->get_opt('footer_layout');
            
            if ($footer_layout <= 0 || !class_exists('Pxltheme_Core') || !is_callable( 'Elementor\Plugin::instance' )) {
                get_template_part( 'template-parts/footer/default');
            } else {
                $args = [
                    'footer_layout' => $footer_layout
                ];
                get_template_part( 'template-parts/footer/elementor','', $args );
            } 

            // Back To Top
            $back_totop_on = autoev()->get_theme_opt('back_totop_on', true); 
            if (isset($back_totop_on) && $back_totop_on) : ?>
                <div class="pxl-scroll-layout">
                <div class="pxl-scroll-top">
                    <a class="pxl-scroll-top-link" href="#">
                        <span>Scroll to top</span>
                    </a>
                    <div class="scrollbar-v">
                        <svg viewBox="0 0 2 100">
                            <path d="M1,100 L1,0" />
                        </svg>
                    </div>
                </div>
                </div>
            <?php endif;

            // Mouse Move Animation
            autoev_mouse_move_animation();

            // Cookie Policy
            autoev_cookie_policy();

            // Subscribe Popup
            autoev_subscribe_popup();
            
        }
 
    }
}
 