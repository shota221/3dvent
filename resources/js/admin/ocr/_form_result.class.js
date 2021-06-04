;(function(factory) {
    module.exports = factory(
        jQuery, 
        require('../../components/util/class.js'),
        require('../../components/form.class.js'),
    );
}(function($, ClassUtils, BaseFormClass) {
    'use strict';

    const $submitBtn = $('<span id="submit" class="btn btn-lg btn-block btn-primary margin-top">登録</span>');

    let FormResultClass;

    return FormResultClass = ClassUtils.Extend(BaseFormClass, function FormResultClass($elem) {
        FormResultClass.prototype.__super__.constructor.call(this, $elem);


    }, {
        activate: function(active) {
            this.$form.find('.qItem').each(function() {
                var $qItem = $(this), qname = $qItem.data('qname'), qtype = $qItem.data('qtype');

                switch (qtype) {
                    case 'text'     : 
                    case 'number'   :
                        var $input = $qItem.find('input[type="' + qtype + '"]');

                        if (active) {
                            $input.removeAttr('readonly').removeAttr('disabled');
                        } else {
                            $input.prop('readonly', true).prop('disabled', true);
                        } 

                        break;
                    case 'single'   :
                        var $input = $qItem.find('input[type="radio"]');
                        
                        if (active) {
                            $input.removeAttr('disabled');
                        } else {
                            $input.prop('disabled', true);
                        } 

                        break;
                }
            });

            if (active) {
                this.$form.append($submitBtn);
            } else {
                $submitBtn.remove();
            }

            return this;
        }
        , setResult: function(q, a, src) {
            var qname = q
                , $qItem = this.$form.find('.qItem[data-qname="' + qname + '"]')
                , qtype = $qItem.data('qtype');

            $qItem
                .find('.ocr-src')
                    .html('<img src="' + src + '" />');

            switch (qtype) {
                case 'text'     :
                case 'number'   : 
                    $qItem.find('input[type="' + qtype + '"][name="' + qname + '"]').val(a); 

                    break;
                case 'single'   :
                    var $selectedLabel = $qItem
                        .find('label')
                            .filter(function() {
                                return a === $(this).text()
                            })
                            .first();

                    var selectedValue = $selectedLabel.attr('for');

                    $qItem.find('input[type="radio"][value="' + selectedValue + '"]').prop('checked', true); 

                    break;
            }

            return this;
        }
    });

}));