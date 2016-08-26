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
 *
 */
namespace TheSeer\Autoload {

    class Config {

        private $quietMode = FALSE;
        private $directories = array();
        private $outputFile = 'STDOUT';
        private $pharMode = FALSE;
        private $include = array('*.php');
        private $exclude = array();
        private $whitelist = array('*');
        private $blacklist = array();
        private $baseDirectory = NULL;
        private $template;
        private $linebreak = "\n";
        private $indent;
        private $lint = FALSE;
        private $php;
        private $compatMode = FALSE;
        private $staticMode = FALSE;
        private $tolerant = FALSE;
        private $trusting = TRUE;
        private $once = FALSE;
        private $lowercase = TRUE;
        private $dateFormat;
        private $variable = array();
        private $pharCompression = 'NONE';
        private $pharKey;
        private $pharAll = false;
        private $pharAliasName = '';
        private $pharHashAlgorithm;
        private $followSymlinks = false;
        private $cacheFilename;
        private $prepend = false;
        private $exceptions = true;

        public function __construct(Array $directories) {
            $this->directories = $directories;
            $this->php = (PHP_OS === 'WIN' ? 'C:\php\php.exe' : '/usr/bin/php');
        }

        public function setBaseDirectory($baseDirectory) {
            $this->baseDirectory = $baseDirectory;
        }

        public function getBaseDirectory() {
            if ($this->baseDirectory !== NULL) {
                return realpath($this->baseDirectory);
            }
            if ($this->isPharMode()) {
                $comparator = new PathComparator($this->directories);
                return  $comparator->getCommonBase();
            }
            if ($this->outputFile != 'STDOUT') {
                return realpath(dirname($this->outputFile) ?: '.');
            }
            $tmp = $this->getDirectories();
            return realpath(is_dir($tmp[0]) ? $tmp[0] : (dirname($tmp[0]) ?: '.'));
        }

        public function setCompatMode($compatMode) {
            $this->compatMode = $compatMode;
        }

        public function isCompatMode() {
            return $this->compatMode === true;
        }

        public function setDateFormat($dateFormat) {
            $this->dateFormat = $dateFormat;
        }

        public function getDateFormat() {
            return $this->dateFormat;
        }

        public function setExclude(Array $exclude) {
            $this->exclude = $exclude;
        }

        public function getExclude() {
            return $this->exclude;
        }

        public function setInclude(Array $include) {
            $this->include = $include;
        }

        public function getInclude() {
            return $this->include;
        }

        /**
         * @return array
         */
        public function getBlacklist() {
            return $this->blacklist;
        }

        /**
         * @param array $blacklist
         */
        public function setBlacklist($blacklist) {
            $this->blacklist = $blacklist;
        }

        /**
         * @return array
         */
        public function getWhitelist() {
            return $this->whitelist;
        }

        /**
         * @param array $whitelist
         */
        public function setWhitelist($whitelist) {
            $this->whitelist = $whitelist;
        }

        public function setIndent($indent) {
            $this->indent = $indent;
        }

        public function getIndent() {
            if ($this->indent !== NULL) {
                if (is_numeric($this->indent) && (int)$this->indent == $this->indent) {
                    return str_repeat(' ', (int)$this->indent);
                }
                return $this->indent;
            }
            if ($this->isStaticMode()) {
                return '';
            }
            return str_repeat(' ', $this->isCompatMode() ? 12 : 16);
        }

        public function setLinebreak($linebreak) {
            $lbr = array('LF' => "\n", 'CR' => "\r", 'CRLF' => "\r\n" );
            if (isset($lbr[$linebreak])) {
                $this->linebreak = $lbr[$linebreak];
            } else {
                $this->linebreak = $linebreak;
            }
        }

        public function getLinebreak() {
            return $this->linebreak;
        }

        public function setLintMode($lint) {
            $this->lint = (boolean)$lint;
        }

        public function isLintMode() {
            return $this->lint;
        }

        public function setLowercaseMode($lowercase) {
            $this->lowercase = (boolean)$lowercase;
        }

        public function isLowercaseMode() {
            return $this->lowercase;
        }

        public function setOnceMode($once) {
            $this->once = (boolean)$once;
        }

        public function isOnceMode() {
            return $this->once;
        }

        public function setOutputFile($outputFile) {
            $this->outputFile = $outputFile;
        }

        public function getOutputFile() {
            return $this->outputFile;
        }

