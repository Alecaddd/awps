<?php

namespace demo\a {

   class demo1 { }

}

namespace demo\b {

   use demo\a;

   class demo2 extends a\demo1 { }
}
?>