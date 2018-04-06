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
/******/ ([
/* 0 */,
/* 1 */,
/* 2 */,
/* 3 */,
/* 4 */,
/* 5 */,
/* 6 */,
/* 7 */,
/* 8 */,
/* 9 */,
/* 10 */,
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(12);


/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

/**
 * Import your Gutenberg custom blocks here
 */
__webpack_require__(13);
__webpack_require__(14);

/***/ }),
/* 13 */
/***/ (function(module, exports) {

var _wp$blocks = wp.blocks,
    registerBlockType = _wp$blocks.registerBlockType,
    RichText = _wp$blocks.RichText,
    BlockControls = _wp$blocks.BlockControls,
    AlignmentToolbar = _wp$blocks.AlignmentToolbar,
    source = _wp$blocks.source;

registerBlockType('gutenberg-test/hello-world', {
	title: 'Hello World',
	icon: 'universal-access-alt',
	category: 'layout',

	attributes: {
		content: {
			type: 'array',
			source: 'children',
			selector: 'p'
		},
		alignment: {
			type: 'string'
		}
	},

	edit: function edit(_ref) {
		var attributes = _ref.attributes,
		    className = _ref.className,
		    isSelected = _ref.isSelected,
		    setAttributes = _ref.setAttributes;
		var content = attributes.content,
		    alignment = attributes.alignment;

		function onChangeContent(newContent) {
			setAttributes({ content: newContent });
		}

		function onChangeAlignment(newAlignment) {
			setAttributes({ alignment: newAlignment });
		}

		return [isSelected && React.createElement(BlockControls, { key: 'controls' }, React.createElement(AlignmentToolbar, {
			value: alignment,
			onChange: onChangeAlignment
		})), React.createElement(RichText, {
			key: 'editable',
			tagName: 'p',
			className: className,
			style: { textAlign: alignment },
			onChange: onChangeContent,
			value: content
		})];
	},
	save: function save(_ref2) {
		var attributes = _ref2.attributes,
		    className = _ref2.className;
		var content = attributes.content,
		    alignment = attributes.alignment;

		return React.createElement('p', { className: className, style: { textAlign: alignment } }, content);
	}
});

/***/ }),
/* 14 */
/***/ (function(module, exports) {

var registerBlockType = wp.blocks.registerBlockType;
var withAPIData = wp.components.withAPIData;

registerBlockType('gutenberg-test/latest-post', {
    title: 'Latest Post',
    icon: 'megaphone',
    category: 'widgets',

    edit: withAPIData(function () {
        return {
            posts: '/wp/v2/posts?per_page=1'
        };
    })(function (_ref) {
        var posts = _ref.posts,
            className = _ref.className;

        if (!posts.data) {
            return 'loading !';
        }
        if (posts.data.length === 0) {
            return 'No posts';
        }
        var post = posts.data[0];

        return React.createElement('a', { className: className, href: post.link }, post.title.rendered);
    }),

    save: function save() {
        // Rendering in PHP
        return null;
    }
});

/***/ })
/******/ ]);