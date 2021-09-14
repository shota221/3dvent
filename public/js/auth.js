// ログイン
$("#login").on(
    'click',
    function (e) {
        var parameters = {};
        parameters['name'] = $('[name=name]').val();
        parameters['password'] = $('[name=password]').val();
        parameters['remember'] = $('[name=remember]:checked').val();

        var $element = $(this);

        var successCallback = function(data) {
            var url = data['result']['redirect_to'];
            window.location.href = url;
        }

        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
)
