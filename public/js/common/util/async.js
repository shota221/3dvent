/**
 * 
 * @param {*} $featureElement data-{attr}を記述する要素。これをもとにAjax通信先を設定。
 * @param {Object} parameters リクエストパラメータ
 * @param {Function} successCallback 通信成功時の処理を記述。
 * @param {boolean} withMessages 通信の結果をメッセージとしてユーザーに通知したい場合はtrue。
 * @param {Object} extraSettings その他ajax設定。
 */
 function utilAsyncExecuteAjax($featureElement, parameters = {}, withMessages = false, successCallback = function (data) { }, extraSettings = {}) {
    //data-{}の属性から抽出
    var url = $featureElement.data('url');
    var type = $featureElement.data('method');

    if (withMessages) {
        utilAsyncRemoveAlertMessage();
        utilFormRemoveValidationErrorMessage();
        message = utilAsyncBuildCompletionMessage(type)
    }

    $(document).ajaxSend(function () {
        utilAsyncShowIndicator();
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    var ajaxSettings = Object.assign({
        url: url,
        type: type,
        data: parameters,
        timeout: 120000,
        cache: false,
    },extraSettings);

    $.ajax(ajaxSettings).done(function (data) {
        if (withMessages) {
            utilAsyncAlertMessage(message)
        }
        successCallback(data);
    }).fail(function (error) {
        switch (error.status) {
            case 0:
                alert(i18n('message.timeout'));
                break;
            case 400:
                if (withMessages) {
                    var result = JSON.parse(error.responseText);
                    utilFormDisplayValidationErrorMessage(result.errors);
                }
                break;
            case 500:
                alert(i18n('message.internal_server_error'));
                break;
            default:
                alert(i18n('message.internal_server_error'));
        }
    }).always(function () {
        utilAsyncHideIndicator();
    });
}

function utilAsyncShowIndicator() {
    $("#overlay").fadeIn(100);
}

function utilAsyncHideIndicator() {
    setTimeout(function () {
        $("#overlay").fadeOut(100);
    }, 50);
}

function utilAsyncAlertMessage(message) {
    var asyncAlertMessageElement = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' + message + '</strong> </div>'
    $(asyncAlertMessageElement).insertAfter('#alert-message');
}

function utilAsyncRemoveAlertMessage() {
    $('.alert').remove();
}

function utilAsyncBuildCompletionMessage(type) {
    switch (type.toLowerCase()) {
        case 'put':
            return i18n('message.updated');
            break;
        case 'post':
            return i18n('message.registered');
            break;
        case 'delete':
            return i18n('message.deleted');
            break;
        default:
            return '';
    }
}