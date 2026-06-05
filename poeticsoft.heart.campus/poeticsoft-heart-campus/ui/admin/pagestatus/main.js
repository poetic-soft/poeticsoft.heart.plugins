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
  return "<".concat(elm, " id=\"").concat(postId, "\" class=\"PHCAccess\">\n    <div class=\"AccessTools\">\n      <div class=\"PostId\">").concat(postId.replace('post-', ''), "</div>\n      <div class=\"Access\">").concat(data.isopen ? 'Abierta' : 'Restringida', "</div>\n    </div>\n  </").concat(elm, ">");
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

/***/ "./src/ui/admin/pagestatus/js/quickedit.js"
/*!*************************************************!*\
  !*** ./src/ui/admin/pagestatus/js/quickedit.js ***!
  \*************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./src/ui/admin/pagestatus/js/utils.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($) {
  $(document).on('click', '.editinline', function () {
    var _this = this;
    var $this = $(this);
    var postId = $this.closest('tr').attr('id').replace('post-', '');
    (0,_utils__WEBPACK_IMPORTED_MODULE_0__.getPageStatus)(postId).then(function (result) {
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
  $(document).ajaxSuccess(function (event, xhr, settings) {
    if (settings.data && settings.data.indexOf('action=inline-save') !== -1) {
      var formData = Object.fromEntries(new URLSearchParams(settings.data));
      window.poeticsoft_heart_campus_admin_pageslist_refresh();
      window.poeticsoft_heart_campus_admin_pageslist_updatedata();
    }
  });
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
/* harmony export */   getPageStatus: () => (/* binding */ getPageStatus),
/* harmony export */   updateData: () => (/* binding */ updateData)
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
var updateData = function updateData($, $pageStatuses) {
  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/access/get',
    method: "GET"
  }).then(function (response) {
    var pages = response.data.pages;
    $pageStatuses.each(function () {
      var $this = $(this);
      var id = $this.attr('id').replace('post-', '');
      var $access = $this.find('.AccessTools .Access');
      if (pages[id] === 'abierta') {
        $access.addClass('isOpen');
        $access.html('Abierta');
      } else {
        $access.removeClass('isOpen');
        $access.html('Restringida');
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
/* harmony import */ var _js_utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/utils */ "./src/ui/admin/pagestatus/js/utils.js");
/* harmony import */ var _js_quickedit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./js/quickedit */ "./src/ui/admin/pagestatus/js/quickedit.js");
/* harmony import */ var _js_pagestatus__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./js/pagestatus */ "./src/ui/admin/pagestatus/js/pagestatus.js");




(function ($) {
  var $body = $('body');
  var $pagesStatus;
  var formClass;
  var waitPageslist = setInterval(function () {
    if (poeticsoft_heart_campus_admin_pageslist) {
      clearInterval(waitPageslist);
      if ($body.hasClass('block-editor-page')) {
        formClass = 'EditPage';
        $pagesStatus = (0,_js_pagestatus__WEBPACK_IMPORTED_MODULE_3__.editPageStatus)($);
      }
      if ($body.hasClass('edit-php')) {
        formClass = 'PagesList';
        $pagesStatus = (0,_js_pagestatus__WEBPACK_IMPORTED_MODULE_3__.normalPagesStatus)($);
      }
      if ($pagesStatus && $pagesStatus.length) {
        window.poeticsoft_heart_campus_admin_pageslist_updatedata = function () {
          (0,_js_utils__WEBPACK_IMPORTED_MODULE_1__.updateData)($, $pagesStatus, formClass);
        };
        window.poeticsoft_heart_campus_admin_pageslist_updatedata();
      }
      (0,_js_quickedit__WEBPACK_IMPORTED_MODULE_2__["default"])($);
    }
  }, 100);
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=main.js.map