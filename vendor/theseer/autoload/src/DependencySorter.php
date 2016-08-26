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

    /**
     * Sorting classes by depdendency for static requires
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     */
    class ClassDependencySorter {

        private $classList;
        private $dependencies;

        private $level;

        private $sorted = array();

        public function __construct(Array $classes, Array $dependencies) {
            $this->classList    = $classes;
            $this->dependencies = $dependencies;
        }

        public function process() {
            $this->level = 0;
            foreach($this->classList as $class => $file) {
                if (!in_array($class, $this->sorted)) {
                    $this->resolve($class);
                }
            }

            $res = array();
            foreach($this->sorted as $class) {
                if (!isset($this->classList[$class])) {
                    continue;
                }
                $res[$class] = $this->classList[$class];
            }
            return $res;
        }

        private function resolve($class) {
            $this->level++;
            if ($this->level == 50) {
                throw new ClassDependencySorterException("Can't resolve more than 50 levels of dependencies", ClassDependencySorterException::TooManyDependencyLevels);
            }
            if (isset($this->dependencies[$class])) {
                foreach($this->dependencies[$class] as $depclass) {
                    if (!in_array($depclass, $this->sorted)) {
                        $this->resolve($depclass);
                    }
                }
            }
            $this->sorted[] = $class;
            $this->level--;
        }
    }

    class ClassDependencySorterException extends \Exception {

        const TooManyDependencyLevels = 1;

    }
}
