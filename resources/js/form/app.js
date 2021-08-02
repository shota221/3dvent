/**
 * node modules 
 */

// jQuery
window.$ = window.jQuery = require('jquery');

// lodash
//window._ = require('lodash');

// ie11 promise対応
window.Promise = window.Promise || require('es6-promise-promise');

// bootstrap
require('bootstrap');

// i18n ja
window.i18n = require('../i18n/i18n')('ja');