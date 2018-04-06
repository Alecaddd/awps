<?php
/**
 * Build Gutenberg Blocks
 *
 * @package awps
 */

namespace Awps\Api;

/**
 * Customizer class
 */
class Gutenberg
{
	/**
	 * Register default hooks and actions for WordPress
	 *
	 * @return WordPress add_action()
	 */
	public function register() 
	{
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'gutenberg_enqueue' ) );
	}

	/**
	 * Enqueue scripts and styles of your Gutenberg blocks
	 * @return
	 */
	public function gutenberg_enqueue()
	{
		wp_register_script( 'gutenberg-test', get_template_directory_uri() . '/assets/dist/js/gutenberg.js', array( 'wp-blocks', 'wp-element' ) );

		wp_register_style( 'gutenberg-test', get_template_directory_uri() . '/assets/dist/css/gutenberg.css', array( 'wp-edit-blocks' ), time() );

		register_block_type( 'gutenberg-test/hello-world', array(
			'editor_script' => 'gutenberg-test', // Load script in the editor
			'editor_style' => 'gutenberg-test', // Load style in the editor
			'style' => 'gutenberg-test', // Load style in the front-end
		) );

		register_block_type( 'gutenberg-test/latest-post', array(
			'render_callback' => array( $this, 'awps_render_block_latest_post' ),
			'editor_style' => 'gutenberg-test',
			'style' => 'gutenberg-test'
		) );
	}

	public function awps_render_block_latest_post( $attributes )
	{
		$recent_posts = wp_get_recent_posts( array(
			'numberposts' => 1,
			'post_status' => 'publish',
		) );

		if ( count( $recent_posts ) === 0 ) {
			return 'No posts';
		}

		$post = $recent_posts[ 0 ];
		$post_id = $post[ 'ID' ];

		return sprintf(
			'<a class="wp-block-awps-latest-post" href="%1$s">%2$s</a>',
			esc_url( get_permalink( $post_id ) ),
			esc_html( get_the_title( $post_id ) )
		);
	}
}