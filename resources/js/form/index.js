; (function (factory) {
    module.exports = factory(
        jQuery,
        require('./_form_org_regist.class')
    );
}(function ($, FormOrgRegistClass) {
    'use strict'

    const
        FormOrgRegist = new FormOrgRegistClass($('form#form-content'))
        ;

    subscribeEvents();

    FormOrgRegist.build();

    function subscribeEvents() {
        FormOrgRegist
            .on('submit', function (submitDeferred, formData, action, method) {
                $.ext.ajax({
                    ajaxName: 'registOrg',
                    type: method,
                    url: action,
                    data: formData,
                    success: function (parsedResult) {
                        $.ext.notify.success(i18n('message.applied'));

                        //非同期処理完了
                        submitDeferred.resolve();
                    },
                    error: function (errors) {
                        FormOrgRegist.handleFieldErrors(errors);

                        //非同期処理異常終了
                        submitDeferred.reject();
                    }
                })
            })
    }
}));