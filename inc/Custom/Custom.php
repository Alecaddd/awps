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
	public function register() {
		add_action( 'init', array( $this, 'custom_post_type'), 10 , 4 );
		add_action( 'after_switch_theme', array( $this, 'rewrite_flush') );	
	}	
	
  /**
    * Create Custom Post Types
    * @return The registered post type object, or an error object
    */
    public function custom_post_type()
    {
		/**
		 * Add the post types and their details
		 */
		$customPosts = array (
			array(
				'singular' => 'Artist',
				'plural' => 'Artists',
				'menu_icon' => 'dashicons-admin-customizer',
				'menu_position' => 18,
				'text_domain' => 'text-domain',
				'supports' => array( 'title', /*'editor', 'thumbnail' , 'excerpt', 'author', 'comments'*/ )
			),
			array(
				'singular' => 'Book',
				'plural'  => 'Books',
				'menu_icon' => 'dashicons-book-alt',
				'menu_position' => 18,
				'text_domain' => 'text-domain',
				'supports' => array( 'title', 'editor', 'thumbnail' , 'excerpt', 'author', /*'comments'*/ )
			)
		);
		
		
		foreach ( $customPosts as $customPost ) {
			$labels = array(
				'name'               => _x( $customPost['plural'], 'post type general name', $customPost['text_domain'] ),
				'singular_name'      => _x( $singular, 'post type singular name', $customPost['text_domain'] ),
				'menu_name'          => _x( $customPost['plural'], 'admin menu', $customPost['text_domain'] ),
				'name_admin_bar'     => _x( $customPost['singular'], 'add new on admin bar', $customPost['text_domain'] ),
				'add_new'            => _x( 'Add New ' . $customPost['singular'], ' awps' ),
				'add_new_item'       => __( 'Add New ' . $customPost['singular'], ' awps' ),
				'new_item'           => __( 'New ' . $customPost['singular'], ' awps' ),
				'edit_item'          => __( 'Edit ' . $customPost['singular'], ' awps' ),
				'view_item'          => __( 'View ' . $customPost['singular'], ' awps' ),
				'view_items'         => __( 'View ' . $customPost['plural'], ' awps' ),
				'all_items'          => __( 'All ' . $customPost['plural'], ' awps' ),
				'search_items'       => __( 'Search' . $customPost['plural'], ' awps' ),
				'parent_item_colon'  => __( 'Parent ' . $customPost['plural'], ' awps' ),
				'not_found'          => __( 'No ' . $customPost['plural'] . ' found.', $customPost['text_domain'] ),
				'not_found_in_trash' => __( 'No ' . $customPost['plural'] . ' found in Trash.', $customPost['text_domain'] )
			  );
			  $args = array(
				'labels'             => $labels,
				'description'        => __( 'Description.', $customPost['text_domain'] ),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'menu_icon'          => $customPost['menu_icon'],
				'query_var'          => true,
				'rewrite'            => array( 'slug' => strtolower( $customPost['plural'] ) ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => $customPost['menu_position'],
				'supports'           => $customPost['supports'],
				'show_in_rest'       => true
			  );
			  register_post_type( strtolower( $customPost['plural'] ), $args );
		}
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
