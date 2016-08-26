<?php
namespace TheSeer\Autoload {

    class CacheEntry {

        /**
         * @var ParseResult
         */
        private $result;

        /**
         * @var int
         */
        private $timestamp;

        public function __construct($timestamp, ParseResult $result) {
            $this->timestamp = $timestamp;
            $this->result = $result;
        }

        /**
         * @return ParseResult
         */
        public function getResult() {
            return $this->result;
        }

        /**
         * @return int
         */
        public function getTimestamp() {
            return $this->timestamp;
        }


    }

}
