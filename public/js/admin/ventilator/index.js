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

        var successCallback = function (data) {
            patient_code = data.result.patient_code;
            $editModal.find('input[name="patient_code"]').val(patient_code);
        }

        utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);

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
            location.reload();
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

/** 
 * checkbox管理
 */
$('#paginated-list').on(
    'click',
    '.item-check',
    function () {
        var checkboxCount = $('.item-check').length;
        var selectedCount = $('.item-check:checked').length;
        if (checkboxCount === selectedCount) {
            $('#bulk-check').prop('indeterminate', false).prop('checked', true);
        } else if (selectedCount === 0) {
            $('#bulk-check').prop('indeterminate', false).prop('checked', false);
        } else {
            $('#bulk-check').prop('indeterminate', true).prop('checked', true);
        }
    }
).on(
    'click',
    '#bulk-check',
    function () {
        var checked = $(this).prop('checked');
        $('.item-check').each(function () {
            $(this).prop('checked', checked);
        });
    }
);

/**
 * 一括削除
 */
$('#paginated-list').on(
    'click',
    '#btn-bulk-delete',
    function () {
        var selectedCount = $('.item-check:checked').length;

        if (selectedCount > 0) {
            var confirmed = confirm(i18n('message.delete_count_confirm', { count: selectedCount }));
            if (confirmed) {
                var $featureElement = $(this);

                var parameters = { 'ids': [] }

                $('.item-check:checked').closest('tr').each(function (i, elm) {
                    parameters['ids'].push($(elm).data('id'));
                });

                var successCallback = function(data){
                    location.reload();
                }

                utilAsyncExecuteAjax($featureElement,parameters,true,successCallback)
            }
        } else {
            alert(i18n('message.object_unselected'));
        }
    }
)