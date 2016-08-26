<?php

namespace TheSeer\Autoload\Tests {

    use TheSeer\Autoload\PathComparator;

    class PathComparatorTest extends \PHPUnit_Framework_TestCase {

        /**
         * @dataProvider directoriesProvider
         */
        public function testComparatorYieldsCorrectCommonBase(array $directories, $common) {
            $comparator = new PathComparator($directories);
            $this->assertEquals($common, $comparator->getCommonBase());
        }

        public function directoriesProvider() {
            return array(
                'empty' => array(
                    array(), '/'
                ),
                'single' => array(
                    array(__DIR__), __DIR__
                ),
                'two' => array(
                    array(__DIR__, dirname(__DIR__)), dirname(__DIR__)
                ),
                'parents' => array(
                    array(__DIR__ . '/../src', __DIR__ . '/../tests/_data'), dirname(__DIR__)
                ),
                'with0' => array(
                    array($a=__DIR__.'/_data/parser/trait0.php'), $a
                ),
                'dirwithprefix' => array(
                    array(__DIR__.'/_data/parser/trait0.php', __DIR__.'/_data/parser/trait1.php'), __DIR__.'/_data/parser'
                ),
                'dirwithoutprefix' => array(
                    array(__DIR__, '/usr'), '/'
                )
            );
        }
    }

}
