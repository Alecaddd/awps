<?php
/**
 * Copyright (c) 2009-2010 Arne Blankerts <arne@blankerts.de>
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

    use TheSeer\Autoload\Parser;
    use TheSeer\Autoload\AutoloadRenderer;

    /**
     * Unit tests for PHPFilter iterator class
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     */
    class AutoloadRendererTest extends \PHPUnit_Framework_TestCase {

        private $classlist;
        private $template;

        public function setUp() {
            $this->classlist = array();
            $this->classlist['demo1'] = realpath(__DIR__ . '/_data/parser/class.php');
            $this->classlist['demo2'] = realpath(__DIR__ . '/_data/parser/class.php');
            $this->template = file_get_contents(__DIR__ . '/_data/templates/default.php.tpl');
        }

        /**
         *
         * @covers \TheSeer\Autoload\AutoloadRenderer::__construct
         * @covers \TheSeer\Autoload\AutoloadRenderer::render
         */
        public function testDefaultRendering() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $expected = "         \$classes = array(\n                'demo1' => '".__DIR__."/_data/parser/class.php',\n";
            $expected = strtr($expected, '\\', '/');
            $this->assertContains($expected, $ab->render($this->template));
            $expected = "require \$classes[\$cn]";
            $this->assertContains($expected, $ab->render($this->template));
        }

        /**
         *
         * @covers \TheSeer\Autoload\AutoloadRenderer::setLinebreak
         * @covers \TheSeer\Autoload\AutoloadRenderer::render
         */
        public function testWindowsLFRendering() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $ab->setLineBreak("\r\n");
            $expected = "_data/parser/class.php',\r\n";
            $this->assertContains($expected, $ab->render($this->template));
        }

        /**
         *
         * @covers \TheSeer\Autoload\AutoloadRenderer::setLinebreak
         * @covers \TheSeer\Autoload\AutoloadRenderer::getLinebreak
         */
        public function testSettingAndGettingLinebreakWorks() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $ab->setLineBreak('foo');
            $this->assertEquals('foo', $ab->getLineBreak());
        }

        /**
         *
         * @covers \TheSeer\Autoload\AutoloadRenderer::setIndent
         * @covers \TheSeer\Autoload\AutoloadRenderer::render
         */
        public function testIndentWithTabsRendering() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $ab->setIndent("\t");
            $expected = "\t'demo2'";
            $this->assertContains($expected, $ab->render($this->template));
        }


        /**
         *
         * @covers \TheSeer\Autoload\AutoloadRenderer::setBaseDir
         * @covers \TheSeer\Autoload\AutoloadRenderer::render
         */
        public function testSetBaseDirRendering() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $ab->setBaseDir(realpath(__DIR__ . '/..'));
            $result = $ab->render($this->template);

            $expected = "require __DIR__ . \$classes[\$cn];";
            $expected = strtr($expected, '\\', '/');
            $this->assertContains($expected, $result);

            $expected = "         \$classes = array(\n                'demo1' => '/tests/_data/parser/class.php',\n";
            $this->assertContains($expected, $result);
        }

        /**
         *
         * @covers \TheSeer\Autoload\AutoloadRenderer::render
         */
        public function testRenderingInCompatMode() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $ab->setCompat(true);
            $ab->setBaseDir(realpath(__DIR__));
            $expected = "require dirname(__FILE__) . \$classes[\$cn];";
            $this->assertContains($expected, $ab->render($this->template));

        }

        /**
         * @covers \TheSeer\Autoload\AutoloadRenderer::resolvePath
         */
        public function testRelativeSubBaseDirRendering() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $ab->setBaseDir(realpath(__DIR__.'/_data/dependency'));
            $expected = "'demo1' => '/../parser/class.php'";
            $this->assertContains($expected, $ab->render($this->template));
        }

        /**
         *
         * @expectedException \TheSeer\Autoload\AutoloadBuilderException
         */
        public function testSettingInvalidTimestamp() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $ab->setTimestamp('Bad');
        }

        public function testSettingTimestamp() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $now = time();
            $ab->setTimestamp($now);
            $this->assertEquals(date('r',$now), $ab->render('___CREATED___'));
        }

        /**
         *
         * @depends testSettingTimestamp
         */
        public function testSettingDateTimeFormat() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $now = time();
            $ab->setTimestamp($now);
            $ab->setDateTimeFormat('dmYHis');
            $this->assertEquals(date('dmYHis',$now), $ab->render('___CREATED___'));
        }

        /**
         *
         * @covers \TheSeer\Autoload\AutoloadRenderer::setVariable
         */
        public function testSetVariable() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $ab->setVariable('TEST','variableValue');
            $this->assertEquals('variableValue', $ab->render('___TEST___'));
        }

        /**
         *
         * @covers \TheSeer\Autoload\AutoloadRenderer::render
         */
        public function testGetUniqueReproducibleValueForAutoloadName() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $first = $ab->render('___AUTOLOAD___');
            $this->assertEquals($first, $ab->render('___AUTOLOAD___'));
        }

        /**
         *
         * @covers \TheSeer\Autoload\AutoloadRenderer::render
         */
        public function testGetUniqueValueForAutoloadName() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $first = $ab->render('___AUTOLOAD___');

            $aSecond = $this->classlist;
            array_pop($aSecond);
            $ab = new \TheSeer\Autoload\AutoloadRenderer($aSecond);
            $this->assertNotEquals($first, $ab->render('___AUTOLOAD___'));
        }

        /**
         * @covers \TheSeer\Autoload\AutoloadRenderer::setCompat
         */
        public function testSetCompatMode() {
            $ab = new \TheSeer\Autoload\AutoloadRenderer($this->classlist);
            $ab->setCompat(true);
            $ab->setBaseDir('.');
            $this->assertEquals('dirname(__FILE__) . ', $ab->render('___BASEDIR___'));
        }

    }

}
