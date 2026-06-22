/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/ui/admin/ingestor/components/DashboardHeader.js"
/*!*************************************************************!*\
  !*** ./src/ui/admin/ingestor/components/DashboardHeader.js ***!
  \*************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Dashboard Header Component.
 */
var DashboardHeader = function DashboardHeader() {
  return /*#__PURE__*/React.createElement("div", {
    className: "babel-dashboard-header"
  }, /*#__PURE__*/React.createElement("p", {
    className: "description"
  }, "Upload documents, parse structures recursively, convert them to Markdown, and automatically index them inside your sovereign vector database."));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DashboardHeader);

/***/ },

/***/ "./src/ui/admin/ingestor/components/DragDropZone.js"
/*!**********************************************************!*\
  !*** ./src/ui/admin/ingestor/components/DragDropZone.js ***!
  \**********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
var useRef = wp.element.useRef;

/**
 * Drag & Drop Zone Component.
 * Supports directory drop traversal and file selection.
 */
var DragDropZone = function DragDropZone(_ref) {
  var onUploadTriggered = _ref.onUploadTriggered,
    disabled = _ref.disabled;
  var dropzoneRef = useRef(null);
  var fileSelectorRef = useRef(null);
  var folderSelectorRef = useRef(null);

  // Helper: Traverse folder files recursively
  var _traverseFileTree = function traverseFileTree(item) {
    var path = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
    return new Promise(function (resolve) {
      if (item.isFile) {
        item.file(function (file) {
          file.customRelativePath = path + file.name;
          resolve([file]);
        });
      } else if (item.isDirectory) {
        var dirReader = item.createReader();
        dirReader.readEntries(function (entries) {
          var promises = entries.map(function (entry) {
            return _traverseFileTree(entry, path + item.name + '/');
          });
          Promise.all(promises).then(function (results) {
            resolve(results.flat());
          });
        });
      } else {
        resolve([]);
      }
    });
  };
  var handleDrop = /*#__PURE__*/function () {
    var _ref2 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee(e) {
      var items, promises, i, item, fileGroups, allFiles;
      return _regenerator().w(function (_context) {
        while (1) switch (_context.n) {
          case 0:
            e.preventDefault();
            if (!disabled) {
              _context.n = 1;
              break;
            }
            return _context.a(2);
          case 1:
            items = e.dataTransfer.items;
            if (items) {
              _context.n = 2;
              break;
            }
            onUploadTriggered(Array.from(e.dataTransfer.files));
            return _context.a(2);
          case 2:
            promises = [];
            for (i = 0; i < items.length; i++) {
              item = items[i].webkitGetAsEntry();
              if (item) {
                promises.push(_traverseFileTree(item));
              }
            }
            _context.n = 3;
            return Promise.all(promises);
          case 3:
            fileGroups = _context.v;
            allFiles = fileGroups.flat();
            onUploadTriggered(allFiles);
          case 4:
            return _context.a(2);
        }
      }, _callee);
    }));
    return function handleDrop(_x) {
      return _ref2.apply(this, arguments);
    };
  }();
  var handleDragOver = function handleDragOver(e) {
    e.preventDefault();
    if (disabled) return;
    e.currentTarget.classList.add('hover');
  };
  var handleDragLeave = function handleDragLeave(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('hover');
  };
  return /*#__PURE__*/React.createElement("div", {
    ref: dropzoneRef,
    className: "babel-dropzone-area",
    onDragOver: handleDragOver,
    onDragLeave: handleDragLeave,
    onDrop: function onDrop(e) {
      handleDragLeave(e);
      handleDrop(e);
    },
    onClick: function onClick() {
      return !disabled && fileSelectorRef.current && fileSelectorRef.current.click();
    }
  }, /*#__PURE__*/React.createElement("span", {
    className: "dashicons dashicons-upload icon-upload"
  }), /*#__PURE__*/React.createElement("h2", null, "Drag & Drop files or folders here"), /*#__PURE__*/React.createElement("p", {
    className: "formats"
  }, "Supported formats: .md, .txt, .pdf, .docx"), /*#__PURE__*/React.createElement("div", {
    className: "babel-dropzone-actions",
    onClick: function onClick(e) {
      return e.stopPropagation();
    }
  }, /*#__PURE__*/React.createElement("button", {
    type: "button",
    className: "button button-primary",
    onClick: function onClick() {
      return fileSelectorRef.current && fileSelectorRef.current.click();
    },
    disabled: disabled
  }, "Select Files"), /*#__PURE__*/React.createElement("button", {
    type: "button",
    className: "button button-secondary",
    onClick: function onClick() {
      return folderSelectorRef.current && folderSelectorRef.current.click();
    },
    disabled: disabled
  }, "Select Folder")), /*#__PURE__*/React.createElement("input", {
    type: "file",
    ref: fileSelectorRef,
    multiple: true,
    style: {
      display: 'none'
    },
    accept: ".md,.txt,.pdf,.docx",
    onChange: function onChange(e) {
      onUploadTriggered(Array.from(e.target.files || []));
      e.target.value = '';
    },
    disabled: disabled
  }), /*#__PURE__*/React.createElement("input", {
    type: "file",
    ref: folderSelectorRef,
    webkitdirectory: "",
    directory: "",
    multiple: true,
    style: {
      display: 'none'
    },
    onChange: function onChange(e) {
      onUploadTriggered(Array.from(e.target.files || []));
      e.target.value = '';
    },
    disabled: disabled
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DragDropZone);

/***/ },

/***/ "./src/ui/admin/ingestor/components/LogConsole.js"
/*!********************************************************!*\
  !*** ./src/ui/admin/ingestor/components/LogConsole.js ***!
  \********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
var _wp$element = wp.element,
  useEffect = _wp$element.useEffect,
  useRef = _wp$element.useRef;

/**
 * Log Console Component.
 * Automatically scrolls to bottom when logs are updated.
 */
var LogConsole = function LogConsole(_ref) {
  var logs = _ref.logs,
    _ref$className = _ref.className,
    className = _ref$className === void 0 ? '' : _ref$className;
  var consoleRef = useRef(null);
  useEffect(function () {
    if (consoleRef.current) {
      consoleRef.current.scrollTop = consoleRef.current.scrollHeight;
    }
  }, [logs]);
  if (!logs || logs.length === 0) return null;
  return /*#__PURE__*/React.createElement("div", {
    className: "log-console ".concat(className),
    ref: consoleRef
  }, logs.map(function (log, index) {
    return /*#__PURE__*/React.createElement("div", {
      key: index,
      className: "log-line ".concat(log.type)
    }, log.message);
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (LogConsole);

/***/ },

/***/ "./src/ui/admin/ingestor/components/ProgressCard.js"
/*!**********************************************************!*\
  !*** ./src/ui/admin/ingestor/components/ProgressCard.js ***!
  \**********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LogConsole__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LogConsole */ "./src/ui/admin/ingestor/components/LogConsole.js");


/**
 * Progress Card Component.
 * Displays titles, custom progress bars, and scrolling consoles.
 */
var ProgressCard = function ProgressCard(_ref) {
  var title = _ref.title,
    progress = _ref.progress,
    logs = _ref.logs,
    completed = _ref.completed,
    completionMessage = _ref.completionMessage,
    _ref$progressBarColor = _ref.progressBarColor,
    progressBarColor = _ref$progressBarColor === void 0 ? '#46b450' : _ref$progressBarColor;
  return /*#__PURE__*/React.createElement("div", {
    className: "babel-dashboard-card progress-card"
  }, /*#__PURE__*/React.createElement("div", {
    className: "progress-header"
  }, /*#__PURE__*/React.createElement("h3", null, title), /*#__PURE__*/React.createElement("span", {
    className: "percentage",
    style: {
      color: progressBarColor
    }
  }, progress, "%")), /*#__PURE__*/React.createElement("div", {
    className: "progress-bar-container"
  }, /*#__PURE__*/React.createElement("div", {
    className: "progress-bar",
    style: {
      width: "".concat(progress, "%"),
      backgroundColor: progressBarColor
    }
  })), /*#__PURE__*/React.createElement(_LogConsole__WEBPACK_IMPORTED_MODULE_0__["default"], {
    logs: logs
  }), completed && /*#__PURE__*/React.createElement("div", {
    className: "completion-badge"
  }, /*#__PURE__*/React.createElement("span", {
    className: "dashicons dashicons-yes-alt"
  }), completionMessage));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ProgressCard);

/***/ },

/***/ "./src/ui/admin/ingestor/components/Reducer.js"
/*!*****************************************************!*\
  !*** ./src/ui/admin/ingestor/components/Reducer.js ***!
  \*****************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var useReducer = wp.element.useReducer;

// 1. Initial State Definition
var initialState = {
  rootData: {},
  pendingFiles: [],
  taggedPostsCount: 0,
  // Upload State
  uploading: false,
  uploadProgress: 0,
  uploadLogs: [],
  // Ingest State
  ingesting: false,
  ingestProgress: 0,
  ingestLogs: [],
  ingestCompleted: false,
  // WP Sync State
  syncingWP: false,
  wpSyncLogs: []
};

// 2. State Reducer Definition (Deterministic State Flows)
var ingestorReducer = function ingestorReducer(state, action) {
  switch (action.type) {
    case 'INITIALIZE':
      return _objectSpread(_objectSpread({}, state), {}, {
        rootData: action.payload.rootData,
        pendingFiles: action.payload.pendingFiles,
        taggedPostsCount: action.payload.taggedPostsCount
      });

    // Upload Actions
    case 'UPLOAD_START':
      return _objectSpread(_objectSpread({}, state), {}, {
        uploading: true,
        uploadProgress: 0,
        uploadLogs: [],
        ingestProgress: 0,
        ingestLogs: [],
        ingestCompleted: false
      });
    case 'ADD_UPLOAD_LOG':
      return _objectSpread(_objectSpread({}, state), {}, {
        uploadLogs: [].concat(_toConsumableArray(state.uploadLogs), [action.payload])
      });
    case 'UPDATE_LAST_UPLOAD_LOG':
      return _objectSpread(_objectSpread({}, state), {}, {
        uploadLogs: state.uploadLogs.map(function (log, index) {
          return index === state.uploadLogs.length - 1 ? action.payload : log;
        })
      });
    case 'SET_UPLOAD_PROGRESS':
      return _objectSpread(_objectSpread({}, state), {}, {
        uploadProgress: action.payload
      });
    case 'UPLOAD_COMPLETE':
      {
        var newFiles = action.payload && action.payload.newFiles ? action.payload.newFiles : [];
        var updatedPendingFiles = Array.from(new Set([].concat(_toConsumableArray(state.pendingFiles), _toConsumableArray(newFiles))));
        return _objectSpread(_objectSpread({}, state), {}, {
          uploading: false,
          pendingFiles: updatedPendingFiles
        });
      }

    // Ingestion Actions
    case 'INGEST_START':
      return _objectSpread(_objectSpread({}, state), {}, {
        ingesting: true,
        ingestProgress: 0,
        ingestLogs: [],
        ingestCompleted: false
      });
    case 'ADD_INGEST_LOG':
      return _objectSpread(_objectSpread({}, state), {}, {
        ingestLogs: [].concat(_toConsumableArray(state.ingestLogs), [action.payload])
      });
    case 'UPDATE_LAST_INGEST_LOG':
      return _objectSpread(_objectSpread({}, state), {}, {
        ingestLogs: state.ingestLogs.map(function (log, index) {
          return index === state.ingestLogs.length - 1 ? action.payload : log;
        })
      });
    case 'SET_INGEST_PROGRESS':
      return _objectSpread(_objectSpread({}, state), {}, {
        ingestProgress: action.payload
      });
    case 'INGEST_COMPLETE':
      return _objectSpread(_objectSpread({}, state), {}, {
        ingesting: false,
        ingestCompleted: true,
        pendingFiles: []
      });

    // WP Sync Actions
    case 'WP_SYNC_START':
      return _objectSpread(_objectSpread({}, state), {}, {
        syncingWP: true,
        wpSyncLogs: [action.payload]
      });
    case 'ADD_WP_SYNC_LOG':
      return _objectSpread(_objectSpread({}, state), {}, {
        wpSyncLogs: [].concat(_toConsumableArray(state.wpSyncLogs), [action.payload])
      });
    case 'WP_SYNC_COMPLETE':
      return _objectSpread(_objectSpread({}, state), {}, {
        syncingWP: false,
        taggedPostsCount: action.payload.success ? 0 : state.taggedPostsCount
      });
    default:
      return state;
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (function () {
  return useReducer(ingestorReducer, initialState);
});
__webpack_require__.dn(__WEBPACK_DEFAULT_EXPORT__);

/***/ },

/***/ "./src/ui/admin/ingestor/components/StorageInbox.js"
/*!**********************************************************!*\
  !*** ./src/ui/admin/ingestor/components/StorageInbox.js ***!
  \**********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/**
 * Storage Inbox Component.
 * Displays list of raw pending files and action buttons to process them.
 */
var StorageInbox = function StorageInbox(_ref) {
  var pendingFiles = _ref.pendingFiles,
    onIngestTriggered = _ref.onIngestTriggered,
    disabled = _ref.disabled;
  return /*#__PURE__*/React.createElement("div", {
    className: "babel-dashboard-card"
  }, /*#__PURE__*/React.createElement("h2", {
    className: "card-title"
  }, "Babel Storage Inbox (raw/)"), pendingFiles.length === 0 ? /*#__PURE__*/React.createElement("div", {
    className: "empty-state"
  }, /*#__PURE__*/React.createElement("span", {
    className: "dashicons dashicons-yes-alt success-icon"
  }), /*#__PURE__*/React.createElement("p", null, "No pending files in raw storage. Upload some files or folders to begin.")) : /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("div", {
    className: "babel-file-tree"
  }, /*#__PURE__*/React.createElement("ul", null, pendingFiles.map(function (file, index) {
    return /*#__PURE__*/React.createElement("li", {
      key: index
    }, /*#__PURE__*/React.createElement("span", {
      className: "file-info"
    }, /*#__PURE__*/React.createElement("span", {
      className: "dashicons ".concat(file.endsWith('.pdf') ? 'dashicons-pdf' : 'dashicons-document')
    }), file), /*#__PURE__*/React.createElement("span", {
      className: "file-ext"
    }, file.split('.').pop().toUpperCase()));
  }))), /*#__PURE__*/React.createElement("div", {
    className: "card-actions"
  }, /*#__PURE__*/React.createElement("button", {
    type: "button",
    className: "button button-primary button-large",
    onClick: onIngestTriggered,
    disabled: disabled
  }, "Process and Convert Inbox (Asynchronously)"), /*#__PURE__*/React.createElement("span", {
    className: "action-desc"
  }, pendingFiles.length, " ", pendingFiles.length === 1 ? 'file' : 'files', ' ', "ready to be converted into Markdown and vectorized chunk-by-chunk.")), /*#__PURE__*/React.createElement("p", {
    style: {
      fontSize: '11px',
      color: '#888',
      marginTop: '10px',
      fontStyle: 'italic'
    }
  }, "Note: Processing runs on a secure, single-request AJAX queue to guarantee zero server timeouts even on very large corpus books.")));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (StorageInbox);

/***/ },

