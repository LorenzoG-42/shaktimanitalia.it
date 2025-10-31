<?php
/**
 * @package Bravis-Themes
 */
$archive_date = autoev()->get_theme_opt( 'archive_date', true );
$archive_category = autoev()->get_theme_opt( 'archive_category', true );
$archive_readmore_text = autoev()->get_theme_opt('archive_readmore_text', esc_html__('Read more', 'autoev'));

// Define $show_author variable
$show_author = autoev()->get_theme_opt('show_author', true); // Default to false if not set

$img_url = '';
if (has_post_thumbnail(get_the_ID()) && wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), false)) {
    $img_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), false);
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('pxl---post pxl-item--archive pxl-item--archive pxl-content--standard row'); ?>>
    <div class="pxl-content col-4">
        <?php if($archive_category) : ?>
            <div class="pxl-item--category"><?php the_terms( get_the_ID(), 'category', '' ); ?></div>
        <?php endif; ?>
        <h2 class="pxl-item--title">
            <a href="<?php echo esc_url( get_permalink()); ?>" title="<?php the_title_attribute(); ?>">
                <?php if(is_sticky()) { ?>
                <?php } ?>
                <?php the_title(); ?>
            </a>
        </h2>
        
        <div class="pxl-date">
        <?php if ($show_author == 'true'): ?>
            <?php 
            $author = get_user_by('id', $post->post_author);
            $author_avatar = get_avatar($post->post_author, 60, '', $author->display_name, ['class' => '']);
            ?>
            <div class="pxl-post--author pxl-flex pxl-mr-16">
                <div class="pxl-author--img pxl-mr-10">
                    <?php wp_kses_post($author_avatar); ?>
                </div>
                <div class="pxl-author-meta">
                    <a href="<?php echo esc_url(get_author_posts_url($post->post_author, $author->user_nicename)); ?>">
                        <?php echo esc_html($author->display_name); ?>
                    </a>
                </div>
            </div>
        <?php endif; ?> 
            <i aria-hidden="true" class="fal flaticon-calendar-1"></i>
            <?php if($archive_date) : ?>
                <div class="pxl-item--date"><?php echo get_the_date('j'); ?></div>
                <div class="pxl-item--date2"><?php echo get_the_date('M'); ?></div>
                <div class="pxl-item--date3"><?php echo get_the_date('Y'); ?></div>
            <?php endif; ?>
            
        </div>
    </div>
    <div class="pxl-item--excerpt col-4">
        <?php
            autoev()->blog->get_excerpt();
            wp_link_pages( array(
                'before'      => '<div class="page-links">',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
            ) );
        ?>
    </div>    
    <div class="pxl-content-thumbnail col-4 ">
        <?php if (has_post_thumbnail()) { 
            echo '<div class="pxl-item--image">'; ?>
            <a href="<?php echo esc_url( get_permalink()); ?>"><?php the_post_thumbnail('autoev-large'); ?></a>
            <?php echo '</div>';
        } ?>
    </div>
</article>
