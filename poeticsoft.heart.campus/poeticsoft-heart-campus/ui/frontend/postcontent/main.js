/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

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
    fetch('/wp-json/poeticsoft/contentpayment/' + data.url, {
      method: "POST",
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-WP-Nonce': poeticsoft_content_payment_api.nonce
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
      if (data.result == 'ok') {
        (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, 'Identifiación confirmada. Redirigiendo a la página de contenidos', 'Info');
        setTimeout(function () {
          window.location.reload();
        }, 2000);
      } else {
        console.log(data);
        (0,_common_message__WEBPACK_IMPORTED_MODULE_1__["default"])($, $wrapper, data.message, 'Error');
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
  var accessType = poeticsoft_content_payment_core_block_postcontent_accesstype_origin;
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
        if (data.result == 'error') {
          switch (accessType) {
            case 'gsheets':
              (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Email no registrado, solicita tu identificación', 'Error');
              break;
            case 'mailrelay':
              (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Email no registrado, tienes que registrarte', 'Error');
              setTimeout(function () {
                (0,_do_register_should__WEBPACK_IMPORTED_MODULE_4__["default"])($, $wrapper, email);
              }, 2000);
              break;
            case 'directus':
              (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Email no registrado, solicita tu identificación', 'Error');
              break;
            default:
              (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'No hay método de identificación', 'Error');
              break;
          }
        } else {
          (0,_do_confirmcode__WEBPACK_IMPORTED_MODULE_3__["default"])($, $wrapper, email, data.code);
        }
      })["catch"](function (error) {
        console.log(error);
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
    $('body').append("\n        <div class=\"poeticsoft_content_payment_login_overlay\">\n          <div class=\"poeticsoft_content_payment_login\">\n            <div class=\"Forms Identify\"></div>\n            <div class=\"Close\"></div>\n          </div>\n        </div>\n      ");
    var $loginWrapper = $('body .poeticsoft_content_payment_login_overlay');
    var $wrapper = $loginWrapper.find('.poeticsoft_content_payment_login');
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

/***/ "./src/ui/frontend/postcontent/js/identify/do-usetemporalcode.js"
/*!***********************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/do-usetemporalcode.js ***!
  \***********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _common_message__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../common/message */ "./src/ui/frontend/postcontent/js/common/message.js");
/* harmony import */ var _common_utils__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../common/utils */ "./src/ui/frontend/postcontent/js/common/utils.js");
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/identify/forms.js");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper) {
  var $forms = $wrapper.find('.Forms.UseTemporalCode');
  $forms.find('.Form').remove();
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_2__["default"])({
    form: 'useTemporalCode'
  }));
  var $useTemporalCode = $forms.find('.Form.UseTemporalCode');
  var $useTemporalCodeCode = $useTemporalCode.find('input.TemporalCode');
  var $useTemporalCodeSend = $useTemporalCode.find('button.SendTemporalCode');
  function checkCode() {
    var $this = $(this);
    var code = $this.val();
    if (code.length > 4) {
      $useTemporalCodeSend.prop('disabled', false);
    } else {
      $useTemporalCodeSend.prop('disabled', true);
    }
    (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, '', '');
  }
  $useTemporalCodeCode.on('change', checkCode);
  $useTemporalCodeCode.on('keydown', checkCode);
  $useTemporalCodeCode.on('keyup', checkCode);
  $useTemporalCodeSend.on('click', function () {
    var code = $useTemporalCodeCode.val();
    (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Enviando...', 'Warn');
    if (code.length > 4) {
      $useTemporalCodeCode.prop('disabled', true);
      $useTemporalCodeSend.prop('disabled', true);
      (0,_common_utils__WEBPACK_IMPORTED_MODULE_1__.apiFetch)({
        url: 'identify/subscriber/checktemporalcode',
        body: {
          code: code
        }
      }).then(function (result) {
        if (result.result == 'ok') {
          setTimeout(function () {
            window.location.reload();
          }, 2000);
        } else {
          (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, result.message, 'Error');
          setTimeout(function () {
            (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, '', '');
            $useTemporalCodeCode.prop('disabled', false);
            $useTemporalCodeSend.prop('disabled', false);
          }, 2000);
        }
      })["catch"](function (error) {
        console.log(error);
        (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Error de servidor, intentalo de nuevo, por favor.', 'Error');
        $useTemporalCodeCode.prop('disabled', false);
        $useTemporalCodeSend.prop('disabled', false);
      });
    } else {
      (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'El mail no es válido.', 'Error');
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
  var accessType = poeticsoft_content_payment_core_block_postcontent_accesstype_origin;
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

/***/ "./src/ui/frontend/postcontent/js/identify/form-usetemporalcode.js"
/*!*************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/identify/form-usetemporalcode.js ***!
  \*************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form UseTemporalCode\">\n      <div class=\"FormName\">Use Temporal Code</div>\n      <div class=\"Explain\">\n        Este contenido es reservado para suscriptores, \n        por favor, identif\xEDcate con el <strong>c\xF3digo temporal</strong> de acceso.\n      </div>\n      <div class=\"Fields\">\n        <div class=\"Field TemporalCode\">\n          <input\n            class=\"TemporalCode\"\n            type=\"text\"\n            placeholder=\"C\xF3digo\"\n            name=\"temporalcode\"\n          />      \n          <div class=\"Tools wp-block-button\">\n            <button \n              class=\"\n                SendTemporalCode\n                wp-block-button__link \n                wp-element-button\n              \"\n              disabled=\"disabled\"\n            >\n              ENVIAR\n            </button>\n          </div>\n        </div>\n      </div>\n      <div class=\"Message\"></div>          \n    </div>\n  ";
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
/* harmony import */ var _form_usetemporalcode__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./form-usetemporalcode */ "./src/ui/frontend/postcontent/js/identify/form-usetemporalcode.js");
/* harmony import */ var _form_confirmcode__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./form-confirmcode */ "./src/ui/frontend/postcontent/js/identify/form-confirmcode.js");
/* harmony import */ var _form_register_should__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./form-register-should */ "./src/ui/frontend/postcontent/js/identify/form-register-should.js");
/* harmony import */ var _form_register_want__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./form-register-want */ "./src/ui/frontend/postcontent/js/identify/form-register-want.js");
/* harmony import */ var _form_register_confirm__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./form-register-confirm */ "./src/ui/frontend/postcontent/js/identify/form-register-confirm.js");






var forms = {
  identify: _form_identify__WEBPACK_IMPORTED_MODULE_0__["default"],
  useTemporalCode: _form_usetemporalcode__WEBPACK_IMPORTED_MODULE_1__["default"],
  registerShould: _form_register_should__WEBPACK_IMPORTED_MODULE_3__["default"],
  registerWant: _form_register_want__WEBPACK_IMPORTED_MODULE_4__["default"],
  registerConfirm: _form_register_confirm__WEBPACK_IMPORTED_MODULE_5__["default"],
  confirmCode: _form_confirmcode__WEBPACK_IMPORTED_MODULE_2__["default"]
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return forms[data.form](data);
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-bizum.js"
/*!*************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-bizum.js ***!
  \*************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _common_message__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../common/message */ "./src/ui/frontend/postcontent/js/common/message.js");
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/shouldpay/forms.js");
/* harmony import */ var _common_utils__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../common/utils */ "./src/ui/frontend/postcontent/js/common/utils.js");
/* harmony import */ var _do_paychannel__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./do-paychannel */ "./src/ui/frontend/postcontent/js/shouldpay/do-paychannel.js");




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper) {
  var postContentData = $wrapper.data();
  var $forms = $wrapper.find('.Forms.ShouldPay');
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_1__["default"])({
    form: 'confirmPayBizum'
  }));
  var $confirmPay = $forms.find('.Form.ConfirmPay');
  var $confirmPayPay = $confirmPay.find('button.Pay');
  var $confirmPayOther = $confirmPay.find('a.OtherChannel');
  var allowBack = true;
  $confirmPayOther.on('click', function () {
    allowBack && (0,_do_paychannel__WEBPACK_IMPORTED_MODULE_3__["default"])($);
  });
  $confirmPayPay.on('click', function () {
    $confirmPayPay.prop('disabled', true);
    $confirmPayOther.addClass('Disabled');
    allowBack = false;
    (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Enviando...', 'Warn');
    (0,_common_utils__WEBPACK_IMPORTED_MODULE_2__.apiFetch)({
      url: 'pay/init',
      body: {
        type: 'bizum',
        email: postContentData.email,
        postid: postContentData.postid
      }
    }).then(function (data) {
      $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_1__["default"])({
        form: 'confirmPayBizumEnd',
        result: data
      }));
    })["catch"](function (error) {
      console.log(error);
      (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Error de servidor, intentalo de nuevo, por favor.', 'Error');
      $confirmPayPay.prop('disabled', false);
      $confirmPayOther.removeClass('Disabled');
      allowBack = true;
    });
  });
  $confirmPayOther.on('click', function () {
    (0,_do_paychannel__WEBPACK_IMPORTED_MODULE_3__["default"])($);
    return false;
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-stripe-end.js"
/*!******************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-stripe-end.js ***!
  \******************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/shouldpay/forms.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper) {
  var $forms = $wrapper.find('.Forms.ShouldPay');
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_0__["default"])({
    form: 'confirmpaystripeend'
  }));
  var $confirmpay = $forms.find('.Form.ConfirmPay');
  var $confirmpayaccess = $confirmpay.find('button.Access');
  $confirmpayaccess.on('click', function () {
    window.location.reload();
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-stripe.js"
/*!**************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-stripe.js ***!
  \**************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _common_message__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../common/message */ "./src/ui/frontend/postcontent/js/common/message.js");
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/shouldpay/forms.js");
/* harmony import */ var _common_utils__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../common/utils */ "./src/ui/frontend/postcontent/js/common/utils.js");
/* harmony import */ var _do_confirmpay_stripe_end__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./do-confirmpay-stripe-end */ "./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-stripe-end.js");
/* harmony import */ var _do_paychannel__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./do-paychannel */ "./src/ui/frontend/postcontent/js/shouldpay/do-paychannel.js");





