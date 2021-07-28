;(function(factory) {
    module.exports = factory(
        jQuery, 
        require('../../components/util/class.js'),
        require('../../components/form.class.js'),
    );
}(function($, ClassUtils, BaseFormClass) {
    'use strict';

    let FormRegist;

    return FormRegist = ClassUtils.Extend(BaseFormClass, function FormRegist($elem) {
        FormRegist.prototype.__super__.constructor.call(this, $elem);

        this.$fieldTokenInput = this.$form.find('input[name="token"]');

        this.$fieldEmailInput = this.$form.find('input[name="email"]');

        this.$fieldPasswordInput = this.$form.find('input[name="password"]');

        this.$fieldPasswordConfirmInput = this.$form.find('input[name="passwordConfirm"]');
    }, {
        validationTypes: function() {
            var self = this;

            return {
                passwordConfirm: [
                    (function unmatched(value) {
                        return self.$fieldPasswordInput.val() === self.$fieldPasswordConfirmInput.val();
                    }),
                ]
            }     
        }
    });
}));