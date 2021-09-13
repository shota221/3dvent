const
    $registerModal = $('#modal-organization-create'),
    $showRegisterModalBtn = $('#show-register-modal'),
    $organizationCreateBtn = $('#async-organization-create'),
    $modalCancelBtn = $('button.modal-cancel'),
    $editModal = $('#modal-organization-update'),
    $paginatedList = $('#paginated-list'),
    $userList = $('#user-list'),
    $organizationUpdateBtn = $('#async-organization-update'),
    $searchForm = $('#async-search-form'),
    $searchBtn = $('#async-search'),
    $clearSearchFormBtn = $('#clear-search-form'),
    $userListModal = $('#modal-user-list')
    ;

$showRegisterModalBtn.on(
    'click',
    function () {
        utilFormRemoveValidationErrorMessage()

        $registerModal.modal();

        return false;
    });

//登録イベント
$organizationCreateBtn.on(
    'click',
    function () {
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
            var $featureElement = $('.page-item' + '.active').children('button');

            var parameters = {};

            if (!$featureElement.length) {
                $featureElement = $searchBtn;
                parameters = buildSearchParameters($searchForm);
            }

            var successCallback = function (paginated_list) {
                $paginatedList.html(paginated_list);
            }

            utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);

            $registerModal.modal('hide');
        }

        utilAsyncExecuteAjax($organizationCreateBtn, parameters, true, successCallback);

        return false;
    });

$modalCancelBtn.on(
    'click',
    function () {
        $(this).closest('.modal').modal('hide');

        return false;
    });

$paginatedList.on(
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
$organizationUpdateBtn.on(
    'click',
    function () {
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
            var $featureElement = $('.page-item' + '.active').children('button');

            var parameters = {};

            if (!$featureElement.length) {
                $featureElement = $searchBtn;
                parameters = buildSearchParameters($searchForm);
            }

            var successCallback = function (paginated_list) {
                $paginatedList.html(paginated_list);
            }

            utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);

            $editModal.modal('hide');
        }

        utilAsyncExecuteAjax($organizationUpdateBtn, parameters, true, successCallback);

        return false;
    });

$paginatedList.on(
    'click',
    '.show-user-list-modal',
    function () {
        var id = $(this).closest('tr').data('id');

        var parameters = {};

        parameters['id'] = id;

        var successCallback = function (user_list) {
            $userList.html(user_list);
            $userListModal.modal();
        };

        var $featureElement = $(this);

        utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);

        return false;
    }
)

//ページネーション
// pagination
$paginatedList.on('click', '.page-link', function (e) {
    var $featureElement = $(this);

    var parameters = {};

    var successCallback = function (paginated_list) {
        $paginatedList.html(paginated_list);
    }

    utilAsyncExecuteAjax($featureElement, parameters, false, successCallback)
});

// async-search
$searchBtn.on(
    'click',
    function (e) {
        var $form = $searchForm;
        var parameters = buildSearchParameters($form);

        var successCallback = function (paginated_list) {
            $paginatedList.html(paginated_list);
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, false, successCallback);

        return false;
    }
)

// clear search form
$clearSearchFormBtn.on(
    'click',
    function (e) {
        $searchForm[0].reset();
    }
)

// build search parameters
function buildSearchParameters($form) {
    console.log('test');
    var parameters = {};

    parameters['organization_code'] = $form.find('input[name="organization_code"]').val();
    parameters['organization_name'] = $form.find('input[name="organization_name"]').val();
    parameters['representative_name'] = $form.find('input[name="representative_name"]').val();
    parameters['registered_at_from'] = $form.find('input[name="registered_at_from"]').val();
    parameters['registered_at_to'] = $form.find('input[name="registered_at_to"]').val();
    parameters['edc_linked_flg'] = [];
    $form.find('input[name="edc_linked_flg"]:checked').each(function (i, elm) {
        parameters['edc_linked_flg'].push($(elm).val());
    });
    parameters['patient_obs_approved_flg'] = [];
    $form.find('input[name="patient_obs_approved_flg"]:checked').each(function (i, elm) {
        parameters['patient_obs_approved_flg'].push($(elm).val());
    });
    parameters['disabled_flg'] = [];
    $form.find('input[name="disabled_flg"]:checked').each(function (i, elm) {
        parameters['disabled_flg'].push($(elm).val());
    });

    return parameters;
}