/********************************
 * jQuery.fn 拡張 コールバック
 ********************************/

if (typeof jQuery === 'undefined') throw new Error('required jQuery');

;(function($) {
    /**
     * 要素にコールバック関数を実行
     */
    $.fn.exec = function(callback) {
        return this.each(function() {
            callback.call($(this));
        })
    };
})(jQuery);