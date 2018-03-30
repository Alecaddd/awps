<?php
/**
 * Theme Customizer
 *
 * @package awps
 */

namespace Awps\Api;

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
	 * Add postMessage support for site title and description for the Theme Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function setup( $wp_customize ) 
	{
		$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	public function preview() 
	{
		wp_enqueue_script( 'customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '1.0.0', true );
	}
}
