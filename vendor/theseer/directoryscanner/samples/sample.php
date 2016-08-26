<?php

  require __DIR__ . '/../autoload.php';

  $scanner = new \TheSeer\DirectoryScanner\DirectoryScanner;
  $scanner->addInclude('*.php');
  $scanner->addExclude('*filter*');
  $scanner->addExclude('./src/*');

  foreach($scanner('.') as $i) {
     var_dump($i);
  }
