<?php
/**
 * Callbacks for Settings API
 *
 * @package awps
 */

namespace Awps\Api\Callbacks;

/**
 * Settings API Callbacks Class
 */
class SettingsCallback
{
	public function admin_index() 
	{
		return require_once( get_template_directory() . '/views/admin/index.php' );
	}

	public function admin_faq() 
	{
		echo '<div class="wrap"><h1>FAQ Page</h1></div>';
	}

	public function awps_options_group( $input ) 
	{
		return $input;
	}

	public function awps_admin_index() 
	{
		echo 'Customize this Theme Settings section and add description and instructions';
	}

	public function first_name()
	{
		$first_name = esc_attr( get_option( 'first_name' ) );
		echo '<input id="first_name" type="text" class="regular-text" name="first_name" value="'.$first_name.'" placeholder="First Name" />';
	}
}