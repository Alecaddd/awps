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

eval("/**\n * Import your Gutenberg custom blocks here\n */\n__webpack_require__(13);\n__webpack_require__(20);//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvc3JjL3NjcmlwdHMvZ3V0ZW5iZXJnLmpzPzg2NDkiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7OztBQUdBLG9CQUFTO0FBQ1Qsb0JBQVMiLCJmaWxlIjoiMTIuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvKipcbiAqIEltcG9ydCB5b3VyIEd1dGVuYmVyZyBjdXN0b20gYmxvY2tzIGhlcmVcbiAqL1xucmVxdWlyZSggJy4vYmxvY2tzL2hlbGxvLXdvcmxkLmpzJyApO1xucmVxdWlyZSggJy4vYmxvY2tzL2xhdGVzdC1wb3N0LmpzJyApO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL3NyYy9zY3JpcHRzL2d1dGVuYmVyZy5qcyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///12\n");

/***/ }),

/***/ 13:
/***/ (function(module, exports) {

eval("var _wp$blocks = wp.blocks,\n    registerBlockType = _wp$blocks.registerBlockType,\n    RichText = _wp$blocks.RichText,\n    BlockControls = _wp$blocks.BlockControls,\n    AlignmentToolbar = _wp$blocks.AlignmentToolbar,\n    source = _wp$blocks.source;\n\nregisterBlockType('gutenberg-test/hello-world', {\n\ttitle: 'Hello World',\n\ticon: 'universal-access-alt',\n\tcategory: 'layout',\n\n\tattributes: {\n\t\tcontent: {\n\t\t\ttype: 'array',\n\t\t\tsource: 'children',\n\t\t\tselector: 'p'\n\t\t},\n\t\talignment: {\n\t\t\ttype: 'string'\n\t\t}\n\t},\n\n\tedit: function edit(_ref) {\n\t\tvar attributes = _ref.attributes,\n\t\t    className = _ref.className,\n\t\t    isSelected = _ref.isSelected,\n\t\t    setAttributes = _ref.setAttributes;\n\t\tvar content = attributes.content,\n\t\t    alignment = attributes.alignment;\n\n\t\tfunction onChangeContent(newContent) {\n\t\t\tsetAttributes({ content: newContent });\n\t\t}\n\n\t\tfunction onChangeAlignment(newAlignment) {\n\t\t\tsetAttributes({ alignment: newAlignment });\n\t\t}\n\n\t\treturn [isSelected && React.createElement(BlockControls, { key: 'controls' }, React.createElement(AlignmentToolbar, {\n\t\t\tvalue: alignment,\n\t\t\tonChange: onChangeAlignment\n\t\t})), React.createElement(RichText, {\n\t\t\tkey: 'editable',\n\t\t\ttagName: 'p',\n\t\t\tclassName: className,\n\t\t\tstyle: { textAlign: alignment },\n\t\t\tonChange: onChangeContent,\n\t\t\tvalue: content\n\t\t})];\n\t},\n\tsave: function save(_ref2) {\n\t\tvar attributes = _ref2.attributes,\n\t\t    className = _ref2.className;\n\t\tvar content = attributes.content,\n\t\t    alignment = attributes.alignment;\n\n\t\treturn React.createElement('p', { className: className, style: { textAlign: alignment } }, content);\n\t}\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvc3JjL3NjcmlwdHMvYmxvY2tzL2hlbGxvLXdvcmxkLmpzPzliYjEiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6ImlCQUFpRixHOzs7Ozs7O0FBRWpGLGtCQUFtQjtRQUVsQjtPQUNBO1dBRUE7Ozs7U0FHRTtXQUNBO2FBRUQ7QUFKQzs7U0FTRjtBQUpFO0FBTkQ7O0FBTitDLDJCQWdCYTtNQUFBO01BQUE7TUFBQTtNQUFBO01BQ3BELFVBQXVCLFdBQXZCO01BQVMsWUFBYyxXQUUvQjs7V0FBUyxnQkFBaUIsWUFDekI7aUJBQWUsRUFBRSxTQUNqQjtBQUVEOztXQUFTLGtCQUFtQixjQUMzQjtpQkFBZSxFQUFFLFdBQ2pCO0FBRUQ7O1VBQ0Msb0JBQ0UsY0FBRCxpQkFBZSxLQUNkLGtDQUFDO1VBRUE7YUFBVztBQURYLElBRkYsdUJBT0E7UUFFQTtZQUNBO2NBQ0E7VUFBUSxFQUFFLFdBQ1Y7YUFDQTtVQUdGO0FBUkUsR0FERCxDQVRNO0FBb0JSO0FBL0NnRCw0QkErQ2Q7TUFBQTtNQUFBO01BQ3pCLFVBQXVCLFdBQXZCO01BQVMsWUFBYyxXQUUvQjs7U0FBTywyQkFBRyxXQUFZLFdBQVksT0FBUSxFQUFFLFdBQTJCLGVBQ3ZFO0FBbkQrQztBQUNoRCIsImZpbGUiOiIxMy5qcyIsInNvdXJjZXNDb250ZW50IjpbImNvbnN0IHsgcmVnaXN0ZXJCbG9ja1R5cGUsIFJpY2hUZXh0LCBCbG9ja0NvbnRyb2xzLCBBbGlnbm1lbnRUb29sYmFyLCBzb3VyY2UgfSA9IHdwLmJsb2NrcztcblxucmVnaXN0ZXJCbG9ja1R5cGUoICdndXRlbmJlcmctdGVzdC9oZWxsby13b3JsZCcsIHtcblx0dGl0bGU6ICdIZWxsbyBXb3JsZCcsXG5cdGljb246ICd1bml2ZXJzYWwtYWNjZXNzLWFsdCcsXG5cdGNhdGVnb3J5OiAnbGF5b3V0JyxcblxuXHRhdHRyaWJ1dGVzOiB7XG5cdFx0Y29udGVudDoge1xuXHRcdFx0dHlwZTogJ2FycmF5Jyxcblx0XHRcdHNvdXJjZTogJ2NoaWxkcmVuJyxcblx0XHRcdHNlbGVjdG9yOiAncCdcblx0XHR9LFxuXHRcdGFsaWdubWVudDoge1xuXHRcdFx0dHlwZTogJ3N0cmluZydcblx0XHR9XG5cdH0sXG5cblx0ZWRpdCggeyBhdHRyaWJ1dGVzLCBjbGFzc05hbWUsIGlzU2VsZWN0ZWQsIHNldEF0dHJpYnV0ZXMgfSApIHtcblx0XHRjb25zdCB7IGNvbnRlbnQsIGFsaWdubWVudCB9ID0gYXR0cmlidXRlcztcblxuXHRcdGZ1bmN0aW9uIG9uQ2hhbmdlQ29udGVudCggbmV3Q29udGVudCApIHtcblx0XHRcdHNldEF0dHJpYnV0ZXMoIHsgY29udGVudDogbmV3Q29udGVudCB9ICk7XG5cdFx0fVxuXG5cdFx0ZnVuY3Rpb24gb25DaGFuZ2VBbGlnbm1lbnQoIG5ld0FsaWdubWVudCApIHtcblx0XHRcdHNldEF0dHJpYnV0ZXMoIHsgYWxpZ25tZW50OiBuZXdBbGlnbm1lbnQgfSApO1xuXHRcdH1cblxuXHRcdHJldHVybiBbXG5cdFx0XHRpc1NlbGVjdGVkICYmIChcblx0XHRcdFx0PEJsb2NrQ29udHJvbHMga2V5PVwiY29udHJvbHNcIj5cblx0XHRcdFx0XHQ8QWxpZ25tZW50VG9vbGJhclxuXHRcdFx0XHRcdFx0dmFsdWU9eyBhbGlnbm1lbnQgfVxuXHRcdFx0XHRcdFx0b25DaGFuZ2U9eyBvbkNoYW5nZUFsaWdubWVudCB9XG5cdFx0XHRcdFx0Lz5cblx0XHRcdFx0PC9CbG9ja0NvbnRyb2xzPlxuXHRcdFx0KSxcblx0XHRcdDxSaWNoVGV4dFxuXHRcdFx0XHRrZXk9XCJlZGl0YWJsZVwiXG5cdFx0XHRcdHRhZ05hbWU9XCJwXCJcblx0XHRcdFx0Y2xhc3NOYW1lPXsgY2xhc3NOYW1lIH1cblx0XHRcdFx0c3R5bGU9eyB7IHRleHRBbGlnbjogYWxpZ25tZW50IH0gfVxuXHRcdFx0XHRvbkNoYW5nZT17IG9uQ2hhbmdlQ29udGVudCB9XG5cdFx0XHRcdHZhbHVlPXsgY29udGVudCB9XG5cdFx0XHQvPlxuXHRcdF07XG5cdH0sXG5cblx0c2F2ZSggeyBhdHRyaWJ1dGVzLCBjbGFzc05hbWUgfSApIHtcblx0XHRjb25zdCB7IGNvbnRlbnQsIGFsaWdubWVudCB9ID0gYXR0cmlidXRlcztcblxuXHRcdHJldHVybiA8cCBjbGFzc05hbWU9eyBjbGFzc05hbWUgfSBzdHlsZT17IHsgdGV4dEFsaWduOiBhbGlnbm1lbnQgfSB9PnsgY29udGVudCB9PC9wPjtcblx0fVxufSApO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIC4vYXNzZXRzL3NyYy9zY3JpcHRzL2Jsb2Nrcy9oZWxsby13b3JsZC5qcyJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///13\n");

/***/ }),

