<?php
/**
 * @package Bravis-Themes
 */
$post_type_name = get_post_type();
if($post_type_name == 'product') {
    $btn_text = esc_html__('View Product', 'autoev');
    $img_size = 'full';
} else {
    $btn_text = esc_html__('Read More', 'autoev');
    $img_size = 'autoev-large';
}
$archive_readmore_text = autoev()->get_theme_opt('archive_readmore_text', $btn_text);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('pxl---post pxl-item--archive pxl-item--standard'); ?>>
    <?php if (has_post_thumbnail()) { 
        echo '<div class="pxl-item--image">'; ?>
            <a href="<?php echo esc_url( get_permalink()); ?>"><?php the_post_thumbnail($img_size); ?></a>
        <?php echo '</div>';
    } ?>
    <?php autoev()->blog->get_archive_meta(); ?>
    <div class="pxl-item--holder">
        <h2 class="pxl-item--title">
            <a href="<?php echo esc_url( get_permalink()); ?>" title="<?php the_title_attribute(); ?>">
                <?php if(is_sticky()) { ?>
                    <i class="caseicon-check-mark pxl-mr-4"></i>
                <?php } ?>
                <?php the_title(); ?>
            </a>
        </h2>
        <div class="pxl-item--excerpt">
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
        <div class="pxl-item--readmore">
            <a class="btn--readmore" href="<?php echo esc_url( get_permalink()); ?>">
                <span class="btn-readmore--icon pxl-mr-10"><i class="flaticon-right rtl-reverse"></i></span>
                <span class="btn-readmore--text"><?php echo autoev_html($archive_readmore_text); ?></span>
            </a>
        </div>
    </div>
</article>