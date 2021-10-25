const 
    $asyncUpdate                      = $('#async-page-update'),
    $cancelModal                      = $('button.modal-cancel'),
    $vtPerKgInput                     = $('#vt_per_kg'),
    $ventilatorValueScanIntervalInput = $('#ventilator_value_scan_interval');

    $asyncUpdate.on(
    'click',
    function() {

        var parameters = {};
        parameters['vt_per_kg'] = $vtPerKgInput.val();
        parameters['ventilator_value_scan_interval'] = $ventilatorValueScanIntervalInput.val();

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, true);

        return false;
    }
)

// hide modal 
$cancelModal.on(
    'click',
    function (e) {
        $(this).closest('.modal').modal('hide');

        return false;
    }
)


