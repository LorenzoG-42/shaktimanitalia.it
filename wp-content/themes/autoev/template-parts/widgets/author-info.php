<?php
defined( 'ABSPATH' ) or exit( -1 );

/**
 * Author Information widgets
 *
 */

if(!function_exists('pxl_register_wp_widget')) return;
add_action( 'widgets_init', function(){
    pxl_register_wp_widget( 'PXL_Author_Info_Widget' );
});
class PXL_Author_Info_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'pxl_author_info_widget',
            esc_html__('autoev About Author', 'autoev'),
            array('description' => esc_html__('Show Author Information', 'autoev'),)
        );
    }

    function widget($args, $instance)
    {
        extract($args);
        $author_image_id = !empty($instance['author_image']) ? $instance['author_image'] : '';
        $author_image_url = wp_get_attachment_image_url($author_image_id, '');
        $author_name = !empty($instance['author_name']) ? $instance['author_name'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';

        $twitter_link = !empty($instance['author_twitter_link']) ? $instance['author_twitter_link'] : '';
        $facebook_link = !empty($instance['author_facebook_link']) ? $instance['author_facebook_link'] : '';
        $instagram_link = !empty($instance['author_instagram_link']) ? $instance['author_instagram_link'] : '';
        $youtube_link = !empty($instance['author_youtube_link']) ? $instance['author_youtube_link'] : '';
        $linkedin_link = !empty($instance['author_linkedin_link']) ? $instance['author_linkedin_link'] : '';
         
        ?>
        <div class="pxl-author-info widget" >
            <div class="content-inner">
                <?php if (!empty($author_name)): ?>
                    <h4 class="author-name"><?php echo esc_html($author_name);?></h4>
                <?php endif; ?>
                <div class="author-image">
                    <div class="image-wrap flash-hover">
                        <img src="<?php echo esc_url($author_image_url)?>" alt="<?php echo esc_attr__('Author Image', 'autoev');?>">
                    </div>
                </div>
                <?php if (!empty($description)): ?>
                    <div class="author-desc"><?php echo autoev_html(nl2br($description)); ?></div>
                <?php endif; ?>
                <?php if ( !empty($twitter_link) || !empty($facebook_link) || !empty($instagram_link) || !empty($youtube_link) || !empty($linkedin_link)): ?>
                    <div class="author-social">
                        <?php if(!empty($facebook_link)): ?>
                            <div class="social-item">
                                <a href="<?php echo esc_url($facebook_link); ?>" target="_blank"><span class="caseicon-facebook"></span></a>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($twitter_link)): ?>
                            <div class="social-item">
                                <a href="<?php echo esc_url($twitter_link); ?>" target="_blank"><span class="caseicon-twitter"></span></a>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($instagram_link)): ?>
                            <div class="social-item">
                                <a href="<?php echo esc_url($instagram_link); ?>" target="_blank"><span class="caseicon-instagram"></span></a>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($youtube_link)): ?>
                            <div class="social-item">
                                <a href="<?php echo esc_url($youtube_link); ?>" target="_blank"><span class="caseicon-youtube"></span></a>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($linkedin_link)): ?>
                            <div class="social-item">
                                <a href="<?php echo esc_url($linkedin_link); ?>" target="_blank"><span class="caseicon-linkedin"></span></a>
                            </div>
                        <?php endif; ?>
                         
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['author_image'] = strip_tags($new_instance['author_image']);
        $instance['author_name'] = strip_tags($new_instance['author_name']);
        $instance['description'] = strip_tags($new_instance['description']);

        $instance['author_twitter_link'] = strip_tags($new_instance['author_twitter_link']);
        $instance['author_facebook_link'] = strip_tags($new_instance['author_facebook_link']);
        $instance['author_instagram_link'] = strip_tags($new_instance['author_instagram_link']);
        $instance['author_youtube_link'] = strip_tags($new_instance['author_youtube_link']);
        $instance['author_linkedin_link'] = strip_tags($new_instance['author_linkedin_link']);
         
        return $instance;
    }

    function form($instance)
    {
        $author_image = isset($instance['author_image']) ? esc_attr($instance['author_image']) : '';
        $author_name = isset($instance['author_name']) ? esc_html($instance['author_name']) : '';
        $description = isset($instance['description']) ? esc_html($instance['description']) : '';

        $author_twitter_link = isset($instance['author_twitter_link']) ? esc_html($instance['author_twitter_link']) : '';
        $author_facebook_link = isset($instance['author_facebook_link']) ? esc_html($instance['author_facebook_link']) : '';
        $author_instagram_link = isset($instance['author_instagram_link']) ? esc_html($instance['author_instagram_link']) : '';
        $author_youtube_link = isset($instance['author_youtube_link']) ? esc_html($instance['author_youtube_link']) : '';
        $author_linkedin_link = isset($instance['author_linkedin_link']) ? esc_html($instance['author_linkedin_link']) : '';
        ?>
        <div class="author-image-wrap">
            <label for="<?php echo esc_url($this->get_field_id('author_image')); ?>"><?php esc_html_e('Author Image', 'autoev'); ?></label>
            <input type="hidden" class="widefat hide-image-url"
                   id="<?php echo esc_attr($this->get_field_id('author_image')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('author_image')); ?>"
                   value="<?php echo esc_attr($author_image) ?>"/>
            <div class="pxl-show-image">
                <?php
                if ($author_image != "") {
                    ?>
                    <img src="<?php echo wp_get_attachment_image_url($author_image) ?>">
                    <?php
                }
                ?>
            </div>
            <?php
            if ($author_image != "") {
                ?>
                <a href="#" class="pxl-select-image pxl-btn" style="display: none;"><?php esc_html_e('Select Image', 'autoev'); ?></a>
                <a href="#" class="pxl-remove-image pxl-btn"><?php esc_html_e('Remove Image', 'autoev'); ?></a>
                <?php
            } else {
                ?>
                <a href="#" class="pxl-select-image pxl-btn"><?php esc_html_e('Select Image', 'autoev'); ?></a>
                <a href="#" class="pxl-remove-image pxl-btn" style="display: none;"><?php esc_html_e('Remove Image', 'autoev'); ?></a>
                <?php
            }
            ?>
        </div>
         
        <p>
            <label for="<?php echo esc_url($this->get_field_id('author_name')); ?>"><?php esc_html_e( 'Author Name', 'autoev' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('author_name') ); ?>" name="<?php echo esc_attr( $this->get_field_name('author_name') ); ?>" type="text" value="<?php echo esc_attr( $author_name ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_url($this->get_field_id('description')); ?>"><?php esc_html_e('Description', 'autoev'); ?></label>
            <textarea class="widefat" rows="4" cols="20" id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>"><?php echo wp_kses_post($description); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_url($this->get_field_id('author_facebook_link')); ?>"><?php esc_html_e( 'Facebook Link', 'autoev' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('author_facebook_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('author_facebook_link') ); ?>" type="text" value="<?php echo esc_attr( $author_facebook_link ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_url($this->get_field_id('author_twitter_link')); ?>"><?php esc_html_e( 'Twitter Link', 'autoev' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('author_twitter_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('author_twitter_link') ); ?>" type="text" value="<?php echo esc_attr( $author_twitter_link ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_url($this->get_field_id('author_instagram_link')); ?>"><?php esc_html_e( 'Instagram Link', 'autoev' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('author_instagram_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('author_instagram_link') ); ?>" type="text" value="<?php echo esc_attr( $author_instagram_link ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_url($this->get_field_id('author_youtube_link')); ?>"><?php esc_html_e( 'Youtube Link', 'autoev' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('author_youtube_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('author_youtube_link') ); ?>" type="text" value="<?php echo esc_attr( $author_youtube_link ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_url($this->get_field_id('author_linkedin_link')); ?>"><?php esc_html_e( 'Linkedin Link', 'autoev' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('author_linkedin_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('author_linkedin_link') ); ?>" type="text" value="<?php echo esc_attr( $author_linkedin_link ); ?>" />
        </p>
        <?php
    }

} 