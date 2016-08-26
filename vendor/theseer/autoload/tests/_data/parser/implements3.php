<?php
namespace a {

   interface demo1 { }

}

namespace b {

   interface demo2 { }

   class demo3 implements \a\demo1, demo2 { }

}
