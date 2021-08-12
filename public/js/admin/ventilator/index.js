//TODO: modal共通化
//register-modal
var $registerModal = $('#modal-ventilator-create');

$('#show-register-modal').on(
    'click',
    function () {
        utilFormRemoveValidationErrorMessage()

        $registerModal.modal();

        return false;
    });

$('button.modal-cancel').on(
    'click',
    function () {
        $(this).closest('.modal').modal('hide');

        return false;
    });

//edit-modal
var $editModal = $('#modal-ventilator-update');

$('#paginated-list').on(
    'click',
    '.show-edit-modal',
    function () {
        var dataset = $(this).closest('tr').data();

        $targetForm = $editModal.find('form[name="ventilator-update"]').eq(0);

        utilFormRemoveValidationErrorMessage();

        utilFormInputParameters($targetForm, dataset);

        var $featureElement = $(this);

        var parameters = {};

        var successCallback = function(data) {
            patient_code = data.result.patient_code;
            $editModal.find('input[name="patient_code"]').val(patient_code);
        }

        utilAsyncExecuteAjax($featureElement,parameters,false,successCallback);

        $editModal.modal();

        return false;
    });

//編集イベント
$('#async-ventilator-update',).on(
    'click',
    function () {
        //TODO:parameters格納
        var parameters = {};

        
        var $targetForm = $('form[name="ventilator-update"]');

        parameters['id'] = $targetForm.find('input[name="id"]').val();
        parameters['start_using_at'] = $targetForm.find('input[name="start_using_at"]').val();

        var successCallback = function (data) {
            $editModal.modal('hide');
        }

        var $btn = $('#async-ventilator-update');

        utilAsyncExecuteAjax($btn, parameters, true, successCallback);

        return false;
    });

//バグ一覧モーダル
var $ventilatorBugListModal = $('#modal-ventilator-bug-list');

$('#paginated-list').on(
    'click',
    '.show-ventilator-bug-list-modal',
    function () {
        var id = $(this).closest('tr').data('id');

        var parameters = {};

        parameters['id'] = id;

        var successCallback = function (ventilator_bug_list) {
            $('#ventilator-bug-list').html(ventilator_bug_list);
            $ventilatorBugListModal.modal();
        };

        var $featureElement = $(this);

        utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);

        return false;
    }
);