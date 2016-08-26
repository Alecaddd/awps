<?php

trait trait1 {
    public function demo() {}
}

trait trait2 {
    public function trait2Method() {}
}

class test {
    use trait1, trait2;
}
