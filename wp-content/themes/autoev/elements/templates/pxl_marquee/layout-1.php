<?php
    $html_id = pxl_get_element_id($settings);

    $label = $widget->parse_text_editor($settings['label']); 

    $animate = esc_attr($settings['pxl_animate']);
    $animate_delay = esc_attr($settings['pxl_animate_delay'].'ms');
    $is_items = isset($settings['items']) && !empty($settings['items']) && count($settings['items']) > 0;

    $i = !empty($settings['infinite']) ? 1 : 2;
    $infinite = ($i === 1) ? 'infinite' : '';
    $pause_on_hover = !empty($settings['pause_on_hover']) ? 'pause-on-hover' : '';
    $line = !empty($settings['label_line_show']) ? 'pxl-show-line' : '';
?>

<?php if ($is_items): ?>
    <div class="pxl-marquee pxl-marquee1 <?php echo esc_attr($settings['style']); ?>">
        <?php if (!empty($settings['label'])): ?>
            <h5 class="pxl-item--label <?php echo esc_attr($line . ' ' . $settings['label_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['label_animate_delay'].'ms'); ?>">
                <span class="pxl-label--text">
                    <?php pxl_print_html($label); ?>
                </span>
            </h5>
        <?php endif; ?>
        <div class="pxl-item--container">
            <div class="pxl-item--inner <?php echo esc_attr($infinite . ' ' . $settings['direction'] . ' ' . $pause_on_hover); ?>">
                <?php while ($i <= 2): ?>
                    <?php 
                        $class = ($i === 1) ? 'main' : 'duplicate';    
                    ?>
                    <div class="pxl-item--list <?php echo esc_attr($class); ?>">
                        <?php foreach ($settings['items'] as $key => $item): ?>
                            <div class="pxl-item--single <?php echo esc_attr($animate); ?>" data-wow-delay="<?php echo esc_attr($animate_delay); ?>">
                                <div class="pxl-single--inner">
                                    <?php 
                                        switch ($item['type']) :
                                            case 'icon':
                                                autoev_render_icon($item['pxl_icon']);
                                                break;

                                            case 'image':
                                                $image_id = $item['image']['id'] ?? '';
                                                $img_size = $item['img_size'] ?? 'full';
                                                $img = pxl_get_image_by_size([
                                                    'attach_id'  => $image_id,
                                                    'thumb_size' => $img_size,
                                                    'class' => 'no-lazyload',
                                                ]);
                                                $thumbnail = $img['thumbnail']; 
                                                $thumbnail_url = $img['url'];

                                                $img_popup = pxl_get_image_by_size([
                                                    'attach_id' => $image_id,
                                                    'thumb_size' => $img_size,
                                                    'class' => 'no-lazyload',
                                                ]);
                                                $thumbnail_popup = $img_popup['url'];
                                                
                                                if (!empty($thumbnail_popup)): ?>
                                                    <a href="<?php echo esc_url($thumbnail_popup); ?>" data-elementor-lightbox-slideshow="pxl-gallery-<?php echo esc_attr($html_id); ?>">
                                                        <?php 
                                                        if ($item['image_type'] === 'image') {
                                                            pxl_print_html($thumbnail);
                                                        } else {
                                                            pxl_print_html('<div class="pxl-item--bg" style="background-image: url(\'' . esc_url($thumbnail_url) . '\')"></div>');
                                                        } 
                                                        ?>
                                                    </a>
                                                <?php endif;
                                                break;

                                            case 'text':
                                                pxl_print_html('<div class="pxl-item--text">' . esc_html($item['text']) . '</div>');
                                                break;
                                        endswitch;
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php $i += 1; ?>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
<?php endif; ?> 
