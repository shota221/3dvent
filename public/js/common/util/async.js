/**
 * 
 * @param {*} $featureElement data-{attr}を記述する要素。これをもとにAjax通信先を設定。
 * @param {Object} parameters リクエストパラメータ
 * @param {Function} successCallback 通信成功時の処理を記述。
 * @param {boolean} withMessages 通信の結果をメッセージとしてユーザーに通知したい場合はtrue。
 */
function asyncExecuteAjax($featureElement, parameters = {}, withMessages = false, successCallback = function (data) { }) {
    console.log($featureElement);
    //data-{}の属性から抽出
    var url = $featureElement.dataset.url;
    var type = $featureElement.dataset.method;

    if (withMessages) {
        asyncRemoveValidationErrorMessage();
        asyncRemoveAlertMessage();
        message = asyncBuildCompletionMessage(type)
    }

    $(document).ajaxSend(function () {
        asyncShowIndicator();
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
            asyncAlertMessage(message)
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
                    asyncDisplayValidationErrorMessage(result.errors);
                }
                break;
            case 500:
                alert(i18n('message.internal_server_error'));
                break;
            default:
                alert(i18n('message.internal_server_error'));
        }
    }).always(function () {
        asyncHideIndicator();
    });
}

function asyncShowIndicator() {
    $("#overlay").fadeIn(100);
}

function asyncHideIndicator() {
    setTimeout(function () {
        $("#overlay").fadeOut(100);
    }, 50);
}

function asyncBuildParameters(elements) {
    var parameters = {};
    var length = elements.length;
    for (var i = 0; i < length; i++) {
        switch (elements[i].type) {
            case 'radio':
                if (elements[i].checked) {
                    parameters[elements[i].name] = elements[i].value;
                }
                break;
            case 'checkbox':
                if (elements[i].checked) {
                    var hasProperty = parameters.hasOwnProperty(elements[i].name);
                    if (! hasProperty) {
                        parameters[elements[i].name] = [];
                    }
                    parameters[elements[i].name].push(elements[i].value);
                }
                break;
            default:
                parameters[elements[i].name] = elements[i].value;
        }
    }
    return parameters;
}

function asyncDisplayValidationErrorMessage(errors) {
    errors.forEach(function (error) {
        var key = error.key;
        var message = error.message.translated;
        var errorMessageElement = '<small class="text-danger error-message">' + message + '</small>'
        $(errorMessageElement).insertAfter($('input[name="' + key + '"]'));
    });
}

function asyncRemoveValidationErrorMessage() {
    var errorMessageElements = document.getElementsByClassName('error-message');
    var length = errorMessageElements.length;
    for (var i = 0; i < length; i++) {
        var errorMessageElement = errorMessageElements[0];
        errorMessageElement.remove();
    }
}

function asyncAlertMessage(message) {
    var asyncAlertMessageElement = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' + message + '</strong> </div>'
    $(asyncAlertMessageElement).insertAfter('#alert-message');
}

function asyncRemoveAlertMessage() {
    var asyncAlertMessageElements = document.getElementsByClassName('alert');
    if (asyncAlertMessageElements.length > 0) {
        asyncAlertMessageElements[0].remove();
    }
}

function asyncBuildCompletionMessage(type) {
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