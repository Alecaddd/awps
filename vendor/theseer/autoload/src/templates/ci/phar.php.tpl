<?php
spl_autoload_register(
    function($class) {
        static $classes = null;
        if ($classes === null) {
            $classes = array(
                ___CLASSLIST___
            );
        }
        $cn = strtolower($class);
        if (isset($classes[$cn])) {
            require 'phar://___PHAR___' . $classes[$cn];
        }
    },
    ___EXCEPTION___,
    ___PREPEND___
);
Phar::mapPhar('___PHAR___');
__HALT_COMPILER();
