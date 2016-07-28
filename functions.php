<?php
/**
 *
 * @package awps
 *
 * This theme uses classes and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related files
 * Use the file `inc/custom.php` to write your custom functions
 *
 */

// Base Controller
require get_template_directory() . '/config/baseController.php';

// Classes
require get_template_directory() . '/config/setup.php';
require get_template_directory() . '/config/menus.php';
require get_template_directory() . '/config/widgets.php';
require get_template_directory() . '/config/enqueue.php';
require get_template_directory() . '/config/custom-header.php';
require get_template_directory() . '/config/template-tags.php';
require get_template_directory() . '/config/extras.php';
require get_template_directory() . '/config/customizer.php';
require get_template_directory() . '/config/jetpack.php';


// Define and init all the classes
$awps = new baseController;

$awps -> setup = new setupController;
$awps -> menus = new menusController;
$awps -> widgets = new widgetsController;
$awps -> enqueue = new enqueueController;
$awps -> header = new headerController;
$awps -> tags = new tagsController;
$awps -> extras = new extrasController;
$awps -> customizer = new customizerController;
$awps -> jetpack = new jetpackController;

// Call your custom function
$awps -> custom = new customController;