<?php
$is_new = \Elementor\Icons_Manager::is_migration_allowed();

if (!empty($settings['pxl_icon'])): ?>
    <div class="pxl-icon-box <?php echo esc_attr($settings['style']); ?> ">
        <div class="pxl-item-content wow <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?> ms">
                <div class="pxl-item--content">
                    <div class="pxl-item">
                        <?php if ($is_new): 
                            \Elementor\Icons_Manager::render_icon($settings['pxl_icon'], ['aria-hidden' => 'true']);
                        else: ?>    
                            <i class="<?php echo esc_attr($settings['pxl_icon']); ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                        <div class="pxl-number">
                    <?php if (!empty($settings['number'])): ?>
                        <?php echo esc_html($settings['number']); ?>
                    <?php endif; ?>
                    
                </div>
                    </div>
                    <div class="pxl-description">
                        <?php if (!empty($settings['title'])): ?>
                            <div class="pxl-title"><?php echo esc_html($settings['title']); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($settings['sub_title'])): ?>
                            <div class="pxl-sub--title"><?php echo esc_html($settings['sub_title']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
         
        </div>
    </div>
<?php endif; ?>
