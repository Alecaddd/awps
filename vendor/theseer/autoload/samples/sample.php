<?php

  require __DIR__ . '/../src/autoload.php';

  $parser = new \TheSeer\Autoload\Parser;

  $result = $parser->parse(new \TheSeer\Autoload\SourceFile(__DIR__ . '/../src/Parser.php'));
  var_dump($result->getUnits(), $result->getDependenciesForUnit(strtolower(\TheSeer\Autoload\ParserException::class)));