/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper, paytype) {
  var postContentData = $wrapper.data();
  var $forms = $wrapper.find('.Forms.ShouldPay');
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_1__["default"])({
    form: 'confirmPayStripe'
  }));
  var $confirmPay = $forms.find('.Form.ConfirmPay');
  var $confirmPayPay = $confirmPay.find('button.Pay');
  var $confirmPayOther = $confirmPay.find('a.OtherChannel');
  var allowBack = true;
  $confirmPayOther.on('click', function () {
    allowBack && (0,_do_paychannel__WEBPACK_IMPORTED_MODULE_4__["default"])($);
  });
  $confirmPayPay.on('click', function () {
    $confirmPayPay.prop('disabled', true);
    $confirmPayOther.addClass('Disabled');
    allowBack = false;
    (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Conectando con Stripe...', 'Warn');
    (0,_common_utils__WEBPACK_IMPORTED_MODULE_2__.apiFetch)({
      url: 'pay/init',
      body: {
        type: 'stripe',
        email: postContentData.email,
        postid: postContentData.postid
      }
    }).then(function (data) {
      window.open(data.stripesession.url, 'STRIPE', 'width=1080,height=800');
      (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Esperando confirmación de pago, no cierres esta ventana...', 'Warn');
      var waitStripe = setInterval(function () {
        (0,_common_utils__WEBPACK_IMPORTED_MODULE_2__.apiFetch)({
          url: 'pay/stripe/session/check',
          body: {
            stripesessionid: data.stripesession.id
          }
        }).then(function (result) {
          if (result.done) {
            clearInterval(waitStripe);
            (0,_do_confirmpay_stripe_end__WEBPACK_IMPORTED_MODULE_3__["default"])($);
          }
        });
      }, 3000);
    })["catch"](function (error) {
      console.log(error);
      (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Error de servidor, intentalo de nuevo, por favor.', 'Error');
      $confirmPayPay.prop('disabled', false);
      $confirmPayOther.removeClass('Disabled');
      allowBack = true;
    });
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-transfer.js"
/*!****************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-transfer.js ***!
  \****************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _common_message__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../common/message */ "./src/ui/frontend/postcontent/js/common/message.js");
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/shouldpay/forms.js");
/* harmony import */ var _common_utils__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../common/utils */ "./src/ui/frontend/postcontent/js/common/utils.js");
/* harmony import */ var _do_paychannel__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./do-paychannel */ "./src/ui/frontend/postcontent/js/shouldpay/do-paychannel.js");




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper) {
  var postContentData = $wrapper.data();
  var $forms = $wrapper.find('.Forms.ShouldPay');
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_1__["default"])({
    form: 'confirmPayTransfer'
  }));
  var $confirmPay = $forms.find('.Form.ConfirmPay');
  var $confirmPayPay = $confirmPay.find('button.Pay');
  var $confirmPayOther = $confirmPay.find('a.OtherChannel');
  var allowBack = true;
  $confirmPayOther.on('click', function () {
    allowBack && (0,_do_paychannel__WEBPACK_IMPORTED_MODULE_3__["default"])($);
  });
  $confirmPayPay.on('click', function () {
    $confirmPayPay.prop('disabled', true);
    $confirmPayOther.addClass('Disabled');
    allowBack = false;
    (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Enviando...', 'Warn');
    (0,_common_utils__WEBPACK_IMPORTED_MODULE_2__.apiFetch)({
      url: 'pay/init',
      body: {
        type: 'transfer',
        email: postContentData.email,
        postid: postContentData.postid
      }
    }).then(function (data) {
      $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_1__["default"])({
        form: 'confirmPayTransferEnd',
        result: data
      }));
    })["catch"](function (error) {
      console.log(error);
      (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Error de servidor, intentalo de nuevo, por favor.', 'Error');
      $confirmPayPay.prop('disabled', false);
      $confirmPayOther.removeClass('Disabled');
      allowBack = true;
    });
  });
  $confirmPayOther.on('click', function () {
    (0,_do_paychannel__WEBPACK_IMPORTED_MODULE_3__["default"])($);
    return false;
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/do-paychannel.js"
/*!*******************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/do-paychannel.js ***!
  \*******************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _common_message__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../common/message */ "./src/ui/frontend/postcontent/js/common/message.js");
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/shouldpay/forms.js");
/* harmony import */ var _do_confirmpay_stripe__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./do-confirmpay-stripe */ "./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-stripe.js");
/* harmony import */ var _do_confirmpay_transfer__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./do-confirmpay-transfer */ "./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-transfer.js");
/* harmony import */ var _do_confirmpay_bizum__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./do-confirmpay-bizum */ "./src/ui/frontend/postcontent/js/shouldpay/do-confirmpay-bizum.js");





