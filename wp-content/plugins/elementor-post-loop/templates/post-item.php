<?php
/**
 * Template di esempio per un singolo elemento del loop
 * Questo file può essere copiato nel tema per personalizzazioni
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post;
?>

<article class="elementor-post-loop-item elementor-post-loop-<?php echo esc_attr($template_style ?: 'card'); ?>">
    
    <?php if ($show_image && has_post_thumbnail()): ?>
        <div class="elementor-post-loop-thumbnail">
            <?php if ($link_image): ?>
                <a href="<?php echo esc_url(get_permalink()); ?>">
            <?php endif; ?>
            
            <?php the_post_thumbnail($image_size ?: 'medium', [
                'class' => 'elementor-post-loop-image',
                'alt' => get_the_title(),
            ]); ?>
            
            <?php if ($link_image): ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="elementor-post-loop-content">
        
        <?php if ($show_title): ?>
            <?php 
            $title_tag = $title_tag ?: 'h3';
            $title = get_the_title();
            if ($title_length > 0 && strlen($title) > $title_length) {
                $title = substr($title, 0, $title_length) . '...';
            }
            ?>
            <<?php echo esc_attr($title_tag); ?> class="elementor-post-loop-title">
                <?php if ($link_title): ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>">
                <?php endif; ?>
                
                <?php echo esc_html($title); ?>
                
                <?php if ($link_title): ?>
                    </a>
                <?php endif; ?>
            </<?php echo esc_attr($title_tag); ?>>
        <?php endif; ?>
        
        <?php if ($show_meta && !empty($meta_items)): ?>
            <div class="elementor-post-loop-meta">
                <?php foreach ($meta_items as $meta_item): ?>
                    <?php switch ($meta_item): 
                        case 'date': ?>
                            <span class="elementor-post-loop-meta-item elementor-post-loop-date">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo esc_html(get_the_date($date_format ?: get_option('date_format'))); ?>
                            </span>
                            <?php break;
                        
                        case 'author': ?>
                            <span class="elementor-post-loop-meta-item elementor-post-loop-author">
                                <i class="far fa-user"></i>
                                <?php echo esc_html(get_the_author()); ?>
                            </span>
                            <?php break;
                        
                        case 'comments': ?>
                            <?php if (comments_open() || get_comments_number() > 0): ?>
                                <span class="elementor-post-loop-meta-item elementor-post-loop-comments">
                                    <i class="far fa-comments"></i>
                                    <?php echo esc_html(get_comments_number()); ?>
                                </span>
                            <?php endif; ?>
                            <?php break;
                        
                        case 'categories': ?>
                            <?php $categories = get_the_category(); ?>
                            <?php if (!empty($categories)): ?>
                                <span class="elementor-post-loop-meta-item elementor-post-loop-categories">
                                    <i class="far fa-folder"></i>
                                    <?php echo esc_html(implode(', ', array_map(function($cat) { return $cat->name; }, $categories))); ?>
                                </span>
                            <?php endif; ?>
                            <?php break;
                        
                        case 'tags': ?>
                            <?php $tags = get_the_tags(); ?>
                            <?php if (!empty($tags)): ?>
                                <span class="elementor-post-loop-meta-item elementor-post-loop-tags">
                                    <i class="far fa-tags"></i>
                                    <?php echo esc_html(implode(', ', array_map(function($tag) { return $tag->name; }, $tags))); ?>
                                </span>
                            <?php endif; ?>
                            <?php break;
                    endswitch; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($show_excerpt): ?>
            <div class="elementor-post-loop-excerpt">
                <?php echo esc_html(wp_trim_words(get_the_excerpt(), $excerpt_length ?: 20)); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($show_read_more): ?>
            <div class="elementor-post-loop-read-more-container">
                <a href="<?php echo esc_url(get_permalink()); ?>" class="elementor-post-loop-read-more elementor-post-loop-read-more-link">
                    <?php echo esc_html($read_more_text ?: __('Leggi di più', 'elementor-post-loop')); ?>
                </a>
            </div>
        <?php endif; ?>
        
    </div>
</article>