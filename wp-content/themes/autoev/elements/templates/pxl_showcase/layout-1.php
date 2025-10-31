<?php

$html_id = pxl_get_element_id($settings);
if ( ! empty( $settings['button_link_lv1']['url'] ) ) {
    $widget->add_render_attribute( 'button_lv1', 'href', $settings['button_link_lv1']['url'] );

    if ( $settings['button_link_lv1']['is_external'] ) {
        $widget->add_render_attribute( 'button_lv1', 'target', '_blank' );
    }

    if ( $settings['button_link_lv1']['nofollow'] ) {
        $widget->add_render_attribute( 'button_lv1', 'rel', 'nofollow' );
    }
}

if ( ! empty( $settings['button_link_lv2']['url'] ) ) {
    $widget->add_render_attribute( 'button_lv2', 'href', $settings['button_link_lv2']['url'] );

    if ( $settings['button_link_lv2']['is_external'] ) {
        $widget->add_render_attribute( 'button_lv2', 'target', '_blank' );
    }

    if ( $settings['button_link_lv2']['nofollow'] ) {
        $widget->add_render_attribute( 'button_lv2', 'rel', 'nofollow' );
    }
}
?>
<div class="pxl-showcase pxl-showcase1">
	<div class="pxl-item--inner <?php echo esc_attr($settings['style']); ?> <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
		<div class="pxl-item--image">
			<?php if(!empty($settings['image']['id'])) : 
				$image_size = !empty($settings['img_size']) ? $settings['img_size'] : 'full'; 
				$img = pxl_get_image_by_size( array(
					'attach_id'  => $settings['image']['id'],
					'thumb_size' => $image_size,
				));
				$thumbnail = $img['thumbnail'];
				?>
				<div class="pxl-item--imgprimary">
					<?php echo pxl_print_html($thumbnail); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="pxl-item--buttons">
			<?php if(!empty($settings['button_text_lv1'])) : ?>
	            <div class="pxl-item--button">
	                <a class="btn btn-primary" <?php pxl_print_html($widget->get_render_attribute_string( 'button_lv1' )); ?>><?php echo esc_html($settings['button_text_lv1']); ?></a>
	            </div>
	        <?php endif; ?>
	        <?php if(!empty($settings['button_text_lv2'])) : ?>
		        <div class="pxl-item--button">
	                <a class="btn btn-primary" <?php pxl_print_html($widget->get_render_attribute_string( 'button_lv2' )); ?>><?php echo esc_html($settings['button_text_lv2']); ?></a>
	            </div>
            <?php endif; ?>
		</div>
		<div class="pxl-item--meta">
            <h5 class="pxl-item--title"><?php echo esc_html($settings['title']); ?></h5>
        </div>
	</div>
</div>