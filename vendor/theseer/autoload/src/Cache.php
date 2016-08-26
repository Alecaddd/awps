<?php
namespace TheSeer\Autoload {

    class Cache {

        /**
         * @var CacheEntry[]
         */
        private $loadedEntries = array();

        /**
         * @var CacheEntry[]
         */
        private $usedEntries = array();


        public function __construct(array $initialEntries) {
            $this->loadedEntries = $initialEntries;
        }

        /**
         * @param SourceFile $file
         *
         * @return bool
         */
        public function hasResult(SourceFile $file) {
            $pathname = $file->getPathname();
            if (!isset($this->loadedEntries[$pathname])) {
                return false;
            }
            return $this->loadedEntries[$pathname]->getTimestamp() === $file->getMTime();
        }

        public function getResult(SourceFile $file) {
            if (!$this->hasResult($file)) {
                throw new CacheException('Entry not found');
            }
            $pathname = $file->getPathname();
            $entry = $this->loadedEntries[$pathname];
            $this->usedEntries[$pathname] = $entry;
            return $entry->getResult();
        }

        public function addResult(SourceFile $file, ParseResult $result) {
            $this->usedEntries[$file->getPathname()] = new CacheEntry($file->getMTime(), $result);
        }

        public function persist($fname) {
            if (file_exists($fname)) {
                unlink($fname);
            }
            file_put_contents($fname, serialize($this->usedEntries));
        }
    }


    class CacheException extends \Exception {

    }

}
