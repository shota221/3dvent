/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/ladda/js/ladda.js":
/*!****************************************!*\
  !*** ./node_modules/ladda/js/ladda.js ***!
  \****************************************/
/*! exports provided: create, bind, stopAll */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "create", function() { return create; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "bind", function() { return bind; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "stopAll", function() { return stopAll; });
/* harmony import */ var spin_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! spin.js */ "./node_modules/spin.js/spin.js");
/*!
 * Ladda
 * http://lab.hakim.se/ladda
 * MIT licensed
 *
 * Copyright (C) 2018 Hakim El Hattab, http://hakim.se
 */

 

// All currently instantiated instances of Ladda
var ALL_INSTANCES = [];

/**
 * Creates a new instance of Ladda which wraps the
 * target button element.
 *
 * @return An API object that can be used to control
 * the loading animation state.
 */
function create( button ) {

	if( typeof button === 'undefined' ) {
		console.warn( "Ladda button target must be defined." );
		return;
	}

	// The button must have the class "ladda-button"
	if ( !button.classList.contains('ladda-button') ) {
		button.classList.add( 'ladda-button' );
	}

	// Style is required, default to "expand-right"
	if( !button.hasAttribute( 'data-style' ) ) {
		button.setAttribute( 'data-style', 'expand-right' );
	}

	// The text contents must be wrapped in a ladda-label
	// element, create one if it doesn't already exist
	if( !button.querySelector( '.ladda-label' ) ) {
		var laddaLabel = document.createElement( 'span' );
		laddaLabel.className = 'ladda-label';
		wrapContent( button, laddaLabel );
	}

	// The spinner component
	var spinner,
		spinnerWrapper = button.querySelector( '.ladda-spinner' );

	// Wrapper element for the spinner
	if( !spinnerWrapper ) {
		spinnerWrapper = document.createElement( 'span' );
		spinnerWrapper.className = 'ladda-spinner';
	}

	button.appendChild( spinnerWrapper );

	// Timer used to delay starting/stopping
	var timer;

	var instance = {

		/**
		 * Enter the loading state.
		 */
		start: function() {

			// Create the spinner if it doesn't already exist
			if( !spinner ) {
				spinner = createSpinner( button );
			}

			button.disabled = true;
			button.setAttribute( 'data-loading', '' );

			clearTimeout( timer );
			spinner.spin( spinnerWrapper );

			this.setProgress( 0 );

			return this; // chain

		},

		/**
		 * Enter the loading state, after a delay.
		 */
		startAfter: function( delay ) {

			clearTimeout( timer );
			timer = setTimeout( function() { instance.start(); }, delay );

			return this; // chain

		},

		/**
		 * Exit the loading state.
		 */
		stop: function() {

			if (instance.isLoading()) {
				button.disabled = false;
				button.removeAttribute( 'data-loading' );	
			}

			// Kill the animation after a delay to make sure it
			// runs for the duration of the button transition
			clearTimeout( timer );

			if( spinner ) {
				timer = setTimeout( function() { spinner.stop(); }, 1000 );
			}

			return this; // chain

		},

		/**
		 * Toggle the loading state on/off.
		 */
		toggle: function() {
			return this.isLoading() ? this.stop() : this.start();
		},

		/**
		 * Sets the width of the visual progress bar inside of
		 * this Ladda button
		 *
		 * @param {Number} progress in the range of 0-1
		 */
		setProgress: function( progress ) {

			// Cap it
			progress = Math.max( Math.min( progress, 1 ), 0 );

			var progressElement = button.querySelector( '.ladda-progress' );

			// Remove the progress bar if we're at 0 progress
			if( progress === 0 && progressElement && progressElement.parentNode ) {
				progressElement.parentNode.removeChild( progressElement );
			}
			else {
				if( !progressElement ) {
					progressElement = document.createElement( 'div' );
					progressElement.className = 'ladda-progress';
					button.appendChild( progressElement );
				}

				progressElement.style.width = ( ( progress || 0 ) * button.offsetWidth ) + 'px';
			}

		},

		isLoading: function() {

			return button.hasAttribute( 'data-loading' );

		},

		remove: function() {

			clearTimeout( timer );

			button.disabled = false;
			button.removeAttribute( 'data-loading' );

			if( spinner ) {
				spinner.stop();
				spinner = null;
			}

			ALL_INSTANCES.splice( ALL_INSTANCES.indexOf(instance), 1 );

		}

	};

	ALL_INSTANCES.push( instance );

	return instance;

}

/**
 * Binds the target buttons to automatically enter the
 * loading state when clicked.
 *
 * @param target Either an HTML element or a CSS selector.
 * @param options
 *          - timeout Number of milliseconds to wait before
 *            automatically cancelling the animation.
 *          - callback A function to be called with the Ladda
 *            instance when a target button is clicked.
 */
function bind( target, options ) {

	var targets;

	if( typeof target === 'string' ) {
		targets = document.querySelectorAll( target );
	}
	else if( typeof target === 'object' ) {
		targets = [ target ];
	} else {
		throw new Error('target must be string or object');
	}

	options = options || {};

	for( var i = 0; i < targets.length; i++ ) {
		bindElement(targets[i], options);
	}

}

/**
 * Stops ALL current loading animations.
 */
function stopAll() {

	for( var i = 0, len = ALL_INSTANCES.length; i < len; i++ ) {
		ALL_INSTANCES[i].stop();
	}

}

/**
* Get the first ancestor node from an element, having a
* certain type.
*
* @param elem An HTML element
* @param type an HTML tag type (uppercased)
*
* @return An HTML element
*/
function getAncestorOfTagType( elem, type ) {

	while ( elem.parentNode && elem.tagName !== type ) {
		elem = elem.parentNode;
	}

	return ( type === elem.tagName ) ? elem : undefined;

}

