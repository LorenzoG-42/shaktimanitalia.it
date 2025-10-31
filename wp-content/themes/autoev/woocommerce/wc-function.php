<?php 

//Custom products layout on archive page
add_filter( 'loop_shop_columns', 'autoev_loop_shop_columns', 20 ); 
function autoev_loop_shop_columns() {
	$columns = isset($_GET['col']) ? sanitize_text_field($_GET['col']) : autoev()->get_theme_opt('products_columns', 4);
	return $columns;
}
 

// Change number of products that are displayed per page (shop page)
add_filter( 'loop_shop_per_page', 'autoev_loop_shop_per_page', 20 );
function autoev_loop_shop_per_page( $limit ) {
	$limit = autoev()->get_theme_opt('product_per_page', 9);
	return $limit;
}


/* Remove result count & product ordering & item product category..... */
function autoev_cwoocommerce_remove_function() {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10, 0 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5, 0 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10, 0 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10, 0 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10, 0 );
	remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_sharing', 50 );
}
add_action( 'init', 'autoev_cwoocommerce_remove_function' );

/* Product Category */
add_action( 'woocommerce_before_shop_loop', 'autoev_woocommerce_nav_top', 2 );
function autoev_woocommerce_nav_top() { ?>
	<div class="woocommerce-topbar">
		<div class="woocommerce-result-count">
			<?php woocommerce_result_count(); ?>
		</div>
		<div class="woocommerce-topbar-ordering">
			<?php woocommerce_catalog_ordering(); ?>
		</div>
	</div>
<?php }

add_filter( 'woocommerce_after_shop_loop_item', 'autoev_woocommerce_product' );
function autoev_woocommerce_product() {
	global $product;
	$shop_layout = autoev()->get_theme_opt('shop_layout', 'grid');
	if(isset($_GET['shop-layout'])) {
        $shop_layout = $_GET['shop-layout'];
    }
    $link_video = get_post_meta($product->get_id(), 'link_video', true);
    $product_text_btn = get_post_meta($product->get_id(), 'product_text_btn', true);
    $product_label = get_post_meta($product->get_id(), 'product_label', true);
	?>
	<div class="woocommerce-product-inner item-layout-<?php echo esc_attr($shop_layout); ?>">
		<div class="woocommerce-product-header">
			<a class="woocommerce-product-details" href="<?php the_permalink(); ?>">
				<?php woocommerce_template_loop_product_thumbnail(); ?>
			</a>
			<?php if ( ! $product->managing_stock() && ! $product->is_in_stock() ) { ?>
			<?php } else { ?>
				<div class="woocommerce-add-to-cart">
			    	<?php woocommerce_template_loop_add_to_cart(); ?>
				</div>
			<?php } ?>
			<?php if(!empty($product_label)) : ?>
				<div class="woocommerce-product-label pxl-l-10"><?php echo esc_html($product_label); ?></div>
			<?php endif; ?>
		</div>
		<div class="woocommerce-product-content">
			<div class="woocommerce-product-meta">
				<h5 class="woocommerce-product-title"><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a></h5>
                <div class="woocommerce-product--price">
                    <?php woocommerce_template_loop_price(); ?>
                </div>
			</div>

            <div class="woocommerce-product-video-btn">
                <?php if(!empty($link_video)) : ?>
                    <a class="btn-video" href="<?php echo esc_url($link_video); ?>">
                        <?php if(!empty($product_text_btn)) : ?>
                            <?php echo esc_attr($product_text_btn); ?>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </div>
		</div>
	</div>
<?php }

