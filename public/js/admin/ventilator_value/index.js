const
    $editModal = $('#modal-ventilator-update'),
    $registerModal = $('#modal-ventilator-create'),
    $showRegisterModalBtn = $('#show-register-modal'),
    $modalCancelBtn = $('button.modal-cancel'),
    $paginatedList = $('#paginated-list'),
    $ventilatorValueUpdateBtn = $('#async-ventilator-update'),
    $searchForm = $('#async-search-form'),
    $searchBtn = $('#async-search'),
    $clearSearchFormBtn = $('#clear-search-form'),
    $select2OrganizationName = $('#search-organization-name')
    ;


// $showRegisterModalBtn.on(
//     'click',
//     function () {
//         utilFormRemoveValidationErrorMessage()

//         $registerModal.modal();

//         return false;
//     });

// $modalCancelBtn.on(
//     'click',
//     function () {
//         $(this).closest('.modal').modal('hide');

//         return false;
//     });

// //edit-modal


// $paginatedList.on(
//     'click',
//     '.show-edit-modal',
//     function () {
//         var dataset = $(this).closest('tr').data();

//         $targetForm = $editModal.find('form[name="ventilator-update"]').eq(0);

//         utilFormRemoveValidationErrorMessage();

//         utilFormInputParameters($targetForm, dataset);

//         var $featureElement = $(this);

//         var parameters = {};

//         var successCallback = function (data) {
//             patient_code = data.result.patient_code;
//             $editModal.find('input[name="patient_code"]').val(patient_code);
//         }

//         utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);

//         $editModal.modal();

//         return false;
//     });

// //編集イベント
// $ventilatorUpdateBtn.on(
//     'click',
//     function () {
//         var parameters = {};

//         var $targetForm = $('form[name="ventilator-update"]');

//         parameters['id'] = $targetForm.find('input[name="id"]').val();
//         parameters['start_using_at'] = $targetForm.find('input[name="start_using_at"]').val();

//         var successCallback = function (data) {
//             var $featureElement = $('.page-item' + '.active').children('button');

//             var parameters = {};

//             if (!$featureElement.length) {
//                 $featureElement = $searchBtn;
//                 parameters = buildSearchParameters($searchForm);
//             }

//             var successCallback = function (paginated_list) {
//                 $paginatedList.html(paginated_list);
//             }

//             utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);

//             $editModal.modal('hide');
//         }

//         utilAsyncExecuteAjax($ventilatorUpdateBtn, parameters, true, successCallback);

//         return false;
//     });

/** 
 * checkbox管理(gmail風にunchecked,indeterminate,checkedの三段階)
 */
$paginatedList.on(
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
$paginatedList.on(
    'click',
    '#btn-bulk-delete',
    function () {
        var selectedCount = $('.item-check:checked').length;

        if (selectedCount > 0) {
            var confirmed = confirm(i18n('message.delete_count_confirm', { count: selectedCount }));
            if (confirmed) {
                var $featureElement = $(this);

                var parameters = { 'ids': [] }

                $('.item-check:checked').each(function (i, elm) {
                    parameters['ids'].push($(elm).val());
                });

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
                }

                utilAsyncExecuteAjax($featureElement, parameters, true, successCallback)
            }
        } else {
            alert(i18n('message.object_unselected'));
        }
    }
);

// build select2(organization)
function buildSelect2() {

    var parameters = {};

    var $element = $('#async-organization-data');

    var successCallback = function (data) {
        console.log(data);
        var organizations = [];

        data.forEach(function (datum) {
            var organization = {};
            organization['id'] = datum['id'];
            organization['text'] = datum['name'];
            organizations.push(organization);
        });

        $select2OrganizationName.select2({
            data: organizations,
            placeholder: '',
            allowClear: true,
            width: '100%'
        });
    }

    utilAsyncExecuteAjax($element, parameters, false, successCallback);
}

$select2OrganizationName.on(
    'change',
    function(e) {

        $searchForm.find('input[name="gs1_code"]').prop('disabled', $(this).val()==='');
        $searchForm.find('input[name="patient_code"]').prop('disabled', $(this).val()==='');
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
        $select2OrganizationName.val(null).trigger('change');
    }
)

// build search parameters
function buildSearchParameters($form) {
    var parameters = {};

    parameters['organization_id'] = $form.find('select[name="organization_id"] option:selected').val();
    parameters['registered_at_from'] = $form.find('input[name="registered_at_from"]').val();
    parameters['registered_at_to'] = $form.find('input[name="registered_at_to"]').val();
    parameters['gs1_code'] = $form.find('input[name="gs1_code"]').val();
    parameters['patient_code'] = $form.find('input[name="patient_code"]').val();
    parameters['registered_user_name'] = $form.find('input[name="registered_user_name"]').val();
    parameters['fixed_flg'] = $form.find('input[name="fixed_flg"]:checked').val();
    parameters['confirmed_flg'] = [];
    $form.find('input[name="confirmed_flg"]:checked').each(function (i, elm) {
        parameters['confirmed_flg'].push($(elm).val());
    });

    return parameters;
}


$('input.form-control.datetime').datetimepicker({
    step:5,
    format:'Y-m-d H:i:00'
})  

$('input.form-control.date').datetimepicker({
    timepicker:false,
    format:'Y-m-d'
})  

buildSelect2();