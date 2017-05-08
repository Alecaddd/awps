# ![awps](http://www.alecaddd.com/wp-content/uploads/2017/05/awps.png)

> A Modern WordPress Starter Theme for savvy Developers

[![Build Status](https://travis-ci.org/Alecaddd/awps.svg?branch=master)](https://travis-ci.org/Alecaddd/awps) ![Dependecies](https://david-dm.org/Alecaddd/awps.svg) ![NPM latest](https://img.shields.io/npm/v/npm.svg) ![GPL License](https://img.shields.io/badge/license-GPLv3-blue.svg) [![Code Climate](https://codeclimate.com/github/Alecaddd/awps/badges/gpa.svg)](https://codeclimate.com/github/Alecaddd/awps)


## Prerequisites

This theme relies on **NPM** and **Composer** in order to load dependencies and packages.
**Gulp** should always be running and watching during the development process in order to properly compile and update files.

* Install [Composer](https://getcomposer.org/)
* Install [Node](https://nodejs.org/)
* Install [Gulp](http://gulpjs.com/)


## Installation

* Open a Terminal windows on the location of the theme fodler
* Execute `npm install`
* Execute `composer install`
* Move the `.env` to your WordPress root directory and setup your site variables
* Move the `wp-config.sample.php` to your WordPress root directory, and rename it `wp-config.php` to replace the default one
* Run `gulp watch` at the beginning of every development session OR Run `gulp` to quickly compile without watching


## Features

* Bult-in `gulpfile.js` for fast development and compiling.
* `OOP` PHP, and `namespaces` with `PSR4` autoload.
* `ES6 Javascript` syntax ready.
* Compatible with `JetPack`, `WooCommerce`, `ACF PRO` and all the most famous plugins.
* Built-in `FlexBox` Responsive Grid.
* Modular and Components based file structure.


## License

[GPLv3](https://github.com/Alecaddd/awps/blob/master/LICENSE.txt)