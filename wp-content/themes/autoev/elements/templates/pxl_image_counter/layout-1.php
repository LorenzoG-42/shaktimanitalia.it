<?php
$img_size = '';
if(!empty($settings['img_size'])) {
    $img_size = $settings['img_size'];
} else {
    $img_size = 'full';
}
$widget->add_render_attribute( 'counter', [
    'class' => 'pxl--counter-value '.$settings['effect'].'',
    'data-duration' => $settings['duration'],
    'data-startnumber' => $settings['starting_number'],
    'data-endnumber' => $settings['ending_number'],
    'data-to-value' => $settings['ending_number'],
    'data-delimiter' => $settings['thousand_separator_char'],
] ); 
?>
<div class="pxl-image-counter pxl-image-counter1 ">
    <div class="pxl--item-inner">
    <?php if( $settings['image_type'] != 'none' && !empty( $settings['image']['url'] ) ) : 
            $img  = pxl_get_image_by_size( array(
                'attach_id'  => $settings['image']['id'],
                'thumb_size' => $img_size,
            ) );
            $thumbnail    = $img['thumbnail'];
            ?>
            <div class="pxl-video--holder wow <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                <?php if ($settings['image_type'] == 'img') { ?>
                    <?php if ( ! empty( $settings['image']['url'] ) ) { echo wp_kses_post($thumbnail); } ?>
                <?php } else { ?>
                    <div class="pxl-video--imagebg">
                        <div class="bg-image" style="background-image: url(<?php echo esc_url($settings['image']['url']); ?>);"></div>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>
        <div class="pxl-content">
        <div class="pxl--counter-meta">         
            <div class="pxl--counter-number">
                <div class="pxl-counter-meta">
                    <span class="pxl--counter-prefix el-empty"><?php echo pxl_print_html($settings['prefix']); ?></span>
                    <span <?php pxl_print_html($widget->get_render_attribute_string( 'counter' )); ?>><?php echo esc_html($settings['starting_number']); ?></span>
                    <?php if(!empty($settings['suffix'])) : ?>
                        <span class="pxl--counter-suffix"><?php echo pxl_print_html($settings['suffix']); ?></span>
                    <?php endif; ?>
                </div>
                <?php if(!empty($settings['title'])) : ?>
                    <div class="pxl--item-title"><?php echo pxl_print_html($settings['title']); ?></div>
                <?php endif; ?>  
            </div>
            <?php if(!empty($settings['des'])) : ?>
                    <div class="pxl--item-des"><?php echo pxl_print_html($settings['des']); ?></div>
                <?php endif; ?>
        </div>
        </div>
    </div>
</div>