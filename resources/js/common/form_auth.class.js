;(function(factory) {
    module.exports = factory(
        jQuery, 
        require('../components/util/class.js'),
        require('../components/form.class.js')
    );
}(function($, ClassUtils, BaseFormClass) {
    'use strict';

    let FormAuth;

    return FormAuth = ClassUtils.Extend(BaseFormClass, function FormAuth($elem) {
        FormAuth.prototype.__super__.constructor.call(this, $elem);

        this.$fieldEmailInput = this.$form.find('input[name="email"]');
        
        this.$fieldPasswordInput = this.$form.find('input[name="password"]');

        this.$fieldRememberCheck = this.$form.find('input[name="remember"]');
    }, {
        
    });
}));
