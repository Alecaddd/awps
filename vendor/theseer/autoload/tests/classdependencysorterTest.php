<?php
/**
 * Copyright (c) 2009 Arne Blankerts <arne@blankerts.de>
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
    use TheSeer\Autoload\ClassDependencySorter;

    /**
     * Unit tests for ClassDependencySorter class
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     */
    class ClassDependencySorterTest extends \PHPUnit_Framework_TestCase {


        public function testProcessingDependenciesInOneFile() {
            $classes = array(
            'class1' => 'file1',
            'class2' => 'file1'
            );

            $dependency=array(
            'class1' => array('class2')
            );

            $x = new ClassDependencySorter($classes, $dependency);
            $r = $x->process();

            $expectOrder=array('class2','class1');
            $this->assertEquals(2, count($r));
            $this->assertEquals($expectOrder, array_keys($r));
        }

        public function testProcessingDependenciesOverFileBounderies() {

            $classes = array(
            'class3' => 'file3',
            'class1' => 'file1',
            'class2' => 'file2'
            );

            $dependency=array(
            'class2' => array('class3'),
            'class1' => array('class2')
            );

            $x = new ClassDependencySorter($classes, $dependency);
            $r = $x->process();

            $expectOrder=array(
            'class3','class2','class1'
            );

            $expectFilesOrder=array(
            'file3','file2','file1'
            );

            $this->assertEquals(3, count($r));
            $this->assertEquals($expectOrder, array_keys($r));
            $this->assertEquals($expectFilesOrder, array_unique(array_values($r)));
        }

        /**
         * @expectedException \TheSeer\Autoload\ClassDependencySorterException
         */
        public function testRecusriveDependencyThrowsException() {
            $classes=array('test1' => 'file1');
            $dependency=array('test1' => array('test1'));

            $x = new ClassDependencySorter($classes, $dependency);
            $r = $x->process();

        }

        public function testUnkownDependencyGetsSkippedSilently() {
            $classes=array('test1' => 'file1');
            $dependency=array('test1' => array('test2'));

            $x = new ClassDependencySorter($classes, $dependency);
            $r = $x->process();

        }

    }
}
