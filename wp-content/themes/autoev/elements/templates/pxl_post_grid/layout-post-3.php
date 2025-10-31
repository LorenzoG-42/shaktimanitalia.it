<?php
$html_id = pxl_get_element_id($settings);
$tax = ['category'];
$select_post_by = $widget->get_setting('select_post_by', '');
$source = $post_ids = [];
if($select_post_by === 'post_selected'){
    $post_ids = $widget->get_setting('source_'.$settings['post_type'].'_post_ids', '');
}else{
    $source  = $widget->get_setting('source_'.$settings['post_type'], '');
}
$orderby = $widget->get_setting('orderby', 'date');
$order = $widget->get_setting('order', 'desc');
$limit = $widget->get_setting('limit', 6);
extract(pxl_get_posts_of_grid(
    'post', 
    ['source' => $source, 'orderby' => $orderby, 'order' => $order, 'limit' => $limit, 'post_ids' => $post_ids],
    $tax
));
$filter_default_title = $widget->get_setting('filter_default_title', 'All');

if($settings['col_xl'] == '5') {
    $col_xl = '20-pxl';
} elseif($settings['col_xl'] == 'col-40') {
    $col_xl = '40-pxl';
} else {
   $col_xl = 12 / intval($widget->get_setting('col_xl', 4));
}
if($settings['col_lg'] == '5') {
    $col_lg = '20-pxl';
} elseif($settings['col_lg'] == 'col-40') {
    $col_lg = '40-pxl';
} else {
    $col_lg = 12 / intval($widget->get_setting('col_lg', 4));
}

$col_md = 12 / intval($widget->get_setting('col_md', 3));
$col_sm = 12 / intval($widget->get_setting('col_sm', 2));
$col_xs = 12 / intval($widget->get_setting('col_xs', 1));

$grid_sizer = "col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";

$grid_class = 'pxl-grid-inner row';

$filter = $widget->get_setting('filter', 'false');
$filter_alignment = $widget->get_setting('filter_alignment', 'center');
$pagination_type = $widget->get_setting('pagination_type', 'pagination');

$post_type = $widget->get_setting('post_type','post');
$layout = $widget->get_setting('layout_'.$post_type, 'post-1');

$num_words= $widget->get_setting('num_words');
$show_date = $widget->get_setting('show_date');
$show_author = $widget->get_setting('show_author');
$show_tag = $widget->get_setting('show_tag');
$show_excerpt = $widget->get_setting('show_excerpt');
$img_size = $widget->get_setting('img_size');
$grid_masonry = $widget->get_setting('grid_masonry');
$pxl_animate = $widget->get_setting('pxl_animate');
$load_more = array(
    'num_words'        =>$num_words,
    'tax'                 => $tax,
    'post_type'       => $post_type,   
    'layout'          => $layout,
    'startPage'       => $paged,
    'maxPages'        => $max,
    'total'           => $total,
    'perpage'         => $limit,
    'nextLink'        => $next_link,
    'source'          => $source,
    'orderby'         => $orderby,
    'order'           => $order,
    'limit'           => $limit,
    'post_ids'        => $post_ids,
    'col_xl'          => $col_xl,
    'col_lg'          => $col_lg,
    'col_md'          => $col_md,
    'col_sm'          => $col_sm,
    'col_xs'          => $col_xs,
    'pagination_type' => $pagination_type,
    'show_date'       => $show_date,
    'show_tag'        => $show_tag,
    'show_excerpt'    =>$show_excerpt,
    'show_author'     =>$show_author,
    'img_size'        => $img_size,
    'grid_masonry'    => $grid_masonry,
    'pxl_animate'     => $pxl_animate,
);
?>

<div id="<?php echo esc_attr($html_id) ?>" class="pxl-grid pxl-blog-grid pxl-blog-grid-layout3 pxl-blog-style1" data-layout="masonry" data-start-page="<?php echo esc_attr($paged); ?>" data-max-pages="<?php echo esc_attr($max); ?>" data-total="<?php echo esc_attr($total); ?>" data-perpage="<?php echo esc_attr($limit); ?>" data-next-link="<?php echo esc_attr($next_link); ?>">
    <?php if ($select_post_by == 'term_selected' && $filter == "true"): ?>
        <div class="pxl-grid-filter pxl-grid-filter1">
            <span class="filter-item active" data-filter="*"><?php echo esc_html($filter_default_title); ?></span>
            <?php foreach ($categories as $category): ?>
                <?php $category_arr = explode('|', $category); ?>
                <?php $tax[] = $category_arr[1]; ?>
                <?php $term = get_term_by('slug',$category_arr[0], $category_arr[1]); ?>
                <span class="filter-item" data-filter="<?php echo esc_attr('.' . $term->slug); ?>">
                    <?php echo esc_html($term->name); ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="<?php echo esc_attr($grid_class); ?>" data-gutter="15">
        <?php $load_more['tax'] = $tax; autoev_get_post_grid($posts, $load_more); ?>
        <div class="grid-sizer <?php echo esc_attr($grid_sizer); ?>"></div>
    </div>
    <?php   

    if ($pagination_type == 'pagination') { ?>
        <div class="pxl-grid-pagination" data-loadmore="<?php echo esc_attr(json_encode($load_more)); ?>" data-query="<?php echo esc_attr(json_encode($args)); ?>">
            <?php autoev()->page->get_pagination($query, true); ?>
        </div>
    <?php } ?>
    <?php if ( $pagination_type == 'loadmore') { ?>
        <div class="pxl-load-more pxl-load-more-style1" data-loadmore="<?php echo esc_attr(json_encode($load_more)); ?>">
            <span class="btn">
                <?php echo esc_html__('Load More', 'autoev') ?>
                <span class="pxl-load-icon"></span>
            </span>
        </div>
    <?php } ?>
</div>