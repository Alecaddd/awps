<?php
/**
 * Copyright (c) 2009-2011 Arne Blankerts <arne@blankerts.de>
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
 * @package    DirectoryScanner
 * @author     Arne Blankerts <arne@blankerts.de>
 * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
 * @license    BSD License
 */

namespace TheSeer\DirectoryScanner\Tests {

    use TheSeer\DirectoryScanner\DirectoryScanner;

    /**
     * Unit tests for DirectoryScanner class
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     */
    class DirectoryScannerTest extends \PHPUnit_Framework_TestCase {

        /**
         * Test if enabling following symbolic links works.
         */
        public function testSetFollowSymlinks() {
            $tmp = new DirectoryScanner();
            $this->assertFalse($tmp->isFollowSymlinks());
            $tmp->setFollowSymlinks(TRUE);
            $this->assertTrue($tmp->isFollowSymlinks());
            $tmp->setFollowSymlinks(FALSE);
            $this->assertFalse($tmp->isFollowSymlinks());
        }

        /**
         * @expectedException \TheSeer\DirectoryScanner\Exception
         * @expectedExceptionCode \TheSeer\DirectoryScanner\Exception::InvalidFlag
         */
        public function testSettingInvalidFlagThrowsException() {
            $tmp = new DirectoryScanner();
            $tmp->setFlag(-1);
        }

        /**
         * @expectedException \TheSeer\DirectoryScanner\Exception
         * @expectedExceptionCode \TheSeer\DirectoryScanner\Exception::InvalidFlag
         */
        public function testUnSettingInvalidFlagThrowsException() {
            $tmp = new DirectoryScanner();
            $tmp->unsetFlag(-1);
        }

        /**
         * Set the include to be matching for *.txt via setIncludes
         *
         */
        public function testSetIncludes() {
            $test = array('*.txt');
            $tmp = new DirectoryScanner();
            $tmp->setIncludes($test);
            $this->assertEquals($test, $tmp->getIncludes());
        }

        /**
         * Set an exclude match on *.txt
         *
         */
        public function testSetExcludes() {
            $test = array('*.txt');
            $tmp = new DirectoryScanner();
            $tmp->setExcludes($test);
            $this->assertEquals($test, $tmp->getExcludes());
        }

        /**
         * Adding multiple matches include matches individually
         *
         */
        public function testAddInclude() {
            $tmp = new DirectoryScanner();
            $tmp->addInclude('*.txt');
            $tmp->addInclude('*.xml');

            $this->assertEquals(2, count($tmp->getIncludes()));
            $this->assertEquals(array('*.txt', '*.xml'), $tmp->getIncludes());
        }

        /**
         * Adding multiple exclude maches individually
         *
         */
        public function testAddExclude() {
            $tmp = new DirectoryScanner();
            $tmp->addExclude('*.txt');
            $tmp->addExclude('*.xml');

            $this->assertEquals(array('*.txt', '*.xml'), $tmp->getExcludes());
        }

        /**
         * Trying to scan a non existend directory should throw an exception
         *
         * @expectedException \TheSeer\DirectoryScanner\Exception
         */
        public function testScanOfNonExistendPath() {
            $tmp = new DirectoryScanner();
            $tmp(__DIR__ . '/_data//not/existing');
        }

        /**
         * Recursivly find all files within given test folder without any filter
         */
        public function testRecursiveFindAllFilesInFolder() {
            $tmp = new DirectoryScanner();
            $x = $tmp->getFiles(__DIR__ . '/_data');
            $this->assertEquals(9, count($x));
        }

        /**
         * Non-Recursivly find all files within given test folder without any filter
         */
        public function testNonRecursiveFindAllFilesInFolder() {
            $tmp = new DirectoryScanner();
            $x = $tmp->getFiles(__DIR__ . '/_data', FALSE);
            $this->assertEquals(3, count($x));
        }

        /**
         * Recursivly find *.xml files within test folder
         */
        public function testRecursiveFindXMLFilesInFolder() {
            $tmp = new DirectoryScanner();
            $tmp->addInclude('*.xml');
            $x = $tmp->getFiles(__DIR__ . '/_data');
            $this->assertEquals(3, count($x));
        }

        /**
         * Non-Recursivly find *.xml files within test folder
         */
        public function testNonRecursiveFindXMLFilesInFolder() {
            $tmp = new DirectoryScanner();
            $tmp->addInclude('*.xml');
            $x = $tmp->getFiles(__DIR__ . '/_data', FALSE);
            $this->assertEquals(2, count($x));
        }

        /**
         * Recursivly find all files, not matching *.xml
         */
        public function testRecursiveFindByExclude() {
            $tmp = new DirectoryScanner();
            $tmp->addExclude('*.xml');
            $x = $tmp->getFiles(__DIR__ . '/_data');
            $this->assertEquals(6, count($x));
        }

        /**
         * Find all files matching *.txt and not being in 'nested' folder
         */
        public function testRecursiveFindByIncludeAndExclude() {
            $tmp = new DirectoryScanner();
            $tmp->addInclude('*.txt');
            $tmp->addExclude('*/nested/*');
            $x = $tmp->getFiles(__DIR__ . '/_data');
            $this->assertEquals(1, count($x));
        }

    }

}