var confirmPay = {
  stripe: _do_confirmpay_stripe__WEBPACK_IMPORTED_MODULE_2__["default"],
  transfer: _do_confirmpay_transfer__WEBPACK_IMPORTED_MODULE_3__["default"],
  bizum: _do_confirmpay_bizum__WEBPACK_IMPORTED_MODULE_4__["default"]
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper) {
  var $forms = $wrapper.find('.Forms.ShouldPay');
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_1__["default"])({
    form: 'payChannel'
  }));
  var $paychannel = $forms.find('.Form.PayChannel');
  var $inputChannels = $paychannel.find('.Channel input[type=radio]');
  var $paychannelpay = $paychannel.find('button.Pay');
  $inputChannels.on('change', function () {
    $paychannelpay.prop('disabled', false);
  });
  $paychannelpay.on('click', function () {
    var typeSelected = $paychannel.find('.Channel input[type=radio]:checked').val();
    $inputChannels.prop('disabled', true);
    $paychannelpay.prop('disabled', true);
    (0,_common_message__WEBPACK_IMPORTED_MODULE_0__["default"])($, $wrapper, 'Conectando...', 'Warn');
    confirmPay[typeSelected]($);
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/do-shouldpay.js"
/*!******************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/do-shouldpay.js ***!
  \******************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _forms__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./forms */ "./src/ui/frontend/postcontent/js/shouldpay/forms.js");
/* harmony import */ var _do_paychannel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./do-paychannel */ "./src/ui/frontend/postcontent/js/shouldpay/do-paychannel.js");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function ($, $wrapper) {
  var $adviceText = $wrapper.find('.AdviceText');
  var adviceText = $adviceText.html();
  var $forms = $wrapper.find('.Forms.ShouldPay');
  $forms.html((0,_forms__WEBPACK_IMPORTED_MODULE_0__["default"])({
    form: 'shouldPay',
    adviceText: adviceText
  }));
  var $shouldPay = $forms.find('.Form.ShouldPay');
  var $shouldPayBuy = $shouldPay.find('button.Buy');
  $shouldPayBuy.on('click', function () {
    (0,_do_paychannel__WEBPACK_IMPORTED_MODULE_1__["default"])($);
  });
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-bizum-end.js"
/*!*******************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-bizum-end.js ***!
  \*******************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form ConfirmPay Bizum End\">\n      <div class=\"FormName\">Confirm Pay Bizum End</div>\n      <div class=\"Explain Bizum\">\n        Se ha enviado aviso de pago, Recibir\xE1s la confirmaci\xF3n \n        y el link de acceso en tu correo en un plazo de 24h \n        despu\xE9s de la confirmaci\xF3n del ingreso. \n      </div>   \n    </div>\n  ";
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-bizum.js"
/*!***************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-bizum.js ***!
  \***************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form ConfirmPay Bizum\">\n      <div class=\"FormName\">Confirm Pay Bizum</div>\n      <div class=\"Explain Bizum\">\n        Si eliges el pago por Bizum, el acceso a tu producto \n        digital se activar\xE1 cuando se confirme la recepci\xF3n \n        del importe en nuestro n\xFAmero</br>\n        <strong>+34 XXX XX XX XX</strong></br>\n        Recibir\xE1s la confirmaci\xF3n de pago y el link de acceso en tu correo en un plazo de 24h.\n      </div>\n      <div class=\"Tools wp-block-button\"> \n        <button \n          class=\"\n            Pay\n            wp-block-button__link \n            wp-element-button\n          \"\n        >\n          OK, HAR\xC9 BIZUM\n        </button>\n      </div>\n      <a \n        class=\"Extra OtherChannel\"\n        href=\"#\"\n      >\n        Usar otro m\xE9todo de pago\n      </a>\n      <div class=\"Message\"></div>          \n    </div>\n  ";
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-stripe-end.js"
/*!********************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-stripe-end.js ***!
  \********************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form ConfirmPay Stripe End\">\n      <div class=\"FormName\">Confirm Pay Stripe End</div>\n      <div class=\"Explain Stripe\">\n        Se ha recibido el ingreso, puedes acceder al contenido.\n      </div>\n      <div class=\"Tools wp-block-button\">\n        <button \n          class=\"\n            Pay Access\n            wp-block-button__link \n            wp-element-button\n          \"\n        >\n          ACCEDER\n        </button>\n      </div>       \n    </div>\n  ";
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-stripe.js"
/*!****************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-stripe.js ***!
  \****************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form ConfirmPay Stripe\">\n      <div class=\"FormName\">Confirm Pay Stripe</div>\n      <div class=\"Explain Stripe\">\n        Si eliges el pago con tarjeta, ser\xE1s redirigido \n        a la pasarela segura de <strong>Stripe</strong> para completar \n        la transacci\xF3n. Una vez confirmado el pago, \n        el acceso a tu producto digital se activar\xE1 \n        de forma inmediata. <strong>Stripe</strong> garantiza \n        la seguridad de tus datos mediante encriptaci\xF3n \n        y cumple con los m\xE1s altos est\xE1ndares internacionales \n        de protecci\xF3n de pagos (PCI DSS).\n      </div>\n      <div class=\"Tools wp-block-button\">\n        <button \n          class=\"\n            Pay Stripe\n            wp-block-button__link \n            wp-element-button\n          \"\n        >\n          PAGAR CON TARJETA\n        </button>        \n      </div>\n      <a\n        class=\"Extra OtherChannel\"\n        href=\"#\"\n      >\n        Usar otro m\xE9todo de pago\n      </a>\n      <div class=\"Message\"></div>          \n    </div>\n  ";
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-transfer-end.js"
/*!**********************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-transfer-end.js ***!
  \**********************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form ConfirmPay Transfer End\">\n      <div class=\"FormName\">Confirm Pay Transfer</div>\n      <div class=\"Explain Transfer\">        \n        Se ha enviado aviso de pago, Recibir\xE1s la confirmaci\xF3n \n        y el link de acceso en tu correo en un plazo de 24h \n        despu\xE9s de la confirmaci\xF3n del ingreso.\n      </div>       \n    </div>\n  ";
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-transfer.js"
/*!******************************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-transfer.js ***!
  \******************************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form ConfirmPay Transfer\">\n      <div class=\"FormName\">Confirm Pay Transfer</div>\n      <div class=\"Explain Transfer\">\n        Si eliges el pago por transferencia bancaria, \n        el acceso al producto digital se activar\xE1 \n        una vez que hayamos recibido y verificado \n        el importe en nuestra cuenta</br>\n        <strong>ES XX XXXX XXXX XX XXXXXXXXXX</strong></br> \n        Este proceso puede tardar entre 24 y 48 horas, \n        dependiendo de la entidad bancaria. Recibir\xE1s la confirmaci\xF3n de pago y el link de acceso en tu correo.\n      </div>\n      <div class=\"Tools wp-block-button\">\n        <button \n          class=\"\n            Pay Transfer\n            wp-block-button__link \n            wp-element-button\n          \"\n        >\n          OK, HAR\xC9 TRANFERENCIA\n        </button>\n      </div>\n      <a \n        class=\"Extra OtherChannel\"\n        href=\"#\"\n      >\n        Usar otro m\xE9todo de pago\n      </a>\n      <div class=\"Message\"></div>          \n    </div>\n  ";
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/form-paychannel.js"
/*!*********************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/form-paychannel.js ***!
  \*********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  return "\n    <div class=\"Form PayChannel\">\n      <div class=\"FormName\">Pay Channel</div>\n      <div class=\"Explain\">\n        Puedes elegir varias formas de pago:\n      </div>\n      <div class=\"Fields\">\n        <div class=\"Field RadioButtons\">\n          <div class=\"Channel Stripe\">\n            <input \n              class=\"ChannelStripe\"\n              type=\"radio\"\n              id=\"channel-stripe\"\n              name=\"channel\"\n              value=\"stripe\"\n            />\n            <label\n              class=\"Label\"\n              for=\"channel-stripe\"\n            >\n              Tarjeta\n            </label>\n          </div>\n          <div class=\"Channel Transfer\">\n            <input \n              class=\"ChannelTransfer\"\n              type=\"radio\"\n              id=\"channel-transfer\"\n              name=\"channel\"\n              value=\"transfer\"\n            />\n            <label\n              class=\"Label\"\n              for=\"channel-transfer\"\n            >\n              Transferencia bancaria\n            </label>\n          </div>\n          <div class=\"Channel Bizum\">\n            <input \n              class=\"ChannelTransfer\"\n              type=\"radio\"\n              id=\"channel-bizum\"\n              name=\"channel\"\n              value=\"bizum\"\n            />\n            <label\n              class=\"Label\"\n              for=\"channel-bizum\"\n            >\n              Bizum\n            </label>\n          </div>\n        </div>\n      </div>\n      <div class=\"Tools wp-block-button\">\n        <button \n          class=\"\n            Pay\n            wp-block-button__link \n            wp-element-button\n          \"\n          disabled=\"disabled\"\n        >\n          OBTENER ACCESO\n        </button>\n      </div>\n      <div class=\"Message\"></div>          \n    </div>\n  ";
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/form-shouldpay.js"
/*!********************************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/form-shouldpay.js ***!
  \********************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function (data) {
  var accessType = poeticsoft_content_payment_core_block_postcontent_accesstype_origin;
  var payText = '';
  var accessButton = '';
  switch (accessType) {
    case 'gsheets':
      payText = data.adviceText;
      break;
    default:
      payText = "\n        Este contenido est\xE1 disponible para suscriptores, \n        puedes obtener acceso a estos contenidos \n        por un periodo de <strong>12 meses</strong> a partir de la fecha de adquisici\xF3n.  \n      ";
      accessButton = "\n        <div class=\"Tools wp-block-button\">\n          <button \n            class=\"\n              Buy\n              wp-block-button__link \n              wp-element-button\n            \"\n          >\n            OBTENER ACCESO\n          </button>\n        </div>\n      ";
      break;
  }
  return "\n    <div class=\"Form ShouldPay\">\n      <div class=\"FormName\">Should Pay</div>\n      <div class=\"Explain\">\n        ".concat(payText, "  \n      </div>\n      ").concat(accessButton, "\n      <div class=\"Message\"></div>          \n    </div>\n  ");
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/frontend/postcontent/js/shouldpay/forms.js"
/*!***********************************************************!*\
  !*** ./src/ui/frontend/postcontent/js/shouldpay/forms.js ***!
  \***********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _form_shouldpay__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./form-shouldpay */ "./src/ui/frontend/postcontent/js/shouldpay/form-shouldpay.js");
