<?php
namespace TheSeer\Autoload {

    class CollectorResult {

        /**
         * @var array
         */
        private $whitelist;

        /**
         * @var array
         */
        private $blacklist;

        /**
         * @var array
         */
        private $units = array();

        /**
         * @var array
         */
        private $dependencies = array();

        public function __construct(array $whitelist, array $blacklist) {
            $this->whitelist = $whitelist;
            $this->blacklist = $blacklist;
        }

        public function addParseResult(\SplFileInfo $file, ParseResult $result) {
            if (!$result->hasUnits()) {
                return;
            }
            $filename = $file->getRealPath();
            foreach($result->getUnits() as $unit) {
                if (!$this->accept($unit)) {
                    continue;
                }
                if (isset($this->units[$unit])) {
                    throw new CollectorResultException(
                        sprintf(
                            "Redeclaration of trait, interface or class found:\n\n\tUnit name: %s\n\tFirst occurance: %s\n\tRedeclaration: %s",
                            $unit,
                            $this->units[$unit],
                            $filename
                        ),
                        CollectorResultException::DuplicateUnitName
                    );
                }
                $this->units[$unit] = $filename;
                $this->dependencies[$unit] = $result->getDependenciesForUnit($unit);
            }
        }

        public function hasUnits() {
            return count($this->units) > 0;
        }

        /**
         * @return array
         */
        public function getDependencies() {
            return $this->dependencies;
        }

        /**
         * @return array
         */
        public function getUnits() {
            return $this->units;
        }

        /**
         * @param string $unit
         *
         * @return bool
         */
        private function accept($unit) {
            foreach($this->blacklist as $entry) {
                if (fnmatch($entry, $unit)) {
                    return false;
                }
            }
            foreach($this->whitelist as $entry) {
                if (fnmatch($entry, $unit)) {
                    return true;
                }
            }
            return false;
        }

    }

    class CollectorResultException extends \Exception {
        const DuplicateUnitName = 1;
    }

}
