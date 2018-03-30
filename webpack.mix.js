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
			{ from: 'src/images', to: 'assets/images', ignore: [ '.keep' ] },
			{ from: 'src/fonts', to: 'assets/fonts', ignore: [ '.keep' ] }
		]),
		new ImageminPlugin({
			test: /\.(jpe?g|png|gif|svg)$/i,
			plugins: [
				imageminMozjpeg({
					quality: 90
				})
			]
		})
	]
});

mix.js( 'src/scripts/app.js', 'assets/js' )
	.js( 'src/scripts/admin.js', 'assets/js' )
	.sass( 'src/sass/style.scss', 'assets/css' )
	.sass( 'src/sass/admin.scss', 'assets/css' )
	.sourceMaps();