function createSpinner( button ) {

	var height = button.offsetHeight,
		spinnerColor,
		spinnerLines;

	if( height === 0 ) {
		// We may have an element that is not visible so
		// we attempt to get the height in a different way
		height = parseFloat( window.getComputedStyle( button ).height );
	}

	// If the button is tall we can afford some padding
	if( height > 32 ) {
		height *= 0.8;
	}

	// Prefer an explicit height if one is defined
	if( button.hasAttribute( 'data-spinner-size' ) ) {
		height = parseInt( button.getAttribute( 'data-spinner-size' ), 10 );
	}

	// Allow buttons to specify the color of the spinner element
	if( button.hasAttribute( 'data-spinner-color' ) ) {
		spinnerColor = button.getAttribute( 'data-spinner-color' );
	}

	// Allow buttons to specify the number of lines of the spinner
	if( button.hasAttribute( 'data-spinner-lines' ) ) {
		spinnerLines = parseInt( button.getAttribute( 'data-spinner-lines' ), 10 );
	}

	var radius = height * 0.2,
		length = radius * 0.6,
		width = radius < 7 ? 2 : 3;

	return new spin_js__WEBPACK_IMPORTED_MODULE_0__["Spinner"]( {
		color: spinnerColor || '#fff',
		lines: spinnerLines || 12,
		radius: radius,
		length: length,
		width: width,
		animation: 'ladda-spinner-line-fade',
		zIndex: 'auto',
		top: 'auto',
		left: 'auto',
		className: ''
	} );

}

function wrapContent( node, wrapper ) {

	var r = document.createRange();
	r.selectNodeContents( node );
	r.surroundContents( wrapper );
	node.appendChild( wrapper );

}

function bindElement( element, options ) {
	if( typeof element.addEventListener !== 'function' ) {
		return;
	}

	var instance = create( element );
	var timeout = -1;

	element.addEventListener( 'click', function() {

		// If the button belongs to a form, make sure all the
		// fields in that form are filled out
		var valid = true;
		var form = getAncestorOfTagType( element, 'FORM' );

		if( typeof form !== 'undefined' && !form.hasAttribute('novalidate') ) {
			// Modern form validation
			if( typeof form.checkValidity === 'function' ) {
				valid = form.checkValidity();
			}
		}

		if( valid ) {
			// This is asynchronous to avoid an issue where disabling
			// the button prevents forms from submitting
			instance.startAfter( 1 );

			// Set a loading timeout if one is specified
			if( typeof options.timeout === 'number' ) {
				clearTimeout( timeout );
				timeout = setTimeout( instance.stop, options.timeout );
			}

			// Invoke callbacks
			if( typeof options.callback === 'function' ) {
				options.callback.apply( null, [ instance ] );
			}
		}

	}, false );

}


/***/ }),

/***/ "./node_modules/spin.js/spin.js":
/*!**************************************!*\
  !*** ./node_modules/spin.js/spin.js ***!
  \**************************************/
