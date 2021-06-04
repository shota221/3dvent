;(function(factory) {
    module.exports = factory(
        jQuery, 
        require('../components/util/class.js'),
        require('../common/form_password.class.js')
    );
}(function($, ClassUtils, FormPasswordClass) {
    'use strict';

    let FormClass;

    return FormClass = ClassUtils.Extend(FormPasswordClass, function FormClass($elem) {
        FormClass.prototype.__super__.constructor.call(this, $elem);
        
        this.$fieldFirstNameInput = this.$form.find('input[name="first_name"]');

        this.$fieldLastNameInput = this.$form.find('input[name="last_name"]');

        this.$fieldFirstNameKanaInput = this.$form.find('input[name="first_name_kana"]');

        this.$fieldLastNameKanaInput = this.$form.find('input[name="last_name_kana"]');


        this.$fieldEmailInput = this.$form.find('input[name="email"]');
    }, {
        bind: function(editData) {
            editData = editData || {};

            this.clear();

            if (! $.isEmptyObject(editData)) {

                this.$fieldFirstNameInput.val(editData.first_name);

                this.$fieldLastNameInput.val(editData.last_name);

                this.$fieldFirstNameKanaInput.val(editData.first_name_kana);

                this.$fieldLastNameKanaInput.val(editData.last_name_kana);

                this.$fieldEmailInput.val(editData.email);
            }

            return this;
        }
    });
}));