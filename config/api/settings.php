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
	 * Admin pages array to enqueue scripts
	 * @var private array
	 */
	private static $enqueue_on_pages = array();

	private static $admin_pages = array();
	private static $admin_subpages = array();

	/**
	 * Enqueue scripts if $eneuques not empty
	 */
	public function __construct()
	{
		if ( !empty( self::$enqueues ) )
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );

		if ( !empty( self::$admin_pages ) )
			add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );
	}

	/**
	 * Dinamically enqueue styles and scripts in admin area
	 *
	 * @param  array  $scripts file paths or wp related keywords of embedded files
	 * @param  array $page    pages id where to load scripts
	 * @return null
	 */
	public static function admin_enqueue( $scripts = array(), $pages = array() )
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

		if ( !empty( $pages ) ) :
			self::$enqueue_on_pages = $pages;
		endif;
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
			return ( $type === 'style' ) ? array( 'wp_enqueue_style' => get_template_directory_uri() . $script ) : array( 'wp_enqueue_script' => get_template_directory_uri() . $script );
		else :
			return ( $type === 'style' ) ? array( 'wp_enqueue_style' => $script ) : array( 'wp_enqueue_script' => $script );
		endif;
	}

	/**
	 * Print the methods to be called by the admin_enqueue_scripts hook
	 *
	 * @param  var $hook      page id or filename passed by admin_enqueue_scripts
	 * @return null
	 */
	public function admin_scripts( $hook )
	{
		// dd( $hook );
		self::$enqueue_on_pages = ( !empty( self::$enqueue_on_pages ) ) ? self::$enqueue_on_pages : array( $hook );

		if ( in_array( $hook, self::$enqueue_on_pages ) ) :
			foreach ( self::$enqueues as $enqueue ) :
				if ( $enqueue === 'wp_enqueue_media' ) :
					$enqueue();
				else :
					foreach( $enqueue as $key => $val ) {
						$key($val, $val);
					}
				endif;
			endforeach;
		endif;
	}

	public static function add_admin_pages( $pages ) 
	{
		self::$admin_pages = $pages;
		// dd(self::$admin_pages);
	}

	public static function add_admin_subpages( $pages ) 
	{
		self::$admin_subpages = $pages;
		// dd(self::$admin_pages);
	}

	public function add_admin_menu()
	{
		foreach( self::$admin_pages as $page ) {
			add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], get_template_directory_uri() . $page['icon_url'], $page['position'] );
		}

		foreach( self::$admin_subpages as $page ) {
			add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'] );
		}
	}
}
