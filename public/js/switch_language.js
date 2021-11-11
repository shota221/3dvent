$('#nav-lang li').on(
    'click',
    function() {
        var parameters = {};

        var successCallback = function (data) {
            
            var cookie_key    = data['result']['cookie_key'];
            var language_code = data['result']['language_code'];
            document.cookie   = cookie_key + '=' + language_code;            

            window.location.reload();
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, false, successCallback);

        return false;
    }
)