/***/ 20:
/***/ (function(module, exports) {

eval("var registerBlockType = wp.blocks.registerBlockType;\nvar withAPIData = wp.components.withAPIData;\n\nregisterBlockType('gutenberg-test/latest-post', {\n    title: 'Latest Post',\n    icon: 'megaphone',\n    category: 'widgets',\n\n    edit: withAPIData(function () {\n        return {\n            posts: '/wp/v2/posts?per_page=1'\n        };\n    })(function (_ref) {\n        var posts = _ref.posts,\n            className = _ref.className;\n\n        if (!posts.data) {\n            return 'loading !';\n        }\n        if (posts.data.length === 0) {\n            return 'No posts';\n        }\n        var post = posts.data[0];\n\n        return React.createElement('a', { className: className, href: post.link }, post.title.rendered);\n    }),\n\n    save: function save() {\n        // Rendering in PHP\n        return null;\n    }\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvc3JjL3NjcmlwdHMvYmxvY2tzL2xhdGVzdC1wb3N0LmpzP2Y2ZTIiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IndCQUE4QixHO2tCQUNOLEc7O0FBRXhCLGtCQUFtQjtXQUVmO1VBQ0E7Y0FFQTs7c0JBQW1CLFlBQ2Y7O21CQUdIO0FBRk87QUFGRixPQUlELGdCQUE0QjtZQUFBO1lBQUEsaUJBQzdCOztZQUFLLENBQUUsTUFBTSxNQUNUO21CQUNIO0FBQ0Q7WUFBSyxNQUFNLEtBQUssV0FBVyxHQUN2QjttQkFDSDtBQUNEO1lBQUksT0FBTyxNQUFNLEtBRWpCOztlQUFPLDJCQUFHLFdBQVksV0FBWSxNQUFPLEtBQ25DLGFBQUssTUFFZDtBQUVEOztBQXZCNkMsMEJBd0J6QztBQUNBO2VBQ0g7QUExQjRDO0FBQzdDIiwiZmlsZSI6IjIwLmpzIiwic291cmNlc0NvbnRlbnQiOlsiY29uc3QgeyByZWdpc3RlckJsb2NrVHlwZSB9ID0gd3AuYmxvY2tzO1xuY29uc3QgeyB3aXRoQVBJRGF0YSB9ID0gd3AuY29tcG9uZW50cztcblxucmVnaXN0ZXJCbG9ja1R5cGUoICdndXRlbmJlcmctdGVzdC9sYXRlc3QtcG9zdCcsIHtcbiAgICB0aXRsZTogJ0xhdGVzdCBQb3N0JyxcbiAgICBpY29uOiAnbWVnYXBob25lJyxcbiAgICBjYXRlZ29yeTogJ3dpZGdldHMnLFxuXG4gICAgZWRpdDogd2l0aEFQSURhdGEoICgpID0+IHtcbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgIHBvc3RzOiAnL3dwL3YyL3Bvc3RzP3Blcl9wYWdlPTEnXG4gICAgICAgIH07XG4gICAgfSApKCAoIHsgcG9zdHMsIGNsYXNzTmFtZSB9ICkgPT4ge1xuICAgICAgICBpZiAoICEgcG9zdHMuZGF0YSApIHtcbiAgICAgICAgICAgIHJldHVybiAnbG9hZGluZyAhJztcbiAgICAgICAgfVxuICAgICAgICBpZiAoIHBvc3RzLmRhdGEubGVuZ3RoID09PSAwICkge1xuICAgICAgICAgICAgcmV0dXJuICdObyBwb3N0cyc7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIHBvc3QgPSBwb3N0cy5kYXRhWyAwIF07XG5cbiAgICAgICAgcmV0dXJuIDxhIGNsYXNzTmFtZT17IGNsYXNzTmFtZSB9IGhyZWY9eyBwb3N0LmxpbmsgfT5cbiAgICAgICAgICAgIHsgcG9zdC50aXRsZS5yZW5kZXJlZCB9XG4gICAgICAgIDwvYT47XG4gICAgfSApLFxuXG4gICAgc2F2ZSgpIHtcbiAgICAgICAgLy8gUmVuZGVyaW5nIGluIFBIUFxuICAgICAgICByZXR1cm4gbnVsbDtcbiAgICB9XG59ICk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gLi9hc3NldHMvc3JjL3NjcmlwdHMvYmxvY2tzL2xhdGVzdC1wb3N0LmpzIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///20\n");

/***/ })

/******/ });