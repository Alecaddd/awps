<?php
namespace TheSeer\Autoload {

    class PathComparator {

        /**
         * @var string[]
         */
        private $directories = array();

        /**
         * PathComparator constructor.
         *
         * @param array $directories
         */
        public function __construct(array $directories) {
            foreach($directories as $dir) {
                $this->directories[] = realpath($dir).'/';
            }
        }

        public function getCommonBase() {
            if (count($this->directories) == 0) {
                return '/';
            }
            $result = $this->directories[0];
            foreach($this->directories as $dir) {
                $result = substr($dir, 0, $this->commonPrefix($result, $dir));
            }
            return ($result ?: '/');
        }


        private function commonPrefix( $s1, $s2 ) {
            $l1 = strlen($s1);
            $l2 = strlen($s2);
            $i=0;
            while($i < $l1 && $i < $l2 && $s1[$i] == $s2[$i]) {
                $i++;
            }
            return strrpos(substr($s1, 0, $i), '/');
        }
    }

}
