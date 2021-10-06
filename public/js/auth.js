const 
    $nameInput     = $('[name=name]'),
    $passwordInput = $('[name=password]');

// ログイン
$("#login").on(
    'click',
    function (e) {
        var parameters = {};
        parameters['name'] = $nameInput.val();
        parameters['password'] = $passwordInput.val();
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
