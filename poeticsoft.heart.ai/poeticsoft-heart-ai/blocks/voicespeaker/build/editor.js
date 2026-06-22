/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/block/voicespeaker/edit.scss"
/*!******************************************!*\
  !*** ./src/block/voicespeaker/edit.scss ***!
  \******************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ },

/***/ "./node_modules/uuid/dist/native.js"
/*!******************************************!*\
  !*** ./node_modules/uuid/dist/native.js ***!
  \******************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const randomUUID = typeof crypto !== 'undefined' && crypto.randomUUID && crypto.randomUUID.bind(crypto);
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({ randomUUID });


/***/ },

/***/ "./node_modules/uuid/dist/regex.js"
/*!*****************************************!*\
  !*** ./node_modules/uuid/dist/regex.js ***!
  \*****************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (/^(?:[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}|00000000-0000-0000-0000-000000000000|ffffffff-ffff-ffff-ffff-ffffffffffff)$/i);


/***/ },

/***/ "./node_modules/uuid/dist/rng.js"
/*!***************************************!*\
  !*** ./node_modules/uuid/dist/rng.js ***!
  \***************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ rng)
/* harmony export */ });
let getRandomValues;
const rnds8 = new Uint8Array(16);
function rng() {
    if (!getRandomValues) {
        if (typeof crypto === 'undefined' || !crypto.getRandomValues) {
            throw new Error('crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported');
        }
        getRandomValues = crypto.getRandomValues.bind(crypto);
    }
    return getRandomValues(rnds8);
}


/***/ },

/***/ "./node_modules/uuid/dist/stringify.js"
/*!*********************************************!*\
  !*** ./node_modules/uuid/dist/stringify.js ***!
  \*********************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__),
/* harmony export */   unsafeStringify: () => (/* binding */ unsafeStringify)
/* harmony export */ });
/* harmony import */ var _validate_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validate.js */ "./node_modules/uuid/dist/validate.js");

const byteToHex = [];
for (let i = 0; i < 256; ++i) {
    byteToHex.push((i + 0x100).toString(16).slice(1));
}
function unsafeStringify(arr, offset = 0) {
    return (byteToHex[arr[offset + 0]] +
        byteToHex[arr[offset + 1]] +
        byteToHex[arr[offset + 2]] +
        byteToHex[arr[offset + 3]] +
        '-' +
        byteToHex[arr[offset + 4]] +
        byteToHex[arr[offset + 5]] +
        '-' +
        byteToHex[arr[offset + 6]] +
        byteToHex[arr[offset + 7]] +
        '-' +
        byteToHex[arr[offset + 8]] +
        byteToHex[arr[offset + 9]] +
        '-' +
        byteToHex[arr[offset + 10]] +
        byteToHex[arr[offset + 11]] +
        byteToHex[arr[offset + 12]] +
        byteToHex[arr[offset + 13]] +
        byteToHex[arr[offset + 14]] +
        byteToHex[arr[offset + 15]]).toLowerCase();
}
function stringify(arr, offset = 0) {
    const uuid = unsafeStringify(arr, offset);
    if (!(0,_validate_js__WEBPACK_IMPORTED_MODULE_0__["default"])(uuid)) {
        throw TypeError('Stringified UUID is invalid');
    }
    return uuid;
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (stringify);


/***/ },

/***/ "./node_modules/uuid/dist/v4.js"
/*!**************************************!*\
  !*** ./node_modules/uuid/dist/v4.js ***!
  \**************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _native_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./native.js */ "./node_modules/uuid/dist/native.js");
/* harmony import */ var _rng_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./rng.js */ "./node_modules/uuid/dist/rng.js");
/* harmony import */ var _stringify_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./stringify.js */ "./node_modules/uuid/dist/stringify.js");



function _v4(options, buf, offset) {
    options = options || {};
    const rnds = options.random ?? options.rng?.() ?? (0,_rng_js__WEBPACK_IMPORTED_MODULE_1__["default"])();
    if (rnds.length < 16) {
        throw new Error('Random bytes length must be >= 16');
    }
    rnds[6] = (rnds[6] & 0x0f) | 0x40;
    rnds[8] = (rnds[8] & 0x3f) | 0x80;
    if (buf) {
        offset = offset || 0;
        if (offset < 0 || offset + 16 > buf.length) {
            throw new RangeError(`UUID byte range ${offset}:${offset + 15} is out of buffer bounds`);
        }
        for (let i = 0; i < 16; ++i) {
            buf[offset + i] = rnds[i];
        }
        return buf;
    }
    return (0,_stringify_js__WEBPACK_IMPORTED_MODULE_2__.unsafeStringify)(rnds);
}
function v4(options, buf, offset) {
    if (_native_js__WEBPACK_IMPORTED_MODULE_0__["default"].randomUUID && !buf && !options) {
        return _native_js__WEBPACK_IMPORTED_MODULE_0__["default"].randomUUID();
    }
    return _v4(options, buf, offset);
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (v4);


/***/ },

