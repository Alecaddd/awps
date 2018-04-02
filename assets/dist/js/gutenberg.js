/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 11);
/******/ })
/************************************************************************/
/******/ ({

/***/ 11:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(12);


/***/ }),

/***/ 12:
/***/ (function(module, exports, __webpack_require__) {

eval("/**\n * Import your Gutenberg custom blocks here\n */\n__webpack_require__(13);//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvc3JjL3NjcmlwdHMvZ3V0ZW5iZXJnLmpzPzg2NDkiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7OztBQUdBLG9CQUFTIiwiZmlsZSI6IjEyLmpzIiwic291cmNlc0NvbnRlbnQiOlsiLyoqXG4gKiBJbXBvcnQgeW91ciBHdXRlbmJlcmcgY3VzdG9tIGJsb2NrcyBoZXJlXG4gKi9cbnJlcXVpcmUoICcuL2Jsb2Nrcy9oZWxsby13b3JsZC5qcycgKTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyAuL2Fzc2V0cy9zcmMvc2NyaXB0cy9ndXRlbmJlcmcuanMiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///12\n");

/***/ }),

/***/ 13:
/***/ (function(module, exports) {

eval("var registerBlockType = wp.blocks.registerBlockType;\n\nregisterBlockType('gutenberg-test/hello-world', {\n\ttitle: 'Hello World',\n\ticon: 'universal-access-alt',\n\tcategory: 'layout',\n\n\tedit: function edit(_ref) {\n\t\tvar className = _ref.className;\n\n\t\treturn React.createElement('p', { className: className }, 'Hello editor.');\n\t},\n\tsave: function save(_ref2) {\n\t\tvar className = _ref2.className;\n\n\t\treturn React.createElement('p', { className: className }, 'Hello saved content.');\n\t}\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvc3JjL3NjcmlwdHMvYmxvY2tzL2hlbGxvLXdvcmxkLmpzPzliYjEiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IndCQUE4QixHOztBQUU5QixrQkFBbUI7UUFFbEI7T0FDQTtXQUVBOztBQUxnRCwyQkFLMUI7TUFBQSxpQkFDckI7O1NBQU8sMkJBQUcsV0FBWSxhQUN0QjtBQUVEO0FBVGdELDRCQVMxQjtNQUFBLGtCQUNyQjs7U0FBTywyQkFBRyxXQUFZLGFBQ3RCO0FBWCtDO0FBQ2hEIiwiZmlsZSI6IjEzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiY29uc3QgeyByZWdpc3RlckJsb2NrVHlwZSB9ID0gd3AuYmxvY2tzO1xuXG5yZWdpc3RlckJsb2NrVHlwZSggJ2d1dGVuYmVyZy10ZXN0L2hlbGxvLXdvcmxkJywge1xuXHR0aXRsZTogJ0hlbGxvIFdvcmxkJyxcblx0aWNvbjogJ3VuaXZlcnNhbC1hY2Nlc3MtYWx0Jyxcblx0Y2F0ZWdvcnk6ICdsYXlvdXQnLFxuXG5cdGVkaXQoIHsgY2xhc3NOYW1lIH0gKSB7XG5cdFx0cmV0dXJuIDxwIGNsYXNzTmFtZT17IGNsYXNzTmFtZSB9PkhlbGxvIGVkaXRvci48L3A+O1xuXHR9LFxuXG5cdHNhdmUoIHsgY2xhc3NOYW1lIH0gKSB7XG5cdFx0cmV0dXJuIDxwIGNsYXNzTmFtZT17IGNsYXNzTmFtZSB9PkhlbGxvIHNhdmVkIGNvbnRlbnQuPC9wPjtcblx0fVxufSApO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL3NyYy9zY3JpcHRzL2Jsb2Nrcy9oZWxsby13b3JsZC5qcyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///13\n");

/***/ })

/******/ });