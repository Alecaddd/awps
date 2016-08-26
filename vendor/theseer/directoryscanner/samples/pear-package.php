<?php

if (!$argc==3) {
    echo "usage: {$argv[0]} </path/to/package.xml> <directory>";
}

require __DIR__ . '/../autoload.php';

$scanner = new \TheSeer\DirectoryScanner\DirectoryScanner;

$dom = new DOMDocument();
$dom->load($argv[1]);
$dom->formatOutput = true;

$xp = new DOMXPath($dom);
$xp->registerNamespace('pear', "http://pear.php.net/dtd/package-2.0");
$baseDir = $xp->query('/pear:package/pear:contents/pear:dir[1]')->item(0);
if (!$baseDir) {
    die('package.xml does not contain a <dir> node');
}

foreach($scanner($argv[2]) as $i) {
    $path = explode('/', dirname($i->getPathname()));
    $ctx = $baseDir;
    foreach($path as $sp) {
        if ($sp == '..' or $sp == '.') continue;
        $dir = $xp->query('pear:dir[@name="'.$sp.'"]', $ctx);
        if ($dir->length == 0) {
            $node = $dom->createElementNS("http://pear.php.net/dtd/package-2.0", 'dir');
            $node->setAttribute('name', $sp);
            $ctx->appendChild($node);
            $ctx = $node;
        } else {
            $ctx = $dir->item(0);
        }
    }
    if ($i->getFilename() == 'package.xml') continue;
    if ($xp->query('pear:file[@name="'.$i->getFilename().'"]', $ctx)->length == 0) {
        $file = $dom->createElementNS("http://pear.php.net/dtd/package-2.0", 'file');
        $file->setAttribute('baseinstalldir','/');
        $file->setAttribute('role', 'php');
        if (stripos($i->getPathname(), 'tests/')!==FALSE || $i->getFilename() == 'phpunit.xml.dist') {
             $file->setAttribute('role', 'test');
        }
        $doc = array('README.md','LICENSE');
        if (in_array($i->getFilename(), $doc)) {
             $file->setAttribute('role', 'doc');
        }
        $file->setAttribute('name', $i->getFilename());
        $ctx->appendChild($file);
    }
}

echo $dom->saveXML();