/*! exports provided: Spinner */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "Spinner", function() { return Spinner; });
var __assign = (undefined && undefined.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var defaults = {
    lines: 12,
    length: 7,
    width: 5,
    radius: 10,
    scale: 1.0,
    corners: 1,
    color: '#000',
    fadeColor: 'transparent',
    animation: 'spinner-line-fade-default',
    rotate: 0,
    direction: 1,
    speed: 1,
    zIndex: 2e9,
    className: 'spinner',
    top: '50%',
    left: '50%',
    shadow: '0 0 1px transparent',
    position: 'absolute',
};
var Spinner = /** @class */ (function () {
    function Spinner(opts) {
        if (opts === void 0) { opts = {}; }
        this.opts = __assign(__assign({}, defaults), opts);
    }
    /**
     * Adds the spinner to the given target element. If this instance is already
     * spinning, it is automatically removed from its previous target by calling
     * stop() internally.
     */
    Spinner.prototype.spin = function (target) {
        this.stop();
        this.el = document.createElement('div');
        this.el.className = this.opts.className;
        this.el.setAttribute('role', 'progressbar');
        css(this.el, {
            position: this.opts.position,
            width: 0,
            zIndex: this.opts.zIndex,
            left: this.opts.left,
            top: this.opts.top,
            transform: "scale(" + this.opts.scale + ")",
        });
        if (target) {
            target.insertBefore(this.el, target.firstChild || null);
        }
        drawLines(this.el, this.opts);
        return this;
    };
    /**
     * Stops and removes the Spinner.
     * Stopped spinners may be reused by calling spin() again.
     */
    Spinner.prototype.stop = function () {
        if (this.el) {
            if (typeof requestAnimationFrame !== 'undefined') {
                cancelAnimationFrame(this.animateId);
            }
            else {
                clearTimeout(this.animateId);
            }
            if (this.el.parentNode) {
                this.el.parentNode.removeChild(this.el);
            }
            this.el = undefined;
        }
        return this;
    };
    return Spinner;
}());

/**
 * Sets multiple style properties at once.
 */
function css(el, props) {
    for (var prop in props) {
        el.style[prop] = props[prop];
    }
    return el;
}
/**
 * Returns the line color from the given string or array.
 */
function getColor(color, idx) {
    return typeof color == 'string' ? color : color[idx % color.length];
}
/**
 * Internal method that draws the individual lines.
 */
function drawLines(el, opts) {
    var borderRadius = (Math.round(opts.corners * opts.width * 500) / 1000) + 'px';
    var shadow = 'none';
    if (opts.shadow === true) {
        shadow = '0 2px 4px #000'; // default shadow
    }
    else if (typeof opts.shadow === 'string') {
        shadow = opts.shadow;
    }
    var shadows = parseBoxShadow(shadow);
    for (var i = 0; i < opts.lines; i++) {
        var degrees = ~~(360 / opts.lines * i + opts.rotate);
        var backgroundLine = css(document.createElement('div'), {
            position: 'absolute',
            top: -opts.width / 2 + "px",
            width: (opts.length + opts.width) + 'px',
            height: opts.width + 'px',
            background: getColor(opts.fadeColor, i),
            borderRadius: borderRadius,
            transformOrigin: 'left',
            transform: "rotate(" + degrees + "deg) translateX(" + opts.radius + "px)",
        });
        var delay = i * opts.direction / opts.lines / opts.speed;
        delay -= 1 / opts.speed; // so initial animation state will include trail
        var line = css(document.createElement('div'), {
            width: '100%',
            height: '100%',
            background: getColor(opts.color, i),
            borderRadius: borderRadius,
            boxShadow: normalizeShadow(shadows, degrees),
            animation: 1 / opts.speed + "s linear " + delay + "s infinite " + opts.animation,
        });
        backgroundLine.appendChild(line);
        el.appendChild(backgroundLine);
    }
}
function parseBoxShadow(boxShadow) {
    var regex = /^\s*([a-zA-Z]+\s+)?(-?\d+(\.\d+)?)([a-zA-Z]*)\s+(-?\d+(\.\d+)?)([a-zA-Z]*)(.*)$/;
    var shadows = [];
    for (var _i = 0, _a = boxShadow.split(','); _i < _a.length; _i++) {
        var shadow = _a[_i];
        var matches = shadow.match(regex);
        if (matches === null) {
            continue; // invalid syntax
        }
        var x = +matches[2];
        var y = +matches[5];
        var xUnits = matches[4];
        var yUnits = matches[7];
        if (x === 0 && !xUnits) {
            xUnits = yUnits;
        }
        if (y === 0 && !yUnits) {
            yUnits = xUnits;
        }
        if (xUnits !== yUnits) {
            continue; // units must match to use as coordinates
        }
        shadows.push({
            prefix: matches[1] || '',
            x: x,
            y: y,
            xUnits: xUnits,
            yUnits: yUnits,
            end: matches[8],
        });
    }
    return shadows;
}
/**
 * Modify box-shadow x/y offsets to counteract rotation
 */
function normalizeShadow(shadows, degrees) {
    var normalized = [];
    for (var _i = 0, shadows_1 = shadows; _i < shadows_1.length; _i++) {
        var shadow = shadows_1[_i];
        var xy = convertOffset(shadow.x, shadow.y, degrees);
        normalized.push(shadow.prefix + xy[0] + shadow.xUnits + ' ' + xy[1] + shadow.yUnits + shadow.end);
    }
    return normalized.join(', ');
}
function convertOffset(x, y, degrees) {
    var radians = degrees * Math.PI / 180;
    var sin = Math.sin(radians);
    var cos = Math.cos(radians);
    return [
        Math.round((x * cos + y * sin) * 1000) / 1000,
        Math.round((-x * sin + y * cos) * 1000) / 1000,
    ];
}


/***/ }),

/***/ "./resources/js/admin/ocr/_form_result.class.js":
/*!******************************************************!*\
  !*** ./resources/js/admin/ocr/_form_result.class.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

;

(function (factory) {
  module.exports = factory(jQuery, __webpack_require__(/*! ../../components/util/class.js */ "./resources/js/components/util/class.js"), __webpack_require__(/*! ../../components/form.class.js */ "./resources/js/components/form.class.js"));
})(function ($, ClassUtils, BaseFormClass) {
  'use strict';

  var $submitBtn = $('<span id="submit" class="btn btn-lg btn-block btn-primary margin-top">登録</span>');
  var FormResultClass;
  return FormResultClass = ClassUtils.Extend(BaseFormClass, function FormResultClass($elem) {
    FormResultClass.prototype.__super__.constructor.call(this, $elem);
  }, {
    activate: function activate(active) {
      this.$form.find('.qItem').each(function () {
        var $qItem = $(this),
            qname = $qItem.data('qname'),
            qtype = $qItem.data('qtype');

        switch (qtype) {
          case 'text':
          case 'number':
            var $input = $qItem.find('input[type="' + qtype + '"]');

            if (active) {
              $input.removeAttr('readonly').removeAttr('disabled');
            } else {
              $input.prop('readonly', true).prop('disabled', true);
            }

            break;

          case 'single':
            var $input = $qItem.find('input[type="radio"]');

            if (active) {
              $input.removeAttr('disabled');
            } else {
              $input.prop('disabled', true);
            }

            break;
        }
      });

      if (active) {
        this.$form.append($submitBtn);
      } else {
        $submitBtn.remove();
      }

      return this;
    },
    setResult: function setResult(q, a, src) {
      var qname = q,
          $qItem = this.$form.find('.qItem[data-qname="' + qname + '"]'),
          qtype = $qItem.data('qtype');
      $qItem.find('.ocr-src').html('<img src="' + src + '" />');

      switch (qtype) {
        case 'text':
        case 'number':
          $qItem.find('input[type="' + qtype + '"][name="' + qname + '"]').val(a);
          break;

        case 'single':
          var $selectedLabel = $qItem.find('label').filter(function () {
            return a === $(this).text();
          }).first();
          var selectedValue = $selectedLabel.attr('for');
          $qItem.find('input[type="radio"][value="' + selectedValue + '"]').prop('checked', true);
          break;
      }

      return this;
    }
  });
});

/***/ }),

