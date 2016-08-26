<?php

namespace demo\a {

   class demo1 { }

}

namespace demo\b {

   class demo2 { }

}

namespace demo\c {

   use demo\a\demo1, demo\b\demo2;

   class demo3 extends demo1 { }
}
?>