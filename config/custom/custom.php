<?php

namespace awps\custom;

/**
 * custom
 * use it to write your custom functions.
 * To apply use add Singular Name, Plural Name, Menu Icon and Menu Position.
 * e.g. $customCPTClass = new custom( 'Publication', 'Publications', 'dashicons-book-alt', 18 );
 */
 class custom
 {
     private $singular;
     private $plural;
     private $menu_icon;
     private $menu_position;

     public function __construct( $singular, $plural, $menu_icon, $menu_position )
     {
        $this->singular = $singular;
        $this->plural = $plural;
        $this->menu_icon = $menu_icon;
        $this->menu_position = $menu_position;

        add_action('init', array(&$this, 'custom_post_type'), 10 , 4);
        add_action('after_switch_theme', array(&$this, 'rewrite_flush'));
     }
     /**
      * Create Custom Post Types
      * @return The registered post type object, or an error object
      */
     public function custom_post_type()
     {
       $labels = array(
         'name'               => _x( $this->plural, 'post type general name', 'awps' ),
         'singular_name'      => _x( $singular, 'post type singular name', 'awps' ),
         'menu_name'          => _x( $this->plural, 'admin menu', 'awps' ),
         'name_admin_bar'     => _x( $this->singular, 'add new on admin bar', 'awps' ),
         'add_new'            => _x( 'Add New ' . $this->singular, ' awps' ),
         'add_new_item'       => __( 'Add New ' . $this->singular, ' awps' ),
         'new_item'           => __( 'New ' . $this->singular, ' awps' ),
         'edit_item'          => __( 'Edit ' . $this->singular, ' awps' ),
         'view_item'          => __( 'View ' . $this->singular, ' awps' ),
         'view_items'         => __( 'View ' . $this->plural, ' awps' ),
         'all_items'          => __( 'All ' . $this->plural, ' awps' ),
         'search_items'       => __( 'Search' . $this->plural, ' awps' ),
         'parent_item_colon'  => __( 'Parent ' . $this->plural, ' awps' ),
         'not_found'          => __( 'No ' . $this->plural . ' found.', 'awps' ),
         'not_found_in_trash' => __( 'No ' . $this->plural . ' found in Trash.', 'awps' )
       );
       $args = array(
         'labels'             => $labels,
         'description'        => __( 'Description.', 'awps' ),
         'public'             => true,
         'publicly_queryable' => true,
         'show_ui'            => true,
         'show_in_menu'       => true,
         'menu_icon'          => $this->menu_icon,
         'query_var'          => true,
         'rewrite'            => array( 'slug' => strtolower( $this->singular ) ),
         'capability_type'    => 'post',
         'has_archive'        => true,
         'hierarchical'       => false,
         'menu_position'      => $this->menu_position, // below post
         'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
       );
       register_post_type( strtolower( $this->singular ), $args );
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
