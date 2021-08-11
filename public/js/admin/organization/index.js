//TODO: modal共通化
//register-modal
var $registerModal = $('#modal-organization-create');

$('#show-register-modal').on(
    'click',
    function () {
        asyncRemoveValidationErrorMessage()

        $registerModal.modal();

        return false;
    });

//登録イベント
$('#async-organization-create').on(
    'click',
    function () {
        var parameters = asyncBuildParameters($('form[name="organization-create"]').get(0).elements);

        var successCallback = function (data) {
            $registerModal.modal('hide');
        }

        var btn = document.getElementById('async-organization-create');

        asyncExecuteAjax(btn, parameters, true, successCallback);

        return false;
    });

$('button.modal-cancel').on(
    'click',
    function () {
        $(this).closest('.modal').modal('hide');

        return false;
    });

//edit-modal
var $editModal = $('#modal-organization-update');

$('#paginated-list').on(
    'click',
    '.show-edit-modal',
    function () {
        var dataset = $(this).closest('tr').get(0).dataset;

        $targetForm = $editModal.find('form[name="organization-update"]').eq(0);

        asyncRemoveValidationErrorMessage();

        formInputParameters($targetForm, dataset);

        $editModal.modal();

        return false;
    });

//編集イベント
$('#async-organization-update',).on(
    'click',
    function () {
        var parameters = asyncBuildParameters($('form[name="organization-update"]').get(0).elements);

        var successCallback = function (data) {
            $editModal.modal('hide');
        }

        var btn = document.getElementById('async-organization-update');

        asyncExecuteAjax(btn, parameters, true, successCallback);

        return false;
    });

//ユーザー一覧モーダル
var $usersListModal = $('#modal-users-list');

$('#paginated-list').on(
    'click',
    '.show-users-list-modal',
    function () {
        var id = $(this).closest('tr').get(0).dataset.id;

        var parameters = {};

        parameters['id'] = id;

        var successCallback = function (users_list) {
            $('#users-list').html(users_list);
            $usersListModal.modal();
        };

        var featureElement = $(this).get(0);

        asyncExecuteAjax(featureElement, parameters, false, successCallback);

        return false;
    }
)