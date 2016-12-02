<?php

namespace awps;

use awps\core\tags;
use awps\core\widgets;
use awps\core\customizer;
use awps\setup\setup;
use awps\setup\menus;
use awps\setup\header;
use awps\setup\enqueue;
use awps\custom\custom;
use awps\custom\extras;
use awps\vendor\jetpack;

class init
{
    private static $loaded = false;

    /*
        Construct class to activate actions and hooks as soon as the class is initialized
    */
    public function __construct()
    {
        $this->initClasses();
    }

    public function initClasses()
    {
        if (self::$loaded) {
            return;
        }

        self::$loaded = true;

        new setup();
        new enqueue();
        new header();
        new customizer();
        new extras();
        new jetpack();
        new menus();
        new tags();
        new widgets();

        new custom();
    }
}
