/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/ui/admin/pagestatus/js/form.js"
/*!********************************************!*\
  !*** ./src/ui/admin/pagestatus/js/form.js ***!
  \********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   rowForm: () => (/* binding */ rowForm)
/* harmony export */ });
var rowForm = function rowForm($, postId) {
  var elm = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'div';
  var data = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};
  return "<".concat(elm, " id=\"").concat(postId, "\" class=\"PHCAccess\">\n    <div class=\"AccessTools\">\n      <div class=\"PostId\">").concat(postId.replace('post-', ''), "</div>\n      <div class=\"Access\">\n        <input   \n          type=\"checkbox\"\n          id=\"isopen_").concat(postId, "\"\n          name=\"isopen_").concat(postId, "\"\n          class=\"IsOpen\"\n          ").concat(data.isopen ? 'checked' : '', "\n        />\n        <label \n          for=\"isopen_").concat(postId, "\"\n          class=\"").concat(data.isopen ? 'Open' : '', "\"\n        >\n          Abierta\n        </label>\n      </div>\n    </div>\n  </").concat(elm, ">");
};

/***/ },

/***/ "./src/ui/admin/pagestatus/js/pagestatus.js"
/*!**************************************************!*\
  !*** ./src/ui/admin/pagestatus/js/pagestatus.js ***!
  \**************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   editPageStatus: () => (/* binding */ editPageStatus),
/* harmony export */   normalPagesStatus: () => (/* binding */ normalPagesStatus)
/* harmony export */ });
/* harmony import */ var _form__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./form */ "./src/ui/admin/pagestatus/js/form.js");

var editPageStatus = function editPageStatus($) {
  var $pageStatusWrapper = $('#phc_page_assign_status .inside .statuswrapper');
  if ($pageStatusWrapper.length) {
    $pageStatusWrapper = $pageStatusWrapper.eq(0);
    var postId = $pageStatusWrapper.data('id');
    $pageStatusWrapper.html((0,_form__WEBPACK_IMPORTED_MODULE_0__.rowForm)($, postId));
    var $pageRows = $pageStatusWrapper.find('.PHCPrice');
    return $pageRows;
  }
};
var normalPagesStatus = function normalPagesStatus($) {
  var $pagesList = $('#the-list');
  if ($pagesList.length) {
    var $pagesRow = $pagesList.find('> tr').filter(function () {
      var $pageRow = $(this);
      var postIdRaw = $pageRow.attr('id');
      var id = postIdRaw.replace('post-', '');
      return poeticsoft_heart_campus_admin_campus_ids.includes(postIdRaw);
    });
    var $pageRows = $pagesRow.map(function () {
      var $pageRow = $(this);
      var postId = $pageRow.attr('id');
      var $columnStatus = $pageRow.find('> .access.column-access');
      $columnStatus.append((0,_form__WEBPACK_IMPORTED_MODULE_0__.rowForm)($, postId));
      return $columnStatus.find('.PHCAccess').eq(0);
    });
    return $pageRows;
  }
  return null;
};

/***/ },

/***/ "./src/ui/admin/pagestatus/js/statusform.js"
/*!**************************************************!*\
  !*** ./src/ui/admin/pagestatus/js/statusform.js ***!
  \**************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./src/ui/admin/pagestatus/js/utils.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $pageStatuses) {
  var formClass = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
  $pageStatuses.each(function () {
    var $this = $(this);
    var id = $this.attr('id').replace('post-', '');
    var $toggleFree = $this.find('.AccessTools .Access input.IsOpen');
    var $toggleLabel = $this.find('.AccessTools .Access label');
    $toggleFree.on('click', function () {
      var $this = $(this);
      var isChecked = $this.is(':checked');
      $toggleLabel.removeClass('Open');
      $toggleLabel.addClass('Updating');
      $toggleLabel.html('Actualizando');
      (0,_utils__WEBPACK_IMPORTED_MODULE_0__.updateOpen)($, id, isChecked).then(function (result) {
        $toggleLabel.removeClass('Updating');
        if (result && result.success) {
          if (isChecked) {
            $toggleLabel.addClass('Open');
            $toggleLabel.html('Abierta');
          } else {
            $toggleLabel.html('Restringida');
          }
        } else {
          // If failed, revert the checkbox state
          $this.prop('checked', !isChecked);
          if (!isChecked) {
            $toggleLabel.addClass('Open');
            $toggleLabel.html('Abierta');
          } else {
            $toggleLabel.html('Restringida');
          }
          console.error('Update failed:', result);
        }
      })["catch"](function (error) {
        console.log(error);
      });
    });
  });
  (0,_utils__WEBPACK_IMPORTED_MODULE_0__.updateData)($, $pageStatuses);
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/admin/pagestatus/js/utils.js"
/*!*********************************************!*\
  !*** ./src/ui/admin/pagestatus/js/utils.js ***!
  \*********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   updateData: () => (/* binding */ updateData),
/* harmony export */   updateOpen: () => (/* binding */ updateOpen)
/* harmony export */ });
var _wp = wp,
  apiFetch = _wp.apiFetch;
var updateOpen = function updateOpen($, id, isChecked) {
  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/access/update',
    method: "POST",
    data: {
      postid: id,
      isopen: isChecked
    }
  });
};
var updateData = function updateData($, $pageStatuses) {
  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/access/get',
    method: "GET"
  }).then(function (response) {
    var pages = response.data.pages;
    $pageStatuses.each(function () {
      var $this = $(this);
      var id = $this.attr('id').replace('post-', '');
      var $toggleOpen = $this.find('.AccessTools .Access input.IsOpen');
      var $toggleLabel = $this.find('.AccessTools .Access label');
      if (pages[id] === 'abierta') {
        $toggleOpen.prop("checked", true);
        $toggleLabel.html('Abierta');
        $toggleLabel.addClass('Open');
      } else {
        $toggleLabel.html('Restringida');
      }
    });
  })["catch"](function (error) {
    return console.error('Heart Campus API Error:', error);
  });
};

/***/ },

/***/ "./src/ui/admin/pagestatus/main.scss"
/*!*******************************************!*\
  !*** ./src/ui/admin/pagestatus/main.scss ***!
  \*******************************************/
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
/*!*****************************************!*\
  !*** ./src/ui/admin/pagestatus/main.js ***!
  \*****************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./main.scss */ "./src/ui/admin/pagestatus/main.scss");
/* harmony import */ var _js_pagestatus__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/pagestatus */ "./src/ui/admin/pagestatus/js/pagestatus.js");
/* harmony import */ var _js_statusform__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./js/statusform */ "./src/ui/admin/pagestatus/js/statusform.js");



(function ($) {
  var $body = $('body');
  var $pagesStatus;
  var formClass;
  var waitPageslist = setInterval(function () {
    if (poeticsoft_heart_campus_admin_pageslist) {
      clearInterval(waitPageslist);
      if ($body.hasClass('block-editor-page')) {
        formClass = 'EditPage';
        $pagesStatus = (0,_js_pagestatus__WEBPACK_IMPORTED_MODULE_1__.editPageStatus)($);
      }
      if ($body.hasClass('edit-php')) {
        formClass = 'PagesList';
        $pagesStatus = (0,_js_pagestatus__WEBPACK_IMPORTED_MODULE_1__.normalPagesStatus)($);
      }
      if ($pagesStatus && $pagesStatus.length) {
        (0,_js_statusform__WEBPACK_IMPORTED_MODULE_2__["default"])($, $pagesStatus, formClass);
      }
    }
  }, 100);
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=main.js.map