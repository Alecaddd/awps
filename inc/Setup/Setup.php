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
        add_action( 'after_setup_theme', array( $this, 'custom_logo' ) );
    }

    public function setup()
    {
        /*
         * You can activate this if you're planning to build a multilingual theme
         */
        
        //load_theme_textdomain( 'awps', get_template_directory() . '/languages' );

        /*
         * Default Theme Support options better have
         */
        
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'customize-selective-refresh-widgets' );

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
    
    /*
        Define the size for the logo
    */
    public function custom_logo() {
        $defaults = array(
            'height'      => 250,
            'width'       => 250,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ),
        );
        
        add_theme_support( 'custom-logo', $defaults );
    }
    
    /*
	Displays a custom logo or blog name if none exists.
    */
    public header_logo() {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
        if ( has_custom_logo() ) {
            echo '<a href="'. esc_url( home_url( '/' ) ) .'" rel="home"><img src="'. esc_url( $logo[0] ) .'" alt=' . get_bloginfo( 'name' ) . '></a>';
        } else {
            echo '<h1 class="site-title"><a href="'. esc_url( home_url( '/' ) ) .'" rel="home">' . bloginfo( 'name' ) . '</a></h1>';
        }
    }
}
