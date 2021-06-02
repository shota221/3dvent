;(function(factory) {
    module.exports = factory(
        jQuery,
        i18n, 
        require('../../common/form_auth.class'),
        require('./_form_apply_password_reset.class'),
        require('../../components/modal.class'),
    );
}(function($, i18n, FormAuthClass, FormApplyPasswordResetClass, ModalClass) {
    'use strict';

    const 
        FormAuth                   = new FormAuthClass($('form'))
    
        , $applyPasswordResetBtn    = $('.apply-password-reset')

        , ModalApplyPasswordReset   = new ModalClass($('#modal-apply-password-reset'))

        , FormApplyPasswordReset    = new FormApplyPasswordResetClass(ModalApplyPasswordReset.$modal.find('form'))
    ;

    subscribeEvents();

    // login form build
    FormAuth.build();

    // regist form build
    FormApplyPasswordReset.build();

    // modal build
    ModalApplyPasswordReset.build();

    /**
     * イベントリスナー
     * 
     * @return {[type]} [description]
     */
    function subscribeEvents() {

        FormAuth 
            .on('submit', function(submitDeferred, formData, action, method) {
                //
                $.ext.ajax({
                    ajaxName: 'auth',
                    type    : method,
                    url     : action,
                    data    : $.extend({}, { auth_type: 'session' }, formData),
                    success : function(parsedResult) {
                        location.href = parsedResult.redirect_to;

                        return;
                    },
                    error: function(errors) {
                        FormAuth.handleFieldErrors(errors);
                        
                        submitDeferred.reject();
                    }
                })
            });
        
        $applyPasswordResetBtn
            .on('click', function() {
                ModalApplyPasswordReset.open();
            });

        FormApplyPasswordReset 
            .on('submit', function(submitDeferred, formData, action, method) {
                //
                $.ext.ajax({
                    ajaxName: 'applyPasswordReset',
                    type    : method,
                    url     : action,
                    data    : formData,
                    success : function(parsedResult) {
                        $.ext.notify.info(i18n('message.accept_password_reset_application'));

                        submitDeferred.resolve();

                        ModalApplyPasswordReset.close();
                    },
                    error: function(errors) {
                        FormApplyPasswordReset.handleFieldErrors(errors);
                        
                        submitDeferred.reject();
                    }
                })
            })
            .on('cancel', function(cancelDeferred) {
                cancelDeferred.resolve();
                
                ModalApplyPasswordReset.close();
            });
    }

}));