/***/ "./resources/js/admin/ocr/_form_setting.class.js":
/*!*******************************************************!*\
  !*** ./resources/js/admin/ocr/_form_setting.class.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

;

(function (factory) {
  module.exports = factory(jQuery, __webpack_require__(/*! ../../components/util/class.js */ "./resources/js/components/util/class.js"), __webpack_require__(/*! ../../components/form.class.js */ "./resources/js/components/form.class.js"));
})(function ($, ClassUtils, BaseFormClass) {
  'use strict';

  var $inputUrlCreateQueue = $('input#url-async-create-queue'),
      $btnResetFileUploader = $('.btn.reset-file-uploader'),
      $settingFileArea = $('#setting-file');
  var FormSettingClass;
  return FormSettingClass = ClassUtils.Extend(BaseFormClass, function FormSettingClass($elem) {
    FormSettingClass.prototype.__super__.constructor.call(this, $elem);

    this.$selectFormatId = $('select[name="format_id"]');
    this.$fileUploadInput = $('input[name="file"]');
  }, {
    build: function build() {
      var self = this,
          currentAddedFile;

      FormSettingClass.prototype.__super__.build.call(this); // TODO 暫定非表示たいおう　


      $('<style />').append('<style />').append('.dz-preview { padding: 15px; }').append('.dz-image img { width:100%; }').append('.dz-size { font-size:20px; }').append('.dz-filename { font-size:20px; }').append('.dz-progress { display:none; }').append('.dz-success-mark { display:none; }').append('.dz-error-mark { display:none; }').append('.dz-remove { display:none; }').appendTo('head');
      $btnResetFileUploader.on('click', function () {
        resetFileUploader.call(self);
        return false;
      });
      this.$fileUploadInput.fileUpload({
        url: $inputUrlCreateQueue.val(),
        parallelUploads: 1,
        maxFiles: 1,
        thumbnailWidth: 200,
        //previewsContainer: '#upload-preview',
        acceptedFiles: 'image/jpg,image/jpeg,image/gif,image/png',
        data: {
          format_id: function format_id() {
            return self.$selectFormatId.val();
          }
        },
        dictDefaultMessage: '<span class="desc-file-uploader">クリックまたはドラッグドロップ<br />でアップロード</span>',
        complete: function complete() {},
        success: function success(file, response) {
          self.trigger('uploadedFile', file, response);
        },
        addedFile: function addedFile() {
          $('.dz-message').hide();
        }
      });
      this.$selectFormatId.on('change', function () {
        var formatId = $(this).val();
        resetFileUploader.call(self);

        if (!formatId) {
          $settingFileArea.hide();
        } else {
          $settingFileArea.show();
        }

        self.trigger('changeFormatId', formatId);
        return false;
      }).selectBox('initialValue', null);
      return this;
    }
  });

  function resetFileUploader() {
    this.$fileUploadInput.fileUpload('removeFiles');
    $('.dz-message').show();
  }
});

/***/ }),

/***/ "./resources/js/admin/ocr/index.js":
/*!*****************************************!*\
  !*** ./resources/js/admin/ocr/index.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

;

(function (factory) {
  module.exports = factory(jQuery, i18n, __webpack_require__(/*! ../../components/page.class */ "./resources/js/components/page.class.js"), __webpack_require__(/*! ./_form_setting.class */ "./resources/js/admin/ocr/_form_setting.class.js"), __webpack_require__(/*! ./_form_result.class */ "./resources/js/admin/ocr/_form_result.class.js"), Ladda);
})(function ($, i18n, PageClass, FormSettingClass, FormResultClass, Ladda) {
  'use strict';

  var $loadFormatContentArea = $('#load-format-content'),
      $formatContentArea = $('#format-content'),
      $formatNameArea = $('#format-name'),
      $inputUrlHtmlFormat = $('input#url-async-html-format'),
      $inputUrlCheckQueueStatus = $('input#url-async-check-queue-status'),
      Page = new PageClass(),
      FormSetting = new FormSettingClass($('form#setting')),
      FormResult = new FormResultClass($('form#content')); // ロード

  Page.load(function (deferred) {
    // setting form build 
    FormSetting.on('changeFormatId', function (formatId) {
      $loadFormatContentArea.asyncContent('load', function () {
        return loadFormat(formatId);
      });
    }).on('uploadedFile', function (file, response) {
      var $overlay = $('<div class="content-overlay"><div class="loading" /></div>');
      $formatContentArea.prepend($overlay);
      loadResult(response.result.queue_name).done(function () {
        $overlay.remove();
      });
    }).build(); // result form build 

    FormResult.build();
    deferred.resolve();
  });
  /**
   * フォーマットHTMLロード
   * 
   * @param  {[type]} formatId [description]
   * @return {[type]}          [description]
   */

  function loadFormat(formatId) {
    var deferred = $.Deferred();
    $formatNameArea.empty();
    $formatContentArea.html('<div class="no-content">帳票を選択してください</div>');

    if (formatId) {
      $.ext.ajax({
        ajaxName: 'getFormatHtml',
        url: $inputUrlHtmlFormat.val().replace('FORMAT_ID', formatId),
        success: function success(parsedResult) {
          $formatNameArea.html(parsedResult.name);
          $formatContentArea.html('<div style="border-top: 1px solid #eee;border-bottom: 1px solid #eee;padding: 20px;">' + parsedResult.html + '</div>').find('.answer-area').css('min-height', '50px').each(function () {
            $('<div class="ocr-src" />').append('<div class="no-content" style="margin-bottom: 10px;">回答用紙画像を読み込んでください</div>').prependTo($(this));
          });
          deferred.resolve();
        }
      });
    } else {
      deferred.resolve();
    }

    return deferred.promise().then(function () {
      FormResult.activate(false);
    });
  }
  /** 
   * OCR結果ロード
   * 
   * @param  {[type]} queue [description]
   * @return {[type]}       [description]
   */


  function loadResult(queue_name) {
    var pollingLimitCount = 600,
        pollingInterval = 1000,
        pollingCount = 0,
        deferred = $.Deferred(),
        polling = function polling(queue_name) {
      if (pollingLimitCount === pollingCount++) {
        $.ext.notify.error(i18n('message.queue_failed'));
        deferred.resolve();
        return;
      }

      setTimeout(function () {
        $.ext.ajax({
          ajaxName: 'queue-status-check-polling',
          type: $inputUrlCheckQueueStatus.data('method'),
          url: $inputUrlCheckQueueStatus.val(),
          data: {
            queue_name: queue_name
          },
          success: function success(parsedResult) {
            if (!parsedResult.finished) {
              polling(queue_name);
            } else {
              if (!parsedResult.has_error) {
                // success
                var result = parsedResult.result;
                result.forEach(function (pair) {
                  var q = pair.q,
                      a = pair.a,
                      src = pair.src;
                  FormResult.setResult(q, a, src);
                });
                FormResult.activate(true);
              } else {
                $.ext.notify.error(parsedResult.error_message);
              }

              deferred.resolve();
            }
          }
        });
      }, pollingInterval);
    };

    polling(queue_name);
    return deferred.promise();
  }
});

