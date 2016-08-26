<?php
/**
 * Copyright (c) 2009-2016 Arne Blankerts <arne@blankerts.de>
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

namespace TheSeer\Autoload {

    // PHP 5.3 compat
    define('T_TRAIT_53', 10355);
    if (!defined('T_TRAIT')) {
        define('T_TRAIT', -1);
    }

    /**
     * Namespace aware parser to find and extract defined classes within php source files
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     */
    class Parser implements ParserInterface {

        private $methodMap = array(
            T_TRAIT      => 'processClass',
            T_TRAIT_53   => 'processClass',
            T_CLASS      => 'processClass',
            T_INTERFACE  => 'processInterface',
            T_NAMESPACE  => 'processNamespace',
            T_USE        => 'processUse',
            '}'          => 'processBracketClose',
            '{'          => 'processBracketOpen',
            T_CURLY_OPEN => 'processBracketOpen',
            T_DOLLAR_OPEN_CURLY_BRACES  => 'processBracketOpen'
        );

        private $typeMap = array(
            T_INTERFACE => 'interface',
            T_CLASS => 'class',
            T_TRAIT => 'trait',
            T_TRAIT_53 => 'trait'
        );

        private $caseInsensitive;

        private $tokenArray = array();

        private $inNamespace = '';
        private $inUnit = '';

        private $nsBracket = 0;
        private $classBracket = 0;

        private $bracketLevel = 0;
        private $aliases = array();

        private $found = array();
        private $dependencies = array();
        private $redeclarations = array();

        public function __construct($caseInsensitive = true) {
            $this->caseInsensitive = $caseInsensitive;
        }

        /**
         * Parse a given file for defintions of classes, traits and interfaces
         *
         * @param SourceFile $source file to process
         *
         * @return ParseResult
         */
        public function parse(SourceFile $source) {
            $this->found = array();
            $this->redeclarations = array();
            $this->inNamespace = '';
            $this->aliases = array();
            $this->bracketLevel = 0;
            $this->inUnit = '';
            $this->nsBracket = 0;
            $this->classBracket = 0;
            $this->tokenArray = $source->getTokens();
            $tokenCount = count($this->tokenArray);
            $tokList = array_keys($this->methodMap);
            for($t=0; $t<$tokenCount; $t++) {
                $current = (array)$this->tokenArray[$t];
                if ($current[0]==T_STRING && $current[1]=='trait' && T_TRAIT==-1) {
                    // PHP < 5.4 compat fix
                    $current[0] = T_TRAIT_53;
                    $this->tokenArray[$t] = $current;
                }
                if (!in_array($current[0], $tokList)) {
                    continue;
                }
                // PHP 5.5 has classname::class, reusing T_CLASS
                if ($this->tokenArray[$t-1][0] == T_DOUBLE_COLON) {
                    continue;
                }
                $t = call_user_func(array($this, $this->methodMap[$current[0]]), $t);
            }
            return new ParseResult($this->found, $this->dependencies, $this->redeclarations);
        }

        private function processBracketOpen($pos) {
            $this->bracketLevel++;
            return $pos + 1;
        }

        private function processBracketClose($pos) {
            $this->bracketLevel--;
            if ($this->nsBracket != 0 && $this->bracketLevel < $this->nsBracket) {
                $this->inNamespace = '';
                $this->nsBracket = 0;
                $this->aliases = array();
            }
            if ($this->bracketLevel <= $this->classBracket) {
                $this->classBracket = 0;
                $this->inUnit = '';
            }
            return $pos + 1;
        }

        private function processClass($pos) {
            $list = array('{');
            $stack = $this->getTokensTill($pos, $list);
            $stackSize = count($stack);
            $classname = $this->inNamespace != '' ? $this->inNamespace . '\\' : '';
            $extends = '';
            $extendsFound = false;
            $implementsFound = false;
            $implementsList = array();
            $implements = '';
            $mode = 'classname';
            foreach(array_slice($stack, 1, -1) as $tok) {
                switch ($tok[0]) {
                    case T_COMMENT:
                    case T_DOC_COMMENT:
                    case T_WHITESPACE: {
                        continue;
                    }
                    case T_STRING: {
                        $$mode .= $tok[1];
                        continue;
                    }
                    case T_NS_SEPARATOR: {
                        $$mode .= '\\';
                        continue;
                    }
                    case T_EXTENDS: {
                        $extendsFound = true;
                        $mode = 'extends';
                        continue;
                    }
                    case T_IMPLEMENTS: {
                        $implementsFound = true;
                        $mode = 'implements';
                        continue;
                    }
                    case ',': {
                        if ($mode == 'implements') {
                            $implementsList[] = $this->resolveDependencyName($implements);
                            $implements = '';
                        }
                        continue;
                    }
                    default: {
                        throw new ParserException(sprintf(
                            "Parse error while trying to process class definition (unexpected token in name)."
                            ), ParserException::ParseError
                        );
                    }
                }
            }
            if ($implements != '') {
                $implementsList[] = $this->resolveDependencyName($implements);
            }
            if ($implementsFound && count($implementsList)==0) {
                throw new ParserException(sprintf(
                    "Parse error while trying to process class definition (extends or implements)."
                ), ParserException::ParseError
                );
            }
            $classname = $this->registerUnit($classname, $stack[0][0]);
            $this->dependencies[$classname] = $implementsList;
            if ($extendsFound) {
                $this->dependencies[$classname][] = $this->resolveDependencyName($extends);
            }
            $this->inUnit = $classname;
            $this->classBracket = $this->bracketLevel + 1;
            return $pos + $stackSize - 1;
        }

        private function processInterface($pos) {
            $list = array('{');
            $stack = $this->getTokensTill($pos, $list);
            $stackSize = count($stack);
            $name = $this->inNamespace != '' ? $this->inNamespace . '\\' : '';
            $extends = '';
            $extendsList = array();
            $mode = 'name';
            foreach(array_slice($stack, 1, -1) as $tok) {
                switch ($tok[0]) {
                    case T_NS_SEPARATOR:
                    case T_STRING: {
                        $$mode .= $tok[1];
                        continue;
                    }
                    case T_EXTENDS: {
                        $mode = 'extends';
                        continue;
                    }
                    case ',': {
                        if ($mode == 'extends') {
                            $extendsList[] = $this->resolveDependencyName($extends);
                            $extends = '';
                        }
                    }
                }
            }
            $name = $this->registerUnit($name, T_INTERFACE);
            if ($extends != '') {
                $extendsList[] = $this->resolveDependencyName($extends);
            }
            $this->dependencies[$name] = $extendsList;
            $this->inUnit = $name;
            return $pos + $stackSize - 1;
        }

        private function resolveDependencyName($name) {
            if ($name == '') {
                throw new ParserException(sprintf(
                    "Parse error while trying to process class definition (extends or implements)."
                    ), ParserException::ParseError
                );
            }
            if ($name[0] == '\\') {
                $name = substr($name, 1);
            } else {
                $parts = explode('\\', $name, 2);
                $search = $this->caseInsensitive ? strtolower($parts[0]) : $parts[0];
                $key = array_search($search, $this->aliases);
                if (!$key) {
                    $name = ($this->inNamespace != '' ? $this->inNamespace . '\\' : ''). $name;
                } else {
                    $name = $key;
                    if (isset($parts[1])) {
                        $name .= '\\' . $parts[1];
                    }
                }
            }
            if ($this->caseInsensitive) {
                $name = strtolower($name);
            }
            return $name;
        }

        private function registerUnit($name, $type) {
            if ($name == '' || substr($name, -1) == '\\') {
                throw new ParserException(sprintf(
                    "Parse error while trying to process %s definition.",
                    $this->typeMap[$type]
                    ), ParserException::ParseError
                );
            }
            if ($this->caseInsensitive) {
                $name = strtolower($name);
            }
            if (in_array($name, $this->found)) {
                $this->redeclarations[] = $name;
            } else {
                $this->found[] = $name;
            }
            return $name;
        }

        private function processNamespace($pos) {
            $list = array(';', '{');
            $stack = $this->getTokensTill($pos, $list);
            $stackSize = count($stack);
            $newpos = $pos + count($stack);
            if ($stackSize < 3) { // empty namespace defintion == root namespace
                $this->inNamespace = '';
                $this->aliases = array();
                return $newpos - 1;
            }
            $next = $stack[1];
            if (is_array($next) && $next[0] == T_NS_SEPARATOR) { // inline use - ignore
                return $newpos;
            }
            $this->inNamespace = '';
            foreach(array_slice($stack, 1, -1) as $tok) {
                $this->inNamespace .= $tok[1];
            }
            $this->aliases = array();

            return $pos + $stackSize - 1;
        }

        private function processUse($pos) {
            $list = array(';','(');
            $stack = $this->getTokensTill($pos, $list);
            $stackSize = count($stack);
            $ignore = array(
                '(', // closue use
                T_CONST, // use const foo\bar;
                T_FUNCTION // use function foo\bar;
            );
            if (in_array($stack[1][0], $ignore)) {
                return $pos + $stackSize - 1;
            }

            if ($this->classBracket > 0) {
                $this->parseUseOfTrait($stackSize, $stack);

            } else {
                $this->parseUseAsImport($stack);

            }
            return $pos + $stackSize - 1;
        }

        private function getTokensTill($start, $list) {
            $list = (array)$list;
            $stack = array();
            $skip = array(
                T_WHITESPACE,
                T_COMMENT,
                T_DOC_COMMENT
            );
            $limit = count($this->tokenArray);
            for ($t=$start; $t<$limit; $t++) {
                $current = (array)$this->tokenArray[$t];
                if (in_array($current[0], $skip)) {
                    continue;
                }
                $stack[] = $current;
                if (in_array($current[0], $list)) {
                    break;
                }
            }
            return $stack;
        }

        /**
         * @param $stackSize
         * @param $stack
         */
        private function parseUseOfTrait($stackSize, $stack) {
            $use = '';
            for ($t = 0; $t < $stackSize; $t++) {
                $current = (array)$stack[$t];
                switch ($current[0]) {
                    case '{': {
                        // find closing bracket to skip contents
                        for ($x = $t + 1; $x < $stackSize; $x++) {
                            $tok = $stack[$x];
                            if ($tok[0] == '}') {
                                $t = $x;
                                break;
                            }
                        }
                        continue;
                    }
                    case ';':
                    case ',': {
                        $this->dependencies[$this->inUnit][] = $this->resolveDependencyName($use);
                        $use = '';
                        continue;
                    }
                    case T_NS_SEPARATOR:
                    case T_STRING: {
                        $use .= $current[1];
                        continue;
                    }
                }
            }
        }

        /**
         * @param $stack
         */
        private function parseUseAsImport($stack) {
            $use = '';
            $alias = '';
            $mode = 'use';
            $group = '';
            $ignore = false;
            foreach ($stack as $tok) {
                $current = $tok;
                switch ($current[0]) {
                    case T_CONST:
                    case T_FUNCTION: {
                        $ignore = true;
                        continue;
                    }
                    case '{': {
                        $group = $use;
                        continue;
                    }
                    case ';':
                    case ',': {
                        if (!$ignore) {
                            if ($alias == '') {
                                $nss = strrpos($use, '\\');
                                if ($nss !== FALSE) {
                                    $alias = substr($use, $nss + 1);
                                } else {
                                    $alias = $use;
                                }
                            }
                            if ($this->caseInsensitive) {
                                $alias = strtolower($alias);
                            }
                            $this->aliases[$use] = $alias;
                        }
                        $alias = '';
                        $use = $group;
                        $mode = 'use';
                        $ignore = false;
                        continue;
                    }
                    case T_NS_SEPARATOR:
                    case T_STRING: {
                        $$mode .= $current[1];
                        continue;
                    }
                    case T_AS: {
                        $mode = 'alias';
                        continue;
                    }
                }
            }
        }

    }

    class ParserException extends \Exception {

        const ParseError = 1;

    }
}
