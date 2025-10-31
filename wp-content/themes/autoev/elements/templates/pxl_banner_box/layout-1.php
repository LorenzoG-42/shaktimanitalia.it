<?php
$widget->add_render_attribute( 'pxl_team_wrap', 'id', pxl_get_element_id($settings));
$widget->add_render_attribute( 'pxl_team_wrap', 'class', 'pxl-image-wg');
$widget->add_render_attribute( 'pxl_team_wrap', 'duration', '1');
if(!empty($settings['custom_style']))
    $widget->add_render_attribute( 'pxl_team_wrap', 'class', $settings['custom_style']);

if(!empty($settings['pxl_parallax'])){
    $parallax_settings = json_encode([
        $settings['pxl_parallax'] => $settings['parallax_value']
    ]);
    $widget->add_render_attribute( 'pxl_team_wrap', 'data-parallax', $parallax_settings );
}

$html_id = pxl_get_element_id($settings);
if ( ! empty( $settings['button_link']['url'] ) ) {
    $widget->add_render_attribute( 'button', 'href', $settings['button_link']['url'] );

    if ( $settings['button_link']['is_external'] ) {
        $widget->add_render_attribute( 'button', 'target', '_blank' );
    }

    if ( $settings['button_link']['nofollow'] ) {
        $widget->add_render_attribute( 'button', 'rel', 'nofollow' );
    }
}
?>
<div class="pxl-banner-box pxl-banner-box1">
    <div class="pxl-meta-inner <?php echo esc_attr($settings['style'])?> ">
        <div class="pxl-item-layout1 <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
            <?php if(!empty($settings['image']['id'])) : 
                $image_size = !empty($settings['img_size']) ? $settings['img_size'] : '960x784'; 
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $settings['image']['id'],
                    'thumb_size' => $image_size,
                ));
                $thumbnail = $img['thumbnail'];
                ?>
                <div class="pxl-item--imgprimary">
                    <div <?php pxl_print_html($widget->get_render_attribute_string( 'pxl_team_wrap' )); ?>>
                        <div class="pxl-image-wrapper wow">
                            <?php echo pxl_print_html($thumbnail); ?>
                        </div>
                        <h3 class="pxl-item-heading wow <?php echo esc_attr($settings['pxl_animate2']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay2']); ?>ms">
                        <div class="pxl-icon">
                            <i class="icofont icofont-bulb-alt"></i>    
                        </div>        
                                <div class="pxl-title">
                                    <?php echo esc_attr($settings['title_heading']); ?>
                                </div>    
                        </h3>
                        <div class="pxl-box-line"></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