/***/ }),

/***/ "./resources/js/components/form.class.js":
/*!***********************************************!*\
  !*** ./resources/js/components/form.class.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

;

(function (factory) {
  module.exports = factory(jQuery, i18n, __webpack_require__(/*! ./util/class */ "./resources/js/components/util/class.js"), __webpack_require__(/*! ./util/observable.class */ "./resources/js/components/util/observable.class.js"), __webpack_require__(/*! ./util/validator */ "./resources/js/components/util/validator.js"), __webpack_require__(/*! ladda */ "./node_modules/ladda/js/ladda.js"));
})(function ($, i18n, ClassUtils, Observable, Validator, Ladda) {
  'use strict';

  return ClassUtils.Extend(Observable, function Form($elem) {
    this.$form = $elem;
    this.method = this.$form.attr('method');
    this.action = this.$form.attr('action');
  }, {
    build: function build(options) {
      var self = this,
          opts = $.extend({
        editable: true,
        selectBox: null
      }, options); // icheck init

      $('input[type="checkbox"]', this.$form).iCheck({
        checkboxClass: 'icheckbox_square-blue'
      }); // select init

      $('select', this.$form).selectBox(opts.selectBox); // datepicker

      $('.input-group.date', this.$form).daterangepicker({
        timePicker: false,
        singleDatePicker: true
      }, function (start) {
        this.element.find('input').val(start.format('YYYY-MM-DD'));
      }); // datetimepicker

      $('.input-group.datetime', this.$form).daterangepicker({
        timePicker: true,
        timePickerIncrement: 10,
        singleDatePicker: true
      }, function (start) {
        this.element.find('input').val(start.format('YYYY-MM-DD HH:mm'));
      }); // input enter key submit制御

      $('input', this.$form).keydown(function (e) {
        if (e.which && e.which === 13 || e.keyCode && e.keyCode === 13) {
          return false;
        } else {
          return true;
        }
      }).attr('autocomplete', "off").attr('autocorrect', "off").attr('autocapitalize', "off").attr('spellcheck', "false");
      !opts.editable && this.$form.find('.btn-submit').prop('disabled', true); // events

      this.$form.on('click.form', '.btn-submit', function () {
        if (!opts.editable) return false;
        var deferred = $.Deferred(),
            LaddaSubmit = Ladda.create($(this)[0]);
        deferred.done(function () {
          self.bind({});
        }).always(function () {
          LaddaSubmit.stop().remove();
        });
        self.clearErrors();
        LaddaSubmit.start();

        if ($(this).data('withValidation') && !self.validate()) {
          deferred.reject();
        } else {
          self.trigger('submit', deferred, self.getData(), self.action, self.method);
        }

        return false;
      }).on('click.form', '.btn-cancel', function () {
        var deferred = $.Deferred(),
            LaddaCancel = Ladda.create($(this)[0]);
        deferred.always(function () {
          LaddaCancel.stop().remove();
        });
        self.clearErrors();
        self.trigger('cancel', deferred);
        self.bind({});
        return false;
      });
      return this;
    },
    bind: function bind(data) {// override
    },
    validate: function validate() {
      var error = false,
          fieldErrors = [],
          appendTypes = this.validationTypes ? this.validationTypes() : {};
      this.clearErrors();
      collectFields.call(this).each(function () {
        var $elem = $(this),
            name = $elem.prop('name'),
            value = getFieldValue($elem),
            defaultTypes = $elem.data('validationTypes'),
            types = (defaultTypes || []).concat(appendTypes[name] || []),
            title = $elem.data('validationTitle') || '',
            result;
        result = Validator(value, types, title);

        if (!result.result) {
          error = true;
          fieldErrors[name] = [result.message];
        }
      });
      this.handleFieldErrors(fieldErrors);

      if (appendTypes['global']) {
        var result = Validator(null, appendTypes['global'], 'global');

        if (!result.result) {
          error = true; // TODO global error field
        }
      }

      error && $.ext.notify.warn(i18n('message.invalid_form_inputs'));
      return !error;
    },
    handleFieldErrors: function handleFieldErrors(errors) {
      collectFields.call(this).each(function () {
        var $elem = $(this),
            name = $elem.prop('name');
        errors[name] && createFieldError($elem, errors[name]);
      });
      return this;
    },
    getData: function getData() {
      var data = {};
      collectFields.call(this).each(function () {
        var $elem = $(this),
            name = $elem.prop('name'),
            value = getFieldValue($elem);

        if (null !== value && '' !== value) {
          if (name.endsWith('[]')) {
            name = name.replace('[]', '');
            if (!data[name]) data[name] = [];
            data[name].push(value);
          } else {
            data[name] = value;
          }
        }
      });
      return data;
    },
    hasError: function hasError() {
      return this.$form.find('.ng-alert').length ? true : false;
    },
    clearErrors: function clearErrors(errors) {
      this.$form.find('.ng-alert').remove();
      return this;
    },
    clear: function clear() {
      collectFields.call(this).each(function () {
        clearFieldValue($(this));
      });
      return this;
    },
    destroy: function destroy() {
      this.$form.off('.form'); // icheck init

      $('input[type="checkbox"]', this.$form).iCheck('destroy'); // select init

      $('select', this.$form).selectBox('destroy'); // datepicker

      $('.input-group.date, .input-group.datetime', this.$form).daterangepicker('destroy'); // input enter key submit制御

      $('input', this.$form).off();
      return this;
    }
  });
  /**
   * field値を取得
   * 
   * @param  {[type]} $field [description]
   * @return {[type]}        [description]
   */

  function getFieldValue($field) {
    var value = '';

    switch ($field.prop('tagName')) {
      case 'INPUT':
      case 'SELECT':
      case 'TEXTAREA':
        if ($field.is(':checkbox')) {
          value = $field.is(':checked') ? 1 : 0;
        } else if ($field.is(':radio')) {
          value = $field.filter(function () {
            return $(this).is(':checked');
          }).val();
        } else {
          value = $field.val();
        }

        break;
    }

    return value;
  }
  /** 
   * field値を空にする
   * 
   * @param  {[type]} $field [description]
   * @return {[type]}        [description]
   */


  function clearFieldValue($field) {
    switch ($field.prop('tagName')) {
      case 'INPUT':
      case 'SELECT':
      case 'TEXTAREA':
        if ($field.is(':checkbox') || $field.is(':radio')) {
          $field.iCheck('uncheck').prop('checked', false);
        } else if ($field.is('select')) {
          $field.val(null).change();
        } else {
          $field.val('');
        }

        break;
    }
  }
  /** 
   * フィールド入力値を取得
   * @return {[type]} [description]
   */


  function collectFields() {
    var $fields = $();

    for (var property in this) {
      if (property.startsWith('$field')) {
        this[property].each(function () {
          $fields.push($(this)[0]);
        });
      }
    }

    return $fields;
  }
  /**
   * フィールドエラー生成
   * 
   * @param  {[type]} $field [description]
   * @param  {[type]} messages [description]
   * @return {[type]}        [description]
   */


  function createFieldError($field, messages) {
    if (messages) {
      $field.before('<p class="ng-alert">' + messages.join('<br />') + '</p>');
    }
  }
});

