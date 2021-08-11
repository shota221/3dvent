$('#async-page-update').on(
    'click',
    function(e) {
        var parameters = utilFormBuildParameters($('form[name="async-page-update"]').get(0).elements);

        var element = $('#async-page-update').get(0);

        utilAsyncExecuteAjax(element, parameters, true);

        return false;
    }
)


