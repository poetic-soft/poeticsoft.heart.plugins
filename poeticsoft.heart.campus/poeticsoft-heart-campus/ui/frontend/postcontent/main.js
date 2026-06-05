/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/ui/frontend/postcontent/js/cantaccess/do-cantaccess.js"
/*!********************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/cantaccess/do-cantaccess.js ***!
  \********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/cantaccess/forms.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper) {
  var $adviceText = $wrapper.find('.AdviceText');
  var adviceText = $adviceText.html();
  var $forms = $wrapper.find('.Forms.CantAccess');
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_0__["default"])({
    form: 'cantAccess',
    adviceText: adviceText
  }));
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/cantaccess/form-cantaccess.js"
/*!**********************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/cantaccess/form-cantaccess.js ***!
  \**********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  var accessText = "\n    Solicita tu registro para acceder a este contenido.  \n  ";
  return "\n    <div class=\"Form CantAccess\">\n      <div class=\"FormName\">Cant Access</div>\n      <div class=\"Explain\">\n        ".concat(accessText, "  \n      </div>         \n    </div>\n  ");
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/cantaccess/forms.js"
/*!************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/cantaccess/forms.js ***!
  \************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _form_cantaccess__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./form-cantaccess */ "./src/ui/frontend/postcontent/js/cantaccess/form-cantaccess.js");

var forms = {
  cantAccess: _form_cantaccess__WEBPACK_IMPORTED_MODULE_0__["default"]
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return forms[data.form](data);
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/common/message.js"
/*!**********************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/common/message.js ***!
  \**********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper, message, type) {
  var $message = $wrapper.find('.Forms .Form .Message');
  $message.removeClass('Error Warn Info');
  $message.addClass(type);
  $message.html(message);
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/common/utils.js"
/*!********************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/common/utils.js ***!
  \********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   apiFetch: () => (/* binding */ apiFetch),
