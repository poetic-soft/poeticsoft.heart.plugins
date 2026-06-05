/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/ui/admin/pagesquickedit/js/quickedit.js"
/*!*****************************************************!*\
  !*** ./src/ui/admin/pagesquickedit/js/quickedit.js ***!
  \*****************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var _wp = wp,
  apiFetch = _wp.apiFetch;
var getPageStatus = function getPageStatus(pageId) {
  return apiFetch({
    path: "poeticsoft/heart/campus/v1/page/access/get/".concat(pageId),
    method: "GET"
  })["catch"](function (error) {
    return console.error('Heart Campus API Error:', error);
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($) {
  $(document).on('click', '.editinline', function () {
    var _this = this;
    var $this = $(this);
    var postId = $this.closest('tr').attr('id').replace('post-', '');
    getPageStatus(postId).then(function (result) {
      if (result.success) {
        var inCampus = result.data.in_campus;
        var $inlineEditRow = $(_this).closest('tr').next();
        if (!$inlineEditRow.hasClass('inline-edit-row')) {
          $inlineEditRow = $inlineEditRow.next();
        }
        var $statusFieldset = $inlineEditRow.find('fieldset.inline-edit-col-right.poeticsoft-heart-campus-access');
        if (inCampus) {
          var status = result.data.access;
          var $statusSelect = $statusFieldset.find('select.poeticsoft-heart-campus-access');
          $statusSelect.val(status);
        } else {
          $statusFieldset.remove();
        }
      }
    });
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/admin/pagesquickedit/main.scss"
/*!***********************************************!*\
  !*** ./src/ui/admin/pagesquickedit/main.scss ***!
  \***********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/set anonymous default export name */
/******/ 	(() => {
/******/ 		// set .name for anonymous default exports per ES spec
/******/ 		__webpack_require__.dn = (x) => {
/******/ 			(Object.getOwnPropertyDescriptor(x, "name") || {}).writable || Object.defineProperty(x, "name", { value: "default", configurable: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!*********************************************!*\
  !*** ./src/ui/admin/pagesquickedit/main.js ***!
  \*********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./main.scss */ "./src/ui/admin/pagesquickedit/main.scss");
/* harmony import */ var _js_quickedit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/quickedit */ "./src/ui/admin/pagesquickedit/js/quickedit.js");


(function ($) {
  var $body = $('body');
  var waitpages = setInterval(function () {
    if (poeticsoft_heart_campus_admin_pageslist) {
      clearInterval(waitpages);
      if ($body.hasClass('edit-php')) {
        (0,_js_quickedit__WEBPACK_IMPORTED_MODULE_1__["default"])($);
      }
    }
  }, 100);
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=main.js.map