        public function enablePharMode($compression = 'NONE', $all = true, $key = NULL, $alias = NULL) {
            $this->pharMode = true;
            $this->pharCompression = $compression;
            $this->pharAll = (boolean)$all;
            $this->pharKey = $key;
            $this->pharAliasName = $alias;
        }

        public function isPharMode() {
            return $this->pharMode;
        }

        public function isPharAllMode() {
            return $this->pharAll;
        }

        public function getPharCompression() {
            return $this->pharCompression;
        }

        public function getPharKey() {
            return $this->pharKey;
        }

        public function getPharAliasName() {
            return $this->pharAliasName;
        }

        public function hasPharHashAlgorithm() {
            return $this->pharHashAlgorithm !== null;
        }

        /**
         * @return string
         */
        public function getPharHashAlgorithm() {
            return $this->pharHashAlgorithm;
        }

        /**
         * @param string $pharHashAlgorithm
         */
        public function setPharHashAlgorithm($pharHashAlgorithm) {
            if (!in_array($pharHashAlgorithm, array('SHA-512','SHA-256','SHA-1'))) {
                throw new \InvalidArgumentException(
                    sprintf('Algorithm %s not supported', $pharHashAlgorithm)
                );
            }
            $this->pharHashAlgorithm = $pharHashAlgorithm;
        }

        public function setPhp($php) {
            $this->php = $php;
        }

        public function getPhp() {
            return $this->php;
        }

        public function setQuietMode($quietMode) {
            $this->quietMode = (boolean)$quietMode;
        }

        public function setStaticMode($staticMode) {
            $this->staticMode = (boolean)$staticMode;
        }

        public function isStaticMode() {
            return $this->staticMode;
        }

        public function setTemplate($template) {
            $this->template = $template;
        }

        public function getTemplate() {
            $tplType = $this->isLowercaseMode() ? 'ci' : 'cs';
            $template = $this->template;
            if ($template !== NULL) {
                if (!file_exists($template)) {
                    $alternative = __DIR__.'/templates/'. $tplType .'/'.$template;
                    if (file_exists($alternative)) {
                        $template = $alternative;
                    }
                    $alternative .= '.php.tpl';
                    if (file_exists($alternative)) {
                        $template = $alternative;
                    }
                }
                return $template;
            }

            // determine auto template to use
            $tplFile = 'default.php.tpl';
            if ($this->isCompatMode()) {
                $tplFile = 'php52.php.tpl';
            }

            if ($this->isPharMode()) {
                if ($this->isStaticMode()) {
                    $tplFile = 'staticphar.php.tpl';
                    $tplType = '.';
                } else {
                    $tplFile = 'phar.php.tpl';
                }
            } elseif ($this->isStaticMode()) {
                $tplFile = 'static.php.tpl';
                $tplType = '.';
            }

            return __DIR__.'/templates/'.$tplType.'/'.$tplFile;

        }

        public function setTolerantMode($tolerant) {
            $this->tolerant = (boolean)$tolerant;
        }

        public function isTolerantMode() {
            return $this->tolerant;
        }

        public function setTrusting($trusting) {
            $this->trusting = (boolean)$trusting;
        }

        public function setFollowSymlinks($followSymlinks) {
            $this->followSymlinks = (boolean)$followSymlinks;
        }

        public function isFollowSymlinks() {
            return $this->followSymlinks;
        }

        public function isTrustingMode() {
            return $this->trusting;
        }

        public function setVariable($name, $value) {
            $this->variable[$name] = $value;
        }

        public function getVariables() {
            return $this->variable;
        }

        public function isQuietMode() {
            return $this->quietMode;
        }

        public function getDirectories() {
            $list = array();
            foreach($this->directories as $dir) {
                if (is_file($dir) && basename($dir) == 'composer.json') {
                    foreach(new ComposerIterator(new \SplFileInfo($dir)) as $d) {
                        $list[] = $d;
                    }
                } else {
                    foreach(glob($dir) as $match) {
                        $list[] = $match;
                    }
                }
            }
            return $list;
        }

        public function setCacheFile($filename) {
            $this->cacheFilename = $filename;
        }

        public function isCacheEnabled() {
            return $this->cacheFilename !== NULL;
        }

        public function getCacheFile() {
            return $this->cacheFilename;
        }

        public function enablePrepend() {
            $this->prepend = true;
        }

        public function usePrepend() {
            return $this->prepend;
        }

        public function disableExceptions() {
            $this->exceptions = false;
        }

        public function useExceptions() {
            return $this->exceptions;
        }

    }
}
