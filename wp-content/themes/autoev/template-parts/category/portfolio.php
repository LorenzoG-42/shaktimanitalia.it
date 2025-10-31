<?php
/**
 * @package Bravis-Themes
 */
wp_enqueue_script('pxl-direction');
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('col-lg-4 col-md-6 col-sm-12'); ?>>
    <div class="pxl-item--inner">
        <div class="pxl-effect--direction">
            <?php if (has_post_thumbnail()) {
                echo '<div class="pxl-item--featured">'; ?>
                    <a href="<?php echo esc_url( get_permalink()); ?>"><?php the_post_thumbnail('autoev-portfolio'); ?></a>
                <?php echo '</div>';
            } ?>
            <div class="pxl-item--holder pxl-effect--content">
                <div class="pxl-item--shape1 bg-image"></div>
                <div class="pxl-item--shape2 bg-image"></div>
                <div class="pxl-item--meta">
                    <h3 class="pxl-item--title"><?php echo esc_html(get_the_title($post->ID)); ?></h3>
                    <div class="pxl-item--divider"></div>
                </div>
                <a class="pxl-item--link" href="<?php echo esc_url( get_permalink()); ?>"></a>
            </div>
        </div>
    </div>
</article>