/* harmony import */ var _form_paychannel__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./form-paychannel */ "./src/ui/frontend/postcontent/js/shouldpay/form-paychannel.js");
/* harmony import */ var _form_confirmpay_stripe__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./form-confirmpay-stripe */ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-stripe.js");
/* harmony import */ var _form_confirmpay_stripe_end__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./form-confirmpay-stripe-end */ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-stripe-end.js");
/* harmony import */ var _form_confirmpay_transfer__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./form-confirmpay-transfer */ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-transfer.js");
/* harmony import */ var _form_confirmpay_transfer_end__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./form-confirmpay-transfer-end */ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-transfer-end.js");
/* harmony import */ var _form_confirmpay_bizum__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./form-confirmpay-bizum */ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-bizum.js");
/* harmony import */ var _form_confirmpay_bizum_end__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./form-confirmpay-bizum-end */ "./src/ui/frontend/postcontent/js/shouldpay/form-confirmpay-bizum-end.js");








var forms = {
  shouldPay: _form_shouldpay__WEBPACK_IMPORTED_MODULE_0__["default"],
  payChannel: _form_paychannel__WEBPACK_IMPORTED_MODULE_1__["default"],
  confirmPayStripe: _form_confirmpay_stripe__WEBPACK_IMPORTED_MODULE_2__["default"],
  confirmPayStripeEnd: _form_confirmpay_stripe_end__WEBPACK_IMPORTED_MODULE_3__["default"],
  confirmPayTransfer: _form_confirmpay_transfer__WEBPACK_IMPORTED_MODULE_4__["default"],
  confirmPayTransferEnd: _form_confirmpay_transfer_end__WEBPACK_IMPORTED_MODULE_5__["default"],
  confirmPayBizum: _form_confirmpay_bizum__WEBPACK_IMPORTED_MODULE_6__["default"],
  confirmPayBizumEnd: _form_confirmpay_bizum_end__WEBPACK_IMPORTED_MODULE_7__["default"]
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
/* harmony import */ var _js_shouldpay_do_shouldpay__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./js/shouldpay/do-shouldpay */ "./src/ui/frontend/postcontent/js/shouldpay/do-shouldpay.js");
/* harmony import */ var _js_identify_do_usetemporalcode__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./js/identify/do-usetemporalcode */ "./src/ui/frontend/postcontent/js/identify/do-usetemporalcode.js");
/* harmony import */ var _main_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./main.scss */ "./src/ui/frontend/postcontent/main.scss");





(function ($) {
  var waitIdentifyOrigin = setInterval(function () {
    if (poeticsoft_heart_campus_access_by) {
      clearInterval(waitIdentifyOrigin);
      var $postContent = $('.wp-block-poeticsoft_content_payment_postcontent');
      var $formsUseTemporalCode = $postContent.find('.Forms.UseTemporalCode');
      var $formsIdentify = $postContent.find('.Forms.Identify');
      var $formsShouldPay = $postContent.find('.Forms.ShouldPay');
      if ($formsIdentify.length) {
        (0,_js_identify_do_identify__WEBPACK_IMPORTED_MODULE_1__["default"])($, $postContent);
      }
      if ($formsShouldPay.length) {
        (0,_js_shouldpay_do_shouldpay__WEBPACK_IMPORTED_MODULE_2__["default"])($, $postContent);
      }
      if ($formsUseTemporalCode.length) {
        (0,_js_identify_do_usetemporalcode__WEBPACK_IMPORTED_MODULE_3__["default"])($, $postContent);
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