<?php

/**
* jetpack
* @link https://jetpack.com/
*/
class jetpackController extends baseController {
	
	/*
		Contrusct class to activate actions and hooks as soon as the class is initialized
	*/
	function __construct() {
		
		add_action( 'after_setup_theme', array( &$this,'setup' ) );

	}
		
	public function setup() {
		
		// Add theme support for Infinite Scroll.
		add_theme_support( 'infinite-scroll', array(
			'container' => 'main',
			'render'    => '\\jetpackController::infinite_scroll_render',
			'footer'    => 'page',
		) );
		// Add theme support for Responsive Videos.
		add_theme_support( 'jetpack-responsive-videos' );
		
	}
	
	public function infinite_scroll_render() {
		
		while ( have_posts() ) {
			the_post();
			if ( is_search() ) :
				get_template_part( 'views/content', 'search' );
			else :
				get_template_part( 'views/content', get_post_format() );
			endif;
		}
		
	}
	
}