/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/ui/admin/pageprice/js/form.js"
/*!*******************************************!*\
  !*** ./src/ui/admin/pageprice/js/form.js ***!
  \*******************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   rowform: () => (/* binding */ rowform)
/* harmony export */ });
var rowform = function rowform($, postid) {
  var elm = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'div';
  var data = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};
  return "<".concat(elm, " id=\"").concat(postid, "\" class=\"PHCPrice\">\n    <div class=\"PriceTools\">\n      <div class=\"PostId\">").concat(postid.replace('post-', ''), "</div>\n      <div class=\"Access\">\n        <input   \n          type=\"checkbox\"\n          id=\"isfree_").concat(postid, "\"\n          name=\"isfree_").concat(postid, "\"\n          class=\"IsFree\"\n          ").concat(data.isfree ? 'checked' : '', "\n        />\n        <label \n          for=\"isfree_").concat(postid, "\"\n          class=\"").concat(data.isfree ? 'Free' : '', "\"\n        >\n          Abierta\n        </label>\n      </div>\n    </div>\n  </").concat(elm, ">");
};

/***/ },

/***/ "./src/ui/admin/pageprice/js/pageprice.js"
/*!************************************************!*\
  !*** ./src/ui/admin/pageprice/js/pageprice.js ***!
  \************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   editpageprice: () => (/* binding */ editpageprice),
/* harmony export */   normalpagesprices: () => (/* binding */ normalpagesprices)
/* harmony export */ });
/* harmony import */ var _form__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./form */ "./src/ui/admin/pageprice/js/form.js");

var editpageprice = function editpageprice($) {
  var $pagepricewrapper = $('#pcp_page_assign_price .inside .pricewrapper');
  if ($pagepricewrapper.length) {
    $pagepricewrapper = $pagepricewrapper.eq(0);
    var postid = $pagepricewrapper.data('id');
    $pagepricewrapper.html((0,_form__WEBPACK_IMPORTED_MODULE_0__.rowform)($, postid));
    var $pagerows = $pagepricewrapper.find('.PHCPrice');
    return $pagerows;
  }
};
var normalpagesprices = function normalpagesprices($) {
  var $pageslist = $('#the-list');
  if ($pageslist.length) {
    var $pagesrow = $pageslist.find('> tr').filter(function () {
      var $pagerow = $(this);
      var postid = $pagerow.attr('id');
      var id = postid.replace('post-', '');
      return poeticsoft_heart_campus_admin_campus_ids.includes(postid);
    });
    return $pagesrow.map(function () {
      var $pagerow = $(this);
      var postid = $pagerow.attr('id');
      var $columstatus = $pagerow.find('> .status.column-status');
      $columstatus.append((0,_form__WEBPACK_IMPORTED_MODULE_0__.rowform)($, postid));
      return $columstatus.find('.PHCPrice').eq(0);
    });
  }
  return null;
};

/***/ },

/***/ "./src/ui/admin/pageprice/js/priceform.js"
/*!************************************************!*\
  !*** ./src/ui/admin/pageprice/js/priceform.js ***!
  \************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils */ "./src/ui/admin/pageprice/js/utils.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $pagesprices) {
  var formclass = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : '';
  $pagesprices.each(function () {
    var $this = $(this);
    var id = $this.attr('id').replace('post-', '');
    var $tooglefree = $this.find('.PriceTools .Access input.IsFree');
    var $tooglelabel = $this.find('.PriceTools .Access label');
    $tooglefree.on('click', function () {
      var $this = $(this);
      var ischecked = $this.is(':checked');
      $tooglelabel.removeClass('Free');
      $tooglelabel.addClass('Updating');
      $tooglelabel.html('Actualizando');
      (0,_utils__WEBPACK_IMPORTED_MODULE_0__.updatefree)($, id, ischecked).then(function (result) {
        $tooglelabel.removeClass('Updating');
        if (result && result.success) {
          if (ischecked) {
            $tooglelabel.addClass('Free');
            $tooglelabel.html('Abierta');
          } else {
            $tooglelabel.html('Restringida');
          }
        } else {
          // If failed, revert the checkbox state
          $this.prop('checked', !ischecked);
          if (!ischecked) {
            $tooglelabel.addClass('Free');
            $tooglelabel.html('Abierta');
          } else {
            $tooglelabel.html('Restringida');
          }
          console.error('Update failed:', result);
        }
      })["catch"](function (error) {
        console.log(error);
      });
    });
  });
  (0,_utils__WEBPACK_IMPORTED_MODULE_0__.updatedata)($, $pagesprices);
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/admin/pageprice/js/utils.js"
/*!********************************************!*\
  !*** ./src/ui/admin/pageprice/js/utils.js ***!
  \********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   updatedata: () => (/* binding */ updatedata),
/* harmony export */   updatefree: () => (/* binding */ updatefree)
/* harmony export */ });
var _wp = wp,
  apiFetch = _wp.apiFetch;
var updatefree = function updatefree($, id, ischecked) {
  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/free-update',
    method: "POST",
    data: {
      postid: id,
      isfree: ischecked
    }
  });
};
var updatedata = function updatedata($, $pagesprices) {
  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/free-get',
    method: "GET"
  }).then(function (response) {
    var pages = response.data.pages;
    $pagesprices.each(function () {
      var $this = $(this);
      var id = $this.attr('id').replace('post-', '');
      var $tooglefree = $this.find('.PriceTools .Access input.IsFree');
      var $tooglelabel = $this.find('.PriceTools .Access label');
      if (pages[id] === 'free') {
        $tooglefree.prop("checked", true);
        $tooglelabel.html('Abierta');
        $tooglelabel.addClass('Free');
      } else {
        $tooglelabel.html('Restringida');
      }
    });
  })["catch"](function (error) {
    return console.error('Heart Campus API Error:', error);
  });
};

/***/ },

/***/ "./src/ui/admin/pageprice/main.scss"
/*!******************************************!*\
  !*** ./src/ui/admin/pageprice/main.scss ***!
  \******************************************/
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
/*!****************************************!*\
  !*** ./src/ui/admin/pageprice/main.js ***!
  \****************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./main.scss */ "./src/ui/admin/pageprice/main.scss");
/* harmony import */ var _js_pageprice__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/pageprice */ "./src/ui/admin/pageprice/js/pageprice.js");
/* harmony import */ var _js_priceform__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./js/priceform */ "./src/ui/admin/pageprice/js/priceform.js");



(function ($) {
  var $body = $('body');
  var $pagesprices;
  var formclass;
  var waitpageslist = setInterval(function () {
    if (poeticsoft_heart_campus_admin_pageslist) {
      clearInterval(waitpageslist);
      if ($body.hasClass('block-editor-page')) {
        formclass = 'EditPage';
        $pagesprices = (0,_js_pageprice__WEBPACK_IMPORTED_MODULE_1__.editpageprice)($);
      }
      if ($body.hasClass('edit-php')) {
        formclass = 'PagesList';
        $pagesprices = (0,_js_pageprice__WEBPACK_IMPORTED_MODULE_1__.normalpagesprices)($);
      }
      if ($pagesprices && $pagesprices.length) {
        (0,_js_priceform__WEBPACK_IMPORTED_MODULE_2__["default"])($, $pagesprices, formclass);
      }
    }
  }, 100);
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=main.js.map