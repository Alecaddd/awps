let mix = require( 'laravel-mix' );

/*
 * AWPS uses Laravel Mix
 *
 * Check the documentation at
 * https://laravel.com/docs/5.6/mix
 */

const ImageminPlugin = require( 'imagemin-webpack-plugin' ).default;
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const imageminMozjpeg = require( 'imagemin-mozjpeg' );

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

mix.js( 'assets/src/scripts/app.js', 'assets/dist/js' )
	.js( 'assets/src/scripts/admin.js', 'assets/dist/js' )
	.sass( 'assets/src/sass/style.scss', 'assets/dist/css' )
	.sass( 'assets/src/sass/admin.scss', 'assets/dist/css' )
	.sourceMaps();
