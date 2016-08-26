<?php
namespace TheSeer\Autoload {

    class ComposerIterator implements \Iterator {

        /**
         * @var array
         */
        private $directories = array();

        private $pos = 0;

        public function __construct(\SplFileInfo $composerFile) {
            if (!$composerFile->isFile() || !$composerFile->isReadable()) {
                throw new ComposerIteratorException(
                    sprintf('Composer file "%s" not found or not readable', $composerFile->getPathname()),
                    ComposerIteratorException::InvalidComposerJsonFile
                );
            }
            $composerDir = dirname($composerFile->getRealPath());
            $composerData = json_decode(file_get_contents($composerFile->getRealPath()), true);
            if (isset($composerData['require'])) {
                foreach($composerData['require'] as $require => $version) {
                    if ($require == 'php' || strpos($require, 'ext-') === 0) {
                        continue;
                    }
                    $this->processRequire($composerDir, $require);
                }
            }
            if (isset($composerData['autoload'])) {
                $this->processAutoload($composerDir, $composerData['autoload']);
            }
        }

        private function processAutoload($baseDir, array $map) {
            if (isset($map['classmap'])) {
                foreach($map['classmap'] as $dir) {
                    $this->addDirectory($baseDir . '/' . $dir);
                }
            }
            foreach(array('psr-0', 'psr-4') as $psr) {
                if (isset($map[$psr])) {
                    foreach ($map[$psr] as $node => $dir) {
                        if ($dir === '') {
                            $this->addDirectory($baseDir);
                            continue;
                        }
                        $this->addDirectory($baseDir . '/' . $dir);
                    }
                }
            }
        }

        private function processRequire($basedir, $require) {
            $requireDir = $basedir . '/vendor/' . $require;
            $jsonFile = $this->findComposerJson($requireDir);
            $jsonData = json_decode(file_get_contents($jsonFile), true);

            if (isset($jsonData['require'])) {
                foreach($jsonData['require'] as $require => $version) {
                    if ($require == 'php' || strpos($require, 'ext-') === 0) {
                        continue;
                    }
                    $this->processRequire($basedir, $require);
                }
            }

            if (isset($jsonData['autoload'])) {
                $this->processAutoload($requireDir, $jsonData['autoload']);
                return;
            }
            $this->addDirectory($requireDir);
        }

        private function findComposerJson($dir) {
            if (file_exists($dir . '/composer.json')) {
                return $dir . '/composer.json';
            }
            foreach(glob($dir . '/*', GLOB_ONLYDIR) as $subDir) {
                $result = $this->findComposerJson($subDir);
                if ($result !== NULL) {
                    return $result;
                }
            }
        }

        private function addDirectory($dir) {
            $dir = rtrim($dir, '/');
            if (!in_array($dir, $this->directories)) {
                $this->directories[] = $dir;
            }
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Return the current element
         *
         * @link http://php.net/manual/en/iterator.current.php
         * @return mixed Can return any type.
         */
        public function current() {
            return $this->directories[$this->pos];
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Move forward to next element
         *
         * @link http://php.net/manual/en/iterator.next.php
         * @return void Any returned value is ignored.
         */
        public function next() {
            $this->pos++;
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Return the key of the current element
         *
         * @link http://php.net/manual/en/iterator.key.php
         * @return mixed scalar on success, or null on failure.
         */
        public function key() {
            return $this->pos;
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Checks if current position is valid
         *
         * @link http://php.net/manual/en/iterator.valid.php
         * @return boolean The return value will be casted to boolean and then evaluated.
         *       Returns true on success or false on failure.
         */
        public function valid() {
            return $this->pos < count($this->directories);
        }

        /**
         * (PHP 5 &gt;= 5.0.0)<br/>
         * Rewind the Iterator to the first element
         *
         * @link http://php.net/manual/en/iterator.rewind.php
         * @return void Any returned value is ignored.
         */
        public function rewind() {
            $this->pos = 0;
        }

    }

    class ComposerIteratorException extends \Exception {
        const InvalidComposerJsonFile = 1;
    }

}
