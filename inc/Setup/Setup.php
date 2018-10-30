<?php

namespace Awps\Setup;

class Setup
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        add_action( 'after_setup_theme', array( $this, 'content_width' ), 0);
    }

    public function setup()
    {
        /*
         * You can activate this if you're planning to build a multilingual theme
         */
        // load_theme_textdomain( 'awps', get_template_directory() . '/languages' );

        /*
         * Default Theme Support options better have
         */
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'customize-selective-refresh-widgets' );
        
        /**
        * Add woocommerce support and woocommerce override
        */
        // add_theme_support( 'woocommerce' );

        add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );

        add_theme_support( 'custom-background', apply_filters( 'awps_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ) ) );

        /*
         * Activate Post formats if you need
         */
        add_theme_support( 'post-formats', array( 
            'aside',
            'gallery',
            'link',
            'image',
            'quote',
            'status',
            'video',
            'audio',
            'chat',
        ) );
    }

    /*
        Define a max content width to allow WordPress to properly resize your images
    */
    public function content_width()
    {
        $GLOBALS[ 'content_width' ] = apply_filters( 'content_width', 1440 );
    }
}
