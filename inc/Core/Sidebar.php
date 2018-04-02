<?php

namespace Awps\Core;

/**
 * Sidebar.
 */
class Sidebar
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
    }

    /*
        Define the sidebar
    */
    public function widgets_init()
    {
        register_sidebar( array(
            'name' => esc_html__('Sidebar', 'awps'),
            'id' => 'awps-sidebar',
            'description' => esc_html__('Default sidebar to add all your widgets.', 'awps'),
            'before_widget' => '<section id="%1$s" class="widget %2$s p-2">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ) );
    }
}