/* harmony export */   validateEmail: () => (/* binding */ validateEmail)
/* harmony export */ });
var apiFetch = function apiFetch(data) {
  return new Promise(function (resolve, reject) {
    fetch('/wp-json/poeticsoft/heart/campus/v1/' + data.url, {
      method: "POST",
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-WP-Nonce': poeticsoft_heart_campus_api.nonce
      },
      body: JSON.stringify(data.body)
    }).then(function (result) {
      result.json().then(function (data) {
        return resolve(data);
      });
    })["catch"](function (error) {
      console.log(error);
      reject(error);
    });
  });
};
var validateEmail = function validateEmail(email) {
  var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
};

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/do-confirmcode.js"
/*!*******************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/do-confirmcode.js ***!
  \*******************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/identify/forms.js");
/* harmony import */ var _common_message__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../common/message */ "./src/ui/frontend/postcontent/js/common/message.js");
/* harmony import */ var _common_utils__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../common/utils */ "./src/ui/frontend/postcontent/js/common/utils.js");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper, email, code) {
  var $forms = $wrapper.find('.Forms.Identify');
  $forms.find('.Form').remove();
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_0__["default"])({
    form: 'confirmCode',
    code: code
  }));
  var $codeConfirm = $forms.find('.Form.ConfirmCode');
  var $codeConfirmInput = $codeConfirm.find('input.Code');
  var $codeConfirmConfirmCode = $codeConfirm.find('button.ConfirmCode');
  var $identifyResendCode = $codeConfirm.find('a.ResendCode');
  $codeConfirmConfirmCode.on('click', function () {
    var code = $codeConfirmInput.val();
    $codeConfirmInput.prop('disabled', true);
    $codeConfirmConfirmCode.prop('disabled', true);
    (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, 'Confirmando...', 'Warn');
    (0,_common_utils__WEBPACK_IMPORTED_MODULE_2__.apiFetch)({
      url: 'identify/subscriber/confirmcode',
      body: {
        email: email,
        code: code
      }
    }).then(function (data) {
      if (data.success) {
        (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, 'Identifiación confirmada. Redirigiendo a la página de contenidos', 'Info');
        setTimeout(function () {
          window.location.reload();
        }, 2000);
      } else {
        console.log(data);
        (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, data.error.message, 'Error');
      }
      $codeConfirmInput.prop('disabled', false);
      $codeConfirmConfirmCode.prop('disabled', false);
    })["catch"](function (error) {
      console.log(error);
      (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, 'Error de servidor, intentalo de nuevo, por favor.', 'Error');
      $codeConfirmInput.prop('disabled', false);
      $codeConfirmConfirmCode.prop('disabled', false);
    });
  });
  $identifyResendCode.on('click', function () {
    $codeConfirmInput.val('');
    $codeConfirmInput.prop('disabled', false);
    $codeConfirmConfirmCode.prop('disabled', false);
    (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, 'Reenviando...', 'Warn');
    (0,_common_utils__WEBPACK_IMPORTED_MODULE_2__.apiFetch)({
      url: 'identify/subscriber/identify',
      body: {
        email: email
      }
    }).then(function (data) {
      if (data.result == 'ok') {
        // $codeConfirmInput.val(data.code)

        (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, 'Se ha reenviado el código.', 'Info');
      }
    })["catch"](function (error) {
      console.log(error);
      (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, 'Error de servidor, intentalo de nuevo, por favor.', 'Error');
      $codeConfirmInput.prop('disabled', false);
      $codeConfirmConfirmCode.prop('disabled', false);
    });
    return false;
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/do-identify.js"
/*!****************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/do-identify.js ***!
  \****************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _common_message__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../common/message */ "./src/ui/frontend/postcontent/js/common/message.js");
/* harmony import */ var _common_utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../common/utils */ "./src/ui/frontend/postcontent/js/common/utils.js");
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/identify/forms.js");
/* harmony import */ var _do_confirmcode__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./do-confirmcode */ "./src/ui/frontend/postcontent/js/identify/do-confirmcode.js");
/* harmony import */ var _do_register_should__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./do-register-should */ "./src/ui/frontend/postcontent/js/identify/do-register-should.js");
/* harmony import */ var _do_register_want__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./do-register-want */ "./src/ui/frontend/postcontent/js/identify/do-register-want.js");







