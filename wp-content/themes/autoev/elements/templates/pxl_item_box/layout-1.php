<?php
$title = isset($settings['title']) ? $settings['title'] : '';
$sub_title = isset($settings['sub_title']) ? $settings['sub_title'] : '';
$desc = isset($settings['desc']) ? $settings['desc'] : '';
$image = isset($settings['image']) ? $settings['image'] : '';
$img_size = isset($settings['img_size']) ? $settings['img_size'] : '';
$image_size = !empty($img_size) ? $img_size : 'full';
?>

<div class="pxl-item-box" >
    <div class="pxl-grid-inner"> 
    <div class="pxl-item--inner wow <?php echo esc_attr($settings['pxl_animate']); ?>" 
     data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
     
    <?php if (!empty($image['id'])) : ?>
        <div class="pxl-box">
            <div class="pxl-item--image wow">
                <?php
                $img = pxl_get_image_by_size(array(
                    'attach_id'  => $image['id'],
                    'thumb_size' => $image_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
                echo wp_kses_post($thumbnail);
                ?>
            </div>
            
            <div class="pxl-wrap-content">
                <?php if (!empty($title)) : ?>
                    <h5 class="pxl-item--title"><?php echo pxl_print_html($title); ?></h5>
                <?php endif; ?>
                
                <?php if (!empty($sub_title)) : ?>
                    <div class="pxl-sub--title"><?php echo pxl_print_html($sub_title); ?></div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($desc)) : ?>
        <div class="pxl-desc"><?php echo pxl_print_html($desc); ?></div>
    <?php endif; ?>
    
</div>

    </div>
</div>
