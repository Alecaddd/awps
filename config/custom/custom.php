<?php

namespace awps\custom;

/**
 * custom
 * use it to write your custom functions.
 */
class custom
{
    public function __construct()
    {
        add_action('init', array(&$this, 'custom_post_type'));
        add_action('after_switch_theme', array(&$this, 'rewrite_flush'));
    }

    /**
     * Create Custom Post Types
     * @return The registered post type object, or an error object
     */
    public function custom_post_type()
    {

      $singular = 'Book';
      $plural = 'Books';
      $lsingular = strtolower( $singular );

      $labels = array(
        'name'               => _x( $plural, 'post type general name', 'awps' ),
        'singular_name'      => _x( $singular, 'post type singular name', 'awps' ),
        'menu_name'          => _x( $plural, 'admin menu', 'awps' ),
        'name_admin_bar'     => _x( $singular, 'add new on admin bar', 'awps' ),
        'add_new'            => _x( 'Add New ' . $singular, 'awps' ),
        'add_new_item'       => __( 'Add New ' . $singular, 'awps' ),
        'new_item'           => __( 'New ' . $singular, 'awps' ),
        'edit_item'          => __( 'Edit ' . $singular, 'awps' ),
        'view_item'          => __( 'View ' . $singular, 'awps' ),
        'view_items'         => __( 'View ' . $plural, 'awps' ),
        'all_items'          => __( 'All ' . $plural, 'awps' ),
        'search_items'       => __( 'Search' . $plural, 'awps' ),
        'parent_item_colon'  => __( 'Parent ' . $plural, 'awps' ),
        'not_found'          => __( 'No ' . $plural . 'found.', 'awps' ),
        'not_found_in_trash' => __( 'No ' . $plural . 'found in Trash.', 'awps' )
      );

      $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'awps' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-book-alt',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => $lsingular ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5, // below post
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
      );

      register_post_type( $lsingular, $args );
    }

    /**
     * Flush Rewrite on CPT activation
     * @return empty
     */
    public function rewrite_flush()
    {
        // call the CPT init function
        $this->custom_post_type();

        // Flush the rewrite rules only on theme activation
        flush_rewrite_rules();
    }
}
