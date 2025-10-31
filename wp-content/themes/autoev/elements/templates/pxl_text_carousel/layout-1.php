<?php
if (isset($settings['items']) && !empty($settings['items']) && count($settings['items'])): ?>
    <div class="pxl-text-carousel pxl-text-carousel1 pxl-text-slide1 <?php echo esc_attr($settings['style']); ?>">
        <div class="pxl-text-slide <?php echo esc_attr($settings['effect']); ?>" 
             <?php if (!empty($settings['effect_speed'])) { ?>style="animation-duration:<?php echo esc_attr($settings['effect_speed']); ?>ms"<?php } ?>>         
            <?php foreach ($settings['items'] as $key => $value):
                $text = isset($value['text']) ? $value['text'] : '';
                if (!empty($text)) : ?>
                    <div class="pxl--item <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                        <div class="pxl-item--text"><span><?php echo pxl_print_html($text); ?></span></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="pxl-text-fixed">
            <?php foreach ($settings['items'] as $key => $value):
                $text = isset($value['text']) ? $value['text'] : '';
                if (!empty($text)) : ?>
                    <div class="pxl--item <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                        <div class="pxl-item--text"><span><?php echo pxl_print_html($text); ?></span></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