/***/ "./node_modules/uuid/dist/validate.js"
/*!********************************************!*\
  !*** ./node_modules/uuid/dist/validate.js ***!
  \********************************************/
(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _regex_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./regex.js */ "./node_modules/uuid/dist/regex.js");

function validate(uuid) {
    return typeof uuid === 'string' && _regex_js__WEBPACK_IMPORTED_MODULE_0__["default"].test(uuid);
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (validate);


/***/ },

/***/ "./poeticsoft-heart-ai/blocks/voicespeaker/block.json"
/*!************************************************************!*\
  !*** ./poeticsoft-heart-ai/blocks/voicespeaker/block.json ***!
  \************************************************************/
(module) {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":2,"name":"poeticsoft-heart-ai/voicespeaker","title":"Voicespeaker","description":"Bloque voicespeaker para el ecosistema Poeticsoft Heart AI.","category":"poeticsoft-heart-ai","icon":"admin-plugins","keywords":[],"textdomain":"poeticsoft-heart-ai","version":"0.0.0","supports":{},"attributes":{"blockId":{"type":"string","default":""},"refClientId":{"type":"string","default":""}},"editorScript":"file:./build/editor.js","editorStyle":"file:./build/editor.css","viewScript":"file:./build/view.js","viewStyle":"file:./build/view.css","render":"file:./render.php"}');

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
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!******************************************!*\
  !*** ./src/block/voicespeaker/editor.js ***!
  \******************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Edit)
/* harmony export */ });
/* harmony import */ var uuid__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! uuid */ "./node_modules/uuid/dist/v4.js");
/* harmony import */ var blocks_voicespeaker_block_json__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! blocks/voicespeaker/block.json */ "./poeticsoft-heart-ai/blocks/voicespeaker/block.json");
/* harmony import */ var _edit_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit.scss */ "./src/block/voicespeaker/edit.scss");
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }

var __ = wp.i18n.__;
var _wp$blockEditor = wp.blockEditor,
  useBlockProps = _wp$blockEditor.useBlockProps,
  InspectorControls = _wp$blockEditor.InspectorControls,
  RichText = _wp$blockEditor.RichText;
var _wp$components = wp.components,
  PanelBody = _wp$components.PanelBody,
  TextControl = _wp$components.TextControl,
  RangeControl = _wp$components.RangeControl,
  SelectControl = _wp$components.SelectControl,
  Button = _wp$components.Button;
var _wp$element = wp.element,
  Fragment = _wp$element.Fragment,
  useEffect = _wp$element.useEffect,
  useState = _wp$element.useState;
var registerBlockType = wp.blocks.registerBlockType;


