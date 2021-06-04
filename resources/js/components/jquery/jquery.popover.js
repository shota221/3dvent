;(function(factory) {
    module.exports = factory( 
        jQuery,
        //require('bootstrap'), // これ入れるとアカウント情報のドロップダウンが開かない
    );
}(function($) {
    if (typeof $.fn.popover === 'undefined') throw new Error('required Bootstrap');
    
    /**
     * bootstrap popover wrapper
     */
    $.fn.popOver = function(options, args) {
        if (typeof (options || {}) === 'object') {
            return this.each(function() {
                var self = this
                    , opts = $.extend({ 
                        trigger : 'hover',
                        //placement: 'left',
                        template: '<div class="popover fade" role="tooltip" tabindex="-1"><div class="arrow"></div>#HEADER#<div class="popover-content"><div class="data-content"></div></div></div>',
                        html    : true,                 
                    }, options)
                    ;
                
                if (opts.async) {
                    $(this)
                        .popover({
                            template : '<div class="popover fade popover-loader" role="tooltip"><div class="arrow"></div><div class="popover-content"></div></div>',
                            container: opts.container, 
                            placement: 'auto top',
                            content  : '<div class="loader"></div>',
                            title    : null,
                            html     : true,
                        })
                        .on('shown.bs.popover', function() {
                            $.when(opts.async())
                                .always(function(res) {
                                    $(self)
                                        .on('hidden.bs.popover', function() {
                                            if (res) setTimeout(res.callback, 0);
                                        })
                                        .popover('destroy');
                                })
                        })
                        .popover('show');
                } else {
                    var shown = false; // resize用パラメータ
                    
                    $(this)
                        .popover(opts)
                        .on('shown.bs.popover', function() {
                            var $this = $(self);
                            
                            if (shown) return ;
                            
                            shown = true;
                            
                            // shownは非同期よりすでにけされてからコールされる可能性あり
                            var $popover = ($this.data('bs.popover') || {}).tip();
                            
                            if ($popover && $popover.length) {
                                if (~opts.trigger.indexOf(' leave')) { // ex manual leave // hoverならpopover.jsがやってくれる
                                    $this
                                        .one('mouseleave.popOver', function() {
                                            $this.popover('destroy');
                                        })
                                }
                                
                                $popover
                                    .on('mousedown.popOver', '.btn-close-popover', function() {
                                        $this.popover('destroy');
                                    })
                                    .on('mousedown.popOver', function() {
                                        return false; // popover自体を押下したとき他ライブラリの領域外クリックとなる場合を防ぐ
                                    })
                                
                                $(document)
                                    .on('resize.popOver.' + $popover.attr('id'), function() {
                                        // TODO リサイズしないので
                                        $this.popover('show');
                                    })
                                    .on('mousedown.popOver.' + $popover.attr('id'), function(ev) {
                                        $this.popover('hide');
                                    })
                                
                                if (opts.shownCallback) opts.shownCallback($popover);
                            }
                        })
                        .on('hidden.bs.popover', function() {
                            var $popover = ($(this).data('bs.popover') || {}).tip();
                            
                            $popover.off('.popOver');
                            
                            $(document).off('.popOver.' + $popover.attr('id'));
                            
                            if (opts.hiddenCallback) opts.hiddenCallback($popover);
                        })
                }
            })
        } else if ('destroy' == options) {
            var deferred = $.Deferred(), elementCount = this.length, resolveCount = 0;
            
            this.each(function() {
                $(this).data('bs.popover') && $(this)
                    .on('hidden.bs.popover', function() {
                        if (++resolveCount === elementCount) {
                            deferred.resolve();
                        }
                    })
                    .popover('destroy');
            })
            
            return deferred.promise();
        } else if ('content' == options) {
            return this.each(function() {
                var popoverOptions = $(this).data('bs.popover').options;
                
                popoverOptions.content = args.content || ' ';
                
                if (null != args.title) {
                    popoverOptions.title = args.title + '<span class="btn-close-popover">&times;</span>';
                    
                    popoverOptions.template = popoverOptions.template.replace('#HEADER#', '<h3 class="popover-title"></h3>');
                } else {
                    
                    popoverOptions.template = popoverOptions.template.replace('#HEADER#', '<span class="btn-close-popover">&times;</span>');
                }
            })
        } else {
            return this.popover(options);
        }
        
    };
    
}));