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
var _wp = wp,
  apiFetch = _wp.apiFetch;
var rowForm = function rowForm($, postId, access) {
  return "<div id=\"".concat(postId, "\" class=\"PHCAccess\">\n    <div class=\"AccessTools\">\n      <div class=\"PostId\">").concat(postId, "</div>\n      <div class=\"Access ").concat(access === 'abierta' ? 'IsOpen' : '', "\">\n        ").concat(access === 'abierta' ? 'Abierta' : 'Restringida', "\n      </div>\n    </div>\n  </div>");
};
var refresh = function refresh($, pages) {
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
    var postId = id.replace('post-', '');
    var $title = $tr.find('td.column-title a.row-title');
    var $titleContainer = $title.parent('strong');
    var childIds = poeticsoft_heart_campus_admin_pageslist[id];
    $title.html($title.html().split('— ').join(''));
    $titleContainer.addClass('TitleContainer');
    var hasControls = $titleContainer.find('.Control').length > 0; // In case of quick edit, controls are already there, so we don't want to add them again

    if (!hasControls) {
      if (childIds.length) {
        $tr.addClass('HasChildren');
        $titleContainer.prepend('<span class="Control OpenClose"></span>');
      } else {
        $titleContainer.prepend('<span class="Control Indent"></span>');
      }
    }
    if (poeticsoft_heart_campus_admin_campus_ids.includes(id)) {
      var $columnStatus = $tr.find('> .access.column-access');
      $columnStatus.html(rowForm($, postId, pages[postId]));
    }
    var $openclose = $tr.find('.OpenClose');
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
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($) {
  return apiFetch({
    path: 'poeticsoft/heart/campus/v1/page/access/get',
    method: "GET"
  }).then(function (response) {
    var pages = response.data.pages;
    refresh($, pages);
  })["catch"](function (error) {
    return console.error('Heart Campus API Error:', error);
  });
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
  $(document).ajaxSuccess(function (event, xhr, settings) {
    if (settings.data && settings.data.indexOf('action=inline-save') !== -1) {
      var formData = Object.fromEntries(new URLSearchParams(settings.data));
      window.poeticsoft_heart_campus_admin_pageslist_refresh && window.poeticsoft_heart_campus_admin_pageslist_refresh();
    }
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
  var $body = $('body');
  var waitpages = setInterval(function () {
    if (poeticsoft_heart_campus_admin_pageslist) {
      clearInterval(waitpages);
      if ($body.hasClass('edit-php')) {
        window.poeticsoft_heart_campus_admin_pageslist_refresh = function () {
          (0,_js_pagelist__WEBPACK_IMPORTED_MODULE_1__["default"])($);
        };
        window.poeticsoft_heart_campus_admin_pageslist_refresh();
        $('body').addClass('PHCVisible');
        (0,_js_quickedit__WEBPACK_IMPORTED_MODULE_2__["default"])($);
      }
    }
  }, 100);
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=main.js.map