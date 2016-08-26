# Changelog

## Release 1.22.0
* Merge PR [#73](https://github.com/theseer/Autoload/pull/73): no cs/ci dir for statis tpl [Remi]
* Merge PR [#74](https://github.com/theseer/Autoload/pull/74): auto add suffix to (short) template name [Remi]

## Release 1.21.0
* Added --hash option to explicitly choose hash algorithm for phar generation (defaults to best available)

## Release 1.20.3
* Merge PR [#68](https://github.com/theseer/Autoload/pull/68): return parent dir when 2 dirs have a common prefix
* Merge PR [#67](https://github.com/theseer/Autoload/pull/67): don't use 'vendor' in test suite
* Some internal code cleanup

## Release 1.20.2
* Merge PR [#66](https://github.com/theseer/Autoload/pull/66): fix PathComparator when 0 in path [Remi]

## Release 1.20.1
* Fix issue #65: Trait sorted after using class when --static is used
* Fix issue #63: Workdirectory included as subdirectory in phar archive (Regression as of 1.19.0)

## Release 1.20.0
* PHP 7: Added parsing support for new (grouped use syntax)[https://wiki.php.net/rfc/group_use_declarations]

## Release 1.19.2
* Remove debugging artefact

## Release 1.19.1
* Fix regression since 1.15.0: paranoid and trusting mode handling was switched

## Release 1.19.0
* Fix filenames via CLI to actually work [Remi]
* Changed default basedir to be based on the output file rather than the directory to be scanned [Remi]

## Release 1.18.0
* Allow filenames as source instead of only allowing directories (this also fixes composer classmap issues)

## Release 1.17.0
* Added support for parameter 'prepend' of spl_autoload_register to allow for prepending without changing templates
* Added support for parameter 'exception' of spl_autoload_register to optionally disable exceptions on errors

## Release 1.16.1
* Fix minor issues with composer.json handling
* define date.timezone to avoid warning (in buildystem) [Remi]
* Pear installation now deploys as phar

## Release 1.16.0

* Added whitelist/blacklist filter for classnames/namespaces
* Ensure ext/fileinfo is loaded

## Release 1.15.1

* Fix regression for sorted static require lists

## Release 1.15.0

* Added support for composer.json parsing
* Added (optional) caching
* Added explicit wildcard support for directory name matching
* Changed error messages on unit redeclarations
* Some code cleanup and refactoring of internals

## Release 1.14.2

* Fix Trait handling in PHP 5.3
* Changed file type for phpunit.xml.dist in pear package

## Release 1.14.1

* Providing --alias in phar mode now sets ___PHAR___ accordingly
* Updated DirectoryScanner to 1.3.0

## Release 1.14.0

* Added support for PHP 5.5's classname::class constant to parser

## Release 1.13.1

* Fix Regression, make composer installs work again

## Release 1.13.0

* Added alias support to phar mode building

## Release 1.12.0

* Added composer support (Thanks to HCO)
* Made parser code more robust to not crash on invalid names

## Release 1.11.0

* Added support for symlinks (Thanks to Jan Peterson)

## Release 1.10.3

* Support empty indent
* Fixed Trait parsing

## Release 1.10.2

* Fixed mode flag handling on phar mode

## Release 1.10.1

* Various regression fixes after internal cleanup
* Use git version info for development checkouts

## Release 1.10.0

* Added multi directory support
* Added compression support to phar mode
* Added support for openssl key signing of phars

#####Older Releases

Please refer to the git history log for details
