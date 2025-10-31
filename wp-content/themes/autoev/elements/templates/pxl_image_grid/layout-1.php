<?php
$col_xs = $widget->get_setting('col_xs', '');
$col_sm = $widget->get_setting('col_sm', '');
$col_md = $widget->get_setting('col_md', '');
$col_lg = $widget->get_setting('col_lg', '');
$col_xl = $widget->get_setting('col_xl', '');

$col_xl = 12 / intval($col_xl ?: 1);
$col_lg = 12 / intval($col_lg ?: 1);
$col_md = 12 / intval($col_md ?: 1);
$col_sm = 12 / intval($col_sm ?: 1);
$col_xs = 12 / intval($col_xs ?: 1);

$grid_sizer = "col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
$grid_custom_columns = $widget->get_setting('grid_masonry', []);
$msclass = is_admin() ? 'pxl-grid-masonry-adm' : 'pxl-grid-masonry';

if (!empty($grid_custom_columns)) {
    $col_xl_s = 12 / intval($grid_custom_columns[0]['col_xl_m'] ?: 1);
    $col_lg_s = 12 / intval($grid_custom_columns[0]['col_lg_m'] ?: 1);
    $col_md_s = 12 / intval($grid_custom_columns[0]['col_md_m'] ?: 1);
    $col_sm_s = 12 / intval($grid_custom_columns[0]['col_sm_m'] ?: 1);
    $col_xs_s = 12 / intval($grid_custom_columns[0]['col_xs_m'] ?: 1);
    $grid_sizer = "col-xl-{$col_xl_s} col-lg-{$col_lg_s} col-md-{$col_md_s} col-sm-{$col_sm_s} col-{$col_xs_s}";
}

$show_arrow = $widget->get_setting('show_arrow', '');
$pxl_icon = $widget->get_setting('pxl_icon', '');
$pxl_g_id = uniqid();

?>

<?php if (isset($settings['image_list']) && !empty($settings['image_list']) && count($settings['image_list'])) :
    $image_size_popup = !empty($img_size_popup) ? $img_size_popup : 'full'; ?>
    <div class=" pxl-grid pxl-image-grid pxl-image-grid1 wow <?php echo esc_attr($settings['pxl_animate']); ?>" 
         data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms" data-layout="fitRows">
        <div class="pxl-grid-inner <?php echo esc_attr($msclass); ?> row" data-gutter="15">
            <?php foreach ($settings['image_list'] as $key => $value) :
                $sub_title = isset($value['sub_title']) ? $value['sub_title'] : '';
                $title = isset($value['title']) ? $value['title'] : '';
                $desc = isset($value['desc']) ? $value['desc'] : '';
                $image = isset($value['image']) ? $value['image'] : '';
                $img_size = isset($value['img_size']) ? $value['img_size'] : '';
                $image_size = !empty($img_size) ? $img_size : '800x451';
                $button_text = isset($value['button_text']) ? $value['button_text'] : '';
                $link = isset($value['link']) ? $value['link'] : '';
                $link_key = $widget->get_repeater_setting_key('title', 'value', $key);

                // Handle link attributes
                if (!empty($link['url'])) {
                    $widget->add_render_attribute($link_key, 'href', $link['url']);

                    if (!empty($link['is_external'])) {
                        $widget->add_render_attribute($link_key, 'target', '_blank');
                    }

                    if (!empty($link['nofollow'])) {
                        $widget->add_render_attribute($link_key, 'rel', 'nofollow');
                    }
                }
                $link_attributes = $widget->get_render_attribute_string($link_key);

                // Handle custom columns for each item
                if (!empty($grid_custom_columns[$key])) {
                    $col_xl = 12 / intval($grid_custom_columns[$key]['col_xl_m'] ?: 1);
                    $col_lg = 12 / intval($grid_custom_columns[$key]['col_lg_m'] ?: 1);
                    $col_md = 12 / intval($grid_custom_columns[$key]['col_md_m'] ?: 1);
                    $col_sm = 12 / intval($grid_custom_columns[$key]['col_sm_m'] ?: 1);
                    $col_xs = 12 / intval($grid_custom_columns[$key]['col_xs_m'] ?: 1);
                    $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";

                    if (!empty($grid_custom_columns[$key]['img_size_m'])) {
                        $image_size = $grid_custom_columns[$key]['img_size_m'];
                    }
                }
            ?>
                <div class="<?php echo esc_attr($item_class); ?>">
                    <div class="pxl-item--inner <?php echo esc_attr($settings['pxl_animate']); ?>">
                        <?php if (!empty($image['id'])) :
                            $img = pxl_get_image_by_size([
                                'attach_id'  => $image['id'],
                                'thumb_size' => $image_size,
                                'class' => 'no-lazyload',
                            ]);
                            $img_popup = pxl_get_image_by_size([
                                'attach_id' => $image['id'],
                                'thumb_size' => $image_size,
                                'class' => 'no-lazyload',
                            ]);
                            $thumbnail_popup = $img['url'];
                            $thumbnail = $img['thumbnail'];
                        ?>
                            <div class="pxl-box">
                            <a class='pxl-link' <?php echo esc_attr($link_attributes); ?>></a>
                                <div class="pxl-item--image">
                                    <a  href="<?php echo esc_url($thumbnail_popup); ?>" data-elementor-lightbox-slideshow=" pxl-gallery-<?php echo esc_attr($pxl_g_id); ?>">
                                        <?php echo wp_kses_post($thumbnail); ?>
                                        <span class="pxl-image-overlay"></span>
                                    </a>
                                        <span class="pxl-live-preview">
                                            <?php echo pxl_print_html($button_text); ?>
                                        </span>
                                    
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
            
            <?php if ($show_arrow !== 'false'): ?>
            <div class="pxl-swiper-arrow-wrap">
                <div class="pxl-swiper-arrow pxl-swiper-arrow-prev"><i class="fas fa-chevron-left rtl-icon"></i></div>
                <div class="pxl-swiper-arrow pxl-swiper-arrow-next"><i class="fas fa-chevron-right rtl-icon"></i></div>
            </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