/***/ }),

/***/ "./resources/js/components/page.class.js":
/*!***********************************************!*\
  !*** ./resources/js/components/page.class.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

;

(function (factory) {
  module.exports = factory(jQuery, __webpack_require__(/*! ./util/class.js */ "./resources/js/components/util/class.js"), __webpack_require__(/*! ./util/observable.class */ "./resources/js/components/util/observable.class.js"), __webpack_require__(/*! ladda */ "./node_modules/ladda/js/ladda.js"));
})(function ($, ClassUtils, Observable, Ladda) {
  'use strict';

  var $page = $('#page'),
      asyncable = $page.hasClass('async-content'),
      transactional = $page.data('pageTransactional');
  /**
   * 通常ページ
   * 
   * @param {[type]} )             {                      } [description]
   * @param {[type]} options.load: function(callback) {                           var self [description]
   * @param {[type]} 500);                                                                                           }                            return deferred.promise();                        }                    );            } else {                callback( [description]
   */

  var Page = ClassUtils.Extend(Observable, function Page() {
    // 編集可能か
    this.pageEditable = 'true' == ($('#has-page-editable-role').val() || 'false') ? true : false;
  }, {
    load: function load(callback) {
      var self = this;

      if (asyncable) {
        $page.asyncContent('load', function () {
          var deferred = $.Deferred();

          if (callback) {
            callback.call(self, deferred);
          } else {
            setTimeout(function () {
              deferred.resolve();
            }, 500);
          }

          return deferred.promise();
        });
      } else {
        callback.call(self);
      }
    }
  });
  /**
   * 変更内容を一括で登録する必要があるページ
   * 
   * @param {[type]} )  {             PageTransactional.prototype.__super__.constructor.call(this);          this.$commitBtn [description]
   * @param {[type]} {          load: function(callback)                                            {                                        var self [description]
   */

  var PageTransactional = ClassUtils.Extend(Page, function PageTransactional() {
    PageTransactional.prototype.__super__.constructor.call(this);

    this.$commitBtn = $page.find('.btn-commit');
    this.$rollbackBtn = $page.find('.btn-rollback');
    this.LaddaCommit = Ladda.create(this.$commitBtn[0]);
    this.LaddaRollback = Ladda.create(this.$rollbackBtn[0]);
    this.$shouldCommitAlert = $page.find('.should-commit-alert');
    this.unCommitted = false;
  }, {
    load: function load(callback) {
      var self = this;
      this.$commitBtn.prop('disabled', true).on('click.page', function () {
        if (!self.pageEditable) return false;
        var data = $(this).data(),
            deferred = $.Deferred();
        deferred.then(function () {
          // resolve
          self.unCommitted = false;
          self.$shouldCommitAlert.hide();
          self.LaddaCommit.stop();
          self.$commitBtn.prop('disabled', true);
        }, function () {
          // reject
          self.LaddaCommit.stop();
        });
        self.LaddaCommit.start();
        self.trigger('commit', data.method, data.url, deferred);
        return false;
      });
      this.$rollbackBtn.on('click.page', function () {
        self.LaddaRollback.start();
        setTimeout(function () {
          location.reload();
        }, 1);
        return false;
      });

      PageTransactional.prototype.__super__.load.call(this, callback);
    },
    alertShouldCommit: function alertShouldCommit() {
      if (!this.unCommitted) {
        if (!this.pageEditable) return;
        this.unCommitted = true;
        this.$commitBtn.prop('disabled', false); // $(window)
        //     .one('beforeunload.page', function(e) {
        //         e.returnValue = '更新したデータがある場合確定ボタンをクリックしないと登録されません。ページから離れますか？';
        //     });

        this.$shouldCommitAlert.show();
      }
    }
  });
  return transactional ? PageTransactional : Page;
});

