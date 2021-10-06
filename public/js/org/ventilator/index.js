const
    $editModal = $('#modal-ventilator-update'),
    $modalCancelBtn = $('button.modal-cancel'),
    $paginatedList = $('#paginated-list'),
    $ventilatorUpdateBtn = $('#async-ventilator-update'),
    $ventilatorBugListModal = $('#modal-ventilator-bug-list'),
    $searchForm = $('#async-search-form'),
    $searchBtn = $('#async-search'),
    $clearSearchFormBtn = $('#clear-search-form'),
    $ventilatorBugList = $('#ventilator-bug-list')
    ;

$modalCancelBtn.on(
    'click',
    function () {
        $(this).closest('.modal').modal('hide');

        return false;
    });

//edit-modal


$paginatedList.on(
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
$ventilatorUpdateBtn.on(
    'click',
    function () {
        var parameters = {};

        var $targetForm = $('form[name="ventilator-update"]');

        parameters['id'] = $targetForm.find('input[name="id"]').val();
        parameters['start_using_at'] = $targetForm.find('input[name="start_using_at"]').val();

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

        utilAsyncExecuteAjax($ventilatorUpdateBtn, parameters, true, successCallback);

        return false;
    });

//バグ一覧モーダル
$paginatedList.on(
    'click',
    '.show-ventilator-bug-list-modal',
    function () {
        var id = $(this).closest('tr').data('id');

        var parameters = {};

        parameters['id'] = id;

        var successCallback = function (ventilator_bug_list) {
            $ventilatorBugList.html(ventilator_bug_list);
            $ventilatorBugListModal.modal();
        };

        var $featureElement = $(this);

        utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);

        return false;
    }
);

// ページネーション
$paginatedList.on('click', '.page-link', function (e) {
    var $featureElement = $(this);

    var parameters = {};

    var successCallback = function (paginated_list) {
        $paginatedList.html(paginated_list);
    }

    utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);
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
    var parameters = {};

    parameters['start_using_at_from'] = $form.find('input[name="start_using_at_from"]').val();
    parameters['start_using_at_to'] = $form.find('input[name="start_using_at_to"]').val();
    parameters['expiration_date_from'] = $form.find('input[name="expiration_date_from"]').val();
    parameters['expiration_date_to'] = $form.find('input[name="expiration_date_to"]').val();
    parameters['serial_number'] = $form.find('input[name="serial_number"]').val();
    parameters['registered_user_name'] = $form.find('input[name="registered_user_name"]').val();
    parameters['has_bug'] = [];
    $form.find('input[name="has_bug"]:checked').each(function (i, elm) {
        parameters['has_bug'].push($(elm).val());
    });

    return parameters;
}

// ventilator_values遷移
$paginatedList.on(
    'click',
    '.show-ventilator_values',
    function () {
        var $form = $(this).closest('form');

        $form.trigger('submit');
    }
);

$('input.form-control.datetime').datetimepicker({
    step:5,
    format:'Y-m-d H:i:00'
})  

$('input.form-control.date').datetimepicker({
    timepicker:false,
    format:'Y-m-d'
})  