/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper) {
  var accessType = poeticsoft_heart_campus_access_by;
  var $forms = $wrapper.find('.Forms.Identify');
  $forms.find('.Form').remove();
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_2__["default"])({
    form: 'identify'
  }));
  var $identify = $forms.find('.Form.Identify');
  var $identifyEmail = $identify.find('input.Email');
  var $identifySendEmail = $identify.find('button.SendEmail');
  var $identifyNotRegistered = $identify.find('a.NotRegistered');
  function checkEmail() {
    var $this = $(this);
    var email = $this.val();
    if ($this[0].checkValidity() && (0,_common_utils__WEBPACK_IMPORTED_MODULE_1__.validateEmail)(email)) {
      $identifySendEmail.prop('disabled', false);
    } else {
      $identifySendEmail.prop('disabled', true);
    }
    (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, '', '');
  }
  $identifyEmail.on('change', checkEmail);
  $identifyEmail.on('keydown', checkEmail);
  $identifyEmail.on('keyup', checkEmail);
  $identifyNotRegistered.on('click', function () {
    (0,_do_register_want__WEBPACK_IMPORTED_MODULE_5__["default"])($, $wrapper);
    return false;
  });
  $identifySendEmail.on('click', function () {
    var email = $identifyEmail.val();
    (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Enviando...', 'Warn');
    if ($identifyEmail[0].checkValidity() && (0,_common_utils__WEBPACK_IMPORTED_MODULE_1__.validateEmail)(email)) {
      $identifyEmail.prop('disabled', true);
      $identifySendEmail.prop('disabled', true);
      (0,_common_utils__WEBPACK_IMPORTED_MODULE_1__.apiFetch)({
        url: 'identify/subscriber/identify',
        body: {
          email: email
        }
      }).then(function (data) {
        if (data.success) {
          (0,_do_confirmcode__WEBPACK_IMPORTED_MODULE_3__["default"])($, $wrapper, email, data.code);
        } else {
          (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, data.error.message, 'Error');
        }
      })["catch"](function (error) {
        (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Error de servidor, intentalo de nuevo, por favor.', 'Error');
        $identifyEmail.prop('disabled', false);
        $identifySendEmail.prop('disabled', false);
      });
    } else {
      (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'El mail no es válido.', 'Error');
    }
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/do-login.js"
/*!*************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/do-login.js ***!
  \*************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _do_identify__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./do-identify */ "./src/ui/frontend/postcontent/js/identify/do-identify.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $mytools) {
  var $login = $mytools.find('.Login');
  $login.on('click', function () {
    $('body').append("\n        <div class=\"poeticsoft-heart-campus-login-overlay\">\n          <div class=\"poeticsoft-heart-campus-login\">\n            <div class=\"Forms Identify\"></div>\n            <div class=\"Close\"></div>\n          </div>\n        </div>\n      ");
    var $loginWrapper = $('body .poeticsoft-heart-campus-login-overlay');
    var $wrapper = $loginWrapper.find('.poeticsoft-heart-campus-login');
    var $close = $wrapper.find('.Close');
    (0,_do_identify__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper);
    $close.on('click', function () {
      $loginWrapper.remove();
    });
    return false;
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/do-register-should.js"
/*!***********************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/do-register-should.js ***!
  \***********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/identify/forms.js");
/* harmony import */ var _common_message__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../common/message */ "./src/ui/frontend/postcontent/js/common/message.js");
/* harmony import */ var _do_confirmcode__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./do-confirmcode */ "./src/ui/frontend/postcontent/js/identify/do-confirmcode.js");
/* harmony import */ var _do_register_want__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./do-register-want */ "./src/ui/frontend/postcontent/js/identify/do-register-want.js");
/* harmony import */ var _common_utils__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../common/utils */ "./src/ui/frontend/postcontent/js/common/utils.js");





/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper, email) {
  var $forms = $wrapper.find('.Forms.Identify');
  $forms.find('.Form').remove();
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_0__["default"])({
    form: 'registerShould',
    email: email
  }));
  var $registerShould = $forms.find('.Form.RegisterShould');
  var $registerShouldConfirmCode = $registerShould.find('button.RegistryEmail');
  var $registerShouldOtherMail = $registerShould.find('a.OtherMail');
  $registerShouldConfirmCode.on('click', function () {
    $registerShouldConfirmCode.prop('disabled', true);
    (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, 'Enviando...', 'Warn');
    (0,_common_utils__WEBPACK_IMPORTED_MODULE_4__.apiFetch)({
      url: 'identify/subscriber/register',
      body: {
        email: email
      }
    }).then(function (result) {
      if (result.data.errors) {
        var errors = 'Error. ' + Object.keys(result.data.errors).map(function (key) {
          return result.data.errors[key].map(function (e) {
            return e + ' ';
          });
        }).join(', ');
        (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, errors, 'Error');
        $registerShouldConfirmCode.prop('disabled', false);
      } else {
        (0,_do_confirmcode__WEBPACK_IMPORTED_MODULE_2__["default"])($, $wrapper, email, result.usercode);
      }
    })["catch"](function (error) {
      console.log(error);
      (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, 'Error de servidor, intentalo de nuevo, por favor.', 'Error');
      $registerShouldConfirmCode.prop('disabled', false);
    });
  });
  $registerShouldOtherMail.on('click', function () {
    (0,_do_register_want__WEBPACK_IMPORTED_MODULE_3__["default"])($, $wrapper);
    return false;
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/do-register-want.js"
/*!*********************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/do-register-want.js ***!
  \*********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _common_utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../common/utils */ "./src/ui/frontend/postcontent/js/common/utils.js");
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/identify/forms.js");
/* harmony import */ var _common_message__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../common/message */ "./src/ui/frontend/postcontent/js/common/message.js");
/* harmony import */ var _do_identify__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./do-identify */ "./src/ui/frontend/postcontent/js/identify/do-identify.js");
/* harmony import */ var _do_confirmcode__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./do-confirmcode */ "./src/ui/frontend/postcontent/js/identify/do-confirmcode.js");






