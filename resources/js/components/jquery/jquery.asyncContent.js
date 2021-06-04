/********************************
 * jQuery 拡張 非同期コンテンツ切替
 ********************************/

if (typeof jQuery === 'undefined') throw new Error('required jQuery');

;(function($) {

    const loadTargetAreaClassName = 'async-load-area';

    const loaderHtml = '<div class="loading" />';

    var methods = {
        init: function(options) {
            return this.each(function() {
                var $this = $(this), positionStyle = $this.css('position') || 'relative';
                $this
                    .css({ position: positionStyle })
                    .data('isLoading', false)
                    .data('asyncContent', $.extend({ minHeight: ($this.css('min-height') || '500px') }, options))
                    .wrapInner('<div class="' + loadTargetAreaClassName + '" />');
            })
        },
        defaultLoadArea: function() {
            return findDefaultLoadArea.call(this);
        },
        load: function(asyncFunc, $elem, hideLoader) {
            return this.each(function() {
                var $self = $(this)
                    , $target = ($elem || findDefaultLoadArea.call($self))
                    , options = $self.data('asyncContent') || {}
                    , hideLoader = (typeof hideLoader !== 'undefined' ? hideLoader : (options.hideLoader || false))
                    ;

                if ($self.data().isLoading) return ;

                $self.data().isLoading = true;

                $target.css({ 'opacity': 0.0, 'min-height': options.minHeight }).show();

                // $target.animate({ 'opacity': 0.0 }, { duration: 500, queue: true, complete: function() {
                //     $(this).css({'min-height': options.minHeight}).show();
                // }});

                var $loader = $(loaderHtml);

                if (hideLoader) $loader.hide();

                $loader.appendTo($self);

                setTimeout(function() {
                    asyncFunc()
                        .always(function(data) {
                            $loader.animate({ 'opacity': 0.0 }, { duration: 300, queue: true, complete: function() {
                                $(this).remove();

                                $self.data().isLoading = false;

                                $self.trigger('loaded', { loaded: data });
                            }});

                            $target.animate({ 'opacity': 1.0 }, { duration: 500, queue: true });
                    });
                }, 1);
            });
        }
    }

    function findDefaultLoadArea() {
        return this.children('.' + loadTargetAreaClassName).first();
    }

    $.fn.asyncContent = function(options) {
        if (typeof (options || {}) === 'object') {
            return methods.init.apply(this, arguments);
        } else if(typeof options === 'string' && methods[options]) {
            return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
        } else {
            $.error('Method ' +  options + ' does not exist on jQuery.fn');
        }
    };

})(jQuery);