<?php
$active = intval($settings['active']);
$accordion = $widget->get_settings('accordion');
$wg_id = pxl_get_element_id($settings);
if(!empty($accordion)) : ?>
    <div class="pxl-accordion pxl-accordion1 <?php echo esc_attr($settings['style'])?>">
        <?php foreach ($accordion as $key => $value):
            $is_active = ($key + 1) == $active;
            $pxl_id = isset($value['_id']) ? $value['_id'] : '';
            $title = isset($value['title']) ? $value['title'] : '';
            $desc = isset($value['desc']) ? $value['desc'] : '';
            ?>
            <div class="pxl--item <?php echo esc_attr($is_active ? 'active' : '') . ' ' . esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                <<?php pxl_print_html($settings['title_tag']); ?> class="pxl-item--title" data-target="<?php echo esc_attr('#'.$wg_id.'-'.$pxl_id); ?>">
                    <div class="pxl-content">
                        <span class="pxl-title--text"><?php echo wp_kses_post($title); ?></span>
                    </div>
                    <div class="pxl-icon">
                         <i aria-hidden="true"class="fas fa-angle-down"></i>
                    </div>
                </<?php pxl_print_html($settings['title_tag']); ?>>
                <div id="<?php echo esc_attr($wg_id.'-'.$pxl_id); ?>" class="pxl-item--content" <?php if($is_active){ ?>style="display: block;"<?php } ?>><?php echo wp_kses_post(nl2br($desc)); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>