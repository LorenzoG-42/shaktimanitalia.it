<?php 
 
if(!function_exists('autoev_get_post_grid')){
    function autoev_get_post_grid($posts = [], $settings = []){ 
        if (empty($posts) || !is_array($posts) || empty($settings) || !is_array($settings)) {
            return false;
        }
        switch ($settings['layout']) {
            case 'post-1':
                autoev_get_post_grid_layout1($posts, $settings);
                break;

            case 'post-2':
                autoev_get_post_grid_layout2($posts, $settings);
                break;

            case 'post-3':
                autoev_get_post_grid_layout3($posts, $settings);
                break;

            case 'post-4':
                autoev_get_post_grid_layout4($posts, $settings);
                break;

            case 'post-5':
                autoev_get_post_grid_layout5($posts, $settings);
                break;    

            case 'portfolio-1':
                autoev_get_portfolio_grid_layout1($posts, $settings);
                break;

            case 'service-1':
                autoev_get_service_grid_layout1($posts, $settings);
                break;

            case 'service-2':
                autoev_get_service_grid_layout2($posts, $settings);
                break;

            case 'service-3':
                autoev_get_service_grid_layout3($posts, $settings);
                break;

            default:
                return false;
                break;
        }
    }
}
// Start Post Grid
//--------------------------------------------------
function autoev_get_post_grid_layout1($posts = [], $settings = []){ 
    extract($settings);
    
    $images_size = !empty($img_size) ? $img_size : '638x638';
    $post_category = autoev()->get_theme_opt( 'post_category', true );

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == '5') {
                    $col_xl_m = '20-pxl';
                } elseif($grid_masonry[$key]['col_xl_m'] == 'col-40') {
                    $col_xl_m = '40-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == '5') {
                    $col_lg_m = '20-pxl';
                } elseif($grid_masonry[$key]['col_lg_m'] == 'col-40') {
                    $col_lg_m = '40-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";
                
                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $img_id = get_post_thumbnail_id($post->ID);
            if($img_id) {
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $images_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
            } else {
                $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
            }
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner <?php echo esc_attr($settingl); ?> <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                    <div class="pxl-item-thumbnail">
                        <div class="pxl-item--featured">
                            <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                            <div class="pxl-gradiend"></div>
                        </div>
                        <?php if ( $show_tag == 'true' ) : ?>
                            <div class="pxl-item--tag">
                                <?php the_terms( $post->ID, 'post_tag', '', ' ' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="pxl-item--holder">              
                        <div class="pxl-item--category wow fadeInUp"><?php the_terms( $post->ID, 'category', '', ' ' ); ?></div>
                        <h4 class="pxl-item--title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a></h4>
                        <?php if($show_excerpt == 'true'): ?>
                            <div class="pxl-item--content pxl-sz-content">
                                <?php echo wp_trim_words( $post->post_excerpt, $num_words, $more = null ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="pxl-item--meta">                           
                            <?php if($show_author == 'true'): ?>
                                <?php $author = get_user_by('id', $post->post_author);
                                $author_avatar = get_avatar( $post->post_author, 60, '', $author->display_name, array( 'class' => '' ) );
                                $user_position = get_user_meta($post->post_author, 'user_position', true); ?>
                                <div class="pxl-post--author pxl-flex pxl-mr-20">
                                    <div class="pxl-author--img pxl-mr-10">
                                        <?php pxl_print_html($author_avatar); ?>
                                    </div>
                                    <div class="pxl-author-meta">
                                        <a href="<?php echo esc_url(get_author_posts_url($post->post_author, $author->user_nicename)); ?>"><?php echo esc_html($author->display_name); ?></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="pxl-date">
                                <?php if($show_date == 'true'): ?>
                                    <div class="pxl-item--date pxl-flex">
                                        <i class="fal flaticon-calendar-1 pxl-mr-10"></i>
                                        <span>
                                            <?php $date_formart = get_option('date_format'); echo get_the_date($date_formart, $post->ID); ?>
                                        </span>       
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    endif;
}

function autoev_get_post_grid_layout2($posts = [], $settings = []) { 
    extract($settings); 
    
    $images_size = !empty($img_size) ? $img_size : '372x372';
    $post_category = autoev()->get_theme_opt('post_category', true);

    if (is_array($posts) && !empty($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if (isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                $col_xl_m = isset($grid_masonry[$key]['col_xl_m']) ? $grid_masonry[$key]['col_xl_m'] : 12;
                $col_lg_m = isset($grid_masonry[$key]['col_lg_m']) ? $grid_masonry[$key]['col_lg_m'] : 12;
                $col_md_m = isset($grid_masonry[$key]['col_md_m']) ? $grid_masonry[$key]['col_md_m'] : 12;
                $col_sm_m = isset($grid_masonry[$key]['col_sm_m']) ? $grid_masonry[$key]['col_sm_m'] : 12;
                $col_xs_m = isset($grid_masonry[$key]['col_xs_m']) ? $grid_masonry[$key]['col_xs_m'] : 12;
                
                $item_class = "pxl-grid-item col-xl-" . (12 / $col_xl_m) . " col-lg-" . (12 / $col_lg_m) . " col-md-" . (12 / $col_md_m) . " col-sm-" . (12 / $col_sm_m) . " col-" . (12 / $col_xs_m);

                if (!empty($grid_masonry[$key]['img_size_m'])) {
                    $images_size = $grid_masonry[$key]['img_size_m'];
                }
            }

            $filter_class = !empty($tax) ? pxl_get_term_of_post_to_class($post->ID, array_unique($tax)) : '';
            $img_id = get_post_thumbnail_id($post->ID);
            $thumbnail = '';

            if ($img_id) {
                $img = pxl_get_image_by_size([
                    'attach_id' => $img_id,
                    'thumb_size' => $images_size,
                    'class' => 'no-lazyload',
                ]);
                $thumbnail = $img['thumbnail'];
            } else {
                $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
            }
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner <?php echo esc_attr($style); ?> <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                        <div class="pxl-item-thumbnail">
                            <div class="pxl-item--featured">
                                <div class="pxl-date">
                                    <?php if ($show_date == 'true'): ?>
                                        <div class="pxl-item--day"><?php echo get_the_date('d'); ?></div>
                                        <div class="pxl-item--month"><?php echo get_the_date('M'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="pxl-item--category wow fadeInUp"><?php the_terms($post->ID, 'category', '', ' '); ?></div>
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                                <div class="pxl-gradient"></div>
                            </div>
                            <?php if ($show_tag == 'true'): ?>
                                <div class="pxl-item--tag"><?php the_terms($post->ID, 'post_tag', '', ' '); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="pxl-item--holder">
                        <div class="pxl-item--meta">
                            <?php if ($show_author == 'true'): ?>
                                <?php 
                                $author = get_user_by('id', $post->post_author);
                                if ($author) {
                                    $author_avatar = get_avatar($post->post_author, 60, '', $author->display_name, ['class' => '']);
                                ?>
                                    <div class="pxl-post--author pxl-flex pxl-mr-16">
                                        <div class="pxl-author--img pxl-mr-10"><?php pxl_print_html($author_avatar); ?></div>
                                        <div class="pxl-author-meta">
                                            <a href="<?php echo esc_url(get_author_posts_url($post->post_author, $author->user_nicename)); ?>">
                                                <?php echo esc_html($author->display_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php endif; ?>
                            <div class="pxl-date--full">
                                <?php if ($show_date == 'true'): ?>
                                    <div class="pxl-item-date pxl-flex">
                                        <i class="fal flaticon-calendar-1 pxl-mr-8"></i>
                                        <span><?php echo get_the_date(get_option('date_format'), $post->ID); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h4 class="pxl-item--title">
                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a>
                        </h4>
                        <div class="pxl-item--meta--sub">
                            <?php if ($show_author == 'true'): ?>
                                <?php 
                                $author = get_user_by('id', $post->post_author);
                                if ($author) {
                                    $author_avatar = get_avatar($post->post_author, 60, '', $author->display_name, ['class' => '']);
                                ?>
                                    <div class="pxl-post--author pxl-flex pxl-mr-16">
                                        <div class="pxl-author--img pxl-mr-10"><?php pxl_print_html($author_avatar); ?></div>
                                        <div class="pxl-author-meta">
                                            <a href="<?php echo esc_url(get_author_posts_url($post->post_author, $author->user_nicename)); ?>">
                                                <?php echo esc_html($author->display_name); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php endif; ?>
                            <div class="pxl-date--full">
                                <?php if ($show_date == 'true'): ?>
                                    <div class="pxl-item-date pxl-flex">
                                        <i class="fal flaticon-calendar-1 pxl-mr-8"></i>
                                        <span><?php echo get_the_date(get_option('date_format'), $post->ID); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($show_excerpt == 'true'): ?>
                            <div class="pxl-item--content pxl-sz-content">
                                <?php echo wp_trim_words($post->post_excerpt, $num_words); ?>
                            </div>
                        <?php endif; ?>
                        <h4 class="pxl-btn--text">
                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">Continue Reading</a>
                            <i aria-hidden="true" class="fas fa-angle-right"></i>
                        </h4>
                    </div>
                </div>             
            </div>
            <?php
        endforeach;
    endif;
}


function autoev_get_post_grid_layout3($posts = [], $settings = []){ 
    extract($settings);
    $post_category = autoev()->get_theme_opt( 'post_category', true );
    
    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if($grid_masonry[$key]['col_xl_m'] == '5') {
                    $col_xl_m = '20-pxl';
                } elseif($grid_masonry[$key]['col_xl_m'] == 'col-40') {
                    $col_xl_m = '40-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if($grid_masonry[$key]['col_lg_m'] == '5') {
                    $col_lg_m = '20-pxl';
                } elseif($grid_masonry[$key]['col_lg_m'] == 'col-40') {
                    $col_lg_m = '40-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";               
            } 
            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>                        
                        <?php if ( $show_tag == 'true' ) : ?>
                            <div class="pxl-item--tag">
                                <?php the_terms( $post->ID, 'post_tag', '', ' ' ); ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="pxl-item--holder">
                    
                        <div class="pxl-item--meta">                           
                            <?php if($show_author == 'true'): ?>
                                <?php $author = get_user_by('id', $post->post_author);
                                $author_avatar = get_avatar( $post->post_author, 60, '', $author->display_name, array( 'class' => '' ) );
                                $user_position = get_user_meta($post->post_author, 'user_position', true); ?>
                                <div class="pxl-post--author pxl-flex pxl-mr-16">
                                    <div class="pxl-author--img pxl-mr-10">
                                        <?php pxl_print_html($author_avatar); ?>
                                    </div>
                                    <div class="pxl-author-meta">
                                        <a href="<?php echo esc_url(get_author_posts_url($post->post_author, $author->user_nicename)); ?>"><?php echo esc_html($author->display_name); ?></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="pxl-date--full">
                                <?php if($show_date == 'true'): ?>
                                    <div class="pxl-item-date pxl-flex">
                                        <i class="fal flaticon-calendar-1 pxl-mr-8"></i>
                                        <span>
                                            <?php $date_formart = get_option('date_format'); echo get_the_date($date_formart, $post->ID); ?>
                                        </span>       
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>              
                        <h4 class="pxl-item--title"><a href="<?php echo esc_url(get_permalink( $post->ID )); ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a></h4>                        
                        
                    </div>
                    <?php if($show_excerpt == 'true'): ?>
                            <div class="pxl-item--content pxl-sz-content">
                                <?php echo wp_trim_words( $post->post_excerpt, $num_words, $more = null ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
            </div>
        <?php
        endforeach;
    endif;
}
function autoev_get_post_grid_layout4($posts = [], $settings = []) { 
    extract($settings); 
    $images_size = !empty($img_size) ? $img_size : '372x372';
    $post_category = autoev()->get_theme_opt('post_category', true);
    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";

            if (isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                if ($grid_masonry[$key]['col_xl_m'] == '5') {
                    $col_xl_m = '20-pxl';
                } elseif ($grid_masonry[$key]['col_xl_m'] == 'col-40') {
                    $col_xl_m = '40-pxl';
                } else {
                    $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                }
                if ($grid_masonry[$key]['col_lg_m'] == '5') {
                    $col_lg_m = '20-pxl';
                } elseif ($grid_masonry[$key]['col_lg_m'] == 'col-40') {
                    $col_lg_m = '40-pxl';
                } else {
                    $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                }
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";

                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if (!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            $filter_class = !empty($tax) ? pxl_get_term_of_post_to_class($post->ID, array_unique($tax)) : '';

            $img_id = get_post_thumbnail_id($post->ID);
            if ($img_id) {
                $img = pxl_get_image_by_size([
                    'attach_id' => $img_id,
                    'thumb_size' => $images_size,
                    'class' => 'no-lazyload',
                ]);
                $thumbnail = $img['thumbnail'];
            } else {
                $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
            }
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)): ?>
                        <div class="pxl-item-thumbnail">
                            <div class="pxl-item--featured">                             
                                <div class="pxl-item--category wow fadeInUp"><?php the_terms($post->ID, 'category', '', ' '); ?></div>
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                                <div class="pxl-gradiend"></div>
                            </div> 
                            <?php if ($show_tag == 'true'): ?>
                                <div class="pxl-item--tag">
                                    <?php the_terms($post->ID, 'post_tag', '', ' '); ?>
                                </div>
                            <?php endif; ?>
                        </div> 
                    <?php endif; ?>
                    <div class="pxl-item--holder">                
                        <h4 class="pxl-item--title">
                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a>
                        </h4>
                        <?php if ($show_excerpt == 'true'): ?>
                            <div class="pxl-item--content pxl-sz-content">
                                <?php echo wp_trim_words($post->post_excerpt, $num_words); ?>
                            </div>
                        <?php endif; ?>
                        <div class="pxl-item--meta--sub">
                            <?php if ($show_author == 'true'): ?>
                                <?php 
                                $author = get_user_by('id', $post->post_author);
                                $author_avatar = get_avatar($post->post_author, 60, '', $author->display_name, ['class' => '']);
                                ?>
                                <div class="pxl-post--author pxl-flex pxl-mr-16">
                                    <div class="pxl-author--img pxl-mr-10">
                                        <?php pxl_print_html($author_avatar); ?>
                                    </div>
                                    <div class="pxl-author-meta">
                                        <a href="<?php echo esc_url(get_author_posts_url($post->post_author, $author->user_nicename)); ?>">
                                            <?php echo esc_html($author->display_name); ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="pxl-date--full">
                                <?php if ($show_date == 'true'): ?>
                                    <div class="pxl-item-date pxl-flex">
                                        <i class="fal flaticon-calendar-1 pxl-mr-8"></i>
                                        <span><?php echo get_the_date(get_option('date_format'), $post->ID); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div> 
                    </div> 
                </div> 
            </div> 
            <?php
        endforeach;
    endif;
}


function autoev_get_post_grid_layout5($posts = [], $settings = []) { 
    extract($settings); 
    $images_size = !empty($img_size) ? $img_size : '372x372';
    $post_category = autoev()->get_theme_opt('post_category', true);
    $item_class = "pxl-grid-item row col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";

    
    if (is_array($posts) && count($posts) > 0):
        $first_post = $posts[0];
        setup_postdata($first_post);
        $img_id = get_post_thumbnail_id($first_post->ID);
        $thumbnail = $img_id ? pxl_get_image_by_size([
            'attach_id' => $img_id,
            'thumb_size' => $images_size,
            'class' => 'no-lazyload',
        ])['thumbnail'] : get_the_post_thumbnail($first_post->ID, $images_size);
        ?>
        <div class="pxl-grid-item col-6 pxl-col-left">
            <div class="pxl-item--inner <?php echo esc_attr($select); ?> <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                <?php if ($img_id): ?>
                    <div class="pxl-item-thumbnail">
                        <div class="pxl-item--featured">
                            <div class="pxl-item--category wow fadeInUp"><?php the_terms($first_post->ID, 'category', '', ' '); ?></div>
                            <a href="<?php echo esc_url(get_permalink($first_post->ID)); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                            <div class="pxl-gradiend"></div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="pxl-item--holder">
                    <h4 class="pxl-item--title">
                        <a href="<?php echo esc_url(get_permalink($first_post->ID)); ?>"><?php echo esc_html(get_the_title($first_post->ID)); ?></a>
                    </h4>
                    <div class="pxl-item--meta">
                        <?php if ($show_author == 'true'): ?>
                            <div class="pxl-post--author pxl-flex pxl-mr-16">
                                <div class="pxl-author--img pxl-mr-10">
                                    <?php pxl_print_html(get_avatar($first_post->post_author, 60)); ?>
                                </div>
                                <div class="pxl-author-meta">
                                    <a href="<?php echo esc_url(get_author_posts_url($first_post->post_author)); ?>">
                                        <?php echo esc_html(get_the_author_meta('display_name', $first_post->post_author)); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($show_date == 'true'): ?>
                            <div class="pxl-date--full">
                                <div class="pxl-item-date pxl-flex">
                                    <i class="fal flaticon-calendar-1 pxl-mr-8"></i>
                                    <span><?php echo get_the_date(get_option('date_format'), $first_post->ID); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($show_excerpt == 'true'): ?>
                        <div class="pxl-item--content pxl-sz-content">
                            <?php echo wp_trim_words($first_post->post_excerpt, $num_words); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- view 5 post right -->
            <div class="pxl-grid-item col-6 pxl-col-right">
                <?php
                for ($i = 1; $i <= 5 && $i < count($posts); $i++): 
                    $post = $posts[$i];
                    setup_postdata($post);
                    $img_id = get_post_thumbnail_id($post->ID);
                    $thumbnail = $img_id ? pxl_get_image_by_size([
                        'attach_id' => $img_id,
                        'thumb_size' => $images_size,
                        'class' => 'no-lazyload',
                    ])['thumbnail'] : get_the_post_thumbnail($post->ID, $images_size);
                    ?>
                    <div class="pxl-item--inner <?php echo esc_attr($select); ?> <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                        <?php if ($img_id): ?>
                            <div class="pxl-item-thumbnail">
                                <div class="pxl-item--featured">
                                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                                    <div class="pxl-gradiend"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="pxl-item--holder">
                            <div class="pxl-item--meta">
                                <?php if ($show_author == 'true'): ?>
                                    <div class="pxl-post--author pxl-flex pxl-mr-16">
                                        <div class="pxl-author--img pxl-mr-10">
                                            <?php pxl_print_html(get_avatar($post->post_author, 60)); ?>
                                        </div>
                                        <div class="pxl-author-meta">
                                            <a href="<?php echo esc_url(get_author_posts_url($post->post_author)); ?>">
                                                <?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($show_date == 'true'): ?>
                                    <div class="pxl-date--full">
                                        <div class="pxl-item-date pxl-flex">
                                            <i class="fal flaticon-calendar-1 pxl-mr-8"></i>
                                            <span><?php echo get_the_date(get_option('date_format'), $post->ID); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h4 class="pxl-item--title">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a>
                            </h4> 
                        </div>  
                    </div>
                <?php endfor; ?>
            </div>
            
    <?php
    
    endif;
}

// End Post Grid
//--------------------------------------------------

// Start Portfolio Grid
//--------------------------------------------------

function autoev_get_portfolio_grid_layout1($posts = [], $settings = []){ 
    extract($settings);
    
    $images_size = !empty($img_size) ? $img_size : '600x665';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";
                
                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $img_id = get_post_thumbnail_id($post->ID);
            if($img_id) {
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $images_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
            } else {
                $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
            } ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <div class="pxl-item--featured">
                        <a href="<?php echo esc_url(get_permalink( $post->ID )); ?>" title="<?php echo esc_attr(get_the_title($post->ID)); ?>"><?php echo wp_kses_post($thumbnail); ?></a>
                        <?php if($show_button == 'true') : ?>
                            <div class="pxl-item--readmore">
                                <a class="btn" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                    <?php if(!empty($button_text)) {
                                        echo esc_attr($button_text);
                                    } else {
                                        echo esc_html__('View more', 'autoev');
                                    } ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="pxl-item--holder pxl-effect--content">
                        <div class="pxl-item--meta">
                            <h3 class="pxl-item--title"><?php echo esc_html(get_the_title($post->ID)); ?></h3>
                            <?php if($show_category == 'true'): ?>
                                <div class="pxl-item--category">
                                    <?php the_terms( $post->ID, 'portfolio-category', '', ' ' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    endif;
}
// End Portfolio Grid
//--------------------------------------------------

// Start Service Grid
//--------------------------------------------------
function autoev_get_service_grid_layout1($posts = [], $settings = []){ 
    extract($settings);
    
    $images_size = !empty($img_size) ? $img_size : '600x600';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";
                
                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $img_id = get_post_thumbnail_id($post->ID);
            if($img_id) {
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $images_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
            } else {
                $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
            }

            $service_excerpt = get_post_meta($post->ID, 'service_excerpt', true);
            $service_external_link = get_post_meta($post->ID, 'service_external_link', true);
            $custom_service_number = get_post_meta($post->ID, 'custom_service_number', true);
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner pxl-not-active <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <div class="pxl-item--image hover-imge-effect3">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)):
                        $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false); ?>
                            <a href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="pxl-item--number">
                        <?php if($show_number == 'true' && !empty($custom_service_number)): ?>
                            <div class="pxl-number-text">
                                <?php echo wp_trim_words( $custom_service_number); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="pxl-item--holder">
                        <h3 class="pxl-item--title">
                            <a href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a>
                        </h3>
                        <?php if($show_excerpt == 'true' && !empty($service_excerpt)): ?>
                            <div class="pxl-item--content">
                                <?php echo wp_trim_words( $service_excerpt, $num_words, $more = null ); ?>
                            </div>
                        <?php endif; ?>
                        <?php if($show_button == 'true') : ?>
                            <div class="pxl-item--readmore">
                                <a class="btn" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                    <?php if(!empty($button_text)) {
                                        echo esc_attr($button_text);
                                    } else {
                                        echo esc_html__('Read More', 'autoev');
                                    } ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    endif;
}

function autoev_get_service_grid_layout2($posts = [], $settings = []){ 
    extract($settings);
    $images_size = !empty($img_size) ? $img_size : '600x600';
    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";
                
                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $img_id = get_post_thumbnail_id($post->ID);
            if($img_id) {
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $images_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
            } else {
                $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
            }

            $service_excerpt = get_post_meta($post->ID, 'service_excerpt', true);
            $service_external_link = get_post_meta($post->ID, 'service_external_link', true);
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner pxl-not-active <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <div class="pxl-item--image hover-imge-effect3">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)):
                        $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false); ?>
                            <a class="pxl-img-link" href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                            </a>
                            <?php echo wp_kses_post($thumbnail); ?>
                        <?php endif; ?>
                        <?php if($show_button == 'true') : ?>
                            <div class="pxl-item--readmore">
                                <a class="btn" href="<?php echo esc_url(get_permalink( $post->ID )); ?>">
                                    <?php if(!empty($button_text)) {
                                        echo esc_attr($button_text);
                                    } else {
                                        echo esc_html__('Read More', 'autoev');
                                    } ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="pxl-item--holder">
                        <h4 class="pxl-item--title">
                            <a href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a>
                        </h4>
                        <?php if($show_excerpt == 'true' && !empty($service_excerpt)): ?>
                            <div class="pxl-item--content">
                                <?php echo wp_trim_words( $service_excerpt, $num_words, $more = null ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    endif;
}
function autoev_get_service_grid_layout3($posts = [], $settings = []){ 
    extract($settings);
    
    $images_size = !empty($img_size) ? $img_size : '60x60';

    if (is_array($posts)):
        foreach ($posts as $key => $post):
            $item_class = "pxl-grid-item col-xl-{$col_xl} col-lg-{$col_lg} col-md-{$col_md} col-sm-{$col_sm} col-{$col_xs}";
            if(isset($grid_masonry) && !empty($grid_masonry[$key]) && (count($grid_masonry) > 1)) {
                $col_xl_m = 12 / $grid_masonry[$key]['col_xl_m'];
                $col_lg_m = 12 / $grid_masonry[$key]['col_lg_m'];
                $col_md_m = 12 / $grid_masonry[$key]['col_md_m'];
                $col_sm_m = 12 / $grid_masonry[$key]['col_sm_m'];
                $col_xs_m = 12 / $grid_masonry[$key]['col_xs_m'];
                $item_class = "pxl-grid-item col-xl-{$col_xl_m} col-lg-{$col_lg_m} col-md-{$col_md_m} col-sm-{$col_sm_m} col-{$col_xs_m}";
                
                $img_size_m = $grid_masonry[$key]['img_size_m'];
                if(!empty($img_size_m)) {
                    $images_size = $img_size_m;
                }
            } elseif (!empty($img_size)) {
                $images_size = $img_size;
            }

            if(!empty($tax))
                $filter_class = pxl_get_term_of_post_to_class($post->ID, array_unique($tax));
            else 
                $filter_class = '';

            $img_id = get_post_thumbnail_id($post->ID);
            if($img_id) {
                $img = pxl_get_image_by_size( array(
                    'attach_id'  => $img_id,
                    'thumb_size' => $images_size,
                    'class' => 'no-lazyload',
                ));
                $thumbnail = $img['thumbnail'];
            } else {
                $thumbnail = get_the_post_thumbnail($post->ID, $images_size);
            }

            $service_excerpt = get_post_meta($post->ID, 'service_excerpt', true);
            $service_external_link = get_post_meta($post->ID, 'service_external_link', true);
            ?>
            <div class="<?php echo esc_attr($item_class . ' ' . $filter_class); ?>">
                <div class="pxl-item--inner pxl-not-active <?php echo esc_attr($pxl_animate); ?>" data-wow-duration="1.2s">
                    <div class="pxl-item--image hover-imge-effect3">
                        <?php if (has_post_thumbnail($post->ID) && wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false)):
                        $thumbnail_url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), false); ?>
                            <a class="pxl-img-link" href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>">
                            </a>
                            <?php echo wp_kses_post($thumbnail); ?>
                            <h3 class="pxl-item--title">
                                <a href="<?php if(!empty($service_external_link)) { echo esc_url($service_external_link); } else { echo esc_url(get_permalink( $post->ID )); } ?>"><?php echo esc_html(get_the_title($post->ID)); ?></a>
                            </h3>
                            <?php if($show_excerpt == 'true' && !empty($service_excerpt)): ?>
                                <div class="pxl-item--content">
                                    <?php echo wp_trim_words( $service_excerpt, $num_words, $more = null ); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            if($key==0) {
                <div class="col-6"> </div>
            }else  {
                <div class="col-6"> </div>
            }
        <?php
        endforeach;
    endif;
}
// End Service Grid
//-------------------------------------------------

add_action( 'wp_ajax_autoev_load_more_product_grid', 'autoev_load_more_product_grid' );
add_action( 'wp_ajax_nopriv_autoev_load_more_product_grid', 'autoev_load_more_product_grid' );
function autoev_load_more_product_grid(){
    try{
        if(!isset($_POST['settings'])){
            throw new Exception(__('Something went wrong while requesting. Please try again!', 'autoev'));
        }
        $settings = isset($_POST['settings']) ? $_POST['settings'] : null;
        set_query_var('paged', $settings['paged']);
        $query_type         = isset($settings['query_type']) ? $settings['query_type'] : 'recent_product';
        $post_per_page      = isset($settings['limit']) ? $settings['limit'] : 8;
        $product_ids        = isset($settings['product_ids']) ? $settings['product_ids'] : '';
        $categories         = isset($settings['categories']) ? $settings['categories'] : '';
        $param_args         = isset($settings['param_args']) ? $settings['param_args'] : [];

        $col_xxl = isset($settings['col_xxl']) ? 'col-xxl-'.str_replace('.', '',12 / floatval($settings['col_xxl'])) : '';
        $col_xl = isset($settings['col_xl']) ? 'col-xl-'.str_replace('.', '',12 / floatval( $settings['col_xl'])) : '';
        $col_lg = isset($settings['col_lg']) ? 'col-lg-'.str_replace('.', '',12 / floatval( $settings['col_lg'])) : '';
        $col_md = isset($settings['col_md']) ? 'col-md-'.str_replace('.', '',12 / floatval( $settings['col_md'])) : '';
        $col_sm = isset($settings['col_sm']) ? 'col-sm-'.str_replace('.', '',12 / floatval( $settings['col_sm'])) : ''; 
        $col_xs = isset($settings['col_xs']) ? 'col-'.str_replace('.', '',12 / floatval( $settings['col_xs'])) : ''; 
 
        $item_class = trim(implode(' ', ['pxl-grid-item', $col_xxl, $col_xl, $col_lg, $col_md, $col_sm, $col_xs]));
          
        $loop = autoev_woocommerce_query($query_type,$post_per_page,$product_ids,$categories,$param_args);
        extract($loop);
        
        $data_animation = []; 
        $animate_cls = '';
        $data_settings = '';
        if ( !empty( $settings['item_animation'] ) ) {
            $animate_cls = ' pxl-animate pxl-invisible animated-'.$settings['item_animation_duration'];
            $data_animation['animation'] = $settings['item_animation'];
            $data_animation['animation_delay'] = $settings['item_animation_delay'];
        }
        if($posts->have_posts()){
            ob_start();
                $d = 0;
                while ($posts->have_posts()) {
                    $posts->the_post();
                    global $product;
                    $d++;
                    $term_list = array();
                    $term_of_post = wp_get_post_terms($product->get_ID(), 'product_cat');
                    foreach ($term_of_post as $term) {
                        $term_list[] = $term->slug;
                    }
                    $filter_class = implode(' ', $term_list);

                    if ( !empty( $data_animation ) ) { 
                        $data_animation['animation_delay'] = ((float)$settings['item_animation_delay'] * $d);
                        $data_animations = json_encode($data_animation);
                        $data_settings = 'data-settings="'.esc_attr($data_animations).'"';
                    }
                     
                    ?>
                    <div class="<?php echo trim(implode(' ', [$item_class, $filter_class, $animate_cls])); ?>" <?php pxl_print_html($data_settings); ?>>
                        <?php
                            do_action( 'woocommerce_before_shop_loop_item' );
                            do_action( 'woocommerce_before_shop_loop_item_title' );
                            do_action( 'woocommerce_shop_loop_item_title' );
                            do_action( 'woocommerce_after_shop_loop_item_title' );
                            do_action( 'woocommerce_after_shop_loop_item' );
                        ?>
                    </div>
                <?php 
                }
                if($settings['layout_mode'] == 'masonry')
                    echo '<div class="grid-sizer '.$item_class.'"></div>';
            $html = ob_get_clean();
            wp_send_json(
                array(
                    'status' => true,
                    'message' => esc_html__('Load Post Grid Successfully!', 'autoev'),
                    'data' => array(
                        'html'  => $html,
                        'paged' => $settings['paged'],
                        'posts' => $posts,
                        'max' => $max,
                    ),
                )
            );
        }else{
            wp_send_json(
                array(
                    'status' => false,
                    'message' => esc_html__('Load Post Grid No More!', 'autoev')
                )
            );
        } 
    }
    catch (Exception $e){
        wp_send_json(array('status' => false, 'message' => $e->getMessage()));
    }
    die;
}

add_action( 'wp_ajax_autoev_get_pagination_html', 'autoev_get_pagination_html' );
add_action( 'wp_ajax_nopriv_autoev_get_pagination_html', 'autoev_get_pagination_html' );
function autoev_get_pagination_html(){
    try{
        if(!isset($_POST['query_vars'])){
            throw new Exception(__('Something went wrong while requesting. Please try again!', 'autoev'));
        }
        $query = new WP_Query($_POST['query_vars']);
        ob_start();
        autoev()->page->get_pagination( $query,  true );
        $html = ob_get_clean();
        wp_send_json(
            array(
                'status' => true,
                'message' => esc_attr__('Load Successfully!', 'autoev'),
                'data' => array(
                    'html' => $html,
                    'query_vars' => $_POST['query_vars'],
                    'post' => $query->have_posts()
                ),
            )
        );
    }
    catch (Exception $e){
        wp_send_json(array('status' => false, 'message' => $e->getMessage()));
    }
    die;
}

add_action( 'wp_ajax_autoev_load_more_post_grid', 'autoev_load_more_post_grid' );
add_action( 'wp_ajax_nopriv_autoev_load_more_post_grid', 'autoev_load_more_post_grid' );
function autoev_load_more_post_grid(){
    try{
        if(!isset($_POST['settings'])){
            throw new Exception(__('Something went wrong while requesting. Please try again!', 'autoev'));
        }
        $settings = isset($_POST['settings']) ? $_POST['settings'] : null;
        set_query_var('paged', $settings['paged']);
        extract(pxl_get_posts_of_grid($settings['post_type'], [
            'source' => isset($settings['source'])?$settings['source']:'',
            'orderby' => isset($settings['orderby'])?$settings['orderby']:'date',
            'order' => isset($settings['order'])?$settings['order']:'desc',
            'limit' => isset($settings['limit'])?$settings['limit']:'6',
            'post_ids' => isset($settings['post_ids'])?$settings['post_ids']:[],
        ]));
        ob_start();
         
        autoev_get_post_grid($posts, $settings);
        $html = ob_get_clean();
        wp_send_json(
            array(
                'status' => true,
                'message' => esc_attr__('Load Successfully!', 'autoev'),
                'data' => array(
                    'html' => $html,
                    'paged' => $settings['paged'],
                    'posts' => $posts,
                    'max' => $max,
                ),
            )
        );
    }
    catch (Exception $e){
        wp_send_json(array('status' => false, 'message' => $e->getMessage()));
    }
    die;
}

add_action( 'wp_ajax_autoev_get_filter_html', 'autoev_get_filter_html' );
add_action( 'wp_ajax_nopriv_autoev_get_filter_html', 'autoev_get_filter_html' );
function autoev_get_filter_html(){
    try{
        if(!isset($_POST['settings'])){
            throw new Exception(__('Something went wrong while requesting. Please try again!', 'autoev'));
        }
        $settings = isset($_POST['settings']) ? $_POST['settings'] : null;
        $loadmore_filter = $_POST['loadmore_filter'];
        if($loadmore_filter == '1'){
            set_query_var('paged', 1);
            $limit = isset($settings['limit'])?$settings['limit']:'6';
            $limitx = (int)$limit * (int)$settings['paged'];
        }else{
            set_query_var('paged', $settings['paged']);
            $limitx = isset($settings['limit'])?$settings['limit']:'6';
        }
        extract(pxl_get_posts_of_grid($settings['post_type'], [
                'source' => isset($settings['source'])?$settings['source']:'',
                'orderby' => isset($settings['orderby'])?$settings['orderby']:'date',
                'order' => isset($settings['order'])?$settings['order']:'desc',
                'limit' => $limitx,
                'post_ids' => isset($settings['post_ids'])?$settings['post_ids']: [],
            ],
            $settings['tax']
        ));
        ob_start(); ?>
        
        <span class="filter-item active" data-filter="*">
            <?php echo esc_html($settings['filter_default_title']); ?>
            <?php if($settings['show_cat_count'] == '1'): ?>
                <span class="filter-item-count"><?php echo count($posts); ?></span> 
            <?php endif; ?>
        </span>
        <?php foreach ($categories as $category):
            $category_arr = explode('|', $category);
            $term = get_term_by('slug',$category_arr[0], $category_arr[1]);
            $tax_count = 0;
            foreach ($posts as $key => $post){
                $this_terms = get_the_terms( $post->ID,  $settings['tax'][0] );
                $term_list = [];
                foreach ($this_terms as $t) {
                    $term_list[] = $t->slug;
                } 
                if(in_array($term->slug,$term_list))
                    $tax_count++;
            } 
            if($tax_count > 0): ?>
                <span class="filter-item" data-filter="<?php echo esc_attr('.' . $term->slug); ?>">
                    <?php echo esc_html($term->name); ?>
                    <?php if($settings['show_cat_count'] == '1'): ?>
                        <span class="filter-item-count"><?php echo esc_html($tax_count); ?></span> 
                    <?php endif; ?>
                </span>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php $html = ob_get_clean();
        wp_send_json(
            array(
                'status' => true,
                'message' => esc_attr__('Load Successfully!', 'autoev'),
                'data' => array(
                    'html' => $html,
                    'paged' => $settings['paged'],
                    'posts' => $posts,
                    'max' => $max,
                ),
            )
        );
    }
    catch (Exception $e){
        wp_send_json(array('status' => false, 'message' => $e->getMessage()));
    }
    die;
}


/* User Login/Register */
function autoev_user_form() {
    if(function_exists('up_get_template_part') && !is_user_logged_in()) : ?>
        <div class="pxl-modal pxl-user-popup">
            <div class="pxl-modal-close"><i class="pxl-icon-close"></i></div>
            <div class="pxl-modal-content">
                <div class="pxl-user pxl-user-register u-close">
                    <div class="pxl-user-content">
                        <h3 class="pxl-user-heading"><?php echo esc_html__('Create your account', 'autoev'); ?></h3>
                        <?php echo do_shortcode('[bravis-user-form form_type="register"]'); ?>
                        <div class="pxl-user-footer">
                            <a href="javascript:void(0)" class="btn-sign-in"> <?php esc_html_e('Sign In', 'autoev');?></a>
                        </div>
                    </div>
                </div>
                <div class="pxl-user pxl-user-login">
                    <div class="pxl-user-content">
                        <h3 class="pxl-user-heading"><?php echo esc_html__('Log in to Your Account', 'autoev'); ?></h3>
                        <?php echo do_shortcode('[bravis-user-form form_type="login" is_logged="profile"]'); ?>  
                        <div class="pxl-user-footer">
                            <a href="javascript:void(0)" class="btn-sign-up"> <?php esc_html_e('Sign Up', 'autoev');?></a>
                        </div>
                        <?php if(class_exists('Woocommerce')) : 
                            $my_ac = get_option( 'woocommerce_myaccount_page_id' ); 
                            $lost_password = get_option( 'woocommerce_myaccount_lost_password_endpoint' );
                            ?>
                            <div class="pxl-user-forgot-pass"><a href="<?php echo esc_url(get_permalink($my_ac)); ?><?php echo esc_html($lost_password); ?>"><?php esc_html_e('Forgot your password?', 'autoev');?></a></div>
                        <?php endif; ?>
                    </div>
                </div> 
            </div>
        </div>
    <?php endif;
}