/**
 * Original File Created by Automattic for Underscore (_s) theme
 * https://github.com/Automattic/_s/
 *
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', awpUpdateSiteText( '.site-title a', value ) );

	wp.customize( 'blogdescription', awpUpdateSiteText( '.site-description', value ) );

	function awpUpdateSiteText( selector, value ) {
		value.bind( function( to ) {
			$( selector ).text( to );
		});
	}

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title a, .site-description' ).css({
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				});
			} else {
				$( '.site-title a, .site-description' ).css({
					'clip': 'auto',
					'position': 'relative'
				});
				$( '.site-title a, .site-description' ).css({
					'color': to
				});
			}
		});
	});
}( jQuery ) );
