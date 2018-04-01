<?php
/**
 * Theme Customizer - Sidebar
 *
 * @package awps
 */

namespace Awps\Api\Customizer;

use WP_Customize_Color_Control;

/**
 * Customizer class
 */
class Sidebar 
{
	/**
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register( $wp_customize ) 
	{
		$wp_customize->add_section( 'awps_sidebar_section' , array(
			'title' => __( 'Sidebar', 'awps' ),
			'description' => __( 'Customize the Sidebar' ),
			'priority' => 161
		) );   

		$wp_customize->add_setting( 'awps_sidebar_setting' , array(
			'default' => '#000000',
			'transport' => 'postMessage', // or refresh if you want the entire page to reload
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
			'label' => __( 'Header Color', 'awps' ),
			'section' => 'awps_sidebar_section',
			'settings' => 'awps_sidebar_setting',
		) ) );
	}
}