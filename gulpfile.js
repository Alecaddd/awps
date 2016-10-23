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
var plumber      = require('gulp-plumber');

// Browers related plugins
var browserSync  = require('browser-sync').create();
var reload       = browserSync.reload;

// Project related variables
var projectURL   = 'http://wp.dev';

var styleSRC	 = './src/scss/style.scss';
var styleURL	 = './assets/css/';
var mapURL		 = './';

var jsSRC		 = './src/scripts/*.js';
var jsURL		 = './assets/js/';

var imgSRC		 = './src/images/**/*';
var imgURL		 = './assets/images/';

var fontsSRC	 = './src/fonts/**/*';
var fontsURL	 = './assets/fonts/';

var styleWatch	 = './src/scss/**/*.scss';
var jsWatch		 = './src/scripts/*.js';
var imgWatch	 = './src/images/**/*.*';
var fontsWatch	 = './src/fonts/**/*.*';
var phpWatch	 = './**/*.php';


// Tasks
gulp.task( 'browser-sync', function() {
	browserSync.init({
		proxy: projectURL,
		injectChanges: true,
		open: false
	});
});

gulp.task( 'styles', function() {
	gulp.src( styleSRC )
		.pipe(sourcemaps.init())
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'compressed'
		}) )
		.on('error', console.error.bind(console))
		.pipe( autoprefixer({ browsers: ['last 2 versions', '> 5%', 'Firefox ESR'] }) )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( sourcemaps.write ( mapURL ) )
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

gulp.task( 'images', function() {
	 return gulp.src( imgSRC )
	 .pipe( plumber() )
	 .pipe( gulp.dest( imgURL ) );
});

gulp.task( 'fonts', function() {
	 return gulp.src( fontsSRC )
	 .pipe( plumber() )
	 .pipe( gulp.dest( fontsURL ) );
});

 gulp.task( 'default', ['styles', 'js', 'images', 'fonts', 'browser-sync'], function() {
	gulp.watch( phpWatch, reload );
	gulp.watch( styleWatch, [ 'styles' ] );
	gulp.watch( jsWatch, [ 'js', reload ] );
	gulp.watch( imgWatch, [ 'images' ] );
	gulp.watch( fontsWatch, [ 'fonts' ] );
 });