function autoev_woocommerce_query($type='recent_product',$post_per_page=-1,$product_ids='',$categories='',$param_args=[]){
    global $wp_query;

    $product_visibility_term_ids = wc_get_product_visibility_term_ids();
    if(!empty($product_ids)){

        if (get_query_var('paged')) {
            $pxl_paged = get_query_var('paged');
        } elseif (get_query_var('page')) {
            $pxl_paged = get_query_var('page');
        } else {
            $pxl_paged = 1;
        }

        $pxl_query = new WP_Query(array(
            'post_type' => 'product',
            'post__in' => array_map('intval', explode(',', $product_ids)),
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => is_search() ? $product_visibility_term_ids['exclude-from-search'] : $product_visibility_term_ids['exclude-from-catalog'],
                    'operator' => 'NOT IN',
                )
            ),
        ));
         
        $posts = $pxl_query;

        $categories = [];
    }else{
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $post_per_page,
            'post_status' => 'publish',
            'post_parent' => 0,
            'date_query' => array(
                array(
                   'before' => date('Y-m-d H:i:s', current_time( 'timestamp' ))
                )
            ),
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => is_search() ? $product_visibility_term_ids['exclude-from-search'] : $product_visibility_term_ids['exclude-from-catalog'],
                    'operator' => 'NOT IN',
                )
            ),
        );

        if(!empty($categories)){

            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'operator' => 'IN',
                'terms' => $categories,
            );
        }

        if( !empty($param_args['pro_atts']) ){
            foreach ($param_args['pro_atts'] as $k => $v) {
                $args['tax_query'][] = array(
                    'taxonomy' => $k,
                    'field' => 'slug',
                    'terms' => $v
                );
            }
        }

        $args['meta_query'] = array(
            'relation'    => 'AND'
        );

        if( !empty($param_args['min_price']) && !empty($param_args['max_price'])){ 
            $args['meta_query'][] =   array(
                'key'     => '_price',
                'value'   => array( $param_args['min_price'], $param_args['max_price'] ),
                'compare' => 'BETWEEN',
                'type'    => 'DECIMAL(10,' . wc_get_price_decimals() . ')',
            );
        }

        $args = autoev_product_filter_type_args($type,$args);

        if (get_query_var('paged')){ 
            $pxl_paged = get_query_var('paged'); 
        }elseif(get_query_var('page')){ 
            $pxl_paged = get_query_var('page'); 
        }else{ 
            $pxl_paged = 1; 
        }
        if($pxl_paged > 1){
            $args['paged'] = $pxl_paged;
        }
 
        $posts = $pxl_query = new WP_Query($args);
 
        if (empty($categories)) {
            $product_categories = get_categories(array( 'taxonomy' => 'product_cat' ));
            $categories = array();
            foreach($product_categories as $key => $category){
                $categories[] = $category->slug;
            }
        }
    }
    global $wp_query;
    $wp_query = $pxl_query;
    $pagination = get_the_posts_pagination(array(
        'screen_reader_text' => '',
        'mid_size' => 2,
        'prev_text' => esc_html__('Back', 'autoev'),
        'next_text' => esc_html__('Next', 'autoev'),
    ));
    global $paged;
    $paged = $pxl_paged; 

    
    wp_reset_query(); 
    return array(
        'posts' => $posts,
        'categories' => $categories,
        'query' => $pxl_query,
        'args' => $args,
        'paged' => $paged,
        'max' => $pxl_query->max_num_pages,
        'next_link' => next_posts($pxl_query->max_num_pages, false),
        'total' => $pxl_query->found_posts,
        'pagination' => $pagination
    );
}

function autoev_product_filter_type_args($type,$args){
    switch ($type) {
        case 'best_selling':
            $args['meta_key']='total_sales';
            $args['orderby']='meta_value_num';
            $args['ignore_sticky_posts']   = 1;
            break;
        case 'featured_product':
            $args['ignore_sticky_posts'] = 1;
            $args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'term_taxonomy_id',
                'terms'    => $product_visibility_term_ids['featured'],
            );
            break;
        case 'top_rate':
            $args['meta_key']   ='_wc_average_rating';
            $args['orderby']    ='meta_value_num';
            $args['order']      ='DESC';
            break;
        case 'recent_product':
            $args['orderby']    = 'date';
            $args['order']      = 'DESC';
            break;
        case 'on_sale':
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
        case 'recent_review':
            if($post_per_page == -1) $_limit = 4;
            else $_limit = $post_per_page;
            global $wpdb;
            $query = $wpdb->prepare("SELECT c.comment_post_ID FROM {$wpdb->prefix}posts p, {$wpdb->prefix}comments c WHERE p.ID = c.comment_post_ID AND c.comment_approved > 0 AND p.post_type = 'product' AND p.post_status = 'publish' AND p.comment_count > 0 ORDER BY c.comment_date ASC LIMIT 0, %d", $_limit);
            $results = $wpdb->get_results($query, OBJECT);
            $_pids = array();
            foreach ($results as $re) {
                $_pids[] = $re->comment_post_ID;
            }

            $args['post__in'] = $_pids;
            break;
        case 'deals':
            $args['meta_query'][] = array(
                                 'key' => '_sale_price_dates_to',
                                 'value' => '0',
                                 'compare' => '>');
            $args['post__in'] = wc_get_product_ids_on_sale();
            break;
        case 'separate':
            if ( ! empty( $product_ids ) ) {
                $ids = array_map( 'trim', explode( ',', $product_ids ) );
                if ( 1 === count( $ids ) ) {
                    $args['p'] = $ids[0];
                } else {
                    $args['post__in'] = $ids;
                }
            }
            break;
    }
    return $args;
}


/* Removes the "shop" title on the main shop page */
function autoev_hide_page_title()
{
    return false;
}
add_filter('woocommerce_show_page_title', 'autoev_hide_page_title');

