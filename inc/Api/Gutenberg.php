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
	 * register default hooks and actions for WordPress
	 * @return
	 */
	public function register() 
	{
		add_action( 'init', array( $this, 'gutenberg_enqueue' ) );
	}

	/**
	 * Enqueue scripts and styles of your Gutenberg blocks
	 * @return
	 */
	public function gutenberg_enqueue()
	{
		wp_register_script( 'gutenberg-test', get_template_directory_uri() . '/assets/src/scripts/gutenberg.js', array( 'wp-blocks', 'wp-element' ) );

		register_block_type( 'gutenberg-test/hello-world', array(
			'editor_script' => 'gutenberg-test',
		) );
	}
}