//TODO: modal共通化
//register-modal
var $registerModal = $('#modal-organization-create');

$(document).on('click', '#show-register-modal', function (e) {
    removeValidationErrorMessage()
    $registerModal.modal();
});

//登録イベント
$(document).on(
    'click',
    '#async-organization-create',
    function (e) {
        e.preventDefault;

        var parameters = buildParameters(document.forms['organization-create'].elements);

        var successCallback = function (data) {
            $registerModal.modal('hide');
        }

        var btn = document.getElementById('async-organization-create');

        executeAjax(btn, parameters, true, successCallback);
    }
)

$(document).on(
    'click',
    'button.modal-cancel',
    function (e) {
        e.preventDefault;

        $(this).closest('.modal').modal('hide');
    }
);

//edit-modal
var $editModal = $('#modal-organization-update');

$(document).on('click', '.show-edit-modal', function (e) {
    var dataset = $(this).closest('tr').get(0).dataset;
    $targetForm = $editModal.find('form[name="organization-update"]').eq(0);
    removeValidationErrorMessage()
    inputParameters($targetForm, dataset);
    $editModal.modal();
});

//編集イベント
$(document).on(
    'click',
    '#async-organization-update',
    function (e) {
        e.preventDefault;

        var parameters = buildParameters(document.forms['organization-update'].elements);

        var successCallback = function (data) {
            $editModal.modal('hide');
        }

        var btn = document.getElementById('async-organization-update');

        executeAjax(btn, parameters, true, successCallback);
    }
)