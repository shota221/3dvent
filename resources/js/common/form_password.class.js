;(function(factory) {
    module.exports = factory(
        jQuery, 
        require('../components/util/class.js'),
        require('../components/form.class.js')
    );
}(function($, ClassUtils, BaseFormClass) {
    'use strict';

    let FormPassword;

    return FormPassword = ClassUtils.Extend(BaseFormClass, function FormPassword($elem) {
        FormPassword.prototype.__super__.constructor.call(this, $elem);

        this.$fieldPasswordChangedCheck = this.$form.find('input[name="passwordChanged"]');
        
        this.$fieldPasswordInput = this.$form.find('input[name="password"]');

        this.$fieldPasswordConfirmInput = this.$form.find('input[name="passwordConfirm"]');
    }, {
        build: function(options) {
            FormPassword.prototype.__super__.build.call(this, options);
            
            var self = this;

            this.$form
                .on('ifChanged.form', 'input[name="passwordChanged"]', function() {
                    var checked = $(this).prop('checked');

                    self.$form.find('.password-change-inputs').collapse(checked ? 'show' : 'hide');

                    self.$fieldPasswordInput.data('validationTypes', (checked ? ['required'] : [])).val('');

                    self.$fieldPasswordConfirmInput.data('validationTypes', (checked ? ['required'] : [])).val('');

                    return false;
                })
        }
        , onCreate: function() {
            this.$fieldPasswordChangedCheck.iCheck('check').prop('disabled', true);

            return this;
        }
        , onEdit: function() {
            this.$form.find('.password-change-inputs').collapse('hide');
            this.$fieldPasswordChangedCheck.iCheck('uncheck').prop('disabled', false);

            return this;
        }
        , validationTypes: function() {
            var self = this;

            return {
                passwordConfirm: [
                    (function unmatched(value) {
                        if (self.$fieldPasswordChangedCheck.prop('checked')) {
                            return self.$fieldPasswordInput.val() === self.$fieldPasswordConfirmInput.val();
                        }

                        return true;
                    }),
                ]
            }     
        }
    });
}));
