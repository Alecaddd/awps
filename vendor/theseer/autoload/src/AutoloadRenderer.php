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
     * Builds spl based autoload code for inclusion into projects
     *
     * @author     Arne Blankerts <arne@blankerts.de>
     * @copyright  Arne Blankerts <arne@blankerts.de>, All rights reserved.
     */
    class AutoloadRenderer {

        /**
         * Associative array of classes (key) and the files (value) they are in
         *
         * @var array
         */
        protected $classes;

        /**
         * An optional base dir to strip for the realpath of the filename
         *
         * @var string
         */
        protected $baseDir = '';

        /**
         * Indenting char(s)
         *
         * @var string
         */
        protected $indent = '                ';

        /**
         * Char(s) used as linebreak
         *
         * @var string
         */
        protected $linebreak = "\n";

        /**
         * Timestamp of production start
         *
         * @var integer
         */
        protected $timestamp;

        /**
         * Format string supplied to date() for use with ___CREATED___
         *
         * @var string
         */
        protected $dateformat = 'r';

        /**
         * Variables for templates
         *
         * @var array
         */
        protected $variables = array();

        /**
         * Flag to toggle PHP 5.2 compat mode
         *
         * @var boolean
         */
        protected $compat = false;

        /**
         * Flag to pass on to spl_autoload_register to prepend
         *
         * @var bool
         */
        private $usePrepend = false;

        /**
         * Flag to pass on to spl_autoload_register to optionally throw exceptions on registration error
         *
         * @var bool
         */
        private $throwExceptions = false;

        /**
         * Constructor of AutoloadRenderer class
         *
         * @param array $classlist Array of classes
         *
         */
        public function __construct(array $classlist) {
            $this->classes = $classlist;
            ksort($this->classes);
        }

        /**
         * Toggle PHP 5.2 compat mode
         *
         * @param boolean  $mode   Mode to set compat to
         */
        public function setCompat($mode) {
            $this->compat = $mode;
        }

        public function enableExceptions() {
            $this->throwExceptions = true;
        }

        public function prependAutoloader() {
            $this->usePrepend = true;
        }

        /**
         * Setter for the Basedir
         *
         * @param string $dir Path to strip from beginning of filenames
         *
         * @return void
         */
        public function setBaseDir($dir) {
            $this->baseDir = $dir;
        }

        /**
         * Overwrite default or previously set indenting option
         *
         * @param string $indent Char(s) to use for indenting
         *
         * @return void
         */
        public function setIndent($indent) {
            $this->indent = $indent;
        }

        /**
         * Overwrite default or previously set linebreak chars
         *
         * @param string $lbs Code to set linebreak
         *
         * @return void
         */
        public function setLineBreak($lbs) {
            $this->linebreak = $lbs;
        }

        /**
         * Accessor for current linebreak setting
         *
         * @return string
         */
        public function getLineBreak() {
            return $this->linebreak;
        }

        /**
         * Setter to use allow usage of fixed date/time for ___CREATED___
         *
         * @param integer $time unix timestamp
         *
         * @throws AutoloadBuilderException
         */
        public function setTimestamp($time) {
            if (!is_int($time) && !is_null($time)) {
                throw new AutoloadBuilderException("'$time' is not a unix timestamp", AutoloadBuilderException::InvalidTimestamp);
            }
            $this->timestamp = $time;
        }

        /**
         * Setter to adjust the date/time format output of ___CREATED___
         *
         * @param string $frmt Date/Time format string
         */
        public function setDateTimeFormat($frmt) {
            $this->dateformat = $frmt;
        }

        /**
         * Set a variable for use with template code
         *
         * @param string $name  Key name (use as ___key___ in template)
         * @param string $value Value to use
         */
        public function setVariable($name, $value) {
            $this->variables['___'.$name.'___'] = $value;
        }


        /**
         * Resolve relative location of file path to basedir if one is set and fix potential
         * broken windows pathnames when run on windows.
         *
         * @param string $fname
         *
         * @return string
         */
        private function resolvePath($fname) {
            if (empty($this->baseDir)) {
                return str_replace('\\', '/', $fname);
            }
            $basedir = explode(DIRECTORY_SEPARATOR, $this->baseDir);
            $filedir = explode(DIRECTORY_SEPARATOR, dirname(realpath($fname)));
            $pos = 0;
            $max = count($basedir);
            while (isset($filedir[$pos]) && $filedir[$pos] == $basedir[$pos]) {
                $pos++;
                if ($pos == $max) {
                    break;
                }
            }
            if ($pos == 0) {
                return str_replace('\\', '/', $fname);
            }
            $rel = join('/', array_slice($filedir, $pos));
            if (!empty($rel)) {
                $rel .= '/';
            }
            if ($pos<count($basedir)) {
                $rel = str_repeat('../', count($basedir)-$pos) . $rel;
            }
            return '/' . $rel . basename($fname);
        }

        /**
         * Render autoload code into a string
         *
         * @param string $template
         *
         * @return string
         */
        public function render($template) {
            $entries = array();
            foreach($this->classes as $class => $file) {
                $fname = $this->resolvePath($file);
                $entries[] = "'". addslashes($class). "' => '$fname'";
            }

            $baseDir = '';
            if ($this->baseDir) {
                $baseDir = $this->compat ? 'dirname(__FILE__) . ' : '__DIR__ . ';
            }

            $replace = array_merge($this->variables, array(
                '___CREATED___'   => date( $this->dateformat, $this->timestamp ? $this->timestamp : time()),
                '___CLASSLIST___' => join( ',' . $this->linebreak . $this->indent, $entries),
                '___BASEDIR___'   => $baseDir,
                '___AUTOLOAD___'  => 'autoload' . md5(serialize($entries)),
                '___EXCEPTION___' => $this->throwExceptions ? 'true' : 'false',
                '___PREPEND___'   => $this->usePrepend ? 'true' : 'false'
            ));
            return str_replace(array_keys($replace), array_values($replace), $template);
        }

    }


    class AutoloadBuilderException extends \Exception {

        const TemplateNotFound = 1;
        const InvalidTimestamp = 2;

    }

}
