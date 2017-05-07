<?php

namespace awps\core;

/**
 * customizer.
 */
class customizer
{
    /*
        Contrusct class to activate actions and hooks as soon as the class is initialized
    */
    public function __construct()
    {
        add_action('customize_register', array(&$this, 'setup'));
        add_action('customize_preview_init', array(&$this, 'preview'));
    }

    public function setup( $wp_customize )
    {
        /*
            Add postMessage support for site title and description for the Theme Customizer.
        */
        $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
        $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
        $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    }

    public function preview()
    {
        wp_enqueue_script( 'customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '1.0.0', true);
    }
}