/***/ }),

/***/ "./resources/js/components/util/class.js":
/*!***********************************************!*\
  !*** ./resources/js/components/util/class.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var ClassUtils = {};
module.exports = ClassUtils;
/**
 * Class Extend
 * 
 * ex)
 * ChildClass = Utils.Extend(SuperClass, function ChildClass() {
 *          // Chlid constructor
 *      }), {
 *          // Child methods
 *      });
 */

ClassUtils.Extend = function (SuperClass, ChildClass, ChildClassMethod) {
  var SuperClass = typeof SuperClass === "string" ? ClassUtils.classes[SuperClass] : SuperClass || Object;
  ChildClass.prototype.__extends__ = (ChildClass.prototype.__extends__ || []).concat([SuperClass.prototype.constructor.name]).concat(SuperClass.prototype.__extends__ || []);
  ChildClass.prototype = $.extend({}, SuperClass.prototype, ChildClass.prototype, {
    constructor: ChildClass.prototype.constructor,
    __super__: SuperClass.prototype
  });

  if (ChildClassMethod && !$.isEmptyObject(ChildClassMethod)) {
    // forはプロトタイプチェーンをたどる、hasOwnPropertyはたどらない
    for (var key in ChildClassMethod || {}) {
      if (ChildClassMethod.hasOwnProperty(key)) {
        ChildClass.prototype[key] = ChildClassMethod[key];
      }
    }
  }

  return ChildClass;
};
/**
 * Class 定義
 * 
 * 継承のないCLass
 */


ClassUtils.Class = function (Class, ClassMethod) {
  return ClassUtils.Extend(null, Class, ClassMethod);
};
/**
 * Classが対象インスタンスかどうか調べる
 */


ClassUtils.instanceOf = function (Obj, className) {
  return className === Obj.constructor.name || Obj.__extends__ && 0 <= Obj.__extends__.indexOf(className);
};

/***/ }),

/***/ "./resources/js/components/util/observable.class.js":
/*!**********************************************************!*\
  !*** ./resources/js/components/util/observable.class.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/********************
 * Class Observable 
 ********************/
module.exports = Observable;

function Observable() {}

;

Observable.prototype.on = function (event, callback) {
  this.listeners = this.listeners || {};

  if (event in this.listeners) {
    this.listeners[event].push(callback);
  } else {
    this.listeners[event] = [callback];
  }

  return this;
};

Observable.prototype.one = function (event, callback) {
  this.oneTimeListenersIndexes = this.oneTimeListenersIndexes || {};
  if (!this.oneTimeListenersIndexes[event]) this.oneTimeListenersIndexes[event] = [];
  this.on(event, callback);
  this.oneTimeListenersIndexes[event].push(this.listeners[event].length - 1);
  return this;
};

Observable.prototype.off = function (event) {
  if (this.listeners) {
    if (event) {
      delete this.listeners[event];
    } else {
      this.listeners = null;
    }
  }

  return this;
};

Observable.prototype.trigger = function (event) {
  if (this.listeners && event in this.listeners) {
    this.invoke.call(this, event, Array.prototype.slice.call(arguments, 1));
  }

  if (this.listeners && '*' in this.listeners) {
    this.invoke.call(this, '*', arguments);
  }

  return this;
};

Observable.prototype.invoke = function (event, params) {
  var eventListeners = this.listeners[event],
      survivalEventListeners = [];

  for (var i = 0, len = eventListeners.length; i < len; i++) {
    var listener = eventListeners[i];
    listener.apply(this, params);

    if (!this.oneTimeListenersIndexes || !this.oneTimeListenersIndexes[event] || !this.oneTimeListenersIndexes[event].includes(i)) {
      survivalEventListeners.push(listener);
    }
  }

  this.oneTimeListenersIndexes && delete this.oneTimeListenersIndexes[event];
  survivalEventListeners.length ? this.listeners[event] = survivalEventListeners : delete this.listeners[event];
};

/***/ }),

