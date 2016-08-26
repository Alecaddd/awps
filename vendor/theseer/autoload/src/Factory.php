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

    use TheSeer\DirectoryScanner\DirectoryScanner;

    class Factory {

        /**
         * @var Config
         */
        private $config;

        /**
         * @var Cache
         */
        private $cache;

        /**
         * @param \TheSeer\Autoload\Config $config
         */
        public function setConfig(Config $config) {
            $this->config = $config;
        }

        /**
         * @return CLI
         */
        public function getCLI() {
            return new CLI($this);
        }

        /**
         * @return Application
         */
        public function getApplication() {
            return new Application($this->getLogger(), $this->config, $this);
        }

        public function getLogger() {
            return new Logger($this->config->isQuietMode());
        }

        /**
         * @return Parser
         */
        public function getParser() {
            $parser = new Parser(
                $this->config->isLowercaseMode()
            );
            if (!$this->config->isCacheEnabled()) {
                return $parser;
            }
            return new CachingParser(
                $this->getCache(),
                $parser
            );
        }

        /**
         * @return Cache
         */
        public function getCache() {
            if (!$this->cache instanceof Cache) {
                $fname = $this->config->getCacheFile();
                if (file_exists($fname)) {
                    $data = unserialize(file_get_contents($fname));
                } else {
                    $data = array();
                }
                $this->cache = new Cache($data);
            }
            return $this->cache;
        }

        public function getCollector() {
            return new Collector(
                $this->getParser(),
                $this->config->isTolerantMode(),
                $this->config->isTrustingMode(),
                $this->config->getWhitelist(),
                $this->config->getBlacklist()
            );
        }

        /**
         * Get instance of DirectoryScanner with filter options applied
         *
         * @param bool $filter
         * @return DirectoryScanner
         */
        public function getScanner($filter = TRUE) {
            $scanner = new DirectoryScanner;
            if ($filter) {
                $scanner->setIncludes($this->config->getInclude());
                $scanner->setExcludes($this->config->getExclude());
            }
            if ($this->config->isFollowSymlinks()) {
                $scanner->setFlag(\FilesystemIterator::FOLLOW_SYMLINKS);
            }
            return $scanner;
        }


        public function getPharBuilder() {
            $builder = new PharBuilder(
                $this->getScanner(!$this->config->isPharAllMode()),
                $this->config->getBaseDirectory()
            );
            $builder->setCompressionMode($this->config->getPharCompression());
            foreach($this->config->getDirectories() as $directory) {
                $builder->addDirectory($directory);
            }

            return $builder;
        }

        /**
         * Helper to get instance of AutoloadRenderer with cli options applied
         *
         * @param CollectorResult $result
         *
         * @throws \RuntimeException
         * @return \TheSeer\Autoload\AutoloadRenderer|\TheSeer\Autoload\StaticRenderer
         */
        public function getRenderer(CollectorResult $result) {
            $isStatic = $this->config->isStaticMode();
            $isPhar   = $this->config->isPharMode();
            $isCompat = $this->config->isCompatMode();
            $isOnce   = $this->config->isOnceMode();

            if ($isStatic === TRUE) {
                $renderer = new StaticRenderer($result->getUnits());
                $renderer->setDependencies($result->getDependencies());
                $renderer->setPharMode($isPhar);
                $renderer->setRequireOnce($isOnce);
            } else {
                $renderer = new AutoloadRenderer($result->getUnits());
                if ($this->config->usePrepend()) {
                    $renderer->prependAutoloader();
                }
                if ($this->config->useExceptions()) {
                    $renderer->enableExceptions();
                }
            }

            $renderer->setCompat($isCompat);

            $basedir = $this->config->getBaseDirectory();
            if (!$basedir || !is_dir($basedir)) {
                throw new \RuntimeException("Given basedir '{$basedir}' does not exist or is not a directory");
            }
            $renderer->setBaseDir($basedir);

            $format = $this->config->getDateFormat();
            if ($format) {
                $renderer->setDateTimeFormat($format);
            }

            $renderer->setIndent($this->config->getIndent());
            $renderer->setLineBreak($this->config->getLinebreak());

            foreach($this->config->getVariables() as $name => $value) {
                $renderer->setVariable($name, $value);
            }

            return $renderer;
        }

    }

}
