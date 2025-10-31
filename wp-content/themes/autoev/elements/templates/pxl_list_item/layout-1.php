<?php
$is_new = \Elementor\Icons_Manager::is_migration_allowed();

if (isset($settings['icons']) && !empty($settings['icons'])): ?>
    <div class="pxl-list-item pxl-list-item1 <?php echo esc_attr($settings['style']); ?> ms">
        <?php foreach ($settings['icons'] as $key => $value):
            $label = $value['label'] ?? '';
            $label_position = $value['label_position'] ?? '';
            $icon_key = $widget->get_repeater_setting_key('pxl_icon', 'icons', $key);
            $widget->add_render_attribute($icon_key, [
                'class' => $value['pxl_icon'],
                'aria-hidden' => 'true',
            ]);

            $link_key = $widget->get_repeater_setting_key('item_link', 'value', $key);
            if (!empty($value['item_link']['url'])) {
                $widget->add_render_attribute($link_key, 'href', $value['item_link']['url']);

                if (!empty($value['item_link']['is_external'])) {
                    $widget->add_render_attribute($link_key, 'target', '_blank');
                }
                if (!empty($value['item_link']['nofollow'])) {
                    $widget->add_render_attribute($link_key, 'rel', 'nofollow');
                }
            }

            if (!empty($value['pxl_icon'])): 
                $link_attributes = $widget->get_render_attribute_string($link_key); ?>
                <div class="pxl-item-content wow elementor-repeater-item-<?php echo esc_attr($value['_id']); ?> <?php echo esc_attr($label_position) . ' ' . esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>">
                    <div class="pxl-content">
                    <div class="pxl-item" >
                        <div class="pxl-icon-content">
                        <?php if ($is_new): 
                            \Elementor\Icons_Manager::render_icon($value['pxl_icon'], ['aria-hidden' => 'true']);
                            else: ?>
                                
                                    <i class="<?php echo esc_attr($value['pxl_icon']); ?>" aria-hidden="true"></i>
                                 
                            <?php endif; ?>
                            <?php if (!empty($label)): ?>
                                <span><?php echo esc_html($label); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="pxl-description">
                            <?php if (!empty($value['title'])): ?>
                                <div class="pxl-title"><?php echo pxl_print_html($value['title']); ?><a <?php echo implode( ' ', [ $link_attributes ] ); ?>></a></div>
                            <?php endif; ?>
                            <?php if (!empty($value['sub_title'])): ?>
                                <div class="pxl-sub--title"><?php echo pxl_print_html($value['sub_title']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="pxl-item-right"> 
                    <a <?php echo implode( ' ', [ $link_attributes ] ); ?>>                   
                            <?php if (!empty($value['text_button'])): ?>
                                <?php echo pxl_print_html($value['text_button']); ?>
                            <?php endif; ?>
                            <i class="icofont icofont-arrow-right"></i>
                            </a>    
                        </div>
                            </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
