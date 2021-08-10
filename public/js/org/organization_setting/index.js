$('#async-page-update').on(
    'click',
    function(e) {
        var parameters = utilFormBuildParameters($('form[name="async-page-update"]').get(0).elements);

        var element = document.getElementById('async-page-update');

        utilAsyncExecuteAjax(element, parameters, true);

        return false;
    }
)


