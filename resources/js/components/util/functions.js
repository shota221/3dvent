if ('__func__' in window) new Error('functionsロードに失敗');

window.__func__ = {};

/**
 * IE8以下のconsoleによるエラーを防ぐ
 */
if (!('console' in window)) {
    window.console = {};
    window.console.log = function (str) {
        return str;
    };
}
if (typeof Object.keys !== 'function') {
    Object.keys = function (obj) {
        var arry = new Array();
        $.each(obj, function (key, value) {
            arry.push(key);
        });
        return arry;
    };
}
if (typeof Object.values !== 'function') { // IE未対応201906現在
    Object.values = function (obj) {
        var arry = new Array();
        $.each(obj, function (key, value) {
            arry.push(value);
        });
        return arry;
    };
}

if (!Number.MAX_SAFE_INTEGER) { // ie11 対応
    Number.MAX_SAFE_INTEGER = Math.pow(2, 53) - 1; // 9007199254740991
}

if (!Array.prototype.includes) { // ie11 対応
    Object.defineProperty(Array.prototype, 'includes', {
        value: function(searchElement, fromIndex) {
            if (this == null) {
                throw new TypeError('"this" is null or not defined');
            }
            var o = Object(this);
            var len = o.length >>> 0;
            if (len === 0) {
                return false;
            }
            var n = fromIndex | 0;
            var k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);
            while (k < len) {
                if (o[k] === searchElement) {
                    return true;
                }
                k++;
            }
            return false;
        }
    });
}


/**
 * jQueryにてSelectorを使用する際、特定文字のエスケープする
 *
 * 「+」や「.(ドット)」は$('#id')のidにはそのまま使用できない。
 * この関数はこれらを使用できるようにエスケープする。
 *
 * 使用例:
 *     var str = "#hoge.",
 *     escapedStr = str.escSelector();
 *
 * @return エスケープされたSelector文字列
 */
String.prototype.escSelector = function () {
    return this.replace(new RegExp('(\\+|\\.)','g'),'\\$1');
}
/**
 * トリム
 */
String.prototype.trim = function () {
    return this.replace(/(^\s+)|(\s+$)/g, '');
};
/**
* n2br
*/
String.prototype.n2br = function() {
    return this.replace(/\r?\n/g, '<br />')
}

/**
 * HTMLエスケープを行う
 * ダミー領域のtextにパラメータを一度セットし、そのhtml要素を返す。
 *
 * 使用例:
 *     var str = "<b>hoge</b>",
 *     escapedStr = str.escapeHTML();
 *
 * @return HTMLエスケープされた文字列
 */
String.prototype.escapeHTML = function () {
    return $('<div/>').text(this.toString()).html().replace(/"/g,'&quot;');
};
/**
 * HTMLデコードを行う
 *
 * 使用例:
 *     var str = "&lt;b&gt;hoge&lt;/b&gt;",
 *     decodedStr = str.decodeHTMLEntity();
 *
 * @return HTMLデコードされた文字列
 */
String.prototype.decodeHTMLEntity = function () {
    return this.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&quot;/g,'"').replace(/&amp;/g,'&').replace(/&#039;/g,"'").replace(/&rdquo;/g,'"').replace(/&rsquo;/g,"'");
}
/**
 *
 */
String.prototype.startsWith = function (prefix) {
    return this.indexOf(prefix) === 0;
}

String.prototype.endsWith = function (suffix) {
    return this.substring(this.length - suffix.length, this.length) === suffix;
}

/**
 * indexにstrを追加する
 */
String.prototype.inserts = function(index, str) {
    return this.slice(0, index) + str + this.slice(index);
}

/**
 * 金額
 * Number(1.00).currency()とすると1になってしまうためtoFixed引数によって強制的に小数点表示
 */
Number.prototype.currency = function (toFixed) {
    var str = toFixed ? this.toFixed(toFixed) : String(this);

    var value = str.split('.');

    if (1 < value.length) {
        return value[0].replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,') + '.' + value[1];
    } else {
        return value[0].replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
    }
};

/**
 * 小数を丸める
 * n:第n位
 */
Number.prototype.formatFloat = function(n) {
    var _pow = Math.pow(10, n);
    return Math.round(this * _pow) / _pow;
}

/**
* searchにkeyが存在するかを返す
*
* @return boolean
*/
Array.prototype.keyExists = function(key, search) {
     if (!search || (search.constructor !== Array && search.constructor !== Object)) {
         return false;
     }
     return key in search;
}
/**
 * Return true if the given object is in the array
 *
 * @param object element
 * @returns boolean
 */
Array.prototype.inArray = function (obj) {
    for (var i=0, len=this.length; i < len; i++) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
};
/**
 * Array convenience method to remove element.
 *
 * @param object element
 * @returns boolean
 */
Array.prototype.remove = function (element) {
    var result = false;
    var array = [];
    for (var i = 0, len = this.length; i < len; i++) {
        if (this[i] == element) {
            result = true;
        } else {
            array.push(this[i]);
        }
    }
    this.clear();
    for (var i = 0, len = array.length; i < len; i++) {
        this.push(array[i]);
    }
    array = null;
    return result;
};
/**
 * Array convenience method to clear membership.
 *
 * @param object element
 * @returns void
 */
Array.prototype.clear = function () {
    this.length = 0;
};

/**
 * format 
 * @param format : Y-m-d H:i:s
 */
Date.prototype.format = function(format) {
    var Y = this.getFullYear()
    ,m = ('0' + (this.getMonth() + 1)).slice(-2)
    ,d = ('0' + this.getDate()).slice(-2)
    ,H = ('0' + this.getHours()).slice(-2)
    ,i = ('0' + this.getMinutes()).slice(-2)
    ,s = ('0' + this.getSeconds()).slice(-2)
    ;
    return format.replace('Y', Y).replace('m', m).replace('d', d).replace('H', H).replace('i', i).replace('s', s);
};

/**
 * ディープコピー
 * 
 * @param  {[type]} obj [description]
 * @return {[type]}     [description]
 */
window.__func__.deepCopy = function(obj) {
    return JSON.parse(JSON.stringify(obj));
}

/**
 * オブジェクト比較
 * 
 * @param  {[type]} obj1 [description]
 * @param  {[type]} obj2 [description]
 * @return {[type]}      [description]
 */
window.__func__.objectEquals = function(obj1, obj2) {
    var result = true, comparedKeys = [];
    for (var key in obj1 || {}) if (obj1.hasOwnProperty(key)) {
        if (obj2.hasOwnProperty(key)) {
            if (typeof obj2[key] == 'object') { // object or array 
                result = __func__.objectEquals(obj1[key], obj2[key]);
            } else {
                result = (obj2[key] == obj1[key]);
            }        
        } else {
            result = false;
        }
        if (! result) return false;
        comparedKeys.push(key);
    }
    for (var key in obj2) if (obj2.hasOwnProperty(key)) {
        if (0 > comparedKeys.indexOf(key)) return false;
    }
    return result;
}