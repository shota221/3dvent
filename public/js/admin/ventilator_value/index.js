const
    $editModal = $('#modal-ventilator_value-update'),
    $showRegisterModalBtn = $('#show-register-modal'),
    $modalCancelBtn = $('button.modal-cancel'),
    $paginatedList = $('#paginated-list'),
    $ventilatorValueUpdateBtn = $('#async-ventilator_value-update'),
    $searchForm = $('#async-search-form'),
    $searchBtn = $('#async-search'),
    $clearSearchFormBtn = $('#clear-search-form'),
    $select2OrganizationName = $('#search-organization-name')
    ;


$modalCancelBtn.on(
    'click',
    function () {
        $(this).closest('.modal').modal('hide');

        return false;
    });


// show edit modal
$paginatedList.on(
    'click',
    '.show-edit-modal',
    function (e) {
        var $status = $editModal.find('span[name="status"]');

        utilFormRemoveValidationErrorMessage();
        $editModal.find('textarea[name="status_use_other"]').prop('disabled', true);
        $status.empty();

        var successCallback = function (data) {

            var $form = $editModal.find('form[name="ventilator_value-update"]').eq(0);

            var isFixed = data['result']['fixed_flg'];
            var isConfirmed = data['result']['confirmed_flg'];
            utilFormInputParameters($form, data['result']);
            $form.find('input[name="confirmed_flg"]').prop('checked',isConfirmed)
        
            //バッジ表記
            if (isFixed) {
                var $fixedBadge = $('<div class="badge badge-primary mr-1">' + i18n('message.fixed_value') + '</div>');
                $status.append($fixedBadge);
            }
            if (isConfirmed) {
                var $confirmedBadge = $('<div class="badge badge-success">' + i18n('message.confirmed') + '</div>');
                $status.append($confirmedBadge);
            }

            $editModal.modal();
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, {}, false, successCallback);

        return false;
    }
)

$editModal.on(
    'change',
    'select[name="status_use"]',
    function () {
        $editModal.find('textarea[name="status_use_other"]').prop('disabled', $(this).val() !== '4');
    }
)

// async-update
$ventilatorValueUpdateBtn.on(
    'click',
    function() {

        var parameters = {};

        var $targetForm = $('form[name="ventilator_value-update"]');

        parameters['id'] = $targetForm.find('input[name="id"]').val();
        parameters['organization_id'] = $targetForm.find('input[name="organization_id"]').val();
        parameters['height'] = $targetForm.find('input[name="height"]').val();
        parameters['weight'] = $targetForm.find('input[name="weight"]').val();
        parameters['gender'] = $targetForm.find('input[name="gender"]:checked').val();
        parameters['airway_pressure'] = $targetForm.find('input[name="airway_pressure"]').val();
        parameters['air_flow'] = $targetForm.find('input[name="air_flow"]').val();
        parameters['o2_flow'] = $targetForm.find('input[name="o2_flow"]').val();
        parameters['status_use'] = $targetForm.find('select[name="status_use"]').val();
        parameters['status_use_other'] = $targetForm.find('textarea[name="status_use_other"]').val();
        parameters['spo2'] = $targetForm.find('input[name="spo2"]').val();
        parameters['etco2'] = $targetForm.find('input[name="etco2"]').val();
        parameters['pao2'] = $targetForm.find('input[name="pao2"]').val();
        parameters['paco2'] = $targetForm.find('input[name="paco2"]').val();
        parameters['confirmed_flg'] = $targetForm.find('input[name="confirmed_flg"]:checked').val();

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

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
);

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
    function (e) {
        $searchForm.find('input[name="gs1_code"]').prop('disabled', $(this).val() === '');
        $searchForm.find('input[name="patient_code"]').prop('disabled', $(this).val() === '');
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

    parameters['organization_id'] = $form.find('select[name="organization_id"]').val();
    parameters['registered_at_from'] = $form.find('input[name="registered_at_from"]').val();
    parameters['registered_at_to'] = $form.find('input[name="registered_at_to"]').val();
    parameters['gs1_code'] = $form.find('input[name="gs1_code"]').val();
    parameters['patient_code'] = $form.find('input[name="patient_code"]').val();
    parameters['registered_user_name'] = $form.find('input[name="registered_user_name"]').val();
    parameters['fixed_flg'] = $form.find('input[name="fixed_flg"]:checked').val();
    if($form.find('[name=confirmed_flg]:checked').length==1){
        parameters['confirmed_flg'] = $form.find('[name=confirmed_flg]:checked').val();
    }

    return parameters;
}


$('input.form-control.datetime').datetimepicker({
    step: 5,
    format: 'Y-m-d H:i:00'
})

$('input.form-control.date').datetimepicker({
    timepicker: false,
    format: 'Y-m-d'
})

buildSelect2();