$('#async-page-update').on(
    'click',
    function(e) {

        var parameters = {};
        parameters['vt_per_kg'] = $("#vt_per_kg").val();
        parameters['ventilator_value_scan_interval'] = $('#ventilator_value_scan_interval').val();

        var $element = $('#async-page-update');

        utilAsyncExecuteAjax($element, parameters, true);

        return false;
    }
)


