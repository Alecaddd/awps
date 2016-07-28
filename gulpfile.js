// load Gulp...of course
var gulp         = require('gulp');

// CSS related plugins
var sass         = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var minifycss    = require('gulp-uglifycss');

// JS related plugins
var concat       = require('gulp-concat');
var uglify       = require('gulp-uglify');

// Utility plugins
var rename       = require('gulp-rename');
var sourcemaps   = require('gulp-sourcemaps');
var notify       = require('gulp-notify');

// Browers related plugins
var browserSync  = require('browser-sync').create();
var reload       = browserSync.reload;

// Project related variables
var projectURL   = 'proposal.dev';

var styleSRC	 = './assets/scss/style.scss';
var styleURL	 = './assets/css/';
var mapURL		 = './';

var jsSRC		 = './assets/scripts/*.js';
var jsURL		 = './assets/js/';

var styleWatch	 = './assets/scss/**/*.js';
var jsWatch		 = './assets/scripts/*.js';
var phpWatch	 = './**/*.php';


// Tasks
gulp.task( 'browser-sync', function() {
	browserSync.init({
		proxy: projectURL,
		open: false,
		injectChanges: true,
	});
});

gulp.task( 'styles', function() {
	gulp.src( styleSRC )
		.pipe( sourcemaps.init() )
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'compressed',
			precision: 10
		}) )
		.on('error', console.error.bind(console))
 		.pipe( sourcemaps.write( { includeContent: false } ) )
 		.pipe( sourcemaps.init( { loadMaps: true } ) )
 		.pipe( sourcemaps.write ( mapURL ) )
 		.pipe( gulp.dest( styleURL ) )
 		.pipe( browserSync.stream() )
 		.pipe( rename( { suffix: '.min' } ) )
 		.pipe( minifycss( {
 			maxLineLen: 10
 		}))
 		.pipe( gulp.dest( styleURL ) )
 		.pipe( browserSync.stream() );
});

gulp.task( 'js', function() {
  	gulp.src( jsSRC )
 		.pipe( concat( 'main.js' ) )
 		.pipe( gulp.dest( jsURL ) )
 		.pipe( rename( {
 			basename: 'main',
 			suffix: '.min'
 		}))
 		.pipe( uglify() )
 		.pipe( gulp.dest( jsURL ) );
 });
 
 gulp.task( 'default', ['styles', 'js', 'browser-sync'], function() {
	gulp.watch( phpWatch, reload );
	gulp.watch( styleWatch, [ 'styles' ] );
	gulp.watch( jsWatch, [ 'js', reload ] );
 });