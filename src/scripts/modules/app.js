'use strict';

const App = class App {

	constructor() {
		this.init();

		/**
		 * You can use jQuery or ES6 javascript to trigger methods on DOM actions
		 */

		// $( '.el' ).on( 'click', this.elClick );

		document.querySelector( '.el' ).addEventListener( 'click', this.elClick );
	}

	init() {
		console.log( 'App Initialized' );
	}

	elClick( e ) {
		e.target.classList.add( 'text-light-grey' );
	}

};

module.exports = App;