/***/ "./src/ui/admin/ingestor/components/WpContentSync.js"
/*!***********************************************************!*\
  !*** ./src/ui/admin/ingestor/components/WpContentSync.js ***!
  \***********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LogConsole__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LogConsole */ "./src/ui/admin/ingestor/components/LogConsole.js");


/**
 * WordPress Content Sync Component.
 */
var WpContentSync = function WpContentSync(_ref) {
  var taggedPostsCount = _ref.taggedPostsCount,
    onSyncTriggered = _ref.onSyncTriggered,
    syncingWP = _ref.syncingWP,
    wpSyncLogs = _ref.wpSyncLogs,
    disabled = _ref.disabled;
  return /*#__PURE__*/React.createElement("div", {
    className: "babel-dashboard-card"
  }, /*#__PURE__*/React.createElement("h2", {
    className: "card-title"
  }, "WordPress Content Sync"), /*#__PURE__*/React.createElement("p", {
    className: "card-desc"
  }, "Synchronize WordPress posts, pages, and custom post types tagged with \"babel\" (including published, private, and scheduled/future content)."), /*#__PURE__*/React.createElement("div", {
    className: "sync-status-box ".concat(taggedPostsCount > 0 ? 'pending' : 'synced')
  }, /*#__PURE__*/React.createElement("div", {
    className: "status-message"
  }, taggedPostsCount > 0 ? /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("span", {
    className: "message-label"
  }, "Pending/Modified Documents:"), /*#__PURE__*/React.createElement("span", {
    className: "count-badge"
  }, taggedPostsCount)) : /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("span", {
    className: "dashicons dashicons-yes-alt success-icon"
  }), /*#__PURE__*/React.createElement("span", null, "All documents are fully synchronized!"))), /*#__PURE__*/React.createElement("div", {
    className: "status-action"
  }, /*#__PURE__*/React.createElement("button", {
    type: "button",
    className: "button button-secondary",
    onClick: onSyncTriggered,
    disabled: taggedPostsCount === 0 || syncingWP || disabled
  }, syncingWP ? 'Synchronizing...' : 'Sync Tagged WordPress Content'))), /*#__PURE__*/React.createElement(_LogConsole__WEBPACK_IMPORTED_MODULE_0__["default"], {
    logs: wpSyncLogs,
    className: "wp-sync-console",
    style: {
      marginTop: '15px'
    }
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (WpContentSync);

/***/ },

/***/ "./src/ui/admin/ingestor/main.scss"
/*!*****************************************!*\
  !*** ./src/ui/admin/ingestor/main.scss ***!
  \*****************************************/
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
/*!***************************************!*\
  !*** ./src/ui/admin/ingestor/main.js ***!
  \***************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _components_DashboardHeader__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/DashboardHeader */ "./src/ui/admin/ingestor/components/DashboardHeader.js");
