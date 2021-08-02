; (function (factory) {
    module.exports = factory(
        jQuery,
        i18n,
    );
}(function ($, i18n) {
    'use strict';

    $("#async").click(function (e) {
        e.preventDefault();

        removeValidationErrorMessage();
        removeAlertMessage();

        var btn = document.getElementById('async');
        var method = btn.dataset.method; //CRUD  
        var elements = document.forms[method].elements;
        var url = document.forms[method].action;
        var type = btn.dataset.type;
        var message = buildCompletionMessage(type);

        // パラメータ作成
        var parameters = buildParameters(elements);

        $(document).ajaxSend(function () {
            showIndicator();
        });

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $.ajax({
            url: url,
            type: type,
            contentType: "application/json",
            data: JSON.stringify(parameters),
            dataType: "json",
            timeout: 120000,
            cache: false,
        }).done(function (data) {
            alertMessage(message)
        }).fail(function (error) {
            switch (error.status) {
                case 0:
                    alert('レスポンスタイムアウト。処理は完了している可能性があります。画面をリロードし、確認後再度実行してみてください。');
                    break;
                case 400:
                    var result = JSON.parse(error.responseText);
                    displayValidationErrorMessage(result.errors);
                    break;
                case 500:
                    alert('エラーが発生しました。再度生じる場合は管理者まで報告をお願いします。');
                    break;
                default:
                    alert('エラーが発生しました。再度生じる場合は管理者まで報告をお願いします。');
            }
        }).always(function () {
            hideIndicator();
        });
    });

    function showIndicator() {
        $("#overlay").fadeIn(100);
    }

    function hideIndicator() {
        setTimeout(function () {
            $("#overlay").fadeOut(100);
        }, 50);
    }

    function buildParameters(elements) {
        var parameters = {};

        for (let element of elements) {
            parameters[element.name] = element.value;
        }

        return parameters;
    }

    function displayValidationErrorMessage(errors) {
        errors.forEach(function (error) {
            var key = error.key;
            var message = error.message.translated;
            var errorMessageElement = '<small class="text-danger error-message">' + message + '</small>'
            $(errorMessageElement).insertAfter('#' + key);
        });
    }

    function removeValidationErrorMessage() {
        var errorMessageElements = document.getElementsByClassName('error-message');
        var length = errorMessageElements.length;
        for (var i = 0; i < length; i++) {
            var errorMessageElement = errorMessageElements[0];
            errorMessageElement.remove();
        }
    }

    function alertMessage(message) {
        var alertMessageElement = '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' + message + '</strong> </div>'
        $(alertMessageElement).insertAfter('#alert-message');
    }

    function removeAlertMessage() {
        var alertMessageElements = document.getElementsByClassName('alert');
        if (alertMessageElements.length > 0) {
            alertMessageElements[0].remove();
        }
    }

    function buildCompletionMessage(type) {
        switch (type.toLowerCase()) {
            case 'put':
                return '更新しました。';
                break;
            case 'post':
                return '登録しました。';
                break;
            case 'delete':
                return '削除しました。';
                break;
            default:
                return '';
        }

    }
}));