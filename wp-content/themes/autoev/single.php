<?php
/**
 * @package Bravis-Themes
 */
get_header();
$autoev_sidebar = autoev()->get_sidebar_args(['type' => 'post', 'content_col'=> '8']);
$post_category = autoev()->get_theme_opt( 'post_category', true );
$post_header_image = autoev()->get_theme_opt( 'post_header_image', true );
?>
<div class="pxl-blog-single">
    <div class="container">
        <?php if($post_header_image) : ?>
            <div class="row pxl-blog-wrap">
                <div class="col-lg-6">
                    <?php if($post_category) : ?>
                        <div class="pxl-item--category wow fadeInUp"><?php the_terms( get_the_ID(), 'category', '' ); ?></div>
                    <?php endif; ?>
                    <h2 class="pxl-item--title wow fadeInUp">
                        <a href="<?php echo esc_url( get_permalink()); ?>" title="<?php the_title_attribute(); ?>">
                            <?php if(is_sticky()) { ?>
                            <?php } ?>
                            <?php the_title(); ?>
                        </a>
                    </h2>
                </div>
                <div class="col-lg-6">
                    <?php if (has_post_thumbnail()) {
                        echo '<div class="pxl-item-image wow fadeInRight">'; ?>
                            <?php the_post_thumbnail('autoev-large'); ?>
                        <?php echo '</div>';
                    }?>
                </div>
            </div>
        <?php endif; ?>
        <div class="row pxl-item-content-wrap <?php echo esc_attr($autoev_sidebar['wrap_class']) ?>">
            <div id="pxl-content-area" class="<?php echo esc_attr($autoev_sidebar['content_class']) ?>">
                <main id="pxl-content-main">
                    <?php while ( have_posts() ) {
                        the_post();
                        get_template_part( 'template-parts/content/content-single', get_post_format() );
                        if ( comments_open() || get_comments_number() ) {
                            comments_template();
                        }
                    } ?>
                </main>
            </div>
            <?php if ($autoev_sidebar['sidebar_class']) : ?>
                <div id="pxl-sidebar-area" class="<?php echo esc_attr($autoev_sidebar['sidebar_class']) ?>">
                    <div class="pxl-sidebar-sticky">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php get_footer();
