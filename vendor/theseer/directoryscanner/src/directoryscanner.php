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
 * @link       http://github.com/theseer/DirectoryScanner
 */

namespace TheSeer\DirectoryScanner {

    /**
     * Recursive scanner for files on given filesystem path with the ability to filter
     * results based on include and exclude patterns
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     * @version    Release: %version%
     */
    class DirectoryScanner {

        /**
         * List of filter for include shell patterns
         *
         * @var Array
         */
        protected $include = array();

        /**
         * List of filter for exclude shell patterns
         *
         * @var Array
         */
        protected $exclude = array();

        /**
         * Flags to pass on to RecursiveDirectoryIterator on construction
         *
         * @var int
         */
        protected $flags = 0;

        /**
         * Add a new pattern to the include array
         *
         * @param string $inc Pattern to add
         *
         * @return void
         */
        public function addInclude($inc) {
            $this->include[] = $inc;
        }

        /**
         * set the include pattern array
         *
         * @param Array $inc Array of include pattern strings
         *
         * @return void
         */
        public function setIncludes(array $inc = array()) {
            $this->include = $inc;
        }

        /**
         * get array of current include patterns
         *
         * @return Array
         */
        public function getIncludes() {
            return $this->include;
        }

        public function setFlag($flag) {
            if (!$this->isValidFlag($flag)) {
                throw new Exception("Invalid flag specified", Exception::InvalidFlag);
            }
            $this->flags = $this->flags | $flag;
        }


        public function unsetFlag($flag) {
            if (!$this->isValidFlag($flag)) {
                throw new Exception("Invalid flag specified", Exception::InvalidFlag);
            }
            $this->flags = $this->flags & ~$flag;
        }

        /**
         * @param boolean $followSymlinks
         *
         * @deprecated Use setFlag / unsetFlag with \FilesystemIterator::FOLLOW_SYMLINKS
         * @return void
         */
        public function setFollowSymlinks($followSymlinks) {
            if ($followSymlinks == true) {
                $this->setFlag(\FilesystemIterator::FOLLOW_SYMLINKS);
                return;
            }
            $this->unsetFlag(\FilesystemIterator::FOLLOW_SYMLINKS);
        }

        /**
         * Public function, so it can be tested properly
         *
         * @return bool
         */
        public function isFollowSymlinks() {
            return ($this->flags & \FilesystemIterator::FOLLOW_SYMLINKS) == \FilesystemIterator::FOLLOW_SYMLINKS;
        }

        /**
         * Add a new pattern to the exclude array
         *
         * @param string $exc Pattern to add
         *
         * @return void
         */
        public function addExclude($exc) {
            $this->exclude[] = $exc;
        }

        /**
         * set the exclude pattern array
         *
         * @param Array $exc Array of exclude pattern strings
         *
         * @return void
         */
        public function setExcludes(array $exc = array()) {
            $this->exclude = $exc;
        }

        /**
         * get array of current exclude patterns
         *
         * @return Array
         */
        public function getExcludes() {
            return $this->exclude;
        }

        /**
         * get an array of splFileObjects from given path matching the
         * include/exclude patterns
         *
         * @param string  $path      Path to work on
         * @param boolean $recursive Scan recursivly or not
         *
         * @return Array of splFileInfo Objects
         */
        public function getFiles($path, $recursive = true) {
            $res = array();
            foreach($this->getIterator($path, $recursive) as $entry) {
                $res[] = $entry;
            }
            return $res;
        }

        /**
         * Magic invoker method to use object in foreach-alike constructs as iterator,
         * delegating work to getIterator() method
         *
         * @see getIterator
         *
         * @param string $path Path to work on
         * @param boolean $recursive Scan recursivly or not
         *
         * @return \Iterator
         */
        public function __invoke($path, $recursive = true) {
            return $this->getIterator($path, $recursive);
        }

        /**
         * Scan given directory for files, returning splFileObjects matching the include/exclude patterns
         *
         * @param string $path Path to work on
         * @param boolean $recursive Scan recursively or not
         *
         * @throws Exception
         * @return \Iterator
         */
        public function getIterator($path, $recursive = true) {
            if (!file_exists($path)) {
                throw new Exception("Path '$path' does not exist.", Exception::NotFound);
            }
            if ($recursive) {
                $worker = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator(
                        $path, $this->flags
                    )
                );
            } else {
                $worker = new \DirectoryIterator($path);
            }
            $filter = new IncludeExcludeFilterIterator( new FilesOnlyFilterIterator($worker) );
            $filter->setInclude( count($this->include) ? $this->include : array('*'));
            $filter->setExclude($this->exclude);
            return $filter;
        }

        protected function isValidFlag($flag) {
            return in_array($flag, array(
                \FilesystemIterator::CURRENT_AS_PATHNAME,
                \FilesystemIterator::CURRENT_AS_FILEINFO,
                \FilesystemIterator::CURRENT_AS_SELF,
                \FilesystemIterator::CURRENT_MODE_MASK,
                \FilesystemIterator::KEY_AS_PATHNAME,
                \FilesystemIterator::KEY_AS_FILENAME,
                \FilesystemIterator::FOLLOW_SYMLINKS,
                \FilesystemIterator::KEY_MODE_MASK,
                \FilesystemIterator::NEW_CURRENT_AND_KEY,
                \FilesystemIterator::SKIP_DOTS,
                \FilesystemIterator::UNIX_PATHS
            ));
        }

    }

    /**
     * DirectoryScanner Exception class
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     */
    class Exception extends \Exception {

        /**
         * Error constant for "notFound" condition
         *
         * @var integer
         */
        const NotFound = 1;

        /**
         *  Error condition for invalid flag passed to setFlag/unsetFlag method
         *
         * @var integer
         */
        const InvalidFlag = 2;
    }

}
