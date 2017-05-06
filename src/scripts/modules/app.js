'use strict';

const App = class App {

	constructor() {
		this.init();

		$( '.el' ).on( 'click', this.elClick );
	}

	init() {
		console.log( 'App Initialized' );
	}

	elClick( event ) {

	}

};

module.exports = App;
