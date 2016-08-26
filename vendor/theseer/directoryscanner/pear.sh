#!/bin/sh
rm -f DirectoryScanner*.tgz
mkdir -p tmp/TheSeer/DirectoryScanner
mkdir -p tmp/tests
cp -r src/* tmp/TheSeer/DirectoryScanner
cp -r tests/* tmp/tests
cp LICENSE phpunit.xml.dist package.xml tmp
cd tmp
pear package
mv DirectoryScanner*.tgz ..
cd ..
rm -rf tmp
