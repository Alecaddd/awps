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
	public static function awps_sanitize_test( $input ) {
		dd( $input );
	}
}