/* Replace text Onsale */
add_filter('woocommerce_sale_flash', 'autoev_custom_sale_text', 10, 3);
function autoev_custom_sale_text($text, $post, $_product)
{
	$regular_price = get_post_meta( get_the_ID(), '_regular_price', true);
	$sale_price = get_post_meta( get_the_ID(), '_sale_price', true);

	$product_sale = '';
	if(!empty($sale_price)) {
		$product_sale = intval( ( (intval($regular_price) - intval($sale_price)) / intval($regular_price) ) * 100);
		return '<span class="onsale">' .esc_html__('Sale', 'autoev'). '</span>';
	}
}

/**
 * Modify image width theme support.
 */
add_filter('woocommerce_get_image_size_gallery_thumbnail', function ($size) {
    $size['width'] = 250;
    $size['height'] = 285;
    $size['crop'] = 1;
    return $size;
});

add_action( 'woocommerce_before_single_product_summary', 'autoev_woocommerce_single_summer_start', 0 );
function autoev_woocommerce_single_summer_start() { ?>
	<?php echo '<div class="woocommerce-summary-wrap row">'; ?>
<?php }
add_action( 'woocommerce_after_single_product_summary', 'autoev_woocommerce_single_summer_end', 5 );
function autoev_woocommerce_single_summer_end() { ?>
	<?php echo '</div></div>'; ?>
<?php }

add_action( 'woocommerce_single_product_summary', 'autoev_woocommerce_sg_product_rating', 5 );
function autoev_woocommerce_sg_product_rating() { global $product; ?>
	<div class="woocommerce-sg-product-rating">
		<?php woocommerce_template_single_rating(); ?>
	</div>
<?php }

add_action( 'woocommerce_single_product_summary', 'autoev_woocommerce_sg_product_title', 10 );
function autoev_woocommerce_sg_product_title() { 
	global $product; 
	$product_title = autoev()->get_theme_opt( 'product_title', false ); 
	if($product_title ) : ?>
		<div class="woocommerce-sg-product-title">
			<?php woocommerce_template_single_title(); ?>
		</div>
<?php endif; }

add_action( 'woocommerce_single_product_summary', 'autoev_woocommerce_sg_product_price', 15 );
function autoev_woocommerce_sg_product_price() { ?>
	<div class="woocommerce-sg-product-price">
		<?php woocommerce_template_single_price(); ?>
	</div>
<?php }

add_action( 'woocommerce_single_product_summary', 'autoev_woocommerce_sg_product_meta', 30 );
function autoev_woocommerce_sg_product_meta() { ?>
	<div class="woocommerce-sg-product-meta">
		<?php woocommerce_template_single_meta(); ?>
	</div>
<?php }


add_action( 'woocommerce_single_product_summary', 'autoev_woocommerce_sg_product_excerpt', 20 );
function autoev_woocommerce_sg_product_excerpt() { ?>
	<div class="woocommerce-sg-product-excerpt">
		<?php woocommerce_template_single_excerpt(); ?>
	</div>
<?php }

add_action( 'woocommerce_single_product_summary', 'autoev_woocommerce_sg_social_share', 40 );
function autoev_woocommerce_sg_social_share() { 
	$product_social_share = autoev()->get_theme_opt( 'product_social_share', false );
	if($product_social_share) : ?>
		<div class="woocommerce-social-share">
			<label><?php echo esc_html__('Share:', 'autoev'); ?></label>
			<a class="fb-social pxl-ml-10" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"><i class="caseicon-facebook"></i></a>
	        <a class="tw-social pxl-ml-10" target="_blank" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>%20"><i class="caseicon-twitter"></i></a>
	        <a class="pin-social pxl-ml-10" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&description=<?php the_title(); ?>%20"><i class="caseicon-pinterest"></i></a>
	        <a class="lin-social pxl-ml-10" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>%20"><i class="caseicon-linkedin"></i></a>
    </div>
<?php endif; }

/* Product Single: Gallery */
add_action( 'woocommerce_before_single_product_summary', 'autoev_woocommerce_single_gallery_start', 0 );
function autoev_woocommerce_single_gallery_start() { ?>
	<?php echo '<div class="woocommerce-gallery col-xl-6 col-lg-6 col-md-6"><div class="woocommerce-gallery-inner">'; ?>
<?php }
add_action( 'woocommerce_before_single_product_summary', 'autoev_woocommerce_single_gallery_end', 30 );
function autoev_woocommerce_single_gallery_end() { ?>
	<?php echo '</div></div><div class="woocommerce-summary-inner col-xl-6 col-lg-6 col-md-6">'; ?>
<?php }

