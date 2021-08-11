/**
 * 
 * @param {*} $featureElement data-{attr}を記述する要素。これをもとにAjax通信先を設定。
 * @param {Object} parameters リクエストパラメータ
 * @param {Function} successCallback 通信成功時の処理を記述。
 * @param {boolean} withMessages 通信の結果をメッセージとしてユーザーに通知したい場合はtrue。
 */
function utilAsyncExecuteAjax($featureElement, parameters = {}, withMessages = false, successCallback = function (data) { }) {
    console.log($featureElement);
    //data-{}の属性から抽出
    var url = $featureElement.dataset.url;
    var type = $featureElement.dataset.method;

    if (withMessages) {
        utilFormRemoveValidationErrorMessage();
        utilAsyncRemoveAlertMessage();
        message = utilAsyncBuildCompletionMessage(type)
    }

    $(document).ajaxSend(function () {
        utilAsyncShowIndicator();
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url: url,
        type: type,
        contentType: "application/json",
        data: JSON.stringify(parameters),
        timeout: 120000,
        cache: false,
    }).done(function (data) {
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
    var asyncAlertMessageElements = document.getElementsByClassName('alert');
    if (asyncAlertMessageElements.length > 0) {
        asyncAlertMessageElements[0].remove();
    }
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