/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper) {
  var $forms = $wrapper.find('.Forms.Identify');
  $forms.find('.Form').remove();
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_1__["default"])({
    form: 'registerWant'
  }));
  var $registerWant = $forms.find('.Form.RegisterWant');
  var $registerWantEmail = $registerWant.find('input.Email');
  var $registerWantSendEmail = $registerWant.find('button.SendEmail');
  var $registerWantBackIdentify = $registerWant.find('a.BackIdentify');
  function checkEmail() {
    var $this = $(this);
    var email = $this.val();
    if ($this[0].checkValidity() && (0,_common_utils__WEBPACK_IMPORTED_MODULE_0__.validateEmail)(email)) {
      $registerWantSendEmail.prop('disabled', false);
    } else {
      $registerWantSendEmail.prop('disabled', true);
    }
    (0,_common_message__WEBPACK_IMPORTED_MODULE_2__["default"])($, $wrapper, '', '');
  }
  $registerWantEmail.on('keydown', checkEmail);
  $registerWantEmail.on('change', checkEmail);
  $registerWantBackIdentify.on('click', function () {
    (0,_do_identify__WEBPACK_IMPORTED_MODULE_3__["default"])($, $wrapper);
    return false;
  });
  $registerWantSendEmail.on('click', function () {
    var email = $registerWantEmail.val();
    if ($registerWantEmail[0].checkValidity() && (0,_common_utils__WEBPACK_IMPORTED_MODULE_0__.validateEmail)(email)) {
      $registerWantEmail.prop('disabled', true);
      $registerWantSendEmail.prop('disabled', true);
      (0,_common_message__WEBPACK_IMPORTED_MODULE_2__["default"])($, $wrapper, 'Enviando...', 'Warn');
      (0,_common_utils__WEBPACK_IMPORTED_MODULE_0__.apiFetch)({
        url: 'identify/subscriber/register',
        body: {
          email: email
        }
      }).then(function (result) {
        if (result.data.errors) {
          var errors = 'Error. ' + Object.keys(result.data.errors).map(function (key) {
            return result.data.errors[key].map(function (e) {
              return e + ' ';
            });
          }).join(', ');
          (0,_common_message__WEBPACK_IMPORTED_MODULE_2__["default"])($, $wrapper, errors, 'Error');
          $registerWantEmail.prop('disabled', false);
          $registerWantSendEmail.prop('disabled', false);
        } else {
          (0,_do_confirmcode__WEBPACK_IMPORTED_MODULE_4__["default"])($, $wrapper, email);
        }
      })["catch"](function (error) {
        console.log(error);
        (0,_common_message__WEBPACK_IMPORTED_MODULE_2__["default"])($, $wrapper, 'Error de servidor, intentalo de nuevo, por favor.', 'Error');
        $registerWantEmail.prop('disabled', false);
        $registerWantSendEmail.prop('disabled', false);
      });
    } else {
      (0,_common_message__WEBPACK_IMPORTED_MODULE_2__["default"])($, $wrapper, 'El mail no es válido.', 'Error');
    }
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/form-confirmcode.js"
/*!*********************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/form-confirmcode.js ***!
  \*********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  // value="${ data.code }"

  return "\n    <div class=\"Form ConfirmCode\">\n      <div class=\"FormName\">ConfirmCode</div>\n      <div class=\"Explain\">\n        Para confirmar tu acceso hemos enviado un c\xF3digo \n        a tu email, por favor introduce el c\xF3digo \n        recibido en el siguiente campo.\n      </div>\n      <div class=\"Fields\">\n        <div class=\"Field Code\">\n          <input\n            class=\"Code\"\n            type=\"text\"\n            placeholder=\"C\xF3digo recibido\"\n            name=\"confirm-code\"\n          />\n          <div class=\"Tools wp-block-button\">\n            <button \n              class=\"\n                ConfirmCode\n                wp-block-button__link \n                wp-element-button\n              \"\n            >\n              CONFIRMAR\n            </button>\n          </div>\n        </div>\n      </div>\n      \n      <a \n        class=\"Extra ResendCode\"\n        href=\"#\"\n      >\n        Reenviar el c\xF3digo\n      </a>\n      <div class=\"Message\"></div>          \n    </div>\n  ";
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/form-identify.js"
/*!******************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/form-identify.js ***!
  \******************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  var needRegister = ['mailrelay'];
  var accessType = poeticsoft_heart_campus_access_by;
  var wantRegister = needRegister.includes(accessType) ? "\n  <a \n    class=\"Extra NotRegistered\"\n    href=\"#\"\n  >\n    Quiero suscribirme\n  </a>\n  " : '';
  return "\n    <div class=\"Form Identify\">\n      <div class=\"FormName\">Identify</div>\n      <div class=\"Explain\">\n        Identif\xEDcate para acceder a los contenidos.\n      </div>\n      <div class=\"Fields\">\n        <div class=\"Field Email\">\n          <input\n            class=\"Email\"\n            type=\"email\"\n            placeholder=\"Tu E-mail\"\n            name=\"user-email\"\n          />      \n          <div class=\"Tools wp-block-button\">\n            <button \n              class=\"\n                SendEmail\n                wp-block-button__link \n                wp-element-button\n              \"\n              disabled=\"disabled\"\n            >\n              ENVIAR\n            </button>\n          </div>\n        </div>\n      </div>\n      ".concat(wantRegister, "\n      <div class=\"Message\"></div>          \n    </div>\n  ");
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/form-register-confirm.js"
/*!**************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/form-register-confirm.js ***!
  \**************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form RegisterConfirm\">\n      <div class=\"FormName\">RegisterConfirm</div>\n      <div class=\"Explain\">\n        Tu mail se ha registrado, recibiras un correo con un c\xF3digo para confirmar tu suscripci\xF3n, por favor introduce el c\xF3digo recibido en el siguiente campo.\n      </div>\n      <div class=\"Fields\">\n        <div class=\"Field Code\">\n          <input\n            class=\"Code\"\n            type=\"text\"\n            placeholder=\"C\xF3digo recibido\"\n            name=\"confirm-code\"\n          />\n        </div>\n      </div>\n      <div class=\"Tools wp-block-button\">\n        <button \n          class=\"\n            ConfirmCode\n            wp-block-button__link \n            wp-element-button\n          \"\n        >\n          CONFIRMAR\n        </button>\n      </div>\n      <div class=\"Message\"></div>          \n    </div>\n  ";
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/form-register-should.js"
/*!*************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/form-register-should.js ***!
  \*************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form RegisterShould\">\n      <div class=\"FormName\">RegisterShould</div>\n      <div class=\"Explain\">\n        El mail <strong>".concat(data.email, "</strong> no se ha encontrado, \n        quieres registrarte con <stong>este mail</strong> para acceder a los contenidos?\n      </div>\n      <div class=\"Tools wp-block-button\">\n        <button \n          class=\"\n            RegistryEmail\n            wp-block-button__link \n            wp-element-button\n          \"\n        >\n          REGISTRAR\n        </button>\n      </div>\n      <a \n        class=\"Extra OtherMail\"\n        href=\"#\"\n      >\n        Quiero suscribirme con otro email\n      </a>\n      <div class=\"Message\"></div>          \n    </div>\n  ");
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/form-register-want.js"
/*!***********************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/form-register-want.js ***!
  \***********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form RegisterWant\">\n      <div class=\"FormName\">RegisterWant</div>\n      <div class=\"Explain\">\n        Registrate con tu correo para acceder a los contenidos.\n      </div>\n      <div class=\"Fields\">\n        <div class=\"Field Email\">\n          <input\n            class=\"Email\"\n            type=\"email\"\n            placeholder=\"Tu E-mail\"\n            name=\"user-email\"\n          />\n          <div class=\"Tools wp-block-button\">\n            <button \n              class=\"\n                SendEmail\n                wp-block-button__link \n                wp-element-button\n              \"\n              disabled=\"disabled\"\n            >\n              ENVIAR\n            </button>\n          </div>\n        </div>\n      </div>      \n      <a \n        class=\"Extra BackIdentify\"\n        href=\"#\"\n      >\n        Ya me registr\xE9, quiero entrar\n      </a>\n      <div class=\"Message\"></div>          \n    </div>\n  ";
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/identify/forms.js"
/*!**********************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/forms.js ***!
  \**********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _form_identify__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./form-identify */ "./src/ui/frontend/postcontent/js/identify/form-identify.js");
/* harmony import */ var _form_confirmcode__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./form-confirmcode */ "./src/ui/frontend/postcontent/js/identify/form-confirmcode.js");
/* harmony import */ var _form_register_should__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./form-register-should */ "./src/ui/frontend/postcontent/js/identify/form-register-should.js");
/* harmony import */ var _form_register_want__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./form-register-want */ "./src/ui/frontend/postcontent/js/identify/form-register-want.js");
/* harmony import */ var _form_register_confirm__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./form-register-confirm */ "./src/ui/frontend/postcontent/js/identify/form-register-confirm.js");





