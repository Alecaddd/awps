<?php
/**
 * Copyright (c) 2009-2014 Arne Blankerts <arne@blankerts.de>
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

namespace TheSeer\DirectoryScanner {

    /**
     * FilterIterator to accept Items based on include/exclude conditions
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     * @version    Release: %version%
     */
    class IncludeExcludeFilterIterator extends \FilterIterator {

        /**
         * List of filter for include shell patterns
         *
         * @var Array
         */
        protected $include;

        /**
         * List of filter for exclude shell patterns
         *
         * @var Array
         */
        protected $exclude;

        /**
         * Set and by that overwrite the include filter array
         *
         * @param Array $inc Array of include pattern strings
         *
         * @return void
         */
        public function setInclude(array $inc = array()) {
            $this->include = $inc;
        }

        /**
         * Set and by that overwrite the exclude filter array
         *
         * @param Array $exc Array of exclude pattern strings
         *
         * @return void
         */
        public function setExclude(array $exc = array()) {
            $this->exclude = $exc;
        }

        /**
         * FilterIterator Method to decide whether or not to include
         * the current item into the list
         *
         * @return boolean
         */
        public function accept() {
            $pathname = $this->current()->getPathname();

            foreach($this->exclude as $out) {
                if (fnmatch($out, $pathname)) {
                    return false;
                }
            }

            foreach($this->include as $in) {
                if (fnmatch($in, $pathname)) {
                    return true;
                }
            }

            return false;
        }

    }

}
