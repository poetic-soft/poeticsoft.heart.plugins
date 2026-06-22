/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/block/voicespeaker/view.scss"
/*!******************************************!*\
  !*** ./src/block/voicespeaker/view.scss ***!
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
/*!****************************************!*\
  !*** ./src/block/voicespeaker/view.js ***!
  \****************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _view_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./view.scss */ "./src/block/voicespeaker/view.scss");
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t["return"] || t["return"](); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }

document.addEventListener('DOMContentLoaded', function () {
  var containers = document.querySelectorAll('.wp-block-poeticsoft-heart-ai-voicespeaker');
  containers.forEach(function (container) {
    var button = container.querySelector('.pshai-speaker-btn');
    var contentDiv = container.querySelector('.pshai-speaker-content');
    var isSpeaking = false;
    var isStreaming = false;
    var speechQueue = [];
    if (!window.speechSynthesis) {
      if (button) button.disabled = true;
      return;
    }

    /**
     * Lógica de Síntesis de Voz
     */
    var _speakNextInQueue = function speakNextInQueue() {
      if (isSpeaking || speechQueue.length === 0) return;
      var text = speechQueue.shift();
      var utterance = new SpeechSynthesisUtterance(text);

      // Configuración desde data-attributes
      var pitch = parseFloat(container.dataset.pitch);
      var rate = parseFloat(container.dataset.rate);
      var voiceURI = container.dataset.voiceuri;
      if (!isNaN(pitch)) utterance.pitch = pitch;
      if (!isNaN(rate)) utterance.rate = rate;
      if (voiceURI) {
        var voices = window.speechSynthesis.getVoices();
        var selectedVoice = voices.find(function (v) {
          return v.voiceURI === voiceURI;
        });
        if (selectedVoice) utterance.voice = selectedVoice;
      } else {
        utterance.lang = 'es-ES';
      }
      utterance.onstart = function () {
        isSpeaking = true;
        button.classList.add('is-speaking');
      };
      utterance.onend = function () {
        isSpeaking = false;
        if (speechQueue.length > 0) {
          _speakNextInQueue();
        } else if (!isStreaming) {
          button.classList.remove('is-speaking');
        }
      };
      window.speechSynthesis.speak(utterance);
    };

    /**
     * Consumo de Streaming (SSE)
     */
    var startStreaming = /*#__PURE__*/function () {
      var _ref = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee(inputText) {
        var buffer, response, reader, decoder, _yield$reader$read, done, value, chunk, lines, _iterator, _step, line, data, parsed, newText, _t, _t2;
        return _regenerator().w(function (_context) {
          while (1) switch (_context.p = _context.n) {
            case 0:
              if (!isStreaming) {
                _context.n = 1;
                break;
              }
              return _context.a(2);
            case 1:
              isStreaming = true;
              button.classList.add('is-speaking');
              contentDiv.innerText = ''; // Limpiar para la nueva respuesta
              buffer = '';
              _context.p = 2;
              _context.n = 3;
              return fetch('/wp-json/psh/v1/voice/stream', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                  text: inputText
                })
              });
            case 3:
              response = _context.v;
              reader = response.body.getReader();
              decoder = new TextDecoder();
            case 4:
              if (false) // removed by dead control flow
{}
              _context.n = 5;
              return reader.read();
            case 5:
              _yield$reader$read = _context.v;
              done = _yield$reader$read.done;
              value = _yield$reader$read.value;
              if (!done) {
                _context.n = 6;
                break;
              }
              return _context.a(3, 15);
            case 6:
              chunk = decoder.decode(value, {
                stream: true
              });
              lines = chunk.split('\n');
              _iterator = _createForOfIteratorHelper(lines);
              _context.p = 7;
              _iterator.s();
            case 8:
              if ((_step = _iterator.n()).done) {
                _context.n = 11;
                break;
              }
              line = _step.value;
              if (!line.startsWith('data: ')) {
                _context.n = 10;
                break;
              }
              data = line.slice(6);
              if (!(data === '[DONE]')) {
                _context.n = 9;
                break;
              }
              return _context.a(3, 11);
            case 9:
              try {
                parsed = JSON.parse(data);
                newText = parsed.text;
                buffer += newText;
                contentDiv.innerText += newText;

                // Si encontramos un signo de puntuación, enviamos a la cola de voz
                if (/[.!?\n]/.test(newText)) {
                  speechQueue.push(buffer.trim());
                  buffer = '';
                  _speakNextInQueue();
                }
              } catch (e) {
                // Ignorar errores de parseo parcial
              }
            case 10:
              _context.n = 8;
              break;
            case 11:
              _context.n = 13;
              break;
            case 12:
              _context.p = 12;
              _t = _context.v;
              _iterator.e(_t);
            case 13:
              _context.p = 13;
              _iterator.f();
              return _context.f(13);
            case 14:
              _context.n = 4;
              break;
            case 15:
              // Al terminar el stream, si queda algo en el buffer, lo añadimos
              if (buffer.trim()) {
                speechQueue.push(buffer.trim());
                _speakNextInQueue();
              }
              _context.n = 17;
              break;
            case 16:
              _context.p = 16;
              _t2 = _context.v;
              console.error('Streaming error:', _t2);
            case 17:
              _context.p = 17;
              isStreaming = false;
              if (!isSpeaking && speechQueue.length === 0) {
                button.classList.remove('is-speaking');
              }
              return _context.f(17);
            case 18:
              return _context.a(2);
          }
        }, _callee, null, [[7, 12, 13, 14], [2, 16, 17, 18]]);
      }));
      return function startStreaming(_x) {
        return _ref.apply(this, arguments);
      };
    }();

    /**
     * Escuchar eventos del VoiceListener
     */
    document.addEventListener('psh-voice-input', function (e) {
      var text = e.detail.text;
      startStreaming(text);
    });

    /**
     * Botón de reproducción manual (mantiene compatibilidad)
     */
    button.addEventListener('click', function () {
      if (isSpeaking || isStreaming) {
        window.speechSynthesis.cancel();
        isSpeaking = false;
        isStreaming = false;
        speechQueue = [];
        button.classList.remove('is-speaking');
        return;
      }
      var text = contentDiv.innerText || contentDiv.textContent;
      if (text.trim()) {
        speechQueue.push(text.trim());
        _speakNextInQueue();
      }
    });
  });
});
})();

/******/ })()
;
//# sourceMappingURL=view.js.map