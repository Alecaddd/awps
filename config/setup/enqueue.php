<?php

namespace awps\setup;

/**
 * enqueue.
 */
class enqueue 
{
    /*
        Contrusct class to activate actions and hooks as soon as the class is initialized
    */
    public function __construct() 
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts() 
    {

        // Deregister the built-in version of jQuery from WordPress
        wp_deregister_script( 'jquery' );

        // CSS
        wp_enqueue_style('main', get_template_directory_uri().'/assets/css/style.min.css', array(), '1.0.0', 'all');

        // JS
        wp_enqueue_script('main', get_template_directory_uri().'/assets/js/main.min.js', array(), '1.0.0', true);

        // Activate browser-sync on development environment
        if ( getenv( 'APP_ENV' ) === 'development' ) :
            wp_enqueue_script('__bs_script__', getenv('WP_SITEURL') . ':3000/browser-sync/browser-sync-client.js', array(), null, true);
        endif;

        // Extra
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}
