<?php

namespace awps;

use awps\core\tags;
use awps\core\widgets;
use awps\api\customizer;
use awps\api\settings;
use awps\api\rest;
use awps\setup\setup;
use awps\setup\menus;
use awps\setup\header;
use awps\setup\enqueue;
use awps\custom\custom;
use awps\custom\extras;
use awps\plugins\jetpack;
use awps\plugins\acf;

class init
{
    private static $loaded = false;

    /*
     * Construct class to activate actions and hooks as soon as the class is initialized
     */
    public function __construct()
    {
        $this->initClasses();
    }

    public function initClasses()
    {
        if (self::$loaded) {
            return;
        }

        self::$loaded = true;

        new setup();
        new enqueue();
        new header();
        new customizer();
        new extras();
        new jetpack();
        new acf();
        new menus();
        new tags();
        new widgets();

        new custom();

        $this->adminArea();
    }

    // Testing
    private function adminArea()
    {
        // Scripts multidimensional array with styles and scripts
        $scripts = array(
            'script' => array( 
                'jquery', 
                'media_uplaoder'
            ),
            'style' => array( 
                '/assets/css/style.min.css',
                'wp-color-picker'
            )
        );

        // Pages array to where enqueue scripts
        $pages = array( 'toplevel_page_awps' );

        // Enqueue files
        settings::admin_enqueue( $scripts, $pages );

        $admin_pages = array(
            array(
                'page_title' => 'AWPS Admin Page',
                'menu_title' => 'AWPS',
                'capability' => 'manage_options',
                'menu_slug' => 'awps',
                'callback' => function() { require_once( get_template_directory() . '/views/admin/index.php' ); },
                'icon_url' => '/assets/images/awps-logo.png',
                'position' => 110,
            )
        );

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

        // Create multiple Admin menu pages
        settings::add_admin_pages( $admin_pages );
        settings::add_admin_subpages( $admin_subpages );

        // Init the class after all the settings have been specified
        new settings();
    }
}
