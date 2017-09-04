<?php
/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * Use the file `config/custom/custom.php` to write your custom functions
 *
 * @package awps
 */

namespace Awps;

final class Init
{
	/**
	 * Set loaded to true if the class was already initialized
	 * @var boolean
	 */
	private static $loaded = false;

	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services()
	{
		return [
			Core\Tags::class,
			Core\Sidebar::class,
			Api\Customizer::class,
			Api\Widgets\TextWidget::class,
			Setup\Setup::class,
			Setup\Menus::class,
			Setup\Header::class,
			Setup\Enqueue::class,
			Custom\Custom::class,
			Custom\Admin::class,
			Custom\Extras::class,
			Plugins\AwpsJetpack::class,
			Plugins\Acf::class
		];
	}

	/**
	 * Loop through the classes, initialize them, and call the register() method if it exists
	 * @return
	 */
	public static function register_services()
	{
		if (self::$loaded)
			return;

		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register') ) {
				$service->register();
			}
		}

		self::$loaded = true;
	}

	/**
	 * Initialize the class
	 * @param  class $class 		class from the services array
	 * @return class instance 		new instance of the class
	 */
	protected static function instantiate( $class )
	{
		return new $class();
	}

}
