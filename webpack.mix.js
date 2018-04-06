/*
 * AWPS uses Laravel Mix
 *
 * Check the documentation at
 * https://laravel.com/docs/5.6/mix
 */

let mix = require( 'laravel-mix' );

// BrowserSync and LiveReload on `npm run watch` command
// Update the `proxy` and the location of your SSL Certificates if you're developing over HTTPS
mix.browserSync({
	proxy: 'https://wp.dev',
	https: {
		key: '/Users/alecaddd/.valet/Certificates/wp.dev.key',
		cert: '/Users/alecaddd/.valet/Certificates/wp.dev.crt'
	},
	files: [
		'**/*.php',
		'assets/dist/css/**/*.css',
		'assets/dist/js/**/*.js'
	],
	injectChanges: true,
	open: false
});

// Autloading jQuery to make it accessible to all the packages, because, you know, reasons
// You can comment this line if you don't need jQuery
mix.autoload({
    'jquery': ['jQuery', '$']
});

// Compile assets
mix.js( 'assets/src/scripts/app.js', 'assets/dist/js' )
	.js( 'assets/src/scripts/admin.js', 'assets/dist/js' )
	.react( 'assets/src/scripts/gutenberg.js', 'assets/dist/js' )
	.sass( 'assets/src/sass/style.scss', 'assets/dist/css' )
	.sass( 'assets/src/sass/admin.scss', 'assets/dist/css' )
	.sass( 'assets/src/sass/gutenberg.scss', 'assets/dist/css' )
	.copyDirectory( 'assets/src/fonts', 'assets/dist/fonts' )
	.copyDirectory( 'assets/src/images', 'assets/dist/images' );
