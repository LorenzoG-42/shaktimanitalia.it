<?php
$progressbar = $widget->get_setting('progressbar','false');  
$arrows = $widget->get_setting('arrows','false');  
$pagination = $widget->get_setting('pagination','false');
$pagination_type = $widget->get_setting('pagination_type','bullets');
$pause_on_hover = $widget->get_setting('pause_on_hover');
$autoplay = $widget->get_setting('autoplay', '');
$autoplay_speed = $widget->get_setting('autoplay_speed', '5000');
$infinite = $widget->get_setting('infinite','false');  
$speed = $widget->get_setting('speed', '500');
$opts = [
    'slide_direction'               => 'horizontal',
    'slide_percolumn'               => '1', 
    'slide_mode'                    => 'fade', 
    'slides_to_show'                => '1',
    'slides_to_show_xxl'            => '1', 
    'slides_to_show_lg'             => '1', 
    'slides_to_show_md'             => '1', 
    'slides_to_show_sm'             => '1',
    'slides_to_show_xs'             => '1', 
    'slides_to_scroll'              => '1',
    'arrow'                         => $arrows,
    'pagination'                    => $pagination,
    'pagination_type'               => $pagination_type,
    'autoplay'                      => $autoplay,
    'pause_on_hover'                => $pause_on_hover,
    'pause_on_interaction'          => 'true',
    'delay'                         => $autoplay_speed,
    'loop'                          => $infinite,
    'speed'                         => $speed,
];
$widget->add_render_attribute( 'carousel', [
    'class'         => 'pxl-swiper-container',
    'dir'           => is_rtl() ? 'rtl' : 'ltr',
    'data-settings' => wp_json_encode($opts)
]);
wp_enqueue_script('stellar-parallax');
if(isset($settings['slides']) && !empty($settings['slides']) && count($settings['slides'])): ?>
    <div class="pxl-swiper-sliders pxl-element-slider pxl-slide pxl-swiper-nogap <?php if($arrows !== 'false') { echo 'pxl-swiper-show-arrow'; } if($pagination !== 'false') { echo ' pxl-swiper-show-pagination'; } ?>" <?php if($settings['drap']) : ?>data-cursor-drap="<?php echo esc_html('DRAG', 'autoev'); ?>"<?php endif; ?> data-slider-mode="fade">
        <div class="pxl-carousel-inner">
            <div <?php pxl_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <div class="pxl-swiper-wrapper">
                    <?php foreach ($settings['slides'] as $key => $value):
                        $bg_image = isset($value['bg_image']) ? $value['bg_image'] : '';
                        if(!empty($value['slide_template'])) : ?>
                            <div class="pxl-swiper-slide">
                                <div class="pxl-slider--inner elementor-repeater-item-<?php echo esc_attr($value['_id']); ?>">
                                    <?php if(!empty($bg_image['id'])) :
                                        $img  = pxl_get_image_by_size( array(
                                            'attach_id'  => $bg_image['id'],
                                            'thumb_size' => 'full',
                                            'class' => 'no-lazyload'
                                        ) );
                                        $thumbnail_url = $img['url']; ?>
                                        <div class="pxl-slider--overlay">
                                            <div class="pxl-slider--image bg-image" style="background-image: url(<?php echo esc_url($thumbnail_url); ?>);"></div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="pxl-slider--content">
                                        <?php $slide_content = Elementor\Plugin::$instance->frontend->get_builder_content_for_display( (int)$value['slide_template']);
                                        pxl_print_html($slide_content); ?>
                                    </div>
                               </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php if($pagination !== 'false'): ?>
                <div class="pxl-swiper-pagination">
                    <div class="pxl-swiper-dots style-1"></div>
                </div>
            <?php endif; ?>

            <?php if($arrows !== 'false'): ?>
                <div class="pxl-swiper-arrow-wrap">
                    <div class="pxl-swiper-arrow pxl-swiper-arrow-prev"><i aria-hidden="true" class="fas fa-chevron-left rtl-icon"></i></div>
                    <div class="pxl-swiper-arrow pxl-swiper-arrow-next"><i aria-hidden="true" class="fas fa-chevron-right rtl-icon"></i></div>
                </div>
            <?php endif; ?>
        </div>

        <?php if($progressbar !== 'false'): ?>
            <div class="pxl-slider-progressbar"></div>
        <?php endif; ?>

    </div>
<?php endif; ?>
