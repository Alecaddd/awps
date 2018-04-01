<?php
/**
 * Theme Customizer
 *
 * @package awps
 */

namespace Awps\Api;

use Awps\Api\Customizer\Sidebar;
use Awps\Api\Customizer\Header;

/**
 * Customizer class
 */
class Customizer 
{
	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register() 
	{
		add_action( 'customize_register', array( $this, 'setup' ) );
		add_action( 'customize_preview_init', array( $this, 'preview' ) );
	}

	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public function get_classes()
	{
		return [
			Sidebar::class,
			Header::class
		];
	}

	/**
	 * Add postMessage support for site title and description for the Theme Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function setup( $wp_customize ) 
	{
		foreach ( $this->get_classes() as $class ) {
			$service = new $class;
			if ( method_exists( $class, 'register') ) {
				$service->register( $wp_customize );
			}
		}
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	public function preview() 
	{
		wp_enqueue_script( 'awps_customizer', get_template_directory_uri() . '/assets/dist/js/customizer.js', array( 'customize-preview' ), '1.0.0', true );
	}
}
