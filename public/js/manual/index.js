const 
    $calculateFio2Form = $('#calculate_fio2'),
    $airFlowInput      = $calculateFio2Form.find('[name="air_flow"]'),
    $o2FlowInput       = $calculateFio2Form.find('[name="o2_flow"]'),
    $fio2Text          = $calculateFio2Form.find('#fio2')
;

$airFlowInput.on(
    'change',
    function () {
        asyncCaluculateFio2();
    }
)

$o2FlowInput.on(
    'change',
    function () { 
        asyncCaluculateFio2();
    }
)

// fio2取得
function asyncCaluculateFio2() {
    utilFormRemoveValidationErrorMessage()

    if ($airFlowInput.val() == "" || $o2FlowInput.val() == "") {
        return
    } 

    var parameters = {};
    parameters['air_flow'] = $airFlowInput.val();
    parameters['o2_flow']  = $o2FlowInput.val();

    var successCallback = function (data) {
        $fio2Text.text(data['result']['fio2']);
    }

    var $element = $calculateFio2Form;
    console.log($element.data('url'));

    utilAsyncExecuteAjax($element, parameters, true, successCallback);
}