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
    use TheSeer\Autoload\SourceFile;

    /**
     * Unit tests for ClassFinder class
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     */
    class ParserTest extends \PHPUnit_Framework_TestCase {

        public function testNoClassDefined() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/noclass.php')));
            $this->assertFalse($rc->hasUnits());
            $this->assertCount(0,$rc->getUnits());
        }

        public function testOneClass() {
            $parser = new \TheSeer\Autoload\Parser;
            $result = $parser->parse(new SourceFile((__DIR__.'/_data/parser/class.php')));
            $this->assertTrue($result->hasUnits());
            $this->assertCount(1, $result->getUnits());
            $this->assertContains('demo', $result->getUnits());
        }

        public function testOneClassCaseSensitive() {
            $parser = new \TheSeer\Autoload\Parser(false,false,true);
            $result = $parser->parse(new SourceFile((__DIR__.'/_data/parser/class.php')));
            $this->assertTrue($result->hasUnits());
            $this->assertContains('Demo', $result->getUnits());
        }

        public function testClassKeywordReusageForResolvingGetsIgnored() {
            $parser = new \TheSeer\Autoload\Parser;
            $units = $parser->parse(new SourceFile((__DIR__.'/_data/parser/classname.php')))->getUnits();
            $this->assertCount(1,$units);
            $this->assertContains('x\\demo', $units);
        }

        public function testRedeclaringThrowsException() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/redeclaration.php')));
            $this->assertTrue($rc->hasRedeclarations());
            $this->assertContains('demo', $rc->getRedeclarations());
        }

        /**
         * @expectedException  \TheSeer\Autoload\ParserException
         * @expectedExceptionCode  \TheSeer\Autoload\ParserException::ParseError
         */
        public function testInvalidClassnameThrowsException() {
            $parser = new \TheSeer\Autoload\Parser;
            $parser->parse(new SourceFile((__DIR__.'/_data/parser/parseerror1.php')));
        }

        /**
         * @expectedException  \TheSeer\Autoload\ParserException
         * @expectedExceptionCode  \TheSeer\Autoload\ParserException::ParseError
         */
        public function testInvalidClassnameWithExtendsThrowsException() {
            $parser = new \TheSeer\Autoload\Parser;
            $parser->parse(new SourceFile((__DIR__.'/_data/parser/parseerror2.php')));
        }

        /**
         * @expectedException  \TheSeer\Autoload\ParserException
         * @expectedExceptionCode  \TheSeer\Autoload\ParserException::ParseError
         */
        public function testInvalidClassnameForExtendsThrowsException() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $parser->parse(new SourceFile((__DIR__.'/_data/parser/parseerror3.php')));
        }

        /**
         * @expectedException  \TheSeer\Autoload\ParserException
         * @expectedExceptionCode  \TheSeer\Autoload\ParserException::ParseError
         */
        public function testInvalidClassnameForImplementsThrowsException() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $parser->parse(new SourceFile((__DIR__.'/_data/parser/parseerror4.php')));
        }

        /**
         * @expectedException  \TheSeer\Autoload\ParserException
         * @expectedExceptionCode  \TheSeer\Autoload\ParserException::ParseError
         */
        public function testSyntacticallyInvalidClassnameThrowsException() {
            $parser = new \TheSeer\Autoload\Parser;
            $parser->parse(new SourceFile((__DIR__.'/_data/parser/invalid1.php')));
        }

        /**
         * @expectedException  \TheSeer\Autoload\ParserException
         * @expectedExceptionCode  \TheSeer\Autoload\ParserException::ParseError
         */
        public function testInvalidTokenInClassnameThrowsException() {
            $parser = new \TheSeer\Autoload\Parser;
            $parser->parse(new SourceFile((__DIR__.'/_data/parser/invalid2.php')));
        }

        /**
         * @expectedException  \TheSeer\Autoload\ParserException
         * @expectedExceptionCode  \TheSeer\Autoload\ParserException::ParseError
         */
        public function testInvalidTokenInClassnameWithinNamespaceThrowsException() {
            $parser = new \TheSeer\Autoload\Parser;
            $parser->parse(new SourceFile((__DIR__.'/_data/parser/invalid3.php')));
        }

        public function testMultipleClasses() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/multiclass.php')));
            $classes = $rc->getUnits();
            $this->assertCount(3,$classes);
            $this->assertContains('demo1', $classes);
            $this->assertContains('demo2', $classes);
            $this->assertContains('demo3', $classes);
        }

        public function testExtends() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/extends.php')));
            $classes = $rc->getUnits();
            $this->assertCount(2,$classes);
            $this->assertContains('demo1', $classes);
            $this->assertContains('demo2', $classes);
        }

        public function testExtendsWithDependency() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/extends.php')));
            $this->assertEquals(array('demo1'), $rc->getDependenciesForUnit('demo2'));
        }

        public function testInterface() {
            $parser = new \TheSeer\Autoload\Parser();
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/interface.php')));
            $interfaces = $rc->getUnits();
            $this->assertCount(1,$interfaces);
            $this->assertContains('demo', $interfaces);
        }

        public function testInterfaceExtendsWithDependency() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/interfaceextends1.php')));
            $this->assertContains('demo2', $rc->getUnits());
            $this->assertEquals(array('demo1'), $rc->getDependenciesForUnit('demo2'));
        }

        public function testInterfaceExtendsWithDependencyAndNamespaceChange() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/interfaceextends2.php')));
            $this->assertEquals(array('a\\demo1','iterator'), $rc->getDependenciesForUnit('a\\demo2'));
        }

        public function testSingleImplements() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/implements1.php')));
            $classes = $rc->getUnits();
            $this->assertCount(2,$classes);
            $this->assertContains('demo1', $classes);
            $this->assertContains('demo2', $classes);
        }

        public function testMultiImplements() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/implements2.php')));
            $classes = $rc->getUnits();
            $this->assertCount(3,$classes);
            $this->assertContains('demo1', $classes);
            $this->assertContains('demo2', $classes);
            $this->assertContains('demo3', $classes);
        }

        public function testMultiImplementsDepdencies() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $result = $parser->parse(new SourceFile((__DIR__.'/_data/parser/implements2.php')));
            $this->assertEquals(array('demo1','demo2'), $result->getDependenciesForUnit('demo3'));
        }

        public function testMultiImplementsDepdenciesWithNamespace() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $result = $parser->parse(new SourceFile((__DIR__.'/_data/parser/implements3.php')));
            $expect = array('a\\demo1','b\\demo2');
            $this->assertEquals($expect, $result->getDependenciesForUnit('b\\demo3'));
        }

        public function testImplementsExtends() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/implementsextends.php')));
            $classes = $rc->getUnits();
            $this->assertCount(3, $classes);
            $this->assertContains('test', $classes);
            $this->assertContains('demo1', $classes);
            $this->assertContains('demo2', $classes);
        }

        public function testNamespaceBracketSyntax() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/namespace1.php')));
            $this->assertCount(1,$rc->getUnits());
            $this->assertContains('demo\\demo1', $rc->getUnits());
        }

        public function testNamespaceBracketSyntaxMultiLevel() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/namespace2.php')));
            $this->assertCount(1,$rc->getUnits());
            $this->assertContains('demo\\level2\\demo1', $rc->getUnits());
        }

        public function testNamespaceSemicolonSyntax() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/namespace3.php')));
            $this->assertCount(1,$rc->getUnits());
            $this->assertContains('demo\\demo1', $rc->getUnits());
        }

        public function testNamespaceSemicolonSyntaxMultiLevel() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/namespace4.php')));
            $this->assertCount(1,$rc->getUnits());
            $this->assertContains('demo\\level2\\demo1', $rc->getUnits());
        }

        public function testNamespaceBracketCounting() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/namespace5.php')));
            $this->assertCount(1,$rc->getUnits());
            $this->assertContains('demo\\level2\\demo1', $rc->getUnits());
        }

        public function testNamespaceSemicolonSyntaxMultiNS() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/namespace6.php')));
            $classes = $rc->getUnits();
            $this->assertCount(2,$classes);
            $this->assertContains('demo\\level2\\demo1', $classes);
            $this->assertContains('demo\\level2\\level3\\demo2', $classes);
        }

        public function testNamespaceBracketSyntaxMultiNS() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/namespace7.php')));
            $classes = $rc->getUnits();
            $this->assertCount(2,$classes);
            $this->assertContains('demo\\level2\\demo1', $classes);
            $this->assertContains('demo\\level2\\level3\\demo2', $classes);
        }

        public function testNamespaceParsingIgnoresConstantAccessUseOfNamespaceKeyword() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/namespaceconstant.php')));
            $classes = $rc->getUnits();
            $this->assertCount(1,$classes);
            $this->assertContains('demo\\level2\\demo1', $classes);
        }

        public function testEmptyNamespaceNameParsingWorks() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/namespace8.php')));
            $classes = $rc->getUnits();
            $this->assertCount(1,$classes);
            $this->assertContains('demo', $classes);
        }

        public function testBracketParsingBugTest1() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/brackettest1.php')));
            $classes = $rc->getUnits();
            $this->assertCount(2,$classes);
            $this->assertContains('x\\foo', $classes);
            $this->assertContains('x\\baz', $classes);
        }

        public function testBracketParsingBugTest2() {
            $parser = new \TheSeer\Autoload\Parser;
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/brackettest2.php')));
            $classes = $rc->getUnits();
            $this->assertCount(2,$classes);
            $this->assertContains('x\\foo', $classes);
            $this->assertContains('x\\baz', $classes);
        }

        public function testDependenciesFound() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/dependency/file1.php')));
            $expect = array('test\\demo1','test\\demo2');
            $this->assertEquals($expect, $rc->getDependenciesForUnit('foo\\demo3'));
        }

        public function testParseTraitWorks() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/trait0.php')));
            $this->assertContains('test', $rc->getUnits());
        }

        public function testParseUseTraitWorks() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/trait1.php')));

            $classes = $rc->getUnits();

            $this->assertContains('test', $classes);
            $this->assertContains('bar', $classes);
            $this->assertEquals(array('test'), $rc->getDependenciesForUnit('bar'));
        }

        public function testParseUseTraitWorksWhenDependencyIsDisabled() {
            $parser = new \TheSeer\Autoload\Parser();
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/trait1.php')));

            $classes = $rc->getUnits();
            $this->assertContains('test', $classes);
            $this->assertContains('bar', $classes);

        }

        public function testParseUseMultipleTraitWorks() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/trait2.php')));

            $classes = $rc->getUnits();

            $this->assertContains('test', $classes);
            $this->assertContains('trait1', $classes);
            $this->assertContains('trait2', $classes);

            $expect = array('trait1', 'trait2');

            $this->assertEquals($expect, $rc->getDependenciesForUnit('test'));
        }

        public function testParseUseTraitWorksEvenWithUseStatementInMethodForClosure() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/trait3.php')));

            $classes = $rc->getUnits();

            $this->assertContains('test', $classes);
            $this->assertContains('trait1', $classes);

            $expect = array('trait1');

            $this->assertEquals($expect, $rc->getDependenciesForUnit('test'));
        }

        public function testParseUseTraitsWithOverwriteSkipsBracketContent() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/trait4.php')));

            $classes = $rc->getUnits();

            $this->assertContains('test', $classes);
            $this->assertContains('trait1', $classes);

            $expect = array('trait1', 'trait2');

            $this->assertEquals($expect, $rc->getDependenciesForUnit('test'));
        }

        public function testNamespaceImportViaUse() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/use1.php')));

            $classes = $rc->getUnits();

            $this->assertContains('demo\\a\\demo1', $classes);
            $this->assertContains('demo\\b\\demo2', $classes);

            $expect = array('demo\\a\\demo1');
            $this->assertEquals($expect, $rc->getDependenciesForUnit('demo\\b\\demo2'));
        }

        public function testNamespaceMultiImportViaUse() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/use2.php')));

            $classes = $rc->getUnits();

            $this->assertContains('demo\\a\\demo1', $classes);
            $this->assertContains('demo\\b\\demo2', $classes);
            $this->assertContains('demo\\c\\demo3', $classes);

            $expect = array('demo\\a\\demo1');
            $this->assertEquals($expect, $rc->getDependenciesForUnit('demo\\c\\demo3'));
        }

        public function testNamespaceImportWithAlias() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/use3.php')));

            $classes = $rc->getUnits();

            $this->assertContains('demo\\a\\demo1', $classes);
            $this->assertContains('demo\\b\\demo2', $classes);

            $expect = array('demo\\a\\demo1');
            $this->assertEquals($expect, $rc->getDependenciesForUnit('demo\\b\\demo2'));
        }

        public function testNamespaceImportWithRelativeAlias() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/use4.php')));
            $classes = $rc->getUnits();
            $this->assertContains('demo\\a\\demo1', $classes);
            $this->assertContains('demo\\b\\demo2', $classes);
            $this->assertEquals(array('demo\\a\\demo1'), $rc->getDependenciesForUnit('demo\\b\\demo2'));
        }

        public function testAliasViaUseGetsIgnoredIfNotNeeded() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/use5.php')));
            $classes = $rc->getUnits();
            $this->assertContains('demo', $classes);
            $this->assertEquals(array(), $rc->getDependenciesForUnit('demo'));
        }

        public function testUseInClosurewithinAClassGetsIgnored() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/use6.php')));
            $classes = $rc->getUnits();
            $this->assertContains('demo\\a\\demo2', $classes);
            $this->assertEquals(array(), $rc->getDependenciesForUnit('demo\\a\\demo2'));
        }

        public function testGlobalUseInClosureGetsIgnored() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/use7.php')));
            $classes = $rc->getUnits();
            $this->assertContains('demo\\a\\demo2', $classes);
            $this->assertEquals(array(), $rc->getDependenciesForUnit('demo\\a\\demo2'));
        }

        public function testUseConstIsIgnored() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/use9.php')));
            $this->assertEquals(array('demo'), $rc->getUnits());
            $this->assertEquals(array('name'), $rc->getDependenciesForUnit('demo'));
        }

        public function testUseFunctionIsIgnored() {
            $parser = new \TheSeer\Autoload\Parser(true);
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/use10.php')));
            $this->assertEquals(array('demo'), $rc->getUnits());
            $this->assertEquals(array('name'), $rc->getDependenciesForUnit('demo'));
        }

        public function testGroupUseSyntaxIsHandeled() {
            $parser = new Parser();
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/groupuse.php')));
            $units = array('some\\name\\space\\classd');
            $dependencies = array(
                'foo\bar\interfacea',
                'my\other\name\interfaceb',
                'my\other\some\interfaced',
                'my\other\name\classa'
            );
            $this->assertEquals($units, $rc->getUnits());
            $this->assertEquals($dependencies, $rc->getDependenciesForUnit('some\\name\\space\\classd'));
        }

        public function testGroupUseSyntaxWithConstIsHandeled() {
            $parser = new Parser();
            $rc = $parser->parse(new SourceFile((__DIR__.'/_data/parser/groupuse2.php')));
            $units = array('some\\name\\space\\classd');
            $dependencies = array(
                'my\other\name\interfaceb',
                'some\name\space\foo'
            );
            $this->assertEquals($units, $rc->getUnits());
            $this->assertEquals($dependencies, $rc->getDependenciesForUnit('some\\name\\space\\classd'));
        }
    }

}
