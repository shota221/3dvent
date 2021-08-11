//TODO: modal共通化
//register-modal
var $registerModal = $('#modal-organization-create');

$('#show-register-modal').on(
    'click',
    function () {
        utilFormRemoveValidationErrorMessage()

        $registerModal.modal();

        return false;
    });

//登録イベント
$('#async-organization-create').on(
    'click',
    function () {
        //TODO:parameters格納
        var parameters = {};

        var $targetForm = $('form[name="organization-create"]');

        parameters['organization_name'] = $targetForm.find('input[name="organization_name"]').val();
        parameters['representative_name'] = $targetForm.find('input[name="representative_name"]').val();
        parameters['representative_email'] = $targetForm.find('input[name="representative_email"]').val();
        parameters['organization_code'] = $targetForm.find('input[name="organization_code"]').val();
        parameters['disabled_flg'] = $targetForm.find('input[name="disabled_flg"]:checked').val();
        parameters['edcid'] = $targetForm.find('input[name="edcid"]').val();
        parameters['patient_obs_approved_flg'] = $targetForm.find('input[name="patient_obs_approved_flg"]:checked').val();

        var successCallback = function (data) {
            $registerModal.modal('hide');
        }

        var $btn = $('#async-organization-create');

        utilAsyncExecuteAjax($btn, parameters, true, successCallback);

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
        var dataset = $(this).closest('tr').data();

        $targetForm = $editModal.find('form[name="organization-update"]').eq(0);

        utilFormRemoveValidationErrorMessage();

        utilFormInputParameters($targetForm, dataset);

        $editModal.modal();

        return false;
    });

//編集イベント
$('#async-organization-update',).on(
    'click',
    function () {
        //TODO:parameters格納
        var parameters = {};

        
        var $targetForm = $('form[name="organization-update"]');

        parameters['id'] = $targetForm.find('input[name="id"]').val();
        parameters['organization_name'] = $targetForm.find('input[name="organization_name"]').val();
        parameters['representative_name'] = $targetForm.find('input[name="representative_name"]').val();
        parameters['representative_email'] = $targetForm.find('input[name="representative_email"]').val();
        parameters['organization_code'] = $targetForm.find('input[name="organization_code"]').val();
        parameters['disabled_flg'] = $targetForm.find('input[name="disabled_flg"]:checked').val();
        parameters['edcid'] = $targetForm.find('input[name="edcid"]').val();
        parameters['patient_obs_approved_flg'] = $targetForm.find('input[name="patient_obs_approved_flg"]:checked').val();

        var successCallback = function (data) {
            $editModal.modal('hide');
        }

        var $btn = $('#async-organization-update');

        utilAsyncExecuteAjax($btn, parameters, true, successCallback);

        return false;
    });

//ユーザー一覧モーダル
var $usersListModal = $('#modal-users-list');

$('#paginated-list').on(
    'click',
    '.show-users-list-modal',
    function () {
        var id = $(this).closest('tr').data('id');

        var parameters = {};

        parameters['id'] = id;

        var successCallback = function (users_list) {
            $('#users-list').html(users_list);
            $usersListModal.modal();
        };

        var $featureElement = $(this);

        utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);

        return false;
    }
)