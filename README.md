# ![awps](http://www.alecaddd.com/wp-content/uploads/2017/05/awps-logo.png)
> A Modern WordPress Starter Theme for savvy Developers

[![Build Status](https://travis-ci.org/Alecaddd/awps.svg?branch=master)](https://travis-ci.org/Alecaddd/awps) ![Dependecies](https://david-dm.org/Alecaddd/awps.svg) ![NPM latest](https://img.shields.io/npm/v/npm.svg) ![GPL License](https://img.shields.io/badge/license-GPLv3-blue.svg) [![Code Climate](https://codeclimate.com/github/Alecaddd/awps/badges/gpa.svg)](https://codeclimate.com/github/Alecaddd/awps)

## AWPS on YouTube

Introduction and installation video [YouTube](https://www.youtube.com/watch?v=NKRheNMczlM)

## Prerequisites

This theme relies on **NPM** and **Composer** in order to load dependencies and packages.
**Gulp** should always be running and watching during the development process, in order to properly compile and update files.

* Install [Composer](https://getcomposer.org/)
* Install [Node](https://nodejs.org/)
* Install [Gulp](http://gulpjs.com/)


## Installation

* Move the `.env.example` to your WordPress root directory, rename it as `.env`, and setup your website variables
* Move the `wp-config.sample.php` to your WordPress root directory and rename it as `wp-config.php`, to replace the default one
* Open a Terminal window on the location of the theme folder
* Execute `composer install`
* Execute `npm install`


## Gulp

* Run `gulp watch` at the beginning of every development session
* Run `gulp` to quickly compile without watching
* Run `gulp --production` to compile the assets for production and remove debug and console messages


## Features

* Bult-in `gulpfile.js` for fast development and compiling.
* `OOP` PHP, and `namespaces` with `PSR4` autoload.
* `ES6 Javascript` syntax ready.
* Compatible with `JetPack`, `WooCommerce`, `ACF PRO`, and all the most famous plugins.
* Built-in `FlexBox` Responsive Grid.
* Modular, Components based file structure.


## License

[GPLv3](https://github.com/Alecaddd/awps/blob/master/LICENSE.txt)