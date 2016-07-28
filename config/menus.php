<?php

/**
* menus
*/
class menusController extends baseController {
	
	/*
		Contrusct class to activate actions and hooks as soon as the class is initialized
	*/
	function __construct() {
		
		add_action( 'after_setup_theme', array( &$this,'menus' ) );

	}
		
	public function menus() {
		/*
			Register all your menus here
		*/
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'awps' ),
		) );
		
	}
	
}