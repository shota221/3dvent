;(function(factory) {
    module.exports = factory(
        jQuery,
        i18n, 
        require('./_form_profile.class'),
        require('../components/modal.class'),
    );
}(function($, i18n, FormProfileClass, ModalClass) {
    'use strict';

    const 
        $urlDataProfileInput = $('input#async-data-profile')

        , $profileEditBtn = $('.btn.edit-profile')

        , ModalProfile = new ModalClass($('#modal-profile-edit'))

        , FormProfile = new FormProfileClass(ModalProfile.$modal.find('form'))
    ;

    subscribeEvents();

    // regist form build
    FormProfile.build();

    // modal build
    ModalProfile.build();

    /**
     * イベントリスナー
     * 
     * @return {[type]} [description]
     */
    function subscribeEvents() {
        
        $profileEditBtn
            .on('click', function() {
                ModalProfile.open();
            });

        ModalProfile
            .on('show', function() {
                ModalProfile
                    .load(function(deferred) { 
                        $.ext.ajax({
                            ajaxName: 'getProfile',
                            type    : $urlDataProfileInput.data('method'),
                            url     : $urlDataProfileInput.val(),
                            success : function(parsedResult) {
                                FormProfile.bind(parsedResult).onEdit();

                                deferred.resolve();
                            }
                        })
                    });
            })

        FormProfile 
            .on('submit', function(submitDeferred, formData, action, method) {
                //
                $.ext.ajax({
                    ajaxName: 'editProfile',
                    type    : method,
                    url     : action,
                    data    : formData,
                    success : function(parsedResult) {
                        if (confirm(i18n('message.registered_reload'))) {
                            location.reload();   
                        } else {
                            submitDeferred.resolve();

                            ModalProfile.close();
                        }
                    },
                    error: function(errors) {
                        FormProfile.handleFieldErrors(errors);
                        
                        submitDeferred.reject();
                    }
                })
            })
            .on('cancel', function(cancelDeferred) {
                cancelDeferred.resolve();
                
                ModalProfile.close();
            });
    }

}));