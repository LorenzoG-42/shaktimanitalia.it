<?php

if( !defined( 'ABSPATH' ) )
	exit; 

class Autoev_Admin_Templates extends Autoev_Base{

	public function __construct() {
		$this->add_action( 'admin_menu', 'register_page', 20 );
	}
 
	public function register_page() {
		add_submenu_page(
			'pxlart',
		    esc_html__( 'Templates', 'autoev' ),
		    esc_html__( 'Templates', 'autoev' ),
		    'manage_options',
		    'edit.php?post_type=pxl-template',
		    false
		);
	}
}
new Autoev_Admin_Templates;