/* harmony import */ var _components_DragDropZone__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./components/DragDropZone */ "./src/ui/admin/ingestor/components/DragDropZone.js");
/* harmony import */ var _components_ProgressCard__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/ProgressCard */ "./src/ui/admin/ingestor/components/ProgressCard.js");
/* harmony import */ var _components_StorageInbox__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/StorageInbox */ "./src/ui/admin/ingestor/components/StorageInbox.js");
/* harmony import */ var _components_WpContentSync__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/WpContentSync */ "./src/ui/admin/ingestor/components/WpContentSync.js");
/* harmony import */ var _components_Reducer__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/Reducer */ "./src/ui/admin/ingestor/components/Reducer.js");
/* harmony import */ var _main_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./main.scss */ "./src/ui/admin/ingestor/main.scss");
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }
var useEffect = wp.element.useEffect;







var createRoot = wp.element.createRoot;

/**
 * Main Babel Ingestor Application Controller (Reducer orchestrated).
 */
var IngestorApp = function IngestorApp() {
  var _reducer = (0,_components_Reducer__WEBPACK_IMPORTED_MODULE_5__["default"])(),
    _reducer2 = _slicedToArray(_reducer, 2),
    state = _reducer2[0],
    dispatch = _reducer2[1];
  var rootData = state.rootData,
    pendingFiles = state.pendingFiles,
    taggedPostsCount = state.taggedPostsCount,
    uploading = state.uploading,
    uploadProgress = state.uploadProgress,
    uploadLogs = state.uploadLogs,
    ingesting = state.ingesting,
    ingestProgress = state.ingestProgress,
    ingestLogs = state.ingestLogs,
    ingestCompleted = state.ingestCompleted,
    syncingWP = state.syncingWP,
    wpSyncLogs = state.wpSyncLogs;

  // Load Initial Configuration from HTML Mount Node
  useEffect(function () {
    var node = document.getElementById('babel-ingestor-root');
    if (node) {
      var files = JSON.parse(node.getAttribute('data-pending-files') || '[]');
      var postsCount = parseInt(node.getAttribute('data-tagged-posts-count') || '0', 10);
      dispatch({
        type: 'INITIALIZE',
        payload: {
          rootData: {
            rawDirPath: node.getAttribute('data-raw-dir-path') || '',
            apiUploadUrl: node.getAttribute('data-api-upload-url') || '',
            apiIngestUrl: node.getAttribute('data-api-ingest-url') || '',
            nonce: node.getAttribute('data-nonce') || '',
            convertedPostsDir: node.getAttribute('data-converted-posts-dir') || ''
          },
          pendingFiles: files,
          taggedPostsCount: postsCount
        }
      });
    }
  }, []);

  // Upload Process Coordinator
  var handleUpload = /*#__PURE__*/function () {
    var _ref = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee(files) {
      var uploadedCount, totalFiles, successfulUploads, i, file, relativePath, formData, response, result, uploadedPath, errorMsg, _t;
      return _regenerator().w(function (_context) {
        while (1) switch (_context.p = _context.n) {
          case 0:
            dispatch({
              type: 'UPLOAD_START'
            });
            uploadedCount = 0;
            totalFiles = files.length;
            successfulUploads = [];
            i = 0;
          case 1:
            if (!(i < totalFiles)) {
              _context.n = 8;
              break;
            }
            file = files[i];
            relativePath = file.customRelativePath || file.webkitRelativePath || file.name;
            dispatch({
              type: 'ADD_UPLOAD_LOG',
              payload: {
                type: 'pending',
                message: "\u23F3 Uploading (".concat(i + 1, "/").concat(totalFiles, "): ").concat(relativePath, "...")
              }
            });
            _context.p = 2;
            formData = new FormData();
            formData.append('file', file);
            formData.append('relative_path', relativePath);
            _context.n = 3;
            return fetch(rootData.apiUploadUrl, {
              method: 'POST',
              headers: {
                'X-WP-Nonce': rootData.nonce
              },
              body: formData
            });
          case 3:
            response = _context.v;
            _context.n = 4;
            return response.json();
          case 4:
            result = _context.v;
            if (response.ok && result.success) {
              dispatch({
                type: 'UPDATE_LAST_UPLOAD_LOG',
                payload: {
                  type: 'success',
                  message: "\u2713 Successfully uploaded: ".concat(relativePath)
                }
              });
              uploadedPath = result.data && result.data.relative_path ? result.data.relative_path : relativePath;
              successfulUploads.push(uploadedPath);
            } else {
              errorMsg = result.error ? result.error.message : 'Unknown error';
              dispatch({
                type: 'UPDATE_LAST_UPLOAD_LOG',
                payload: {
                  type: 'error',
                  message: "\u2717 Error uploading ".concat(relativePath, ": ").concat(errorMsg)
                }
              });
            }
            _context.n = 6;
            break;
          case 5:
            _context.p = 5;
            _t = _context.v;
            dispatch({
              type: 'UPDATE_LAST_UPLOAD_LOG',
              payload: {
                type: 'error',
                message: "\u2717 Connection Failed for ".concat(relativePath)
              }
            });
          case 6:
            uploadedCount++;
            dispatch({
              type: 'SET_UPLOAD_PROGRESS',
              payload: Math.round(uploadedCount / totalFiles * 100)
            });
          case 7:
            i++;
            _context.n = 1;
            break;
          case 8:
            // Completed, notify state to show completed status and update files
            setTimeout(function () {
              dispatch({
                type: 'UPLOAD_COMPLETE',
                payload: {
                  newFiles: successfulUploads
                }
              });
            }, 1000);
          case 9:
            return _context.a(2);
        }
      }, _callee, null, [[2, 5]]);
    }));
    return function handleUpload(_x) {
      return _ref.apply(this, arguments);
    };
  }();

  // Asynchronous Document-by-Document Ingestion Queue (The Timeout-Killer)
  var handleIngest = /*#__PURE__*/function () {
    var _ref2 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee2() {
      var processedCount, totalFiles, i, file, response, result, errorMsg, _t2;
      return _regenerator().w(function (_context2) {
        while (1) switch (_context2.p = _context2.n) {
          case 0:
            dispatch({
              type: 'INGEST_START'
            });
            processedCount = 0;
            totalFiles = pendingFiles.length;
            i = 0;
          case 1:
            if (!(i < totalFiles)) {
              _context2.n = 8;
              break;
            }
            file = pendingFiles[i];
            dispatch({
              type: 'ADD_INGEST_LOG',
              payload: {
                type: 'pending',
                message: "\u23F3 Processing (".concat(i + 1, "/").concat(totalFiles, "): ").concat(file, "...")
              }
            });
            _context2.p = 2;
            _context2.n = 3;
            return fetch(rootData.apiIngestUrl, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': rootData.nonce
              },
              body: JSON.stringify({
                file: file
              })
            });
          case 3:
            response = _context2.v;
            _context2.n = 4;
            return response.json();
          case 4:
            result = _context2.v;
            if (response.ok && result.success) {
              dispatch({
                type: 'UPDATE_LAST_INGEST_LOG',
                payload: {
                  type: 'success',
                  message: "\u2713 Fully parsed, converted, and indexed in ChromaDB: ".concat(file)
                }
              });
            } else {
              errorMsg = result.error ? result.error.message : 'Unknown error';
              dispatch({
                type: 'UPDATE_LAST_INGEST_LOG',
                payload: {
                  type: 'error',
                  message: "\u2717 Failed to process ".concat(file, ": ").concat(errorMsg)
                }
              });
            }
            _context2.n = 6;
            break;
          case 5:
            _context2.p = 5;
            _t2 = _context2.v;
            dispatch({
              type: 'UPDATE_LAST_INGEST_LOG',
              payload: {
                type: 'error',
                message: "\u2717 Network/Timeout error processing ".concat(file)
              }
            });
          case 6:
            processedCount++;
            dispatch({
              type: 'SET_INGEST_PROGRESS',
              payload: Math.round(processedCount / totalFiles * 100)
            });
          case 7:
            i++;
            _context2.n = 1;
            break;
          case 8:
            dispatch({
              type: 'INGEST_COMPLETE'
            });
          case 9:
            return _context2.a(2);
        }
      }, _callee2, null, [[2, 5]]);
    }));
    return function handleIngest() {
      return _ref2.apply(this, arguments);
    };
  }();

  // Async WordPress Content Synchronization
  var handleWpSync = /*#__PURE__*/function () {
    var _ref3 = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee3() {
      var apiSyncUrl, response, result, data, failedList, errorMsg, _t3;
      return _regenerator().w(function (_context3) {
        while (1) switch (_context3.p = _context3.n) {
          case 0:
            dispatch({
              type: 'WP_SYNC_START',
              payload: {
                type: 'pending',
                message: '⏳ Initializing WordPress Content Sync and Chunking...'
              }
            });
            _context3.p = 1;
            apiSyncUrl = rootData.apiUploadUrl.replace('/upload', '/sync-posts');
            _context3.n = 2;
            return fetch(apiSyncUrl, {
              method: 'POST',
              headers: {
                'X-WP-Nonce': rootData.nonce
              }
            });
          case 2:
            response = _context3.v;
            _context3.n = 3;
            return response.json();
          case 3:
            result = _context3.v;
            if (response.ok && result.success) {
              data = result.data;
              failedList = data.failed || [];
              dispatch({
                type: 'ADD_WP_SYNC_LOG',
                payload: {
                  type: 'success',
                  message: "\u2713 Successfully converted & synchronized ".concat(data.success, " WordPress posts/pages!")
                }
              });
              if (failedList.length > 0) {
                failedList.forEach(function (item) {
                  dispatch({
                    type: 'ADD_WP_SYNC_LOG',
                    payload: {
                      type: 'error',
                      message: "\u2717 Post ID ".concat(item.id, " failed: ").concat(item.message)
                    }
                  });
                });
              }
              dispatch({
                type: 'WP_SYNC_COMPLETE',
                payload: {
                  success: true
                }
              });
            } else {
              errorMsg = result.error ? result.error.message : 'Unknown error';
              dispatch({
                type: 'ADD_WP_SYNC_LOG',
                payload: {
                  type: 'error',
                  message: "\u2717 WordPress Sync Failed: ".concat(errorMsg)
                }
              });
              dispatch({
                type: 'WP_SYNC_COMPLETE',
                payload: {
                  success: false
                }
              });
            }
            _context3.n = 5;
            break;
          case 4:
            _context3.p = 4;
            _t3 = _context3.v;
            dispatch({
              type: 'ADD_WP_SYNC_LOG',
              payload: {
                type: 'error',
                message: '✗ Connection or Server timeout during WordPress Sync.'
              }
            });
            dispatch({
              type: 'WP_SYNC_COMPLETE',
              payload: {
                success: false
              }
            });
          case 5:
            return _context3.a(2);
        }
      }, _callee3, null, [[1, 4]]);
    }));
    return function handleWpSync() {
      return _ref3.apply(this, arguments);
    };
  }();
  var globalDisabled = uploading || ingesting || syncingWP;
  return /*#__PURE__*/React.createElement("div", {
    className: "babel-ingestion-dashboard"
  }, /*#__PURE__*/React.createElement(_components_DashboardHeader__WEBPACK_IMPORTED_MODULE_0__["default"], null), /*#__PURE__*/React.createElement(_components_DragDropZone__WEBPACK_IMPORTED_MODULE_1__["default"], {
    onUploadTriggered: handleUpload,
    disabled: globalDisabled
  }), (uploading || uploadLogs.length > 0) && /*#__PURE__*/React.createElement(_components_ProgressCard__WEBPACK_IMPORTED_MODULE_2__["default"], {
    title: "Uploading Files to Raw Storage...",
    progress: uploadProgress,
    logs: uploadLogs,
    completed: !uploading && uploadProgress === 100,
    completionMessage: "All files successfully uploaded to raw storage!",
    progressBarColor: "#007cba"
  }), (ingesting || ingestLogs.length > 0) && /*#__PURE__*/React.createElement(_components_ProgressCard__WEBPACK_IMPORTED_MODULE_2__["default"], {
    title: "Vectorizing Knowledge Inbox...",
    progress: ingestProgress,
    logs: ingestLogs,
    completed: ingestCompleted,
    completionMessage: "All files successfully processed, converted and indexed in ChromaDB!",
    progressBarColor: "#334155"
  }), /*#__PURE__*/React.createElement(_components_StorageInbox__WEBPACK_IMPORTED_MODULE_3__["default"], {
    pendingFiles: pendingFiles,
    onIngestTriggered: handleIngest,
    disabled: globalDisabled
  }), /*#__PURE__*/React.createElement(_components_WpContentSync__WEBPACK_IMPORTED_MODULE_4__["default"], {
    taggedPostsCount: taggedPostsCount,
    onSyncTriggered: handleWpSync,
    syncingWP: syncingWP,
    wpSyncLogs: wpSyncLogs,
    disabled: globalDisabled
  }));
};
var container = document.getElementById('babel-ingestor-root');
if (container) {
  var root = createRoot(container);
  root.render(/*#__PURE__*/React.createElement(IngestorApp, null));
}
})();

/******/ })()
;
//# sourceMappingURL=main.js.map