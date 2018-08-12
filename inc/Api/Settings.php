<?php
/**
 * Settings API
 *
 * @package awps
 */

namespace Awps\Api;

/**
 * Settings API Class
 */
class Settings
{
	/**
	 * Settings array
	 * @var private array
	 */
	public $settings = array();

	/**
	 * Sections array
	 * @var private array
	 */
	public $sections = array();

	/**
	 * Fields array
	 * @var private array
	 */
	public $fields = array();

	/**
	 * Script path
	 * @var string
	 */
	public $script_path;

	/**
	 * Enqueues array
	 * @var private array
	 */
	public $enqueues = array();

	/**
	 * Admin pages array to enqueue scripts
	 * @var private array
	 */
	public $enqueue_on_pages = array();

	/**
	 * Admin pages array
	 * @var private array
	 */
	public $admin_pages = array();

	/**
	 * Admin subpages array
	 * @var private array
	 */
	public $admin_subpages = array();

	/**
	 * Constructor
	 */
	public function __construct()
	{}

	public function register()
	{
		if ( !empty( $this->enqueues ) )
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		if ( !empty( $this->admin_pages ) || !empty( $this->admin_subpages ) )
			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

		if ( !empty( $this->settings ) )
			add_action( 'admin_init', array( $this, 'register_custom_settings' ) );
	}

	/**
	 * Dinamically enqueue styles and scripts in admin area
	 *
	 * @param  array  $scripts file paths or wp related keywords of embedded files
	 * @param  array  $pages    pages id where to load scripts
	 */
	public function admin_enqueue( $scripts = array(), $pages = array() )
	{
		if ( empty( $scripts ) )
			return;

		$i = 0;
		foreach ( $scripts as $key => $value ) :
			foreach ( $value as $val ):
				$this->enqueues[ $i ] = $this->enqueue_script( $val, $key );
				$i++;
			endforeach;
		endforeach;

		if ( !empty( $pages ) ) :
			$this->enqueue_on_pages = $pages;
		endif;

		return $this;
	}

	/**
	 * Call the right WP functions based on the file or string passed
	 *
	 * @param  array $script  file path or wp related keyword of embedded file
	 * @param  var $type      style | script
	 * @return variable functions
	 */
	private function enqueue_script( $script, $type ) {
		if ( $script === 'media_uploader' )
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
		$this->enqueue_on_pages = ( !empty( $this->enqueue_on_pages ) ) ? $this->enqueue_on_pages : array( $hook );

		if ( in_array( $hook, $this->enqueue_on_pages ) ) :
			foreach ( $this->enqueues as $enqueue ) :
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
	public function addPages( $pages ) 
	{
		$this->admin_pages = $pages;

		return $this;
	}

	public function withSubPage( $title = null )
	{
		if ( empty( $this->admin_pages ) ) {
			return $this;
		}
		
		$adminPage = $this->admin_pages[0];

		$subpage = array(
			array(
				'parent_slug' => $adminPage['menu_slug'],
				'page_title' => $adminPage['page_title'],
				'menu_title' => ($title) ? $title : $adminPage['menu_title'],
				'capability' => $adminPage['capability'],
				'menu_slug' => $adminPage['menu_slug'],
				'callback' => $adminPage['callback']
			)
		);

		$this->admin_subpages = $subpage;

		return $this;
	}

	/**
	 * Injects user's defined pages array into $admin_subpages array
	 *
	 * @param  var $pages      array of user's defined pages
	 */
	public function addSubPages( $pages ) 
	{
		$this->admin_subpages = ( count( $this->admin_subpages ) == 0 ) ? $pages : array_merge( $this->admin_subpages, $pages );

		return $this;
	}

	/**
	 * Call WordPress methods to generate Admin pages and subpages
	 */
	public function add_admin_menu()
	{
		foreach( $this->admin_pages as $page ) {
			add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
		}

		foreach( $this->admin_subpages as $page ) {
			add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'] );
		}
	}

	/**
	 * Injects user's defined settings array into $settings array
	 *
	 * @param  var $args      array of user's defined settings
	 */
	public function add_settings( $args ) 
	{
		$this->settings = $args;

		return $this;
	}

	/**
	 * Injects user's defined sections array into $sections array
	 *
	 * @param  var $args      array of user's defined sections
	 */
	public function add_sections( $args ) 
	{
		$this->sections = $args;

		return $this;
	}

	/**
	 * Injects user's defined fields array into $fields array
	 *
	 * @param  var $args      array of user's defined fields
	 */
	public function add_fields( $args ) 
	{
		$this->fields = $args;

		return $this;
	}

	/**
	 * Call WordPress methods to register settings, sections, and fields
	 */
	public function register_custom_settings() 
	{
		foreach( $this->settings as $setting ) {
			register_setting( $setting["option_group"], $setting["option_name"], ( isset( $setting["callback"] ) ? $setting["callback"] : '' ) );
		}

		foreach( $this->sections as $section ) {
			add_settings_section( $section["id"], $section["title"], ( isset( $section["callback"] ) ? $section["callback"] : '' ), $section["page"] );
		}

		foreach( $this->fields as $field ) {
			add_settings_field( $field["id"], $field["title"], ( isset( $field["callback"] ) ? $field["callback"] : '' ), $field["page"], $field["section"], ( isset( $field["args"] ) ? $field["args"] : '' ) );
		}
	}
}
