;(function(factory) {
    module.exports = factory(
        jQuery, 
        require('../../components/util/class.js'),
        require('../../components/form.class.js'),
    );
}(function($, ClassUtils, BaseFormClass) {
    'use strict';

    let FormApplyPasswordReset;

    return FormApplyPasswordReset = ClassUtils.Extend(BaseFormClass, function FormApplyPasswordReset($elem) {
        FormApplyPasswordReset.prototype.__super__.constructor.call(this, $elem);

        this.$fieldEmailInput = this.$form.find('input[name="email"]');
    }, {
    });
}));