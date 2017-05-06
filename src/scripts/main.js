import $ from 'jquery';
import 'slick-carousel';
// import custom modules
import App from './modules/app.js';
import Carousel from './modules/carousel.js';
// export for others scripts to use
window.$ = $;

App();
Carousel();