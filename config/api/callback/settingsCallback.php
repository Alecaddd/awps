<?php
/**
 * Callbacks for Settings API
 *
 * @package awps
 */

namespace awps\api\callback;

/**
 * Settings API Callbacks Class
 */
class settingsCallback
{
	public static function awps_options_group( $input ) 
	{
		return $input;
	}

	public static function awps_admin_index() 
	{
		echo 'Customize this Theme Settings section and add description and instructions';
	}

	public static function first_name()
	{
		$first_name = esc_attr( get_option( 'first_name' ) );
		echo '<input type="text" class="regular-text" name="first_name" value="'.$first_name.'" placeholder="First Name" />';
	}
}