<?php

namespace demo\a {

   class demo1 { }

}

namespace demo\b {

   use demo\a\demo1 as foo;

   class demo2 extends foo { }
}
?>