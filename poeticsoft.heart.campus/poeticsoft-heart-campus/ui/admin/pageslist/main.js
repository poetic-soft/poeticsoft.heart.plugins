/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/ui/admin/pageslist/js/pagelist.js"
/*!***********************************************!*\
  !*** ./src/ui/admin/pageslist/js/pagelist.js ***!
  \***********************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($) {
  var urlParams = new URLSearchParams(window.location.search);
  var postStatus = urlParams.get('post_status');
  if (postStatus != 'trash' && postStatus != 'draft') {
    $('body').addClass('PoeticsoftHeartCampus');
  } else {
    return;
  }
  var statusKey = 'PoeticsoftHeartCampusPageListState';
  var $thelist = $('body.wp-admin.post-type-page #the-list');
  var $trs = $thelist.find('tr');
  var state = {};
  var $trsbyparentid = {};
  $trs.each(function () {
    var $tr = $(this);
    var id = $tr.attr('id');
    var childIds = poeticsoft_heart_campus_admin_pageslist[id];
    $trsbyparentid[id] = childIds.map(function (cid) {
      return $thelist.find('tr#' + cid);
    });
    if (poeticsoft_heart_campus_admin_campus_ids.includes(id)) {
      $tr.addClass('InCampus');
    }
    state[id] = false;
  });
  var _closebranch = function closebranch(id) {
    var $children = $trsbyparentid[id];
    if (!$children) {
      return;
    }
    $children.forEach(function ($c) {
      $c.removeClass('Visible Opened');
    });
    var childIds = poeticsoft_heart_campus_admin_pageslist[id];
    childIds.length && childIds.forEach(function (cid) {
      return _closebranch(cid);
    });
  };
  var updateNav = function updateNav() {
    $trs.each(function () {
      var $tr = $(this);
      var id = $tr.attr('id');
      if (state[id]) {
        $tr.addClass('Opened');
        var $children = $trsbyparentid[id];
        $children.forEach(function ($c) {
          $c.addClass('Visible');
        });
        state[id] = true;
      } else {
        $tr.removeClass('Opened');
        _closebranch(id);
      }
    });
  };
  var saveState = function saveState() {
    localStorage.setItem(statusKey, JSON.stringify(state));
  };
  $trs.each(function () {
    var $tr = $(this);
    var id = $tr.attr('id');
    var $title = $tr.find('td.column-title a.row-title');
    var $titlecontainer = $title.parent('strong');
    var childIds = poeticsoft_heart_campus_admin_pageslist[id];
    $title.html($title.html().split('— ').join(''));
    $titlecontainer.addClass('TitleContainer');
    if (childIds.length) {
      $tr.addClass('HasChildren');
      $titlecontainer.prepend('<span class="OpenClose"></span>');
    } else {
      $titlecontainer.prepend('<span class="Indent"></span>');
    }
    var $openclose = $titlecontainer.find('.OpenClose');
    $openclose.on('click', function () {
      if ($tr.hasClass('Opened')) {
        $tr.removeClass('Opened');
        _closebranch(id);
        state[id] = false;
      } else {
        $tr.addClass('Opened');
        var $children = $trsbyparentid[id];
        $children.forEach(function ($c) {
          $c.addClass('Visible');
        });
        state[id] = true;
      }
      saveState();
      return false;
    });
  });
  var checkState = function checkState() {
    var actualState = JSON.parse(localStorage.getItem(statusKey)) || {};
    var stateKeysCount = Object.keys(actualState).length;
    var trsLength = $trs.length;
    if (stateKeysCount < trsLength) {
      $trs.each(function () {
        var $tr = $(this);
        var id = $tr.attr('id');
        if (actualState[id] === undefined) {
          actualState[id] = false;
        }
      });
    }
    state = actualState;
    updateNav();
  };
  checkState();
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/admin/pageslist/js/quickedit.js"
/*!************************************************!*\
  !*** ./src/ui/admin/pageslist/js/quickedit.js ***!
  \************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($) {
  $(document).on('click', '.editinline', function () {
    console.log('editinline');
    var postId = $(this).closest('tr').attr('id').replace('post-', '');
    var valorActual = $('#post-' + postId).find('.valor-meta-contenedor').data('valor');
    console.log(postId);
    if (valorActual === undefined || valorActual === '') {
      valorActual = '0';
    }
    var inlineEditRow = $(this).closest('tr').next();
    if (!inlineEditRow.hasClass('inline-edit-row')) {
      inlineEditRow = inlineEditRow.next(); // A veces hay filas intermedias
    }
    inlineEditRow.find('.mi-meta-bool-select').val(valorActual);
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/admin/pageslist/main.scss"
/*!******************************************!*\
  !*** ./src/ui/admin/pageslist/main.scss ***!
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
  !*** ./src/ui/admin/pageslist/main.js ***!
  \****************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _main_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./main.scss */ "./src/ui/admin/pageslist/main.scss");
/* harmony import */ var _js_pagelist__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/pagelist */ "./src/ui/admin/pageslist/js/pagelist.js");
/* harmony import */ var _js_quickedit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./js/quickedit */ "./src/ui/admin/pageslist/js/quickedit.js");



(function ($) {
  var waitpages = setInterval(function () {
    if (poeticsoft_heart_campus_admin_pageslist) {
      clearInterval(waitpages);
      var $body = $('body');
      if ($body.hasClass('edit-php')) {
        (0,_js_pagelist__WEBPACK_IMPORTED_MODULE_1__["default"])($);
        (0,_js_quickedit__WEBPACK_IMPORTED_MODULE_2__["default"])($);
        $('body').addClass('PHCVisible');
      }
    }
  }, 100);
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=main.js.map