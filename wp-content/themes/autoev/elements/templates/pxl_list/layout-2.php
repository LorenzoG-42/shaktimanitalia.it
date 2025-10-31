<?php if (isset($settings['lists']) && !empty($settings['lists']) && count($settings['lists'])) : ?>
    <div class="pxl-list pxl-list2 " ;>
        <?php if (!empty($settings['title_heading'])): ?>
            <div class="pxl-heading">
                <?php echo esc_html($settings['title_heading']); ?>
            </div>
        <?php endif; ?>
        
        <?php foreach ($settings['lists'] as $key => $value) : // Corrected from 'text' to 'lists'
            $number = $key + 1;
            $title = isset($value['text']) ? $value['text'] : '';
            $heading = isset($value['heading']) ? $value['heading'] : '';
        ?>
            <div class="pxl--item <?php echo esc_attr($settings['pxl_animate'] )?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                <div class="pxl-item-content">
                    <div class="pxl-item-number"><?php echo pxl_print_html($number); ?></div>
                    <div class="pxl-desc">
                    <span class="pxl-item-heading"><?php echo pxl_print_html($heading); ?></span>
                    <span class="pxl-item-title"><?php echo pxl_print_html($title); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
