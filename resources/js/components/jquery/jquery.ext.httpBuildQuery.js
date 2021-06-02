/********************************
 * jQuery 拡張 クエリビルダー
 ********************************/

 if (typeof jQuery === 'undefined') throw new Error('required jQuery');

;(function($) {

    $.ext = $.ext || {};

    /**
     * formDataからクエリストリングを生成
     * 
     * @param  object formdata         
     * @param  string numericPrefix nullable
     * @param  string separator  nullable
     * @return string               
     */
    $.ext.httpBuildQuery = function (formdata, numericPrefix, separator) {
        var value, key, tmp = [], that = this;

        function _helper(key, val, separator) {
            var k, tmp = [];

            if (val === true) {
                val = "1";
            } else if (val === false) {
                val = "0";
            }
            if (val !== null && typeof(val) === "object") {
                for (k in val) {
                    if (val[k] !== null) {
                        tmp.push(_helper(key + "[" + k + "]", val[k], separator));
                    }
                }

                return tmp.join(separator);
            } else if (typeof(val) !== "function") {
                return urlencode(key) + "=" + urlencode(val);
            } else if (typeof(val) == "function") {
                return '';
            } else {
                throw new Error('There was an error processing for httpBuildQuery().');
            }
        };

        if (!separator) {
            separator = "&";
        }
        for (key in formdata) {
            value = formdata[key];

            if (numericPrefix && !isNaN(key)) {
                key = String(numericPrefix) + key;
            }

            tmp.push(_helper(key, value, separator));
        }

        return tmp.join(separator);
    };

    function urlencode (str) {
        str = (str + '').toString();

        return encodeURIComponent(str)
                    .replace(/!/g, '%21')
                    .replace(/'/g, '%27')
                    .replace(/\(/g, '%28')
                    .replace(/\)/g, '%29')
                    .replace(/\*/g, '%2A')
                    .replace(/%20/g, '+');
    };
})(jQuery);