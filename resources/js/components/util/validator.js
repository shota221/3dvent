;(function(factory) {
    module.exports = factory(
        jQuery,
        i18n
    );
}(function($, i18n) {
    
    var methods = {
        _mergeResult : function(resultObjs) {
            var result = true
            ,messages = []
            ,errors = []
            ;
            $.each(resultObjs, function(i, resultObj) {
                if (!resultObj.result) {
                    result = false;
                    
                    messages.push(resultObj.message);
                    
                    errors.push(resultObj);
                }
            });
            return {
                result  : result,
                errors  : errors,
                message : result ? null : $.unique(messages)
            }
        },
        _createResult: function(result, messageKey, title, messageArgs) {
            var message = null;

            if (! result) {
                var messageArgs = $.extend({ name: (title ? title : '') }, (messageArgs || {}));

                message = i18n('validate.' + messageKey, messageArgs); 
            }

            return {
                result          : result,
                validationName  : messageKey,
                message         : message
            };
        },
        _stop: function(type, currentResult) {
            if (type == 'required' && !currentResult.result) {
                return true;
            }
            return false;
        },
        numeric: function(value, title) {
            var result = true, over = false;

            if (! isEmptyValue(value)) {
                $.each(($.isArray(value) ? value : [value]), function(i, v) {
                    if (! $.isNumeric(v)) {
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

            return methods._createResult(result, (result ? null : (over ? 'over_max_safe_integer' : 'numeric')), title);
        },
        positiveNum: function(value, title) {
            // 正数バリデーション
            var result = true;

            if (! isEmptyValue(value)) {
                var numericValidation = methods.numeric(value, title);

                if (! numericValidation.result) {
                    return numericValidation;
                } else {
                    if ('' !== value && 0 > +value) {
                        result = false;
                    }
                }
            }

            return methods._createResult(result, (result ? null : 'positive_num'), title);
        },
        strMaxWidth: function(value, title, range) {
            var result = true;

            if (! isEmptyValue(value)) {
                if (range.max < value.length) result = false;
            }

            return methods._createResult(result, (result ? null : 'str_max_width'), title);
        },
        digitsGreaterThan: function(value, title, range) {
            var result = true, messageKey;
            
            if (! isEmptyValue(value)) {
                var numericValidation = methods.numeric(value, title);

                if (! numericValidation.result) {
                    return numericValidation;
                } else {
                    var allowEqual = range.equal;

                    if ((allowEqual && range.min === +value) || range.min < +value) {
                        result = true;
                    } else {
                        result = false;
                    }

                    messageKey = allowEqual ? 'digits_greater_than_equal_to' : 'digits_greater_than';
                }
            }

            return methods._createResult(
                result, 
                (result ? null : messageKey), 
                title, 
                { min: range.min }
            );
        },
        digitsLessThan: function(value, title, range) {
            var result = true, messageKey;

            if (! isEmptyValue(value)) {
                var numericValidation = methods.numeric(value, title);

                if (! numericValidation.result) {
                    return numericValidation;
                } else {
                    var allowEqual = range.equal;

                    if ((allowEqual && range.max === +value) || range.max > +value) {
                        result = true;
                    } else {
                        result = false;
                    }

                    messageKey = allowEqual ? 'digits_less_than_equal_to' : 'digits_less_than';
                }
            }

            return methods._createResult(
                result, 
                (result ? null : messageKey), 
                title, 
                { max: range.max }
            );
        },
        decimalDigitsInRange: function(value, title, range) {
            // 小数バリデーション（許容は設定値）
            var result = true;

            if (! isEmptyValue(value)) {
                var numericValidation = methods.numeric(value, title)
                    ;

                if (! numericValidation.result) {
                    return numericValidation;
                }

                var numbers = String(value).split('.');

                if (2 < numbers.length || (numbers[1] && range.max < numbers[1].length)) {
                    result = false;
                }
            }

            return methods._createResult(
                result, 
                (result ? null : 'decimal_digit_in_range'), 
                title, 
                { digits: range.max, name: title }
            );
        },
        required: function(value, title) {
            var result = ! isEmptyValue(value);

            return methods._createResult(result, (result ? null : 'required'), title);
        },
        length: function(value, title, range) {
            var result = true;

            if (! isEmptyValue(value)) {
                if (value.length > range.max) {
                    result = false;
                }

                return methods._createResult(
                    result, 
                    (result ? null : 'length'), 
                    title, 
                    { max: range.max, name: title }
                );
            }
        },
        date: function(value, title) {
            var result = true;

            if (! isEmptyValue(value)) {
                if (!/^\d{1,4}(\/|-)\d{1,2}\1\d{1,2}$/.test(value)) {
                    result = false;
                } else {
                    var [year, month, day] = value.split(/\/|-/).map(v => parseInt(v, 10));

                    var date = new Date(year, month - 1, day);

                    result = date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day;
                }
            }

            return methods._createResult(result, (result ? null : 'date'), title);
        },
        custom: function(value, title, callback) {
            var result = true;

            if (! isEmptyValue(value)) {
                result = callback(value);
            }

            return methods._createResult(result, callback.name, title);
        }
    }
    
    return function(value, types, title) {
        if (null == types) {
            return methods._createResult(true);
        }
        
        var results = [];
        
        $.each(Array.isArray(types) ? types : [types], function(i, type) {
            var result;

            if (typeof type === 'function') {
                result = methods.custom(value, title, type);
            } else {
                var matchedArgsStr = type.match(/\{.+\}/);

                if (matchedArgsStr) {
                    var argsStr = matchedArgsStr[0];

                    var args = (new Function("return " + argsStr))();

                    result = methods[type.replace(argsStr, '')](value, title, args);
                } else {
                    result = methods[type](value, title);
                }
            }
            
            results.push(result);
            
            if (methods._stop(type, result)) return false; // break;
        })
        
        return methods._mergeResult(results);
    };

    function isEmptyValue(value) {
        if (null != value && '' != value && !$.isEmptyObject(value)) {
            return false;
        }

        return true;
    }
}));