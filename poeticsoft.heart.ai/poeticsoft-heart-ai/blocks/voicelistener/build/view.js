/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/block/voicelistener/view.scss"
/*!*******************************************!*\
  !*** ./src/block/voicelistener/view.scss ***!
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
/*!*****************************************!*\
  !*** ./src/block/voicelistener/view.js ***!
  \*****************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _view_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./view.scss */ "./src/block/voicelistener/view.scss");

document.addEventListener('DOMContentLoaded', function () {
  var containers = document.querySelectorAll('.wp-block-poeticsoft-heart-ai-voicelistener');
  console.log(containers);
  containers.forEach(function (container) {
    var button = container.querySelector('.pshai-voice-btn');
    var output = container.querySelector('.pshai-voice-output');
    var status = container.querySelector('.pshai-voice-status');
    var isListening = false;
    var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
      if (status) status.textContent = 'Speech API no soportada.';
      if (button) button.disabled = true;
      return;
    }
    var recognition = new SpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'es-ES';
    recognition.onresult = function (event) {
      var interimTranscript = '';
      var finalTranscript = '';
      for (var i = event.resultIndex; i < event.results.length; ++i) {
        if (event.results[i].isFinal) {
          finalTranscript += event.results[i][0].transcript;
        } else {
          interimTranscript += event.results[i][0].transcript;
        }
      }
      if (output) {
        output.value = finalTranscript + interimTranscript;
      }
    };
    recognition.onstart = function () {
      isListening = true;
      button.classList.add('is-listening');
      if (status) status.textContent = 'Escuchando...';
    };
    recognition.onend = function () {
      isListening = false;
      button.classList.remove('is-listening');
      if (status) status.textContent = '';

      // Al finalizar la escucha, emitimos el texto completo
      var finalText = output.value.trim();
      if (finalText) {
        document.dispatchEvent(new CustomEvent('psh-voice-input', {
          detail: {
            text: finalText,
            source: container.id
          }
        }));
      }
    };
    recognition.onerror = function (event) {
      console.error('Speech recognition error', event.error);
      if (status) status.textContent = 'Error: ' + event.error;
    };
    button.addEventListener('click', function () {
      if (isListening) {
        recognition.stop();
      } else {
        recognition.start();
      }
    });
  });
});
})();

/******/ })()
;
//# sourceMappingURL=view.js.map