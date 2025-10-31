<?php if(isset($settings['lists']) && !empty($settings['lists']) && count($settings['lists'])): 
    $is_new = \Elementor\Icons_Manager::is_migration_allowed(); ?>
    <div class="pxl-list-box pxl-list-box1 <?php echo esc_attr($settings['style']); ?>">
        <?php foreach ($settings['lists'] as $key => $value): 
            $icon_image = isset($value['icon_image']) ? $value['icon_image'] : '';
            $icon_type = isset($value['icon_type']) ? $value['icon_type'] : '';
            $icon_key = $widget->get_repeater_setting_key( 'pxl_icon', 'icons', $key );
            $widget->add_render_attribute( $icon_key, [
                'class' => $value['pxl_icon'],
                'aria-hidden' => 'true',
            ] );
            $link_key = $widget->get_repeater_setting_key( 'link', 'value', $key );
            if ( ! empty( $value['link']['url'] ) ) {
                $widget->add_render_attribute( $link_key, 'href', $value['link']['url'] );

                if ( $value['link']['is_external'] ) {
                    $widget->add_render_attribute( $link_key, 'target', '_blank' );
                }

                if ( $value['link']['nofollow'] ) {
                    $widget->add_render_attribute( $link_key, 'rel', 'nofollow' );
                }
            }
            $link_attributes = $widget->get_render_attribute_string( $link_key ); ?>
            <div class="pxl--item <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
                <?php if ( $icon_type == 'icon' && ! empty( $value['pxl_icon'] ) ) : ?>
                    <div class="pxl-item--icon pxl-mr-10">
                        <?php if ( $is_new ):
                            \Elementor\Icons_Manager::render_icon( $value['pxl_icon'], [ 'aria-hidden' => 'true' ] );
                        elseif(!empty($value['pxl_icon'])): ?>
                            <i class="<?php echo esc_attr( $value['pxl_icon'] ); ?>" aria-hidden="true"></i>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ( $icon_type == 'image' && !empty($icon_image['id']) ) : 
                    $img_icon  = pxl_get_image_by_size( array(
                        'attach_id'  => $icon_image['id'],
                        'thumb_size' => 'full',
                    ) );
                    $thumbnail_icon    = $img_icon['thumbnail']; ?>
                    <div class="pxl-item--icon pxl-mr-30">
                        <?php echo pxl_print_html($thumbnail_icon); ?>
                    </div>
                <?php endif; ?>
                <div class="pxl-item-content">
                    <h5 class="pxl-item--text"><?php echo pxl_print_html($value['text'])?></h5>
                    <div class="pxl-sub-text">
                        <a class="pxl-btn" <?php echo implode( ' ', [ $link_attributes ] ); ?>>
                            <?php echo pxl_print_html($value['sub_text'])?>
                        </a>      
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>