<?php
/**
 * Settings API
 *
 * @package awps
 */

namespace awps\api;

/**
 * Settings API Class
 */
class settings
{
	/**
	 * Sections array
	 * @var private array
	 */
	private static $sections = array();

	/**
	 * Fields array
	 * @var private array
	 */
	private static $fields = array();

	/**
	 * Script path
	 * @var string
	 */
	private static $script_path;

	/**
	 * Enqueues array
	 * @var private array
	 */
	private static $enqueues = array();

	/**
	 * Enqueue scripts if $eneuques not empty
	 */
	public function __construct()
	{
		if ( !empty( self::$enqueues ) )
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );
	}

	/**
	 * Dinamically enqueue styles and scripts in admin area
	 *
	 * @param  array  $scripts file paths or wp related keywords of embedded files
	 * @param  string $type    style | script
	 * @return null
	 */
	public static function admin_enqueue( $scripts = array() )
	{
		if ( empty( $scripts ) )
			return;

		$i = 0;
		foreach ( $scripts as $key => $value ) :
			foreach ( $value as $val ):
				self::$enqueues[ $i ] = self::enqueue_script( $val, $key );
				$i++;
			endforeach;
		endforeach;

		return;
	}

	/**
	 * Call the right WP functions based on the file or string passed
	 *
	 * @param  array $script  file path or wp related keyword of embedded file
	 * @param  var $type      style | script
	 * @return variable functions
	 */
	private static function enqueue_script( $script, $type ) {
		if ( $script === 'media_uplaoder' )
			return 'wp_enqueue_media';

		self::$script_path = pathinfo( $script );

		if ( isset( self::$script_path[ 'extension' ] ) ) :
			return ( $type === 'style' ) ? [ 'wp_enqueue_style' => get_template_directory_uri() . $script ] : [ 'wp_enqueue_script' => get_template_directory_uri() . $script ];
		else :
			return ( $type === 'style' ) ? [ 'wp_enqueue_style' => $script ] : [ 'wp_enqueue_script' => $script ];
		endif;
	}

	/**
	 * Print the methods to be called by the admin_enqueue_scripts hook
	 * @return null
	 */
	public function admin_scripts()
	{
		// dd(self::$enqueues);
		foreach ( self::$enqueues as $enqueue ) :
			if ( $enqueue === 'wp_enqueue_media' ) :
				$enqueue();
			else :
				foreach( $enqueue as $key => $val ) {
					$key($val, $val);
				}
			endif;
		endforeach;
	}
}
