<?php

namespace test {

  class base { }
  
  interface demo1 { }
  interface demo2 { }
}

namespace foo {
  class demo3 implements \test\demo1, \test\demo2 { }
  
  class demo5 extends demo3 { }
}

namespace bar {
  class demo4 extends \foo\demo3 { }
  
  class ex extends \Exception { }
}

?>
