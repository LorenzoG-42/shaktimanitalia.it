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
?>
<div class="pxl-showcase pxl-showcase2">
	<div class="pxl-item--inner <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
		<div class="pxl-item--image">
			<?php if(!empty($settings['image2']['id'])) : 
				$image_size2 = !empty($settings['img_size2']) ? $settings['img_size2'] : '48x47'; 
				$img2 = pxl_get_image_by_size( array(
					'attach_id'  => $settings['image2']['id'],
					'thumb_size' => $image_size2,
				));
				$thumbnail2 = $img2['thumbnail'];
				?>
				<div class="pxl-item--img2">
					<?php echo pxl_print_html($thumbnail2); ?>
				</div>
			<?php endif; ?>
			<?php if(!empty($settings['image']['id'])) : 
				$image_size = !empty($settings['img_size']) ? $settings['img_size'] : 'full'; 
				$img = pxl_get_image_by_size( array(
					'attach_id'  => $settings['image']['id'],
					'thumb_size' => $image_size,
				));
				$thumbnail = $img['thumbnail'];
				?>
				<div class="pxl-item--img">
					<a <?php pxl_print_html($widget->get_render_attribute_string( 'button_lv1' )); ?>></a>
					<?php echo pxl_print_html($thumbnail); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="pxl-item--meta">
			<?php if(!empty($settings['new'])) : ?>
				<span class="pxl-item-new"><?php echo esc_html($settings['new']); ?></span>
			<?php endif; ?>
            <span class="pxl-item--title"><?php echo esc_html($settings['title']); ?></span>
        </div>
        <div class="pxl-item--sub">
            <?php echo esc_attr($settings['sub_title']); ?>
        </div>
	</div>
</div>