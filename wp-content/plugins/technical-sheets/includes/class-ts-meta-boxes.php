<?php
/**
 * Meta Boxes Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Meta_Boxes {
    
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'ts_gallery_meta_box',
            __('Gallery Images', 'technical-sheets'),
            array($this, 'gallery_meta_box_callback'),
            'technical_sheet',
            'normal',
            'high'
        );
        
        add_meta_box(
            'ts_basic_info_meta_box',
            __('Basic Information Table', 'technical-sheets'),
            array($this, 'basic_info_meta_box_callback'),
            'technical_sheet',
            'normal',
            'high'
        );
        
        add_meta_box(
            'ts_accordion_meta_box',
            __('Accordion Sections', 'technical-sheets'),
            array($this, 'accordion_meta_box_callback'),
            'technical_sheet',
            'normal',
            'high'
        );
        
        add_meta_box(
            'ts_pdf_attachment_meta_box',
            __('PDF Attachment', 'technical-sheets'),
            array($this, 'pdf_attachment_meta_box_callback'),
            'technical_sheet',
            'side',
            'default'
        );
    }
    
    /**
     * Gallery meta box callback
     */
    public function gallery_meta_box_callback($post) {
        wp_nonce_field('ts_gallery_meta_box', 'ts_gallery_meta_box_nonce');
        
        $gallery_images = get_post_meta($post->ID, '_ts_gallery_images', true);
        if (!is_array($gallery_images)) {
            $gallery_images = array();
        }
        ?>
        <div id="ts-gallery-container" style="diplay:none">
            <div id="ts-gallery-images" class="ts-sortable-container">
                <?php foreach ($gallery_images as $index => $image_id): ?>
                    <div class="ts-gallery-item ts-sortable-item" data-index="<?php echo $index; ?>">
                        <div class="ts-controls">
                            <div class="ts-arrow-controls">
                                <div class="ts-drag-handle" title="<?php _e('Drag to reorder', 'technical-sheets'); ?>">
                                    <span class="dashicons dashicons-menu"></span>
                                </div>
                                <button type="button" class="ts-move-up" title="<?php _e('Move up', 'technical-sheets'); ?>">
                                    <span class="dashicons dashicons-arrow-up-alt2"></span>
                                </button>
                                <button type="button" class="ts-move-down" title="<?php _e('Move down', 'technical-sheets'); ?>">
                                    <span class="dashicons dashicons-arrow-down-alt2"></span>
                                </button>
                            </div>
                        </div>
                        <img src="<?php echo wp_get_attachment_image_url($image_id, 'thumbnail'); ?>" alt="">
                        <input type="hidden" name="ts_gallery_images[]" value="<?php echo $image_id; ?>">
                        <button type="button" class="remove-gallery-image">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-gallery-image" class="button"><?php _e('Add Image', 'technical-sheets'); ?></button>
        </div>
        <?php
    }
    
    /**
     * Basic info meta box callback
     */
    public function basic_info_meta_box_callback($post) {
        wp_nonce_field('ts_basic_info_meta_box', 'ts_basic_info_meta_box_nonce');
        
        $basic_info = get_post_meta($post->ID, '_ts_basic_info', true);
        if (!is_array($basic_info)) {
            $basic_info = array();
        }
        ?>
        <div id="ts-basic-info-container">
            <div id="ts-basic-info-rows" class="ts-sortable-container">
                <?php foreach ($basic_info as $index => $row): ?>
                    <div class="ts-basic-info-row ts-sortable-item" data-index="<?php echo $index; ?>">
                        <div class="ts-controls">

                            <div class="ts-arrow-controls">
                                <div class="ts-drag-handle" title="<?php _e('Drag to reorder', 'technical-sheets'); ?>">
                                    <span class="dashicons dashicons-menu"></span>
                                </div>
                                <button type="button" class="ts-move-up" title="<?php _e('Move up', 'technical-sheets'); ?>">
                                    <span class="dashicons dashicons-arrow-up-alt2"></span>
                                </button>
                                <button type="button" class="ts-move-down" title="<?php _e('Move down', 'technical-sheets'); ?>">
                                    <span class="dashicons dashicons-arrow-down-alt2"></span>
                                </button>
                            </div>
                        </div>
                        <div class="ts-field-inputs">
                            <input type="text" name="ts_basic_info[<?php echo $index; ?>][label]" value="<?php echo esc_attr($row['label']); ?>" placeholder="<?php _e('Label', 'technical-sheets'); ?>">
                            <input type="text" name="ts_basic_info[<?php echo $index; ?>][value]" value="<?php echo esc_attr($row['value']); ?>" placeholder="<?php _e('Value', 'technical-sheets'); ?>">
                        </div>
                        <button type="button" class="remove-basic-info-row">×</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-basic-info-row" class="button"><?php _e('Add Row', 'technical-sheets'); ?></button>
        </div>
        <?php
    }
    
    /**
     * Accordion meta box callback
     */
    public function accordion_meta_box_callback($post) {
        wp_nonce_field('ts_accordion_meta_box', 'ts_accordion_meta_box_nonce');
        
        $accordion_sections = get_post_meta($post->ID, '_ts_accordion_sections', true);
        if (!is_array($accordion_sections)) {
            $accordion_sections = array();
        }
        ?>
        <div id="ts-accordion-container">
            <div id="ts-accordion-sections" class="ts-sortable-container">
                <?php foreach ($accordion_sections as $index => $section): ?>
                    <div class="ts-accordion-section ts-sortable-item" data-index="<?php echo $index; ?>">
                        <div class="ts-accordion-section-header">
                            <div class="ts-controls">

                                <div class="ts-arrow-controls">
                                    <div class="ts-drag-handle" title="<?php _e('Drag to reorder', 'technical-sheets'); ?>">
                                        <span class="dashicons dashicons-menu"></span>
                                    </div>
                                    <button type="button" class="ts-move-up" title="<?php _e('Move up', 'technical-sheets'); ?>">
                                        <span class="dashicons dashicons-arrow-up-alt2"></span>
                                    </button>
                                    <button type="button" class="ts-move-down" title="<?php _e('Move down', 'technical-sheets'); ?>">
                                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                                    </button>
                                </div>
                            </div>
                            <h4 class="ts-accordion-toggle" data-section="<?php echo $index; ?>">
                                <span class="ts-section-number"><?php _e('Section', 'technical-sheets'); ?> <?php echo $index + 1; ?></span>
                                <span class="ts-section-title"><?php echo !empty($section['title']) ? ' - ' . esc_html($section['title']) : ''; ?></span>
                                <span class="ts-collapse-icon dashicons dashicons-arrow-down-alt2"></span>
                            </h4>
                            <button type="button" class="remove-accordion-section button"><?php _e('Remove Section', 'technical-sheets'); ?></button>
                        </div>
                        <div class="ts-accordion-section-content collapsed" data-section="<?php echo $index; ?>">
                            <p>
                                <label><?php _e('Title', 'technical-sheets'); ?>:</label>
                                <input type="text" name="ts_accordion_sections[<?php echo $index; ?>][title]" value="<?php echo esc_attr($section['title']); ?>" class="ts-section-title-input">
                            </p>
                            <p>
                                <label><?php _e('Image', 'technical-sheets'); ?>:</label>
                                <input type="hidden" name="ts_accordion_sections[<?php echo $index; ?>][image]" value="<?php echo esc_attr($section['image']); ?>">
                                <button type="button" class="select-accordion-image button"><?php _e('Select Image', 'technical-sheets'); ?></button>
                                <?php if (!empty($section['image'])): ?>
                                    <img src="<?php echo wp_get_attachment_image_url($section['image'], 'thumbnail'); ?>" class="accordion-image-preview" alt="">
                                    <button type="button" class="remove-accordion-image button"><?php _e('Remove Image', 'technical-sheets'); ?></button>
                                <?php endif; ?>
                            </p>
                            <p>
                                <label><?php _e('Content', 'technical-sheets'); ?>:</label>
                                <textarea name="ts_accordion_sections[<?php echo $index; ?>][content]" rows="5"><?php echo esc_textarea($section['content']); ?></textarea>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="add-accordion-section" class="button"><?php _e('Add Section', 'technical-sheets'); ?></button>
        </div>
        <?php
    }
    
    /**
     * PDF attachment meta box callback
     */
    public function pdf_attachment_meta_box_callback($post) {
        wp_nonce_field('ts_pdf_attachment_meta_box', 'ts_pdf_attachment_meta_box_nonce');
        
        $pdf_attachment_id = get_post_meta($post->ID, '_ts_pdf_attachment', true);
        $pdf_url = '';
        $pdf_filename = '';
        
        if ($pdf_attachment_id) {
            $pdf_url = wp_get_attachment_url($pdf_attachment_id);
            $pdf_filename = basename($pdf_url);
        }
        ?>
        <div id="ts-pdf-attachment-container">
            <input type="hidden" id="ts_pdf_attachment" name="ts_pdf_attachment" value="<?php echo esc_attr($pdf_attachment_id); ?>">
            
            <div id="ts-pdf-preview">
                <?php if ($pdf_attachment_id): ?>
                    <p><strong><?php _e('Current PDF:', 'technical-sheets'); ?></strong></p>
                    <p>
                        <a href="<?php echo esc_url($pdf_url); ?>" target="_blank"><?php echo esc_html($pdf_filename); ?></a>
                    </p>
                <?php else: ?>
                    <p><?php _e('No PDF selected', 'technical-sheets'); ?></p>
                <?php endif; ?>
            </div>
            
            <p>
                <button type="button" id="select-pdf-attachment" class="button"><?php _e('Select PDF', 'technical-sheets'); ?></button>
                <?php if ($pdf_attachment_id): ?>
                    <button type="button" id="remove-pdf-attachment" class="button"><?php _e('Remove PDF', 'technical-sheets'); ?></button>
                <?php endif; ?>
            </p>
        </div>
        <?php
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check if this is a quick edit (inline edit)
        // Quick edit doesn't include our custom fields, so we should skip saving to prevent data loss
        if (isset($_POST['_inline_edit']) || isset($_POST['action']) && $_POST['action'] === 'inline-save') {
            return;
        }
        
        // Check if this is a bulk edit
        if (isset($_POST['bulk_edit'])) {
            return;
        }
        
        // Check the post type
        if (!isset($_POST['post_type']) || $_POST['post_type'] !== 'technical_sheet') {
            return;
        }
        
        // Check if user has permission to edit
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save gallery images
        if (isset($_POST['ts_gallery_images']) && is_array($_POST['ts_gallery_images'])) {
            $gallery_images = array_map('intval', $_POST['ts_gallery_images']);
            update_post_meta($post_id, '_ts_gallery_images', $gallery_images);
        } else {
            delete_post_meta($post_id, '_ts_gallery_images');
        }
        
        // Save basic info
        if (isset($_POST['ts_basic_info']) && is_array($_POST['ts_basic_info'])) {
            $basic_info = array();
            foreach ($_POST['ts_basic_info'] as $row) {
                if (!empty($row['label']) && !empty($row['value'])) {
                    $basic_info[] = array(
                        'label' => sanitize_text_field($row['label']),
                        'value' => sanitize_text_field($row['value'])
                    );
                }
            }
            update_post_meta($post_id, '_ts_basic_info', $basic_info);
        } else {
            delete_post_meta($post_id, '_ts_basic_info');
        }
        
        // Save accordion sections
        if (isset($_POST['ts_accordion_sections']) && is_array($_POST['ts_accordion_sections'])) {
            $accordion_sections = array();
            foreach ($_POST['ts_accordion_sections'] as $section) {
                if (!empty($section['title']) && !empty($section['content'])) {
                    $accordion_sections[] = array(
                        'title' => sanitize_text_field($section['title']),
                        'content' => sanitize_textarea_field($section['content']),
                        'image' => !empty($section['image']) ? intval($section['image']) : ''
                    );
                }
            }
            update_post_meta($post_id, '_ts_accordion_sections', $accordion_sections);
        } else {
            delete_post_meta($post_id, '_ts_accordion_sections');
        }
        
        // Save PDF attachment
        if (isset($_POST['ts_pdf_attachment'])) {
            $pdf_attachment_id = intval($_POST['ts_pdf_attachment']);
            if ($pdf_attachment_id) {
                update_post_meta($post_id, '_ts_pdf_attachment', $pdf_attachment_id);
            } else {
                delete_post_meta($post_id, '_ts_pdf_attachment');
            }
        }
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }
        
        global $post;
        if ($post->post_type !== 'technical_sheet') {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('ts-admin-script', TECHNICAL_SHEETS_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'), TECHNICAL_SHEETS_VERSION, true);
        wp_enqueue_style('ts-admin-style', TECHNICAL_SHEETS_PLUGIN_URL . 'assets/css/admin.css', array(), TECHNICAL_SHEETS_VERSION);
        
        // Add inline script for debugging
        /*
        wp_add_inline_script('ts-admin-script', '
            console.log("jQuery version:", jQuery.fn.jquery);
            console.log("jQuery UI version:", jQuery.ui ? jQuery.ui.version : "not loaded");
            console.log("Sortable available:", typeof jQuery.fn.sortable !== "undefined");
        ', 'after');
        */
        
        // Add jQuery UI fallback if not loaded
        wp_add_inline_script('ts-admin-script', '
            if (typeof jQuery.fn.sortable === "undefined") {
                console.log("Loading jQuery UI from CDN...");
                var script = document.createElement("script");
                script.src = "https://code.jquery.com/ui/1.13.0/jquery-ui.min.js";
                script.onload = function() {
                    console.log("jQuery UI loaded from CDN");
                    if (typeof tsInitSortable === "function") {
                        tsInitSortable();
                    }
                };
                document.head.appendChild(script);
            }
        ', 'before');
    }
}
