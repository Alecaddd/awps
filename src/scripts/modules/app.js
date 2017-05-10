'use strict';

class App {

	constructor() {
		this.el = document.querySelector( '.el' );

		this.listeners();
		this.init();
	}

	init() {
		console.info( 'App Initialized' );
	}

	listeners() {
		return ( this.el ) ? this.el.addEventListener( 'click', this.elClick, false ) : '' ;
	}

	elClick( e ) {
		e.target.classList.add( 'text-light-grey' );
	}

}

export default App;
