$('#nav-lang li').on(
    'click',
    function() {
        var parameters = {};

        var successCallback = function (data) {
            
            // クッキーの設定値格納
            var cookie_key    = data['result']['cookie_key'];
            var language_code = data['result']['language_code'];
            var domain        = data['result']['domain'];
            var path          = data['result']['path'];
            var max_age       = data['result']['max_age'];

            // クッキー設定
            var cookie        = cookie_key + '=' + language_code;
            var domainOption  = '; Domain=' + domain;
            var pathOption    = '; Path=' + path;
            var maxAgeOption  = '; Max-Age=' + max_age;
            var cookieSetting = cookie + domainOption + pathOption + maxAgeOption;
            
            document.cookie = cookieSetting;
            
            window.location.reload();
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, false, successCallback);

        return false;
    }
)