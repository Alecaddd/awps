<?php

trait trait1 {
    public function demo() {}
}

class test {

    use trait1;

    protected function foo() {
        $y = 123;
        $x = function() use ($y) {
            echo $y;
        }
    }

}