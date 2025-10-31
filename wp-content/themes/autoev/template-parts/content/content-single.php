<?php
/**
 * Template part for displaying posts in loop
 *
 * @package Bravis-Themes
 */
?>
<article id="pxl-post-<?php the_ID(); ?>" <?php post_class('pxl---post'); ?>>
    <div class="pxl-item--holder">
        <div class="pxl-item--content clearfix">
            <?php
                the_content();
                wp_link_pages( array(
                    'before'      => '<div class="page-links">',
                    'after'       => '</div>',
                    'link_before' => '<span>',
                    'link_after'  => '</span>',
                ) );
            ?>
        </div>

    </div>
</article><!-- #post -->