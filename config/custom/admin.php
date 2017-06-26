<?php

namespace awps\custom;

use awps\api\settings;
use awps\api\callback\settingsCallback;

/**
 * Admin
 * use it to write your admin related methods by extending the settings api class.
 */

class admin extends settings
{
	/**
	 * Callback class
	 * @var class instance
	 */
	public $callback;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->callback = new settingsCallback();

		$this->enqueue();

		$this->pages();

		$this->settings();

		$this->sections();

		$this->fields();

		Settings::register();
	}

	private function enqueue()
	{
		// Scripts multidimensional array with styles and scripts
		$scripts = array(
			'script' => array( 
				'jquery', 
				'media_uplaoder',
				get_template_directory_uri() . '/assets/js/admin.min.js'
			),
			'style' => array( 
				get_template_directory_uri() . '/assets/css/admin.min.css',
				'wp-color-picker'
			)
		);

		// Pages array to where enqueue scripts
		$pages = array( 'toplevel_page_awps' );

		// Enqueue files in Admin area
		settings::admin_enqueue( $scripts, $pages );
	}

	private function pages()
	{
		$admin_pages = array(
			array(
				'page_title' => 'AWPS Admin Page',
				'menu_title' => 'AWPS',
				'capability' => 'manage_options',
				'menu_slug' => 'awps',
				'callback' => array( $this->callback, 'admin_index' ),
				'icon_url' => get_template_directory_uri() . '/assets/images/awps-logo.png',
				'position' => 110,
			)
		);

		$admin_subpages = array(
			array(
				'parent_slug' => 'awps',
				'page_title' => 'Awps FAQ',
				'menu_title' => 'FAQ',
				'capability' => 'manage_options',
				'menu_slug' => 'awps_faq',
				'callback' => array( $this->callback, 'admin_faq' )
			)
		);

		// Create multiple Admin menu pages and subpages
		settings::addPages( $admin_pages )->withSubPage( 'Settings' )->addSubPages( $admin_subpages );
	}

	private function settings()
	{
		// Register settings
		$args = array(
			array(
				'option_group' => 'awps_options_group',
				'option_name' => 'first_name',
				'callback' => array( $this->callback, 'awps_options_group' )
			),
			array(
				'option_group' => 'awps_options_group',
				'option_name' => 'awps_test2'
			)
		);

		settings::add_settings( $args );
	}

	private function sections()
	{
		// Register sections
		$args = array(
			array(
				'id' => 'awps_admin_index',
				'title' => 'Settings',
				'callback' => array( $this->callback, 'awps_admin_index' ),
				'page' => 'awps'
			)
		);

		settings::add_sections( $args );
	}

	private function fields()
	{
		// Register fields
		$args = array(
			array(
				'id' => 'first_name',
				'title' => 'First Name',
				'callback' => array( $this->callback, 'first_name' ),
				'page' => 'awps',
				'section' => 'awps_admin_index',
				'args' => array(
					'label_for' => 'first_name',
					'class' => ''
				)
			)
		);

		settings::add_fields( $args );
	}
}