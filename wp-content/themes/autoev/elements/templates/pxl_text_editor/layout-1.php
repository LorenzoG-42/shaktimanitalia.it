<?php
$editor_content = $widget->get_settings_for_display( 'text_ed' );
$editor_content = $widget->parse_text_editor( $editor_content );

$widget->add_render_attribute( 'pxl_text_wrap', 'id', pxl_get_element_id($settings));
$widget->add_render_attribute( 'pxl_text_wrap', 'class', 'pxl-image-wg');
$widget->add_render_attribute( 'pxl_text_wrap', 'duration', '1');
if(!empty($settings['custom_style']))
    $widget->add_render_attribute( 'pxl_text_wrap', 'class', $settings['custom_style']);

if(!empty($settings['pxl_parallax'])){
    $parallax_settings = json_encode([
        $settings['pxl_parallax'] => $settings['parallax_value']
    ]);
    $widget->add_render_attribute( 'pxl_text_wrap', 'data-parallax', $parallax_settings );
}
?>
<div <?php pxl_print_html($widget->get_render_attribute_string( 'pxl_text_wrap' )); ?>>
	<div class="pxl-text-editor">
		<div class="pxl-item--inner <?php echo esc_attr($settings['pxl_animate']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
			<?php echo wp_kses_post($editor_content); ?>		
		</div>
	</div>
</div>