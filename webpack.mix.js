/*
 * AWPS uses Laravel Mix
 *
 * Check the documentation at
 * https://laravel.com/docs/5.6/mix
 */

let mix = require( 'laravel-mix' );
const ImageminPlugin = require( 'imagemin-webpack-plugin' ).default;
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const imageminMozjpeg = require( 'imagemin-mozjpeg' );

// Copy FONTS and IMAGES assets to the dist/ folder, compress IMAGES to lower file size
mix.webpackConfig({
	plugins: [
		new CopyWebpackPlugin([
			{ from: 'assets/src/images', to: 'assets/dist/images', ignore: [ '.keep' ] },
			{ from: 'assets/src/fonts', to: 'assets/dist/fonts', ignore: [ '.keep' ] }
		]),
		new ImageminPlugin({
			test: /\.(jpe?g|png|gif|svg)$/i,
			plugins: [ imageminMozjpeg({ quality: 90 }) ]
		})
	]
});

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

// Compile assets
mix.js( 'assets/src/scripts/app.js', 'assets/dist/js' )
	.js( 'assets/src/scripts/admin.js', 'assets/dist/js' )
	.sass( 'assets/src/sass/style.scss', 'assets/dist/css' )
	.sass( 'assets/src/sass/admin.scss', 'assets/dist/css' )
	.sourceMaps();
