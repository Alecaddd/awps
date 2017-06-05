<?php

namespace awps\custom;

use awps\api\settings;
use awps\api\callback\settingsCallback;

/**
 * Admin
 * use it to write your admin realted methods by extending settings or customizer api.
 */

class admin extends settings
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->enqueue();

		$this->admin_pages();

		$this->admin_subpages();

		$callback = new settingsCallback();

		$this->settings( $callback );

		$this->sections( $callback );

		$this->fields( $callback );

		$this->init_settings_api();
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

	private function admin_pages()
	{
		$admin_pages = array(
			array(
				'page_title' => 'AWPS Admin Page',
				'menu_title' => 'AWPS',
				'capability' => 'manage_options',
				'menu_slug' => 'awps',
				'callback' => function() { require_once( get_template_directory() . '/views/admin/index.php' ); },
				'icon_url' => get_template_directory_uri() . '/assets/images/awps-logo.png',
				'position' => 110,
			)
		);

		// Create multiple Admin menu pages and subpages
		settings::add_admin_pages( $admin_pages );
	}

	private function admin_subpages()
	{
		$admin_subpages = array(
			array(
				'parent_slug' => 'awps',
				'page_title' => 'Awps Settings Page',
				'menu_title' => 'Settings',
				'capability' => 'manage_options',
				'menu_slug' => 'awps',
				'callback' => function() { require_once( get_template_directory() . '/views/admin/index.php' ); }
			),
			array(
				'parent_slug' => 'awps',
				'page_title' => 'Awps FAQ',
				'menu_title' => 'FAQ',
				'capability' => 'manage_options',
				'menu_slug' => 'awps_faq',
				'callback' => function() { echo '<div class="wrap"><h1>FAQ Page</h1></div>'; }
			)
		);

		// Create multiple Admin menu subpages
		settings::add_admin_subpages( $admin_subpages );
	}

	private function settings( $callback )
	{
		// Register settings
		$args = array(
			array(
				'option_group' => 'awps_options_group',
				'option_name' => 'first_name',
				'callback' => array( $callback, 'awps_options_group' )
			),
			array(
				'option_group' => 'awps_options_group',
				'option_name' => 'awps_test2'
			)
		);

		settings::add_settings( $args );
	}

	private function sections( $callback )
	{
		// Register section
		$args = array(
			array(
				'id' => 'awps_admin_index',
				'title' => 'Settings',
				'callback' => array( $callback, 'awps_admin_index' ),
				'page' => 'awps'
			)
		);

		settings::add_sections( $args );
	}

	private function fields( $callback )
	{
		// add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() )
		$args = array(
			array(
				'id' => 'first_name',
				'title' => 'First Name',
				'callback' => array( $callback, 'first_name' ),
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

	private function init_settings_api()
	{
		parent::__construct();
	}
}