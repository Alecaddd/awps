import $ from 'jquery';
import 'slick-carousel';

// Import custom modules
import App from'./modules/app.js';
import Carousel from './modules/carousel.js';

// Export for others scripts to use
window.$ = $;

const app = new App();
const carousel = new Carousel();
