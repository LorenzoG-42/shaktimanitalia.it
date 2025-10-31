<?php
if ( ! empty( $settings['button_link_lv1']['url'] ) ) {
    $widget->add_render_attribute( 'button_lv1', 'href', $settings['button_link_lv1']['url'] );

    if ( $settings['button_link_lv1']['is_external'] ) {
        $widget->add_render_attribute( 'button_lv1', 'target', '_blank' );
    }

    if ( $settings['button_link_lv2']['nofollow'] ) {
        $widget->add_render_attribute( 'button_lv2', 'rel', 'nofollow' );
    }
}
if ( ! empty( $settings['button_link_lv2']['url'] ) ) {
    $widget->add_render_attribute( 'button_lv2', 'href', $settings['button_link_lv2']['url'] );

    if ( $settings['button_link_lv2']['is_external'] ) {
        $widget->add_render_attribute( 'button_lv2', 'target', '_blank' );
    }

    if ( $settings['button_link_lv2']['nofollow'] ) {
        $widget->add_render_attribute( 'button_lv2', 'rel', 'nofollow' );
    }
}
$col_xs = $widget->get_setting('col_xs', '');
$col_sm = $widget->get_setting('col_sm', '');
$col_md = $widget->get_setting('col_md', '');
$col_lg = $widget->get_setting('col_lg', '');
$col_xl = $widget->get_setting('col_xl', '');

$col_xl = 12 / intval($col_xl);
$col_lg = 12 / intval($col_lg);
$col_md = 12 / intval($col_md);
$col_sm = 12 / intval($col_sm);
$col_xs = 12 / intval($col_xs);


$grid_sizer = "col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
if (!empty($grid_custom_columns)) {
    $col_xl_s = 12 / intval($grid_custom_columns[0]['col_xl_m']);
    $col_lg_s = 12 / intval($grid_custom_columns[0]['col_lg_m']);
    $col_md_s = 12 / intval($grid_custom_columns[0]['col_md_m']);
    $col_sm_s = 12 / intval($grid_custom_columns[0]['col_sm_m']);
    $col_xs_s = 12 / intval($grid_custom_columns[0]['col_xs_m']);
    $grid_sizer = "col-xl-{$col_xl_s} col-lg-{$col_lg_s} col-md-{$col_md_s} col-sm-{$col_sm_s} col-{$col_xs_s}";
}

$show_arrow = $widget->get_setting('show_arrow', '');
$pxl_icon = $widget->get_setting('pxl_icon', '');
$pxl_g_id = uniqid();
?>
<?php if (isset($settings['image_list']) && !empty($settings['image_list']) && count($settings['image_list'])) : 
    $image_size_popup = !empty($img_size_popup) ? $img_size_popup : '366x366'; ?>
    <div class="pxl-grid pxl-team-grid pxl-image-grid5" data-layout="fitRows">
        <div class="pxl-grid-inner row" <?php echo esc_attr($settings['pxl_animate']); ?>>
            <?php foreach ($settings['image_list'] as $key => $value) :
                $sub_title = isset($value['sub_title']) ? $value['sub_title'] : '';
                $title = isset($value['title']) ? $value['title'] : '';
                $desc = isset($value['desc']) ? $value['desc'] : '';
                $image = isset($value['image']) ? $value['image'] : '';
                $img_size = isset($value['img_size']) ? $value['img_size'] : '';
                $image_size = !empty($img_size) ? $img_size : '366x366';
                $button_text = isset($value['button_text']) ? $value['button_text'] : '';
                $item_class = $item_class;
                $link = isset($value['link']) ? $value['link'] : '';
                $link_key = $widget->get_repeater_setting_key('title', 'value', $key);
                if (!empty($link['url'])) {
                    $widget->add_render_attribute($link_key, 'href', $link['url']);

                    if ($link['is_external']) {
                        $widget->add_render_attribute($link_key, 'target', '_blank');
                    }

                    if ($link['nofollow']) {
                        $widget->add_render_attribute($link_key, 'rel', 'nofollow');
                    }
                }
                $link_attributes = $widget->get_render_attribute_string($link_key);
                if (!empty($grid_custom_columns[$key])) {
                    $col_xl = 12 / intval($grid_custom_columns[$key]['col_xl_m']);
                    $col_lg = 12 / intval($grid_custom_columns[$key]['col_lg_m']);
                    $col_md = 12 / intval($grid_custom_columns[$key]['col_md_m']);
                    $col_sm = 12 / intval($grid_custom_columns[$key]['col_sm_m']);
                    $col_xs = 12 / intval($grid_custom_columns[$key]['col_xs_m']);
                    $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";

                    if (!empty($grid_custom_columns[$key]['img_size_m']))
                        $image_size = $grid_custom_columns[$key]['img_size_m'];
                }
            ?>
                <div class="<?php echo esc_attr($item_class); ?>">
                    <div class="pxl-item--inner ">
                        <?php if (!empty($image['id'])) : ?>
                            <div class="pxl-box">
                            <div class="pxl-item--image wow">
                                <a class="pxl-wrap-link" <?php echo implode(' ', [$link_attributes]); ?>>
                                    <?php
                                    $img  = pxl_get_image_by_size(array(
                                        'attach_id'  => $image['id'],
                                        'thumb_size' => $image_size,
                                        'class' => 'no-lazyload',
                                    ));
                                   
                                    $thumbnail = $img['thumbnail'];
                                     ?>
                                      
                                    <?php echo wp_kses_post($thumbnail); ?>
                                    <div class="pxl-image-overlay"></div>
                                    <span class="pxl-live-preview">
                                            <?php echo pxl_print_html($button_text); ?>
                                        </span>
                                </a>
                            </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="pxl-wrap-content">
                            <?php if (!empty($sub_title)) : ?>
                                <span class="pxl-sub--title"><?php echo pxl_print_html($sub_title); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($title)) : ?>
                                <h5 class="pxl-item--title"><?php echo pxl_print_html($title); ?></h5>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ($show_arrow == 'true') : ?>
                <div class="col-lg-12 pxl-item--arrow">
                    <?php \Elementor\Icons_Manager::render_icon($pxl_icon, ['aria-hidden' => 'true']);
                    \Elementor\Icons_Manager::render_icon($pxl_icon, ['aria-hidden' => 'true']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>