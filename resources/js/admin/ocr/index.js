;(function(factory) {
    module.exports = factory(
        jQuery,
        i18n, 
        require('../../components/page.class'),
        require('./_form_setting.class'),
        require('./_form_result.class'),
        Ladda
    );
}(function($, i18n, PageClass, FormSettingClass, FormResultClass, Ladda) {
    'use strict';

    const 

        $loadFormatContentArea = $('#load-format-content')

        , $formatContentArea = $('#format-content')

        , $formatNameArea = $('#format-name')


        , $inputUrlHtmlFormat = $('input#url-async-html-format')

        , $inputUrlCheckQueueStatus= $('input#url-async-check-queue-status')


        , Page = new PageClass()

        , FormSetting = new FormSettingClass($('form#setting'))

        , FormResult = new FormResultClass($('form#content'))
    ;

    // ロード
    Page
        .load(function(deferred) {
            // setting form build 
            FormSetting
                .on('changeFormatId', function(formatId) {
                    $loadFormatContentArea
                        .asyncContent(
                            'load', 
                            function() {
                                return loadFormat(formatId);
                            });

                })
                .on('uploadedFile', function(file, response) {
                    var $overlay = $('<div class="content-overlay"><div class="loading" /></div>');

                    $formatContentArea.prepend($overlay);

                    loadResult(response.result.queue_name).done(function() {
                        $overlay.remove();
                    })
                })
                .build();

            // result form build 
            FormResult
                .build();
            
            deferred.resolve();
        });

    /**
     * フォーマットHTMLロード
     * 
     * @param  {[type]} formatId [description]
     * @return {[type]}          [description]
     */
    function loadFormat(formatId) {
        var deferred = $.Deferred();

        $formatNameArea.empty();

        $formatContentArea.html('<div class="no-content">帳票を選択してください</div>');

        if (formatId) {
            $.ext.ajax({
                ajaxName: 'getFormatHtml',
                url     : $inputUrlHtmlFormat.val().replace('FORMAT_ID', formatId),
                success : function(parsedResult) {
                    $formatNameArea
                        .html(parsedResult.name);

                    $formatContentArea
                        .html('<div style="border-top: 1px solid #eee;border-bottom: 1px solid #eee;padding: 20px;">' + parsedResult.html + '</div>')
                        .find('.answer-area').css('min-height', '50px')
                            .each(function() {
                                $('<div class="ocr-src" />')
                                    .append('<div class="no-content" style="margin-bottom: 10px;">回答用紙画像を読み込んでください</div>')
                                    .prependTo($(this))

                                    
                            });

                    deferred.resolve();
                }
            })
        } else {
            deferred.resolve();
        }

        return deferred
            .promise()
            .then(function() { FormResult.activate(false); });
    }

    /** 
     * OCR結果ロード
     * 
     * @param  {[type]} queue [description]
     * @return {[type]}       [description]
     */
    function loadResult(queue_name) {
        var pollingLimitCount   = 600
            , pollingInterval   = 1000
            , pollingCount      = 0
            , deferred          = $.Deferred()
            , polling           = function(queue_name) {
                if (pollingLimitCount === pollingCount++) {
                    $.ext.notify.error(i18n('message.queue_failed'));

                    deferred.resolve();

                    return;
                }
                
                setTimeout(function() {
                    $.ext.ajax({
                        ajaxName: 'queue-status-check-polling',
                        type    : $inputUrlCheckQueueStatus.data('method'),
                        url     : $inputUrlCheckQueueStatus.val(),
                        data    : { queue_name: queue_name },
                        success : function(parsedResult) {
                            if (! parsedResult.finished) {
                                polling(queue_name);
                            } else {
                                if (! parsedResult.has_error) {
                                    // success
                                    var result = parsedResult.result;

                                    result.forEach(function(pair) {
                                        var q = pair.q, a = pair.a, src = pair.src;

                                        FormResult.setResult(q, a, src);
                                    }) 

                                    FormResult.activate(true);                                              
                                } else {
                                    $.ext.notify.error(parsedResult.error_message);
                                }

                                deferred.resolve();
                            }
                        }
                    });
                }, pollingInterval);
            }
        ;

        polling(queue_name);

        return deferred.promise();
    }

}));