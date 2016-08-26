<?php

namespace demo\a {

   class demo1 { }

}

namespace demo\b {

   use demo\a\demo1;

   class demo2 extends demo1 { }
}
?>