var forms = {
  identify: _form_identify__WEBPACK_IMPORTED_MODULE_0__["default"],
  registerShould: _form_register_should__WEBPACK_IMPORTED_MODULE_2__["default"],
  registerWant: _form_register_want__WEBPACK_IMPORTED_MODULE_3__["default"],
  registerConfirm: _form_register_confirm__WEBPACK_IMPORTED_MODULE_4__["default"],
  confirmCode: _form_confirmcode__WEBPACK_IMPORTED_MODULE_1__["default"]
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return forms[data.form](data);
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/main.scss"
/*!***********************************************!*\
  !*** ./src/ui/frontend/postcontent/main.scss ***!
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
  !*** ./src/ui/frontend/postcontent/main.js ***!
  \*********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _js_identify_do_login__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./js/identify/do-login */ "./src/ui/frontend/postcontent/js/identify/do-login.js");
/* harmony import */ var _js_identify_do_identify__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./js/identify/do-identify */ "./src/ui/frontend/postcontent/js/identify/do-identify.js");
/* harmony import */ var _js_cantaccess_do_cantaccess__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./js/cantaccess/do-cantaccess */ "./src/ui/frontend/postcontent/js/cantaccess/do-cantaccess.js");
/* harmony import */ var _main_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./main.scss */ "./src/ui/frontend/postcontent/main.scss");




(function ($) {
  var waitIdentifyOrigin = setInterval(function () {
    if (poeticsoft_heart_campus_access_by) {
      clearInterval(waitIdentifyOrigin);
      var $postContent = $('.wp-block-poeticsoft-heart-campus-postcontent');
      var $formsIdentify = $postContent.find('.Forms.Identify');
      var $formsCantAccess = $postContent.find('.Forms.CantAccess');
      if ($formsIdentify.length) {
        (0,_js_identify_do_identify__WEBPACK_IMPORTED_MODULE_1__["default"])($, $postContent);
      }
      if ($formsCantAccess.length) {
        (0,_js_cantaccess_do_cantaccess__WEBPACK_IMPORTED_MODULE_2__["default"])($, $postContent);
      }
      var $mytools = $('.wp-block-poeticsoft-mytools');
      if ($mytools.length) {
        (0,_js_identify_do_login__WEBPACK_IMPORTED_MODULE_0__["default"])($, $mytools);
      }
    }
  }, 100);
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=main.js.map