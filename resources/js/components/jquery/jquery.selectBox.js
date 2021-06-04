;(function(factory) {
    module.exports = factory( 
        jQuery,
        require('select2')
    );
}(function($) {
    /**
     * select2 4.0.3 wrapper
     */
    $.fn.selectBox = function(options) {
        var options = options || {};

        if (typeof options === 'object') {
            return this.each(function() {
                var $select = $(this)
                    , opts  = {
                        width                   : $select.attr('width') || '100%',
                        allowClear              : $select.data('allowClear') ? true : false, 
                        minimumResultsForSearch : $select.data('searchable') ? 0 : Infinity,
                        placeholder             : $select.data('allowClear') ? ($select.data('placeholder') || '選択してください') : null,
                        templateResult          : formatOption,
                        templateSelection       : formatOption,
                    }
                ;

                if (options.ajax || $select.data('ajaxUrl')) {
                    var ajaxOpts = options.ajax || {};

                    $.extend(opts, {
                        ajax : {
                            url             : ajaxOpts.url || $select.data('ajaxUrl'),
                            dataType        : 'json',
                            processResults  : ajaxOpts.processResults,
                            processResults  : function(data) {
                                return {
                                    results: $.map(data.result.list, function(result) {
                                        return {
                                            id      : result.id || result, 
                                            text    : result.name || result
                                        };
                                    })
                                };
                            }
                        }
                    });
                }

                $select.select2(opts);                
            });
        } else {
            switch (options) {
                case 'initialValue':
                    var $select = $(this), value = Array.prototype.slice.call(arguments, 1);

                    if (null !== value) {
                        var id = value.id || value, text = value.name || value;

                        if (! $select.find('option[value="' + id + '"]').length) {
                            $select.append('<option value="' + id + '">' + text + '</option>');
                        }
                    }

                    $select.val(id).change();
                    
                    break;
                default:
                    return this.select2(options);
            }
        }
        
    };


    function formatOption(optionData) {
        if (! optionData.id) return optionData.text;

        var $option = $(optionData.element);

        if ($option.data('icon')) {
            return $('<img width="20px" src="' + $option.data('icon') + '" /><span class="inline-block">&nbsp;&nbsp;' + optionData.text + '</span>'); 
        }

        if ($option.data('html')) {
            return $(option.text);
        }

        return optionData.text;
    }
    
}));