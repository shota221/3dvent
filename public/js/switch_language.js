$('#nav-lang li').on(
    'click',
    function() {
        var parameters = {};

        var successCallback = function (data) {
            
            // クッキーの設定値格納
            var cookieKey    = data['result']['cookie_key'];
            var languageCode = data['result']['language_code'];
            var domain       = data['result']['domain'];
            var path         = data['result']['path'];
            var maxAge       = data['result']['max_age'];

            // クッキー設定
            var cookie        = cookieKey + '=' + languageCode;
            var domainOption  = '; Domain=' + domain;
            var pathOption    = '; Path=' + path;
            var maxAgeOption  = '; Max-Age=' + maxAge;
            var cookieSetting = cookie + domainOption + pathOption + maxAgeOption;
            
            document.cookie = cookieSetting;
            
            window.location.reload();
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, false, successCallback);

        return false;
    }
)