/* Ajax update cart item */
add_filter('woocommerce_add_to_cart_fragments', 'autoev_woo_mini_cart_item_fragment');
function autoev_woo_mini_cart_item_fragment( $fragments ) {
	global $woocommerce;
	$product_subtitle = autoev()->get_page_opt( 'product_subtitle' );
    ob_start();
    ?>
    <div class="widget_shopping_cart">
    	<div class="widget_shopping_head">
    		<div class="pxl-item--close pxl-close pxl-cursor--cta"></div>
	    	<div class="widget_shopping_title">
	    		<?php echo esc_html__( 'Cart', 'autoev' ); ?> <span class="widget_cart_counter">(<?php echo sprintf (_n( '%d item', '%d items', WC()->cart->cart_contents_count, 'autoev' ), WC()->cart->cart_contents_count ); ?>)</span>
	    	</div>
	    </div>
        <div class="widget_shopping_cart_content">
            <?php
            	$cart_is_empty = sizeof( $woocommerce->cart->get_cart() ) <= 0;
            ?>
            <ul class="cart_list product_list_widget">

			<?php if ( ! WC()->cart->is_empty() ) : ?>

				<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

							$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
							$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
							$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
							<li>
								<?php if(!empty($thumbnail)) : ?>
									<div class="cart-product-image">
										<a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>">
											<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
										</a>
									</div>
								<?php endif; ?>
								<div class="cart-product-meta">
									<h3><a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>"><?php echo esc_html($product_name); ?></a></h3>
									<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
									<?php
										echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
											'<a href="%s" class="remove_from_cart_button pxl-close" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"></a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_attr__( 'Remove this item', 'autoev' ),
											esc_attr( $product_id ),
											esc_attr( $cart_item_key ),
											esc_attr( $_product->get_sku() )
										), $cart_item_key );
									?>
								</div>	
							</li>
							<?php
						}
					}
				?>

			<?php else : ?>

				<li class="empty">
					<i class="caseicon-shopping-cart-alt"></i>
					<span><?php esc_html_e( 'Your cart is empty', 'autoev' ); ?></span>
					<a class="btn btn-shop" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php echo esc_html__('Browse Shop', 'autoev'); ?></a>
				</li>

			<?php endif; ?>

			</ul><!-- end product list -->
        </div>
        <?php if ( ! WC()->cart->is_empty() ) : ?>
			<div class="widget_shopping_cart_footer">
				<p class="total"><strong><?php esc_html_e( 'Subtotal', 'autoev' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

				<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

				<p class="buttons">
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-shop wc-forward"><?php esc_html_e( 'View Cart', 'autoev' ); ?></a>
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn checkout wc-forward"><?php esc_html_e( 'Checkout', 'autoev' ); ?></a>
				</p>
			</div>
		<?php endif; ?>
    </div>
    <?php
    $fragments['div.widget_shopping_cart'] = ob_get_clean();
    return $fragments;
}

/* Ajax update cart total number */

add_filter( 'woocommerce_add_to_cart_fragments', 'autoev_woocommerce_sidebar_cart_count_number' );
function autoev_woocommerce_sidebar_cart_count_number( $fragments ) {
	ob_start();
	?>
	<span class="widget_cart_counter">(<?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count, 'autoev' ), WC()->cart->cart_contents_count ); ?>)</span>
	<?php
	
	$fragments['span.widget_cart_counter'] = ob_get_clean();
	
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'autoev_woocommerce_sidebar_cart_count_number_header' );
function autoev_woocommerce_sidebar_cart_count_number_header( $fragments ) {
	ob_start();
	?>
	<span class="widget_cart_counter_header"><?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count, 'autoev' ), WC()->cart->cart_contents_count ); ?></span>
	<?php
	
	$fragments['span.widget_cart_counter_header'] = ob_get_clean();
	
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'autoev_woocommerce_sidebar_cart_count_number_sidebar' );
function autoev_woocommerce_sidebar_cart_count_number_sidebar( $fragments ) {
	ob_start();
	?>
	<span class="ct-cart-count-sidebar"><?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count, 'autoev' ), WC()->cart->cart_contents_count ); ?></span>
	<?php
	
	$fragments['span.ct-cart-count-sidebar'] = ob_get_clean();
	
	return $fragments;
}

add_filter( 'woocommerce_output_related_products_args', 'autoev_related_products_args', 20 );
  function autoev_related_products_args( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns'] = 4;
	return $args;
}

/* Pagination Args */
function autoev_filter_woocommerce_pagination_args( $array ) { 
	$array['end_size'] = 1;
	$array['mid_size'] = 1;
    return $array; 
}; 
add_filter( 'woocommerce_pagination_args', 'autoev_filter_woocommerce_pagination_args', 10, 1 ); 

/* Flex Slider Arrow */
add_filter( 'woocommerce_single_product_carousel_options', 'autoev_update_woo_flexslider_options' );
function autoev_update_woo_flexslider_options( $options ) {
$options['directionNav'] = true;
	return $options;
}