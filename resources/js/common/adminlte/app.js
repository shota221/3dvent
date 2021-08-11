/**
 * node modules 
 */

// jQuery
window.$ = window.jQuery = require('jquery');

// ie11 promise対応
window.Promise = window.Promise || require('es6-promise-promise');

// bootstrap
require('bootstrap');

// icheck
require('icheck');

// admin LTE
require('admin-lte');

// admin LTE daterangepicker
require('admin-lte/plugins/daterangepicker/daterangepicker');

// i18n ja
window.i18n = require('../../i18n/i18n')('ja');