<?php
namespace TheSeer\Autoload {

    class CachingParser implements ParserInterface {

        /**
         * @var ParserInterface
         */
        private $parser;

        /**
         * @var Cache
         */
        private $cache;

        public function __construct(Cache $cache, ParserInterface $parser) {
            $this->cache = $cache;
            $this->parser = $parser;
        }

        /**
         * Parse a given file for defintions of classes, traits and interfaces
         *
         * @param SourceFile $source file to process
         *
         * @return ParseResult
         */
        public function parse(SourceFile $source) {
            if ($this->cache->hasResult($source)) {
                return $this->cache->getResult($source);
            }
            $result = $this->parser->parse($source);
            $this->cache->addResult($source, $result);
            return $result;
        }

    }

}
