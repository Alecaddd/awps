/**
 * Manage global libraries like jQuery or THREE from the package.json file
 */
var $ = require( 'jquery' );

// Import libraries
import 'slick-carousel';
import 'izimodal';

// Import custom modules
import App from'./modules/app.js';
import Carousel from './modules/carousel.js';

const app = new App();
const carousel = new Carousel();
