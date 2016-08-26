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

    class PharBuilder {

        private $scanner;
        private $compression;
        private $key;
        private $basedir;
        private $aliasName;
        private $signatureType;

        private $directories = array();

        private $supportedSignatureTypes = array(
            'SHA-512' => \Phar::SHA512,
            'SHA-256' => \Phar::SHA256,
            'SHA-1' => \Phar::SHA1
        );

        public function __construct(DirectoryScanner $scanner, $basedir) {
            $this->scanner = $scanner;
            $this->basedir = $basedir;
        }

        public function setCompressionMode($mode) {
            $this->compression = $mode;
        }

        public function setSignatureType($type) {
            if (!in_array($type, array_keys($this->supportedSignatureTypes))) {
                throw new \InvalidArgumentException(
                    sprintf('Signature type "%s" not known or not supported by this PHP installation.', $type)
                );
            }
            $this->signatureType = $type;
        }

        public function setSignatureKey($key) {
            $this->key = $key;
        }

        public function addDirectory($directory) {
            $this->directories[] = $directory;
        }

        public function setAliasName($name) {
            $this->aliasName = $name;
        }

        public function build($filename, $stub) {
            if (file_exists($filename)) {
                unlink($filename);
            }
            $phar = new \Phar($filename, 0, $this->aliasName != '' ? $this->aliasName : basename($filename));
            $phar->startBuffering();
            $phar->setStub($stub);
            if ($this->key !== NULL) {
                $privateKey = '';
                openssl_pkey_export($this->key, $privateKey);
                $phar->setSignatureAlgorithm(\Phar::OPENSSL, $privateKey);
                $keyDetails = openssl_pkey_get_details($this->key);
                file_put_contents($filename . '.pubkey', $keyDetails['key']);
            } else {
                $phar->setSignatureAlgorithm($this->selectSignatureType($phar));
            }

            $basedir = $this->basedir ? $this->basedir : $this->directories[0];
            foreach($this->directories as $directory) {
                $phar->buildFromIterator($this->scanner->__invoke($directory), $basedir);
            }

            if ($this->compression !== \Phar::NONE) {
                $phar->compressFiles($this->compression);
            }
            $phar->stopBuffering();
        }

        private function selectSignatureType(\Phar $phar) {
            if ($this->signatureType !== NULL) {
                return $this->supportedSignatureTypes[$this->signatureType];
            }
            $supported = $phar->getSupportedSignatures();
            foreach($this->supportedSignatureTypes as $candidate => $type) {
                if (in_array($candidate, $supported)) {
                    return $type;
                }
            }

            // Is there any PHP Version out there that does not support at least SHA-1?
            // But hey, fallback to md5, better than nothing
            return \Phar::MD5;
        }

    }

}
