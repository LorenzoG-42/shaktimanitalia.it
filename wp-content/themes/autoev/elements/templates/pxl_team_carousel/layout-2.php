<?php
$col_xs = $widget->get_setting('col_xs', '');
$col_sm = $widget->get_setting('col_sm', '');
$col_md = $widget->get_setting('col_md', '');
$col_lg = $widget->get_setting('col_lg', '');
$col_xl = $widget->get_setting('col_xl', '');
$col_xxl = $widget->get_setting('col_xxl', '');
if($col_xxl == 'inherit') {
    $col_xxl = $col_xl;
}
$slides_to_scroll = $widget->get_setting('slides_to_scroll', '');
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
    'slide_mode'                    => 'slide', 
    'slides_to_show'                => $col_xl,
    'slides_to_show_xxl'             => $col_xxl, 
    'slides_to_show_lg'             => $col_lg, 
    'slides_to_show_md'             => $col_md, 
    'slides_to_show_sm'             => $col_sm, 
    'slides_to_show_xs'             => $col_xs, 
    'slides_to_scroll'              => $slides_to_scroll,
    'arrow'                         => $arrows,
    'pagination'                    => $pagination,
    'pagination_type'               => $pagination_type,
    'autoplay'                      => $autoplay,
    'pause_on_hover'                => $pause_on_hover,
    'pause_on_interaction'          => 'true',
    'delay'                         => $autoplay_speed,
    'loop'                          => $infinite,
    'speed'                         => $speed
];
$widget->add_render_attribute( 'carousel', [
    'class'         => 'pxl-swiper-container',
    'dir'           => is_rtl() ? 'rtl' : 'ltr',
    'data-settings' => wp_json_encode($opts)
]);

$widget->add_render_attribute( 'pxl_team_wrap', 'id', pxl_get_element_id($settings));
$widget->add_render_attribute( 'pxl_team_wrap', 'class', 'pxl-team-wg');
$widget->add_render_attribute( 'pxl_team_wrap', 'duration', '1');
if(!empty($settings['custom_style']))
    $widget->add_render_attribute( 'pxl_team_wrap', 'class', $settings['custom_style']);

if(!empty($settings['pxl_parallax'])){
    $parallax_settings = json_encode([
        $settings['pxl_parallax'] => $settings['parallax_value']
    ]);
    $widget->add_render_attribute( 'pxl_team_wrap', 'data-parallax', $parallax_settings );
}
?>
<?php if(isset($settings['team']) && !empty($settings['team']) && count($settings['team'])): 
    $image_size = !empty($settings['img_size']) ? $settings['img_size'] : 'full'; ?>
    <div class="pxl-swiper-sliders pxl-team pxl-team-carousel2 pxl-team-layout2 pxl-team-style1" <?php if($settings['drap']) : ?>data-cursor-drap="<?php echo esc_html('DRAG', 'autoev'); ?>"<?php endif; ?>>
        <div class="pxl-carousel-inner <?php echo esc_attr($settings['stylel'])?> ">
            <div <?php pxl_print_html($widget->get_render_attribute_string( 'carousel' )); ?>>
                <div class="pxl-swiper-wrapper">
                    <?php foreach ($settings['team'] as $key => $value):
                        $title = isset($value['title']) ? $value['title'] : '';
                        $image = isset($value['image']) ? $value['image'] : '';
                        $social = isset($value['social']) ? $value['social'] : '';
                        $price = isset($value['price']) ? $value['price'] : '';
                        $item_infor1 = isset($value['item_infor1']) ? $value['item_infor1'] : '';
                        $item_infor2 = isset($value['item_infor2']) ? $value['item_infor2'] : '';
                        $item_infor3 = isset($value['item_infor3']) ? $value['item_infor3'] : '';
                        $item_infor_sb1 = isset($value['item_infor_sb1']) ? $value['item_infor_sb1'] : '';
                        $item_infor_sb2 = isset($value['item_infor_sb2']) ? $value['item_infor_sb2'] : '';
                        $item_infor_sb3 = isset($value['item_infor_sb3']) ? $value['item_infor_sb3'] : '';

                        ?>
                        <div class="pxl-swiper-slide">
                            <div <?php pxl_print_html($widget->get_render_attribute_string( 'pxl_team_wrap' )); ?>>
                                <div class="pxl-item--inner">
                                    <?php if(!empty($image['id'])) { 
                                        $img = pxl_get_image_by_size( array(
                                            'attach_id'  => $image['id'],
                                            'thumb_size' => $image_size,
                                            'class' => 'no-lazyload',
                                        ));
                                        $thumbnail = $img['thumbnail'];
                                        ?>
                                        <div class="pxl-item--image ms">
                                            <div class="pxl-item--holder">
                                                <?php echo wp_kses_post($thumbnail); ?>
                                                <?php if(!empty($social)): ?>
                                                    <div class="pxl-item--social">
                                                        <?php  $team_social = json_decode($social, true);
                                                        foreach ($team_social as $value): ?>
                                                            <a href="<?php echo esc_url($value['url']); ?>" target="_blank"><i class="<?php echo esc_attr($value['icon']); ?>"></i></a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="pxl-item-infor row">
                                        <h5 class="pxl-item--infor col-4">
                                            <div class="pxl-mg">
                                                <div class="pxl-item--title">
                                                    <?php echo pxl_print_html($item_infor1); ?>
                                            </div>
                                            <div class="pxl-item--sub">
                                                <?php echo pxl_print_html($item_infor_sb1); ?>
                                            </div>
                                            </div>
                                        </h5>

                                        <h5 class="pxl-item--infor col-4">
                                            <div class="pxl-mg">
                                                <div class="pxl-item--title">
                                                    <?php echo pxl_print_html($item_infor2); ?>
                                                </div>
                                                <div class="pxl-item--sub">
                                                    <?php echo pxl_print_html($item_infor_sb2); ?>
                                                </div>
                                            </div>
                                        </h5>

                                        <h5 class="pxl-item--infor col-4">
                                            <div class="pxl-mg">
                                            <div class="pxl-item--title">
                                                <?php echo pxl_print_html($item_infor3); ?>
                                            </div>
                                            <div class="pxl-item--sub">
                                                <?php echo pxl_print_html($item_infor_sb3); ?>
                                            </div>
                                            </div>
                                            
                                        </h5>
                                    </div>
                                    <h5 class="pxl-item--title ms">
                                        <?php echo pxl_print_html($title); ?>
                                    </h5>
                                    <h5 class="pxl-item--price ">
                                        <?php echo pxl_print_html($price); ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php if($pagination !== 'false'): ?>
            <div class="pxl-swiper-dots style-1"></div>
        <?php endif; ?>

        <?php if($arrows !== 'false'): ?>
            <div class="pxl-swiper-arrow-wrap">
            <div class="pxl-swiper-arrow pxl-swiper-arrow-prev"><i class="fas fa-chevron-left rtl-icon"></i></div>
            <div class="pxl-swiper-arrow pxl-swiper-arrow-next"><i class="fas fa-chevron-right rtl-icon"></i></div>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
