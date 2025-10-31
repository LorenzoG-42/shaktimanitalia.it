<?php if (isset($settings['history']) && !empty($settings['history']) && count($settings['history'])): 
    ?>
    <div class="pxl-history pxl-history1">
        <div class="pxl-slick-slider " <?php pxl_print_html($widget->get_render_attribute_string('carousel')); ?>>
            <?php $i = 0; foreach ($settings['history'] as $key => $value):
                $i++;
                $oddEven = ($i % 2) ? 'odd' : 'even';
                $title = isset($value['title']) ? $value['title'] : '';
                $year = isset($value['year']) ? $value['year'] : '';
                $desc = isset($value['desc']) ? $value['desc'] : '';
                $image = isset($value['image']) ? $value['image'] : '';
                $image2 = isset($value['image2']) ? $value['image2'] : '';
                $image3 = isset($value['image3']) ? $value['image3'] : '';
                $img_size = isset($settings['img_size']) ? $settings['img_size'] : '409x231';
            ?>
                <div class="slick slick-slide">
                    <div class="pxl-item--inner pxl-item--<?php echo esc_attr($oddEven); ?>">
                        <div class="pxl-item--holder ">
                            <div class="pxl-item--meta ">
                                <div <?php pxl_print_html($widget->get_render_attribute_string('pxl_history_wrap')); ?>>
                                    
                                    <div class="pxl-meta--inner pxl-swiper-history <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                                       
                                        <?php if (!empty($image['id'])): 
                                            $img = pxl_get_image_by_size(array(
                                                'attach_id'  => $image['id'],
                                                'thumb_size' => $img_size,
                                                'class' => 'no-lazyload',
                                            ));
                                            $thumbnail = $img['thumbnail']; ?>
                           
                                            <?php echo wp_kses_post($thumbnail); ?> 
                                        <?php endif; ?>

                                        <?php if (!empty($image2['id'])): 
                                            $img2 = pxl_get_image_by_size(array(
                                                'attach_id'  => $image2['id'],
                                                'thumb_size' => $img_size,
                                                'class' => 'no-lazyload',
                                            ));
                                            $thumbnail = $img2['thumbnail']; ?>
                                            <?php echo wp_kses_post($thumbnail); ?> 
                                        <?php endif; ?>

                                        <?php if (!empty($image3['id'])): 
                                            $img3 = pxl_get_image_by_size(array(
                                                'attach_id'  => $image3['id'],
                                                'thumb_size' => $img_size,
                                                'class' => 'no-lazyload',
                                            ));
                                            $thumbnail = $img3['thumbnail']; ?>                                                         
                                                <?php echo wp_kses_post($thumbnail); ?>       
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="pxl-line-hs"></div>
                            <div class="pxl-information">        
                                <div class="pxl-item--year pxl-empty">
                                    <div class="pxl-dot"></div>
                                    <div class="pxl-line"></div>
                                    <div class="pxl-year"><?php echo pxl_print_html($year); ?></div>
                                </div>
                                <h5 class="pxl-item--title">
                                    <?php echo pxl_print_html($title); ?>
                                    <div class="pxl-item--desc"><?php echo pxl_print_html($desc); ?></div>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
