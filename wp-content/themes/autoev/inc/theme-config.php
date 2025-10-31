<?php if(!function_exists('autoev_configs')){
    function autoev_configs($value){
        $configs = [
            'theme_colors' => [
                'primary'   => [
                    'title' => esc_html__('Primary', 'autoev'), 
                    'value' => autoev()->get_opt('primary_color', '#71ca00')
                ],
                'secondary'   => [
                    'title' => esc_html__('Secondary', 'autoev'), 
                    'value' => autoev()->get_opt('secondary_color', '#707070')
                ],
                'body-bg'   => [
                    'title' => esc_html__('Body Background Color', 'autoev'), 
                    'value' => autoev()->get_page_opt('body_bg_color', '#fff')
                ]
            ],
            'link' => [
                'color' => autoev()->get_opt('link_color', ['regular' => '#151515'])['regular'],
                'color-hover'   => autoev()->get_opt('link_color', ['hover' => '#71ca00'])['hover'],
                'color-active'  => autoev()->get_opt('link_color', ['active' => '#71ca00'])['active'],
            ],
            'gradient' => [
                'color-from' => autoev()->get_opt('gradient_color', ['from' => '#71ca00'])['from'],
                'color-to' => autoev()->get_opt('gradient_color', ['to' => '#f0f1f3'])['to'],
            ],
               
        ];
        return $configs[$value];
    }
}
if(!function_exists('autoev_inline_styles')) {
    function autoev_inline_styles() {  
        
        $theme_colors      = autoev_configs('theme_colors');
        $link_color        = autoev_configs('link');
        $gradient_color    = autoev_configs('gradient');
        ob_start();
        echo ':root{';
            
            foreach ($theme_colors as $color => $value) {
                printf('--%1$s-color: %2$s;', str_replace('#', '',$color),  $value['value']);
            }
            foreach ($theme_colors as $color => $value) {
                printf('--%1$s-color-rgb: %2$s;', str_replace('#', '',$color),  autoev_hex_rgb($value['value']));
            }
            foreach ($link_color as $color => $value) {
                printf('--link-%1$s: %2$s;', $color, $value);
            }
            foreach ($gradient_color as $color => $value) {
                printf('--gradient-%1$s: %2$s;', $color, $value);
            }
        echo '}';

        return ob_get_clean();
         
    }
}
 