function Edit(_ref) {
  var clientId = _ref.clientId,
    attributes = _ref.attributes,
    setAttributes = _ref.setAttributes;
  var blockId = attributes.blockId,
    refClientId = attributes.refClientId,
    placeholder = attributes.placeholder,
    buttonLabel = attributes.buttonLabel,
    content = attributes.content,
    pitch = attributes.pitch,
    rate = attributes.rate,
    voiceURI = attributes.voiceURI;
  var blockProps = useBlockProps();
  var _useState = useState([]),
    _useState2 = _slicedToArray(_useState, 2),
    voices = _useState2[0],
    setVoices = _useState2[1];
  var _useState3 = useState(false),
    _useState4 = _slicedToArray(_useState3, 2),
    isPlaying = _useState4[0],
    setIsPlaying = _useState4[1];
  useEffect(function () {
    // IDs únicos
    if (!blockId || refClientId !== clientId) {
      setAttributes({
        blockId: (0,uuid__WEBPACK_IMPORTED_MODULE_0__["default"])(),
        refClientId: clientId
      });
    }

    // Cargar voces disponibles del sistema
    var loadVoices = function loadVoices() {
      setVoices(window.speechSynthesis.getVoices());
    };
    loadVoices(); // Intento inmediato

    // Algunos navegadores cargan las voces de forma asíncrona
    if (window.speechSynthesis.onvoiceschanged !== undefined) {
      window.speechSynthesis.onvoiceschanged = loadVoices;
    }
  }, [clientId]);
  var testVoice = function testVoice() {
    if (window.speechSynthesis.speaking) {
      window.speechSynthesis.cancel();
      setIsPlaying(false);
      return;
    }
    var textToRead = content || 'Este es un mensaje de prueba para comprobar la configuración de voz.';
    var utterance = new SpeechSynthesisUtterance(textToRead);
    utterance.pitch = pitch !== undefined ? pitch : 1;
    utterance.rate = rate !== undefined ? rate : 1;
    if (voiceURI) {
      var selectedVoice = voices.find(function (v) {
        return v.voiceURI === voiceURI;
      });
      if (selectedVoice) {
        utterance.voice = selectedVoice;
      }
    } else {
      utterance.lang = 'es-ES'; // Default fallback
    }
    utterance.onend = function () {
      return setIsPlaying(false);
    };
    utterance.onerror = function () {
      return setIsPlaying(false);
    };
    setIsPlaying(true);
    window.speechSynthesis.speak(utterance);
  };

  // Preparar opciones para el SelectControl
  var voiceOptions = [{
    label: __('Voz por defecto del sistema', 'poeticsoft-heart-ai'),
    value: ''
  }].concat(_toConsumableArray(voices.map(function (v) {
    return {
      label: "".concat(v.name, " (").concat(v.lang, ")"),
      value: v.voiceURI
    };
  })));
  return /*#__PURE__*/React.createElement(Fragment, null, /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
    title: __('Configuración de Altavoz', 'poeticsoft-heart-ai')
  }, /*#__PURE__*/React.createElement(TextControl, {
    label: __('Texto del Botón', 'poeticsoft-heart-ai'),
    value: buttonLabel,
    onChange: function onChange(value) {
      return setAttributes({
        buttonLabel: value
      });
    }
  }), /*#__PURE__*/React.createElement(SelectControl, {
    label: __('Voz', 'poeticsoft-heart-ai'),
    value: voiceURI,
    options: voiceOptions,
    onChange: function onChange(value) {
      return setAttributes({
        voiceURI: value
      });
    },
    help: __('La disponibilidad de voces depende del navegador y sistema operativo del usuario final.', 'poeticsoft-heart-ai')
  }), /*#__PURE__*/React.createElement(RangeControl, {
    label: __('Tono (Pitch)', 'poeticsoft-heart-ai'),
    value: pitch !== undefined ? pitch : 1,
    onChange: function onChange(value) {
      return setAttributes({
        pitch: value
      });
    },
    min: 0,
    max: 2,
    step: 0.1
  }), /*#__PURE__*/React.createElement(RangeControl, {
    label: __('Velocidad (Rate)', 'poeticsoft-heart-ai'),
    value: rate !== undefined ? rate : 1,
    onChange: function onChange(value) {
      return setAttributes({
        rate: value
      });
    },
    min: 0.5,
    max: 2,
    step: 0.1
  }), /*#__PURE__*/React.createElement("div", {
    style: {
      marginTop: '20px'
    }
  }, /*#__PURE__*/React.createElement(Button, {
    variant: "primary",
    onClick: testVoice,
    style: {
      width: '100%',
      justifyContent: 'center'
    }
  }, isPlaying ? '⏹️ Detener Prueba' : '▶️ Probar Configuración de Voz')))), /*#__PURE__*/React.createElement("div", blockProps, /*#__PURE__*/React.createElement("div", {
    className: "psh-speaker-preview"
  }, /*#__PURE__*/React.createElement("div", {
    className: "psh-speaker-header"
  }, /*#__PURE__*/React.createElement("span", {
    className: "psh-icon"
  }, "\uD83D\uDD0A"), /*#__PURE__*/React.createElement("strong", null, __('Voice Speaker (Text to Speech)', 'poeticsoft-heart-ai'))), /*#__PURE__*/React.createElement("div", {
    className: "psh-speaker-mockup"
  }, /*#__PURE__*/React.createElement("button", {
    disabled: true,
    className: "psh-speaker-btn-mock"
  }, buttonLabel), /*#__PURE__*/React.createElement(RichText, {
    tagName: "div",
    className: "psh-speaker-content-edit",
    value: content,
    onChange: function onChange(value) {
      return setAttributes({
        content: value
      });
    },
    placeholder: __('Escribe el texto que será leído en voz alta...', 'poeticsoft-heart-ai')
  })), /*#__PURE__*/React.createElement("div", {
    className: "psh-block-id-badge"
  }, "ID: ", blockId))));
}
var Save = function Save() {
  return null;
};
registerBlockType(blocks_voicespeaker_block_json__WEBPACK_IMPORTED_MODULE_1__.name, {
  edit: Edit,
  save: Save
});
})();

/******/ })()
;
//# sourceMappingURL=editor.js.map