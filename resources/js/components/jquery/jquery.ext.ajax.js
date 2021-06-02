/********************************
 * jQuery 拡張 $.ajax カスタマイズ
 ********************************/

if (typeof jQuery === 'undefined') throw new Error('required jQuery');

;(function(factory) {
    module.exports = factory(
        jQuery
    );
}(function($) {

    let defaults = {
        notify  : {
            error   : null,
            warn    : null,
            clear   : null,
        },
        csrf       : {
            header  : null,
            token   : null,
            postKey : null,
        },
        validationErrorsParse : function(parsedErrorResult) { return parsedErrorResult; }
    };

    $.ext = $.ext || {};

    /**
     * $.ajax カスタマイズ
     *
     * abort制御など
     * @param object
     */
    $.ext.ajax = function(params) {
        var ajaxParams, token, ajaxName, progress, request;

        ajaxParams = 
            $.extend(
                {
                    type       : 'GET',
                    contentType: 'application/json',
                    headers    : null,
                    url        : '',
                    data       : null,
                    timeout    : 120000, // 既定タイムアウト 120s
                    dataType   : 'json',
                    async      : true,
                    cache      : false,
                }, 
                params || {}, 
                {
                    success: function (data, textStatus, xhr) {
                        if (params.success) params.success(data.result);
                    },
                    error: function (xhr, textStatus, errThrown) {
                        if (textStatus == 'abort' && xhr.statusText == 'abort') {
                            return false;
                        } else {
                            switch (xhr.status) {
                                case 0:
                                    if (xhr.statusText == 'timeout') {
                                        // timeout
                                        if (defaults.notify.error) {
                                            defaults.notify.error('レスポンスタイムアウト。処理は完了している可能性があります。画面をリロードし、確認後再度実行してみてください。');
                                        }
                                    } else {
                                        //
                                    }

                                    if (params.fatal) params.fatal();

                                    break;
                                case 400: // badrequest
                                    var errorObj = {};

                                    try {
                                        // バリデーションエラー
                                        var result = $.parseJSON(xhr.responseText);

                                        /** 
                                         * 整形
                                         *
                                         * @param  {[type]} parsedErrorResult
                                         * @return {[type]} errorObject 
                                         * errorObj = {
                                         *     {fieldName} : [{message}] or [errorObj]
                                         * }
                                         */
                                        errorObj = defaults.validationErrorsParse(result);

                                        //console.log(errorObj);  
                                    } catch (e) {
                                        var e2 = new Error('json parse 失敗');

                                        e2.stack = e.stack;
                                        
                                        throw e2;
                                    }

                                    if (defaults.notify.warn) defaults.notify.warn(errorObj.global || '入力値が不正です。');

                                    if (params.error) params.error(errorObj);

                                    break;
                                case 401: // unauthorized
                                    var result;

                                    try {
                                        result = $.parseJSON(xhr.responseText);
                                    } catch (e) {
                                        defaults.notify.warn('権限がありません。');

                                        return false;
                                        //throw new Error('json parse 失敗');
                                    }

                                    if (result.redirectTo) {
                                        if (confirm('セッション有効期限切れです。' + result.redirectTo + ' にリダイレクトします。')) {
                                            location.href = result.redirectTo;

                                            return false;
                                        }
                                    } else {
                                        location.reload();
                                    }

                                    break;
                                case 500:
                                    if (defaults.notify.error) {
                                        defaults.notify.error('エラーが発生しました。再度生じる場合は管理者まで報告をお願いします。');
                                    }

                                    if (params.fatal) params.fatal();

                                    break;
                                default:
                                    if (defaults.notify.error) {
                                        defaults.notify.error('エラーが発生しました。再度生じる場合は管理者まで報告をお願いします。');
                                    }

                                    if (params.fatal) params.fatal();
                            }

                            console.error(xhr, textStatus, errThrown);
                        }
                    },
                    complete: function(xhr, textStatus) {
                        if (params.complete) params.complete((xhr.status === 200), xhr, textStatus);
                        
                        // progress ajax取得
                        var progress = $(document).data('progressAjaxRequests');

                        // 完了
                        delete progress[ajaxName];

                        $(document).data('progressAjaxRequests', progress);
                    }
                });
            
        //if (ajaxParams.data instanceof Array) { $.error('配列データはNGです。') } 

        // CSRF TOKEN取得
        if (defaults.csrf.token) {
            token = defaults.csrf.token();

            if (defaults.csrf.header) {
                // CSRF-TOKEN HEADER
                ajaxParams.headers = ajaxParams.headers || {}; ajaxParams.headers[defaults.csrf.header] = token;
            }

            if (defaults.csrf.postKey) {
                // BODYにセット
                ajaxParams.data = ajaxParams.data || {}; ajaxParams.data[defaults.csrf.postKey] = token;
            }
        }

        // if to JSON
        if ('application/json' === ajaxParams.contentType && 'GET' !== ajaxParams.type) {
            ajaxParams.data = JSON.stringify(ajaxParams.data);
        }
        
        // ajax name
        ajaxName = ajaxParams.ajaxName || 'ajaxrequest';

        // progress ajax取得
        progress = $(document).data('progressAjaxRequests') || {};
        
        // if exist ajax
        //if (progress[ajaxName]) progress[ajaxName].abort(); // abort before ajax
        if (progress[ajaxName]) {
            params.complete && params.complete(false, { statusText: 'abort' }, 'abort'); // abort after ajax

            return false;
        }

        // notify remove
        if (defaults.notify.clear) defaults.notify.clear();
       
        // request start
        request = $.ajax(ajaxParams);
        
        // progress ajaxに追加
        progress[ajaxName] = request;

        $(document).data('progressAjaxRequests', progress);

        return true;
    };

    /*
     * bind before unload for ajax abort
     */
    $('body')
        .off('ajaxSend')
        .on('ajaxSend', function(c, xhr) {
            $(window).on('beforeunload', function() {
                xhr.abort();
            })
        });

    return defaults;
}));

