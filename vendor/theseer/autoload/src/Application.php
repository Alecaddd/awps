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

    class Application {

        private $logger;
        private $factory;
        private $config;

        public function __construct(Logger $logger, Config $config, Factory $factory) {
            $this->logger = $logger;
            $this->config = $config;
            $this->factory = $factory;
        }

        public function run() {
            $result = $this->runCollector();
            if (!$result->hasUnits()) {
                throw new ApplicationException("No units were found - process aborted.", ApplicationException::NoUnitsFound);
            }
            if ($this->config->isCacheEnabled()) {
                $this->factory->getCache()->persist($this->config->getCacheFile());
            }
            $builder = $this->factory->getRenderer($result);
            $code = $builder->render(file_get_contents($this->config->getTemplate()));
            if ($this->config->isLintMode()) {
                return $this->runLint($code);
            }
            return $this->runSaver($code);
        }

        /**
         * @return CollectorResult
         */
        private function runCollector() {
            if ($this->config->isFollowSymlinks()) {
                $this->logger->log('Following symbolic links is enabled.' . "\n\n");
            }
            $collector = $this->factory->getCollector();
            foreach ($this->config->getDirectories() as $directory) {
                if (is_dir($directory)) {
                    $this->logger->log('Scanning directory ' . $directory . "\n");
                    $scanner = $this->factory->getScanner()->getIterator($directory);
                    $collector->processDirectory($scanner);
                    // this unset is needed to "fix" a segfault on shutdown in some PHP Versions
                    unset($scanner);
                } else {
                    $this->logger->log('Scanning file ' . $directory . "\n");
                    $collector->processFile(new \SplFileInfo($directory));
                }
            }
            return $collector->getResult();
        }

        private function runSaver($code) {
            $output = $this->config->getOutputFile();
            if (!$this->config->isPharMode()) {
                if ($output === 'STDOUT') {
                    $this->logger->log("\n");
                    echo $code;
                    $this->logger->log("\n\n");
                    return CLI::RC_OK;
                }
                // @codingStandardsIgnoreStart
                $written = @file_put_contents($output, $code);
                // @codingStandardsIgnoreEnd
                if ($written != strlen($code)) {
                    $this->logger->log("Writing to file '$output' failed.", STDERR);
                    return CLI::RC_EXEC_ERROR;
                }
                $this->logger->log("\nAutoload file {$output} generated.\n\n");
                return CLI::RC_OK;
            }
            if (strpos($code, '__HALT_COMPILER();') === FALSE) {
                $this->logger->log(
                    "Warning: Template used in phar mode did not contain required __HALT_COMPILER() call\n" .
                        "which has been added automatically. The used stub code may not work as intended.\n\n", STDERR);
                $code .= $this->config->getLineBreak() . '__HALT_COMPILER();';
            }
            $pharBuilder = $this->factory->getPharBuilder();
            if ($keyfile = $this->config->getPharKey()) {
                $pharBuilder->setSignatureKey($this->loadPharSignatureKey($keyfile));
            }
            if ($aliasName = $this->config->getPharAliasName()) {
                $pharBuilder->setAliasName($aliasName);
            }
            if ($this->config->hasPharHashAlgorithm()) {
                $pharBuilder->setSignatureType($this->config->getPharHashAlgorithm());
            }
            $pharBuilder->build($output, $code);
            $this->logger->log("\nphar archive '{$output}' generated.\n\n");
            return CLI::RC_OK;
        }

        private function loadPharSignatureKey($keyfile) {
            if (!extension_loaded('openssl')) {
                throw new ApplicationException("Extension for OpenSSL not loaded - cannot sign phar archive - process aborted.",
                    ApplicationException::OpenSSLError);
            }
            $keydata = file_get_contents($keyfile);
            if (strpos($keydata, 'ENCRYPTED') !== FALSE) {
                $this->logger->log("Passphrase for key '$keyfile': ");
                $g = shell_exec('stty -g');
                shell_exec('stty -echo');
                $passphrase = trim(fgets(STDIN));
                $this->logger->log("\n");
                shell_exec('stty ' . $g);
                $private = openssl_pkey_get_private($keydata, $passphrase);
            } else {
                $private = openssl_pkey_get_private($keydata);
            }
            if (!$private) {
                throw new ApplicationException("Opening private key '$keyfile' failed - process aborted.\n\n", ApplicationException::OpenSSLError);
            }
            return $private;
        }


        /**
         * Execute a lint check on generated code
         *
         * @param string           $code  Generated code to lint
         *
         * @return boolean
         */
        protected function runLint($code) {
            $dsp = array(
                0 => array("pipe", "r"),
                1 => array("pipe", "w"),
                2 => array("pipe", "w")
            );

            $binary = $this->config->getPhp();

            $process = proc_open($binary . ' -l', $dsp, $pipes);

            if (!is_resource($process)) {
                $this->logger->log("Opening php binary for linting failed.\n", STDERR);
                return 1;
            }

            fwrite($pipes[0], $code);
            fclose($pipes[0]);
            fclose($pipes[1]);

            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $rc = proc_close($process);

            if ($rc == 255) {
                $this->logger->log("Syntax errors during lint:\n" .
                    str_replace('in - on line', 'in generated code on line', $stderr) .
                    "\n", STDERR);
                return CLI::RC_LINT_ERROR;
            }

            $this->logger->log("Lint check of geneated code okay\n\n");
            return CLI::RC_OK;
        }

    }

    class ApplicationException extends \Exception {
        const NoUnitsFound = 1;
        const OpenSSLError = 2;
    }

}
