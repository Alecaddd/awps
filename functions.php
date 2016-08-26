<?php
/**
 *
 * @package awps
 *
 * This theme uses classes and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related files
 * Use the file `config/custom.php` to write your custom functions
 *
 */

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ):
  require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

endif;

if( class_exists( 'awps\\setup' ) ):
   new \awps\setup();
endif;
