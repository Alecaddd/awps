<?php

namespace awps\plugins;

/**
 * jetpack.
 *
 * @link https://jetpack.com/
 */
class jetpack
{
    /*
        Contrusct class to activate actions and hooks as soon as the class is initialized
    */
    public function __construct()
    {
        add_action('after_setup_theme', array(&$this, 'setup'));
    }

    public function setup()
    {

        // Add theme support for Infinite Scroll.
        add_theme_support('infinite-scroll', array(
            'container' => 'main',
            'render' => array(&$this, 'infinite_scroll_render'),
            'footer' => 'page',
        ));
        // Add theme support for Responsive Videos.
        add_theme_support('jetpack-responsive-videos');
    }

    public function infinite_scroll_render()
    {
        while (have_posts()) {
            the_post();
            if (is_search()) :
                get_template_part('views/content', 'search'); else :
                get_template_part('views/content', get_post_format());
            endif;
        }
    }
}
