<?php
/**
 * Admin Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class TS_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=technical_sheet',
            __('Settings', 'technical-sheets'),
            __('Settings', 'technical-sheets'),
            'manage_options',
            'technical-sheets-settings',
            array($this, 'settings_page')
        );
    }
    
    /**
     * Admin init
     */
    public function admin_init() {
        register_setting('technical_sheets_settings', 'technical_sheets_options');
        
        add_settings_section(
            'technical_sheets_general_section',
            __('General Settings', 'technical-sheets'),
            array($this, 'general_section_callback'),
            'technical_sheets_settings'
        );
        
        add_settings_field(
            'archive_posts_per_page',
            __('Posts per page in archive', 'technical-sheets'),
            array($this, 'archive_posts_per_page_callback'),
            'technical_sheets_settings',
            'technical_sheets_general_section'
        );
        
        add_settings_field(
            'enable_pdf_export',
            __('Enable PDF Export', 'technical-sheets'),
            array($this, 'enable_pdf_export_callback'),
            'technical_sheets_settings',
            'technical_sheets_general_section'
        );
        
        add_settings_field(
            'enable_print_styles',
            __('Enable Print Styles', 'technical-sheets'),
            array($this, 'enable_print_styles_callback'),
            'technical_sheets_settings',
            'technical_sheets_general_section'
        );
        
        add_settings_field(
            'hero_background_image',
            __('Hero Background Image', 'technical-sheets'),
            array($this, 'hero_background_image_callback'),
            'technical_sheets_settings',
            'technical_sheets_general_section'
        );
    }
    
    /**
     * Settings page
     */
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Technical Sheets Settings', 'technical-sheets'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('technical_sheets_settings');
                do_settings_sections('technical_sheets_settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * General section callback
     */
    public function general_section_callback() {
        echo '<p>' . __('Configure general settings for Technical Sheets.', 'technical-sheets') . '</p>';
    }
    
    /**
     * Archive posts per page callback
     */
    public function archive_posts_per_page_callback() {
        $options = get_option('technical_sheets_options');
        $value = isset($options['archive_posts_per_page']) ? $options['archive_posts_per_page'] : 12;
        ?>
        <input type="number" name="technical_sheets_options[archive_posts_per_page]" value="<?php echo esc_attr($value); ?>" min="1" max="100">
        <?php
    }
    
    /**
     * Enable PDF export callback
     */
    public function enable_pdf_export_callback() {
        $options = get_option('technical_sheets_options');
        $value = isset($options['enable_pdf_export']) ? $options['enable_pdf_export'] : 1;
        ?>
        <input type="checkbox" name="technical_sheets_options[enable_pdf_export]" value="1" <?php checked($value, 1); ?>>
        <label><?php _e('Enable PDF export functionality', 'technical-sheets'); ?></label>
        <?php
    }
    
    /**
     * Enable print styles callback
     */
    public function enable_print_styles_callback() {
        $options = get_option('technical_sheets_options');
        $value = isset($options['enable_print_styles']) ? $options['enable_print_styles'] : 1;
        ?>
        <input type="checkbox" name="technical_sheets_options[enable_print_styles]" value="1" <?php checked($value, 1); ?>>
        <label><?php _e('Enable print-optimized styles', 'technical-sheets'); ?></label>
        <?php
    }
    
    /**
     * Hero background image callback
     */
    public function hero_background_image_callback() {
        $options = get_option('technical_sheets_options');
        $image_id = isset($options['hero_background_image']) ? $options['hero_background_image'] : '';
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'full') : '';
        ?>
        <div class="ts-hero-bg-image-wrapper">
            <input type="hidden" name="technical_sheets_options[hero_background_image]" id="ts_hero_bg_image_id" value="<?php echo esc_attr($image_id); ?>">
            <div class="ts-hero-bg-preview">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="Hero Background" style="max-width: 300px; height: auto;">
                <?php endif; ?>
            </div>
            <button type="button" class="button ts-upload-hero-bg-btn"><?php _e('Carica Immagine Hero', 'technical-sheets'); ?></button>
            <?php if ($image_url) : ?>
                <button type="button" class="button ts-remove-hero-bg-btn"><?php _e('Rimuovi Immagine', 'technical-sheets'); ?></button>
            <?php endif; ?>
            <p class="description"><?php _e('Immagine di sfondo per la hero section delle schede tecniche', 'technical-sheets'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'technical_sheet_page_technical-sheets-settings') {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script('jquery');
        
        wp_add_inline_script('jquery', "
            jQuery(document).ready(function($) {
                var mediaUploader;
                
                $('.ts-upload-hero-bg-btn').on('click', function(e) {
                    e.preventDefault();
                    
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }
                    
                    mediaUploader = wp.media({
                        title: 'Seleziona Immagine Hero',
                        button: {
                            text: 'Usa questa immagine'
                        },
                        multiple: false
                    });
                    
                    mediaUploader.on('select', function() {
                        var attachment = mediaUploader.state().get('selection').first().toJSON();
                        $('#ts_hero_bg_image_id').val(attachment.id);
                        $('.ts-hero-bg-preview').html('<img src=\"' + attachment.url + '\" style=\"max-width: 300px; height: auto;\">');
                        if ($('.ts-remove-hero-bg-btn').length === 0) {
                            $('.ts-upload-hero-bg-btn').after('<button type=\"button\" class=\"button ts-remove-hero-bg-btn\">Rimuovi Immagine</button>');
                        }
                    });
                    
                    mediaUploader.open();
                });
                
                $(document).on('click', '.ts-remove-hero-bg-btn', function(e) {
                    e.preventDefault();
                    $('#ts_hero_bg_image_id').val('');
                    $('.ts-hero-bg-preview').html('');
                    $(this).remove();
                });
            });
        ");
    }
}