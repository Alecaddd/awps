<?php

namespace Awps\Custom;

/**
 * Custom
 * use it to write your custom functions.
 */
class Custom
{
	/**
     * register default hooks and actions for WordPress
     * @return
     */
	public function register()
	{
		add_action( 'init', array( $this, 'custom_post_type' ) );
		add_action( 'after_switch_theme', array( $this, 'rewrite_flush' ) );
	}

	/**
	 * Create Custom Post Types
	 * @return The registered post type object, or an error object
	 */
	public function custom_post_type() 
	{
		$labels = array(
			'name'               => _x( 'Books', 'post type general name', 'awps' ),
			'singular_name'      => _x( 'Book', 'post type singular name', 'awps' ),
			'menu_name'          => _x( 'Books', 'admin menu', 'awps' ),
			'name_admin_bar'     => _x( 'Book', 'add new on admin bar', 'awps' ),
			'add_new'            => _x( 'Add New', 'book', 'awps' ),
			'add_new_item'       => __( 'Add New Book', 'awps' ),
			'new_item'           => __( 'New Book', 'awps' ),
			'edit_item'          => __( 'Edit Book', 'awps' ),
			'view_item'          => __( 'View Book', 'awps' ),
			'view_items'         => __( 'View Books', 'awps' ),
			'all_items'          => __( 'All Books', 'awps' ),
			'search_items'       => __( 'Search Books', 'awps' ),
			'parent_item_colon'  => __( 'Parent Books:', 'awps' ),
			'not_found'          => __( 'No books found.', 'awps' ),
			'not_found_in_trash' => __( 'No books found in Trash.', 'awps' )
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
			'rewrite'            => array( 'slug' => 'book' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5, // below post
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);

		register_post_type( 'book', $args );
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
