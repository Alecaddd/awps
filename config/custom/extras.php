<?php

namespace Awps\Custom;

/**
 * extras.
 */
class Extras
{
	/*
		Contrusct class to activate actions and hooks as soon as the class is initialized
	*/
	public function __construct()
	{
		add_filter('body_class', array($this, 'body_class'));
	}

	public function body_class($classes)
	{

		// Adds a class of group-blog to blogs with more than 1 published author.
		if (is_multi_author()) {
			$classes[] = 'group-blog';
		}
		// Adds a class of hfeed to non-singular pages.
		if (!is_singular()) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}
}