/***/ "./resources/js/components/util/validator.js":
/*!***************************************************!*\
  !*** ./resources/js/components/util/validator.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr && (typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]); if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

;

(function (factory) {
  module.exports = factory(jQuery, i18n);
})(function ($, i18n) {
  var methods = {
    _mergeResult: function _mergeResult(resultObjs) {
      var result = true,
          messages = [],
          errors = [];
      $.each(resultObjs, function (i, resultObj) {
        if (!resultObj.result) {
          result = false;
          messages.push(resultObj.message);
          errors.push(resultObj);
        }
      });
      return {
        result: result,
        errors: errors,
        message: result ? null : $.unique(messages)
      };
    },
    _createResult: function _createResult(result, messageKey, title, messageArgs) {
      var message = null;

      if (!result) {
        var messageArgs = $.extend({
          name: title ? title : ''
        }, messageArgs || {});
        message = i18n('validate.' + messageKey, messageArgs);
      }

      return {
        result: result,
        validationName: messageKey,
        message: message
      };
    },
    _stop: function _stop(type, currentResult) {
      if (type == 'required' && !currentResult.result) {
        return true;
      }

      return false;
    },
    numeric: function numeric(value, title) {
      var result = true,
          over = false;

      if (!isEmptyValue(value)) {
        $.each($.isArray(value) ? value : [value], function (i, v) {
          if (!$.isNumeric(v)) {
            result = false;
          } else {
            if (Number.MAX_SAFE_INTEGER >= v && -Number.MAX_SAFE_INTEGER <= v) {
              result = true;
            } else {
              result = false;
              over = true;
            }
          }
        });
      }

      return methods._createResult(result, result ? null : over ? 'over_max_safe_integer' : 'numeric', title);
    },
    positiveNum: function positiveNum(value, title) {
      // 正数バリデーション
      var result = true;

      if (!isEmptyValue(value)) {
        var numericValidation = methods.numeric(value, title);

        if (!numericValidation.result) {
          return numericValidation;
        } else {
          if ('' !== value && 0 > +value) {
            result = false;
          }
        }
      }

      return methods._createResult(result, result ? null : 'positive_num', title);
    },
    strMaxWidth: function strMaxWidth(value, title, range) {
      var result = true;

      if (!isEmptyValue(value)) {
        if (range.max < value.length) result = false;
      }

      return methods._createResult(result, result ? null : 'str_max_width', title);
    },
    digitsGreaterThan: function digitsGreaterThan(value, title, range) {
      var result = true,
          messageKey;

      if (!isEmptyValue(value)) {
        var numericValidation = methods.numeric(value, title);

        if (!numericValidation.result) {
          return numericValidation;
        } else {
          var allowEqual = range.equal;

          if (allowEqual && range.min === +value || range.min < +value) {
            result = true;
          } else {
            result = false;
          }

          messageKey = allowEqual ? 'digits_greater_than_equal_to' : 'digits_greater_than';
        }
      }

      return methods._createResult(result, result ? null : messageKey, title, {
        min: range.min
      });
    },
    digitsLessThan: function digitsLessThan(value, title, range) {
      var result = true,
          messageKey;

      if (!isEmptyValue(value)) {
        var numericValidation = methods.numeric(value, title);

        if (!numericValidation.result) {
          return numericValidation;
        } else {
          var allowEqual = range.equal;

          if (allowEqual && range.max === +value || range.max > +value) {
            result = true;
          } else {
            result = false;
          }

          messageKey = allowEqual ? 'digits_less_than_equal_to' : 'digits_less_than';
        }
      }

      return methods._createResult(result, result ? null : messageKey, title, {
        max: range.max
      });
    },
    decimalDigitsInRange: function decimalDigitsInRange(value, title, range) {
      // 小数バリデーション（許容は設定値）
      var result = true;

      if (!isEmptyValue(value)) {
        var numericValidation = methods.numeric(value, title);

        if (!numericValidation.result) {
          return numericValidation;
        }

        var numbers = String(value).split('.');

        if (2 < numbers.length || numbers[1] && range.max < numbers[1].length) {
          result = false;
        }
      }

      return methods._createResult(result, result ? null : 'decimal_digit_in_range', title, {
        digits: range.max,
        name: title
      });
    },
    required: function required(value, title) {
      var result = !isEmptyValue(value);
      return methods._createResult(result, result ? null : 'required', title);
    },
    length: function length(value, title, range) {
      var result = true;

      if (!isEmptyValue(value)) {
        if (value.length > range.max) {
          result = false;
        }

        return methods._createResult(result, result ? null : 'length', title, {
          max: range.max,
          name: title
        });
      }
    },
    date: function date(value, title) {
      var result = true;

      if (!isEmptyValue(value)) {
        if (!/^\d{1,4}(\/|-)\d{1,2}\1\d{1,2}$/.test(value)) {
          result = false;
        } else {
          var _value$split$map = value.split(/\/|-/).map(function (v) {
            return parseInt(v, 10);
          }),
              _value$split$map2 = _slicedToArray(_value$split$map, 3),
              year = _value$split$map2[0],
              month = _value$split$map2[1],
              day = _value$split$map2[2];

          var date = new Date(year, month - 1, day);
          result = date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day;
        }
      }

      return methods._createResult(result, result ? null : 'date', title);
    },
    custom: function custom(value, title, callback) {
      var result = true;

      if (!isEmptyValue(value)) {
        result = callback(value);
      }

      return methods._createResult(result, callback.name, title);
    }
  };
  return function (value, types, title) {
    if (null == types) {
      return methods._createResult(true);
    }

    var results = [];
    $.each(Array.isArray(types) ? types : [types], function (i, type) {
      var result;

      if (typeof type === 'function') {
        result = methods.custom(value, title, type);
      } else {
        var matchedArgsStr = type.match(/\{.+\}/);

        if (matchedArgsStr) {
          var argsStr = matchedArgsStr[0];
          var args = new Function("return " + argsStr)();
          result = methods[type.replace(argsStr, '')](value, title, args);
        } else {
          result = methods[type](value, title);
        }
      }

      results.push(result);
      if (methods._stop(type, result)) return false; // break;
    });
    return methods._mergeResult(results);
  };

  function isEmptyValue(value) {
    if (null != value && '' != value && !$.isEmptyObject(value)) {
      return false;
    }

    return true;
  }
});

/***/ }),

/***/ 2:
/*!***********************************************!*\
  !*** multi ./resources/js/admin/ocr/index.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /mnt/workspace/resources/js/admin/ocr/index.js */"./resources/js/admin/ocr/index.js");


/***/ })

/******/ });