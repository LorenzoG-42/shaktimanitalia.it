<?php
/**
 * @package Bravis-Themes
 */
get_header(); ?>
<div class="container">
    <div class="row content-row">
        <div id="pxl-content-area" class="pxl-content-area col-12">
            <main id="pxl-content-main">
                <div class="pxl-error-inner">
                    <div class="pxl-error-holder">
                        <div class="pxl-error-number wow fadeInUp">
                            <span>404</span>
                        </div>
                        <h3 class="pxl-error-title wow fadeInUp">
                            <?php echo esc_html__('Page cannot be found', 'autoev'); ?>
                        </h3>
                        <div class="pxl-error-description wow fadeInUp"><?php echo esc_html__('Sorry, but the page you are looking for does not existing.', 'autoev'); ?></div>
                        <form get_search_form() method="get" class="search-form wow fadeInUp" action="<?php echo esc_url(home_url( '/' )); ?>">
                            <div class="searchform-wrap">
                                <input type="text" placeholder="<?php esc_attr_e('Search...', 'autoev'); ?>" name="s" class="search-field" />
                                <button type="submit" class="search-submit"><i class="flaticon flaticon-search1"></i></button>
                            </div>
                        </form>
                        <a class="btn btn-default btn-secondary wow fadeInUp" href="<?php echo esc_url(home_url('/')); ?>">
                            <span class="pxl--btn-text-meta"><?php echo esc_html__('Go Back Home', 'autoev'); ?></span>
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<?php get_footer();
