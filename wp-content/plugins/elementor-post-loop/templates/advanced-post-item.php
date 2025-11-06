<?php
/**
 * Template avanzato per Custom Post Types
 * Questo template mostra come personalizzare la visualizzazione per diversi CPT
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post;

$post_type = get_post_type();
$template_style = $template_style ?: 'card';
?>

<article class="elementor-post-loop-item elementor-post-loop-<?php echo esc_attr($template_style); ?> elementor-post-loop-type-<?php echo esc_attr($post_type); ?>">
    
    <?php if ($show_image && has_post_thumbnail()): ?>
        <div class="elementor-post-loop-thumbnail">
            <?php if ($link_image): ?>
                <a href="<?php echo esc_url(get_permalink()); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
            <?php endif; ?>
            
            <?php the_post_thumbnail($image_size ?: 'medium', [
                'class' => 'elementor-post-loop-image',
                'alt' => get_the_title(),
                'loading' => 'lazy',
            ]); ?>
            
            <?php if ($link_image): ?>
                </a>
            <?php endif; ?>
            
            <?php if ($post_type === 'product' && function_exists('wc_get_product')): ?>
                <?php $product = wc_get_product(get_the_ID()); ?>
                <?php if ($product && $product->is_on_sale()): ?>
                    <span class="elementor-post-loop-sale-badge">In Offerta</span>
                <?php endif; ?>
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
                    <a href="<?php echo esc_url(get_permalink()); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
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
                                <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date($date_format ?: get_option('date_format'))); ?>
                                </time>
                            </span>
                            <?php break;
                        
                        case 'author': ?>
                            <span class="elementor-post-loop-meta-item elementor-post-loop-author">
                                <i class="far fa-user" aria-hidden="true"></i>
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                    <?php echo esc_html(get_the_author()); ?>
                                </a>
                            </span>
                            <?php break;
                        
                        case 'comments': ?>
                            <?php if (comments_open() || get_comments_number() > 0): ?>
                                <span class="elementor-post-loop-meta-item elementor-post-loop-comments">
                                    <i class="far fa-comments" aria-hidden="true"></i>
                                    <a href="<?php echo esc_url(get_comments_link()); ?>">
                                        <?php 
                                        $comment_count = get_comments_number();
                                        printf(
                                            _n('%d commento', '%d commenti', $comment_count, 'elementor-post-loop'),
                                            $comment_count
                                        );
                                        ?>
                                    </a>
                                </span>
                            <?php endif; ?>
                            <?php break;
                        
                        case 'categories': ?>
                            <?php 
                            if ($post_type === 'post') {
                                $terms = get_the_category();
                            } else {
                                $taxonomies = get_object_taxonomies($post_type, 'names');
                                $terms = [];
                                foreach ($taxonomies as $taxonomy) {
                                    if (!in_array($taxonomy, ['post_tag', 'nav_menu', 'link_category', 'post_format'])) {
                                        $post_terms = get_the_terms(get_the_ID(), $taxonomy);
                                        if ($post_terms && !is_wp_error($post_terms)) {
                                            $terms = array_merge($terms, $post_terms);
                                            break; // Prendi solo la prima tassonomia trovata
                                        }
                                    }
                                }
                            }
                            ?>
                            <?php if (!empty($terms)): ?>
                                <span class="elementor-post-loop-meta-item elementor-post-loop-categories">
                                    <i class="far fa-folder" aria-hidden="true"></i>
                                    <?php
                                    $term_links = array_map(function($term) {
                                        return '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                                    }, array_slice($terms, 0, 3)); // Massimo 3 termini
                                    echo implode(', ', $term_links);
                                    ?>
                                </span>
                            <?php endif; ?>
                            <?php break;
                        
                        case 'tags': ?>
                            <?php $tags = get_the_tags(); ?>
                            <?php if (!empty($tags)): ?>
                                <span class="elementor-post-loop-meta-item elementor-post-loop-tags">
                                    <i class="far fa-tags" aria-hidden="true"></i>
                                    <?php
                                    $tag_links = array_map(function($tag) {
                                        return '<a href="' . esc_url(get_tag_link($tag)) . '">' . esc_html($tag->name) . '</a>';
                                    }, array_slice($tags, 0, 3)); // Massimo 3 tag
                                    echo implode(', ', $tag_links);
                                    ?>
                                </span>
                            <?php endif; ?>
                            <?php break;
                    endswitch; ?>
                <?php endforeach; ?>
                
                <?php // Meta specifici per WooCommerce products ?>
                <?php if ($post_type === 'product' && function_exists('wc_get_product')): ?>
                    <?php $product = wc_get_product(get_the_ID()); ?>
                    <?php if ($product): ?>
                        <span class="elementor-post-loop-meta-item elementor-post-loop-price">
                            <i class="far fa-money-bill-alt" aria-hidden="true"></i>
                            <?php echo $product->get_price_html(); ?>
                        </span>
                        <?php if ($product->get_average_rating()): ?>
                            <span class="elementor-post-loop-meta-item elementor-post-loop-rating">
                                <i class="far fa-star" aria-hidden="true"></i>
                                <?php echo esc_html($product->get_average_rating()); ?>/5
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($show_excerpt): ?>
            <div class="elementor-post-loop-excerpt">
                <?php 
                $excerpt = get_the_excerpt();
                if (empty($excerpt) && $post_type !== 'post') {
                    // Per CPT senza excerpt, usa il contenuto
                    $content = get_the_content();
                    $excerpt = wp_strip_all_tags($content);
                }
                echo esc_html(wp_trim_words($excerpt, $excerpt_length ?: 20));
                ?>
            </div>
        <?php endif; ?>
        
        <?php // Campi personalizzati specifici per alcuni CPT ?>
        <?php if ($post_type === 'evento' && function_exists('get_field')): ?>
            <?php $data_evento = get_field('data_evento'); ?>
            <?php if ($data_evento): ?>
                <div class="elementor-post-loop-custom-field elementor-post-loop-event-date">
                    <i class="far fa-calendar-check" aria-hidden="true"></i>
                    <strong><?php echo esc_html(date('d/m/Y', strtotime($data_evento))); ?></strong>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if ($show_read_more): ?>
            <div class="elementor-post-loop-read-more-container">
                <a href="<?php echo esc_url(get_permalink()); ?>" 
                   class="elementor-post-loop-read-more elementor-post-loop-read-more-link"
                   aria-label="<?php echo esc_attr(sprintf(__('Leggi di più su %s', 'elementor-post-loop'), get_the_title())); ?>">
                    <?php echo esc_html($read_more_text ?: __('Leggi di più', 'elementor-post-loop')); ?>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        <?php endif; ?>
        
    </div>
    
    <?php // Schema markup per SEO ?>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "<?php echo $post_type === 'product' ? 'Product' : 'Article'; ?>",
        "headline": "<?php echo esc_js(get_the_title()); ?>",
        "url": "<?php echo esc_url(get_permalink()); ?>",
        "datePublished": "<?php echo esc_js(get_the_date('c')); ?>",
        "dateModified": "<?php echo esc_js(get_the_modified_date('c')); ?>",
        "author": {
            "@type": "Person",
            "name": "<?php echo esc_js(get_the_author()); ?>"
        }<?php if (has_post_thumbnail()): ?>,
        "image": "<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>"<?php endif; ?>
    }
    </script>
</article>