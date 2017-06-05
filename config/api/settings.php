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
	 * Settings array
	 * @var private array
	 */
	private static $settings = array();

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

	/**
	 * Admin pages array
	 * @var private array
	 */
	private static $admin_pages = array();

	/**
	 * Admin subpages array
	 * @var private array
	 */
	private static $admin_subpages = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{
		if ( !empty( self::$enqueues ) )
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );

		if ( !empty( self::$admin_pages ) || !empty( self::$admin_subpages ) )
			add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );

		if ( !empty( self::$settings ) )
			add_action( 'admin_init', array( &$this, 'register_custom_settings' ) );
	}

	/**
	 * Dinamically enqueue styles and scripts in admin area
	 *
	 * @param  array  $scripts file paths or wp related keywords of embedded files
	 * @param  array $page    pages id where to load scripts
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

		return ( $type === 'style' ) ? array( 'wp_enqueue_style' => $script ) : array( 'wp_enqueue_script' => $script );
	}

	/**
	 * Print the methods to be called by the admin_enqueue_scripts hook
	 *
	 * @param  var $hook      page id or filename passed by admin_enqueue_scripts
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

	/**
	 * Injects user's defined pages array into $admin_pages array
	 *
	 * @param  var $pages      array of user's defined pages
	 */
	public static function add_admin_pages( $pages ) 
	{
		self::$admin_pages = $pages;
	}

	/**
	 * Injects user's defined pages array into $admin_subpages array
	 *
	 * @param  var $pages      array of user's defined pages
	 */
	public static function add_admin_subpages( $pages ) 
	{
		self::$admin_subpages = $pages;
	}

	/**
	 * Call WordPress methods to generate Admin pages and subpages
	 */
	public function add_admin_menu()
	{
		foreach( self::$admin_pages as $page ) {
			add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
		}

		foreach( self::$admin_subpages as $page ) {
			add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'] );
		}
	}

	/**
	 * Injects user's defined settings array into $settings array
	 *
	 * @param  var $args      array of user's defined settings
	 */
	public static function add_settings( $args ) 
	{
		self::$settings = $args;
	}

	/**
	 * Injects user's defined sections array into $sections array
	 *
	 * @param  var $args      array of user's defined sections
	 */
	public static function add_sections( $args ) 
	{
		self::$sections = $args;
	}

	/**
	 * Injects user's defined fields array into $fields array
	 *
	 * @param  var $args      array of user's defined fields
	 */
	public static function add_fields( $args ) 
	{
		self::$fields = $args;
	}

	/**
	 * Call WordPress methods to register settings, sections, and fields
	 */
	public function register_custom_settings() 
	{
		foreach( self::$settings as $setting ) {
			register_setting( $setting["option_group"], $setting["option_name"], ( isset( $setting["callback"] ) ? $setting["callback"] : '' ) );
		}

		foreach( self::$sections as $section ) {
			add_settings_section( $section["id"], $section["title"], ( isset( $section["callback"] ) ? $section["callback"] : '' ), $section["page"] );
		}

		foreach( self::$fields as $field ) {
			add_settings_field( $field["id"], $field["title"], ( isset( $field["callback"] ) ? $field["callback"] : '' ), $field["page"], $field["section"], ( isset( $field["args"] ) ? $field["args"] : '' ) );
		}
	}
}
