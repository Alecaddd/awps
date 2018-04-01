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
		add_action( 'wp_head', array($this , 'output') );

		$wp_customize->add_section( 'awps_sidebar_section' , array(
			'title' => __( 'Sidebar', 'awps' ),
			'description' => __( 'Customize the Sidebar' ),
			'priority' => 161
		) ); 

		$wp_customize->add_setting( 'awps_sidebar_background_color' , array(
			'default' => '#ffffff',
			'transport' => 'postMessage', // or refresh if you want the entire page to reload
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'background_color', array(
			'label' => __( 'Background Color', 'awps' ),
			'section' => 'awps_sidebar_section',
			'settings' => 'awps_sidebar_background_color',
		) ) );

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial( 'awps_sidebar_background_color', array(
				'selector' => '#sidebar-css',
				'render_callback' => function() {
					echo self::css('.widget-area', 'background-color', 'awps_sidebar_background_color');
				},
				'fallback_refresh' => false
			) );
		}
	}

	/**
	 * This will generate a line of CSS for use in header output. If the setting
	 * ($mod_name) has no defined value, the CSS will not be output.
	 * 
	 * @uses get_theme_mod()
	 * @param string $selector CSS selector
	 * @param string $property The name of the CSS *property* to modify
	 * @param string $mod_name The name of the 'theme_mod' option to fetch
	 * @param bool $echo Optional. Whether to print directly to the page (default: true).
	 * @return string Returns a single line of CSS with selectors and a property.
	 */
	public static function css( $selector, $property, $theme_mod )
	{
		$theme_mod = get_theme_mod($theme_mod);

		if ( ! empty( $theme_mod ) ) {
			return sprintf('%s { %s:%s; }', $selector, $property, $theme_mod );
		}
	}

	public function output()
	{
		echo '<style id="sidebar-css">';
		echo self::css('.widget-area', 'background-color', 'awps_sidebar_background_color');
		echo '</style>';
	}
}