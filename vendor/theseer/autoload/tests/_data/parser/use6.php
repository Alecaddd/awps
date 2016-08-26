<?php

namespace demo\a {

   class demo2 {
       public function foo() {
           $a = 123;
           $x = function($y) use ($a) {};
       }
   }
}
?>