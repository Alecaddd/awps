<?php

namespace awps;

/**
* header
*/
class header {

	/*
		Contrusct class to activate actions and hooks as soon as the class is initialized
	*/
	public function __construct() {

		add_action( 'after_setup_theme', array( &$this,'setup' ) );

	}

	public function setup() {
		/*
			Setup the custom header
		*/
		add_theme_support( 'custom-header', apply_filters( 'awps_custom_header_args', array(
			'default-image'          => '',
			'default-text-color'     => '000000',
			'width'                  => 1000,
			'height'                 => 250,
			'flex-height'            => true,
			'wp-head-callback'       => array( &$this,'header_style' ),
		) ) );

	}

	static public function header_style() {

		$header_text_color = get_header_textcolor();

		if ( HEADER_TEXTCOLOR === $header_text_color ) {
			return;
		}

		/*
			This is not the best... >_>
		*/
		$output = '<style type="text/css">';

		if ( ! display_header_text() ) :
			$output .= '.site-title,.site-description{position:absolute;clip:rect(1px, 1px, 1px, 1px);}';
		else :
			$output .= '.site-title a,.site-description{color:#' . esc_attr( $header_text_color ) . ';}';
		endif;

		$output .= '</style>';

		echo $output;

	}

}
