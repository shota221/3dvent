;(function(factory) {
    module.exports = factory(
        jQuery,
        i18n, 
        require('./_form_password_reset.class'),
    );
}(function($, i18n, FormRegistClass) {
    'use strict';

    const 
        FormRegist = new FormRegistClass($('form')).build();
    ;

    subscribeEvents();

    /**
     * イベントリスナー
     * 
     * @return {[type]} [description]
     */
    function subscribeEvents() {
        FormRegist
            .on('submit', submit)
            .on('cancel', function(cancelDeferred) {
                location.reload();
            });
    }

    /**
     * 確定
     * 
     * @return {[type]} [description]
     */
    function submit(submitDeferred, formData, url, method) {
        FormRegist.clearErrors();
        
        $.ext.ajax({
            ajaxName: 'submit',
            type    : method,
            url     : url,
            data    : formData,
            success : function(parsedResult) {
                if (confirm(i18n('message.accept_password_reset'))) {
                    location.href = parsedResult.homeUrl;
                } else {
                    submitDeferred.resolve();
                }
            },
            error: function(errors) {
                if (errors) {
                    FormRegist.handleFieldErrors(errors);
                }

                submitDeferred.reject();
            }
        });
    }

}));