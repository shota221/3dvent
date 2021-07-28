/**
 * node modules 
 */

// i18n ja
window.i18n = require('../i18n/i18n')('ja');

// ladda
window.Ladda = require('ladda');

// moment ja
window.moment = require('../moment/ja');

/**
 * js functions
 */

require('../components/util/functions.js');


/**
 * jQuery 拡張 
 */

// select2 wrapper
require('../components/jquery/jquery.selectBox');

// query builder
require('../components/jquery/jquery.ext.httpBuildQuery.js');

// notifyjs 拡張
require('../components/jquery/jquery.ext.notify.js');

// ajax 拡張
$.extend(
    require('../components/jquery/jquery.ext.ajax.js'), {
        notify : $.ext.notify,
        csrf   : {
            header : 'X-CSRF-TOKEN',
            token  : function() {
                return $('meta[name="csrf-token"]').attr('content');
            }
        },
        validationErrorsParse: function(parsedErrorResult) {
            /**
             * parsedErrorResult = {
             *     errors: error配列
             * }
             * 
             * error = {
             *     key      : (nullable) string {fieldName}
             *     nested   : (nullable) object { {NESTED_INDEX}: [error] }
             *     message  : (nullable) object {
             *         code         : (nullable) int    メッセージコード
             *         translated   : string            message
             *     }
             * }
             */
            var recursive = function(errors) {
                var errorObj = {};
                
                errors.forEach(function(error) {
                    var message     = (error.message || {}).translated
                        , key       = error.key || 'global'
                        , nested    = error.nested
                    ;

                    if (nested) {
                        var content = {};
                        // nested = {
                        //     {NESTED_INDEX}: [
                        //         {
                        //             key:
                        //             message: { code, translated }
                        //         }, 
                        //         ...
                        //     ]
                        // }
                        $.each(nested, function(nestedIndex, errors) {
                            content[nestedIndex] = recursive(errors);
                        });

                        if (! errorObj[key]) {
                            errorObj[key] = [];
                        } 

                        errorObj[key]['nested'] = content;
                    } else {
                        if (! errorObj[key]) {
                            errorObj[key] = [];
                        } 

                        errorObj[key].push(message);

                        if ('global' === key) {
                            errorObj[key] = errorObj[key].join(', ');
                        }
                    }
                });

                return errorObj;
            }

            var errorObj = (parsedErrorResult.errors);

            //console.log(errorObj);
            
            return errorObj;
        }
    }
);

/**
 * jQuery.fn 拡張 
 */

// 非同期表示
require('../components/jquery/jquery.asyncContent.js');

// 要素コールバック
require('../components/jquery/jquery.exec.js');

// bootstrap modal 拡張
require('../components/jquery/jquery.modal.pauseable.js');
