/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/form/index.js":
/*!************************************!*\
  !*** ./resources/js/form/index.js ***!
  \************************************/
/***/ ((module) => {

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

;

(function (factory) {
  module.exports = factory(jQuery, i18n);
})(function ($, i18n) {
  'use strict';

  $("#async").click(function (e) {
    e.preventDefault();
    removeValidationErrorMessage();
    removeAlertMessage();
    var btn = document.getElementById('async');
    var method = btn.dataset.method; //CRUD  

    var elements = document.forms[method].elements;
    var url = document.forms[method].action;
    var type = btn.dataset.type;
    var message = buildCompletionMessage(type); // パラメータ作成

    var parameters = buildParameters(elements);
    $(document).ajaxSend(function () {
      showIndicator();
    });
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      url: url,
      type: type,
      contentType: "application/json",
      data: JSON.stringify(parameters),
      dataType: "json",
      timeout: 120000,
      cache: false
    }).done(function (data) {
      alertMessage(message);
    }).fail(function (error) {
      switch (error.status) {
        case 0:
          alert('レスポンスタイムアウト。処理は完了している可能性があります。画面をリロードし、確認後再度実行してみてください。');
          break;

        case 400:
          var result = JSON.parse(error.responseText);
          displayValidationErrorMessage(result.errors);
          break;

        case 500:
          alert('エラーが発生しました。再度生じる場合は管理者まで報告をお願いします。');
          break;

        default:
          alert('エラーが発生しました。再度生じる場合は管理者まで報告をお願いします。');
      }
    }).always(function () {
      hideIndicator();
    });
  });

  function showIndicator() {
    $("#overlay").fadeIn(100);
  }

  function hideIndicator() {
    setTimeout(function () {
      $("#overlay").fadeOut(100);
    }, 50);
  }

  function buildParameters(elements) {
    var parameters = {};

    var _iterator = _createForOfIteratorHelper(elements),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var element = _step.value;
        parameters[element.name] = element.value;
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }

    return parameters;
  }

  function displayValidationErrorMessage(errors) {
    errors.forEach(function (error) {
      var key = error.key;
      var message = error.message.translated;
      var errorMessageElement = '<small class="text-danger error-message">' + message + '</small>';
      $(errorMessageElement).insertAfter('#' + key);
    });
  }

  function removeValidationErrorMessage() {
    var errorMessageElements = document.getElementsByClassName('error-message');
    var length = errorMessageElements.length;

    for (var i = 0; i < length; i++) {
      var errorMessageElement = errorMessageElements[0];
      errorMessageElement.remove();
    }
  }

  function alertMessage(message) {
    var alertMessageElement = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' + message + '</strong> </div>';
    $(alertMessageElement).insertAfter('#alert-message');
  }

  function removeAlertMessage() {
    var alertMessageElements = document.getElementsByClassName('alert');

    if (alertMessageElements.length > 0) {
      alertMessageElements[0].remove();
    }
  }

  function buildCompletionMessage(type) {
    switch (type.toLowerCase()) {
      case 'put':
        return '更新しました。';
        break;

      case 'post':
        return '登録しました。';
        break;

      case 'delete':
        return '削除しました。';
        break;

      default:
        return '';
    }
  }
});

/***/ })

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
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/js/form/index.js");
/******/ 	
/******/ })()
;