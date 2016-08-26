<?php
/**
 * Copyright (c) 2009-2015 Arne Blankerts <arne@blankerts.de>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 *   * Neither the name of Arne Blankerts nor the names of contributors
 *     may be used to endorse or promote products derived from this software
 *     without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT  * NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER ORCONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
 * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    Autoload
 * @author     Arne Blankerts <arne@blankerts.de>
 * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
 * @license    BSD License
 */

namespace TheSeer\Autoload\Tests {

    use TheSeer\Autoload\ClassDependencySorter;
    use TheSeer\Autoload\Config;
    use TheSeer\Autoload\Factory;

    class Bug65Test extends \PHPUnit_Framework_TestCase {

        public function testBugIsFixed() {
            $config = new Config(array());
            $config->setLowercaseMode(true);

            $factory = new Factory();
            $factory->setConfig($config);

            $collector = $factory->getCollector();

            $scanner = $factory->getScanner()->getIterator(__DIR__ . '/_data/bug65');
            $collector->processDirectory($scanner);
            $result = $collector->getResult();

            $sorter = new ClassDependencySorter($result->getUnits(), $result->getDependencies());

            $expected = array(
                'phpunit_extensions_database_testcase_trait' =>  __DIR__ . "/_data/bug65/trait.php",
                'phpunit_extensions_database_testcase' => __DIR__ . "/_data/bug65/class.php"
            );

            $this->assertEquals($expected, $sorter->process());
        }

    }
}
