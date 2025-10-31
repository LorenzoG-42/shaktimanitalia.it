<?php
$html_id = pxl_get_element_id($settings);
$editor_title = $widget->get_settings_for_display( 'title' );
$editor_title = $widget->parse_text_editor( $editor_title );
$custom_ptitle = get_post_meta(get_the_ID(), 'custom_ptitle', true); ?>
<div id="pxl-<?php echo esc_attr($html_id) ?>" class="pxl-heading <?php echo esc_attr($settings['sub_title_style']); ?>-style">
	<div class="pxl-heading--inner">
		<?php if($settings['title_style'] == 'px-title--divider2') : ?>
			<div class="px-divider--wrap <?php echo esc_attr($settings['pxl_animate_divider']); ?>">
				<div class="px-title--divider px-title--divider2">
					<span></span>
				</div>
			</div>
		<?php endif; ?>

		<?php if(!empty($settings['sub_title']) && $settings['sub_title_style'] !== 'px-sub-title-under') : ?>
			<div class="pxl-item--subtitle <?php echo esc_attr($settings['sub_title_style'].' '.$settings['pxl_animate_sub']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay_sub']); ?>ms">
				<?php if ( $settings['icon_type'] == 'icon' && !empty($settings['pxl_icon']['value']) ) : ?>
	                <div class="pxl-item--icon">
	                    <?php \Elementor\Icons_Manager::render_icon( $settings['pxl_icon'], [ 'aria-hidden' => 'true', 'class' => '' ], 'i' ); ?>
	                </div>
	            <?php endif; ?>
				<span class="pxl-item--subtext">
					<?php echo esc_attr($settings['sub_title']); ?>
				</span>
			</div>
		<?php endif; ?>

		<<?php echo esc_attr($settings['title_tag']); ?> class="pxl-item--title <?php if($settings['pxl_animate'] !== 'wow letter') { echo esc_attr($settings['pxl_animate']); } ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay']); ?>ms">
			<?php if($settings['source_type'] == 'text' && !empty($editor_title)) {
				if($settings['pxl_animate'] == 'wow letter') {
					$arr_str = explode(' ', $editor_title); ?>
					<span class="pxl-item--text">
			            <?php foreach ($arr_str as $index => $value) {
			                $arr_str[$index] = '<span class="pxl-text--slide"><span class="'.$settings['pxl_animate'].'">' . $value . '</span></span>';
			            }
			            $str = implode(' ', $arr_str);
			            echo wp_kses_post($str); ?>
			        </span>
				<?php } else {
					echo wp_kses_post($editor_title);
				} 
			} elseif($settings['source_type'] == 'title') {
				$titles = autoev()->page->get_title();
				if(!empty($_GET['blog_title'])) {
					$blog_title = $_GET['blog_title'];
					$custom_title = explode('_', $blog_title);
					foreach ($custom_title as $index => $value) {
	                    $arr_str_b[$index] = $value;
	                }
	                $str = implode(' ', $arr_str_b);
	                echo wp_kses_post($str);
				} elseif(!empty($custom_ptitle)) {
					echo esc_attr($custom_ptitle);
				} else {
					pxl_print_html($titles['title']);
				}
			}?>		
		</<?php echo esc_attr($settings['title_tag']); ?>>

		<?php if(!empty($settings['sub_title']) && $settings['sub_title_style'] == 'px-sub-title-under') : ?>
			<div class="pxl-item--subtitle <?php echo esc_attr($settings['sub_title_style'].' '.$settings['pxl_animate_sub']); ?>" data-wow-delay="<?php echo esc_attr($settings['pxl_animate_delay_sub']); ?>ms">
				<span class="pxl-item--subtext">
					<?php echo esc_attr($settings['sub_title']); ?>
				</span>
			</div>
		<?php endif; ?>

		<?php if($settings['title_style'] == 'px-title--divider1') : ?>
			<div class="px-divider--wrap <?php echo esc_attr($settings['pxl_animate_divider']); ?>">
				<div class="px-title--divider px-title--divider1">
					<span></span>
				</div>
			</div>
		<?php endif; ?>

		<?php if($settings['title_style'] == 'px-title--divider3') : ?>
			<div class="px-divider--wrap <?php echo esc_attr($settings['pxl_animate_divider']); ?>">
				<div class="px-title--divider px-title--divider3"></div>
			</div>
		<?php endif; ?>
		
	</div>
</div>