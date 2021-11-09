$('#nav-lang li').on(
    'click',
    function() {
        var parameters = {};

        var successCallback = function (data) {
            window.location.reload();
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, false, successCallback);

        return false;
    }
)