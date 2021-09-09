const
    $editModal = $('#modal-ventilator-update'),
    $registerModal = $('#modal-ventilator-create'),
    $showRegisterModalBtn = $('#show-register-modal'),
    $modalCancelBtn = $('button.modal-cancel'),
    $paginatedList = $('#paginated-list'),
    $ventilatorUpdateBtn = $('#async-ventilator-update'),
    $ventilatorBugListModal = $('#modal-ventilator-bug-list'),
    $importModal = $('#modal-ventilator-import'),
    $showImportModalBtn = $('#show-import-modal'),
    $importCsvBtn = $('#async-ventilator-import'),
    $exportCsvBtn = $('#btn-csv-export'),
    $searchForm = $('#async-search-form'),
    $searchBtn = $('#async-search'),
    $clearSearchFormBtn = $('#clear-search-form'),
    $select2OrganizationName = $('#search-organization-name'),
    $ventilatorBugList = $('#ventilator-bug-list')
    ;


$showRegisterModalBtn.on(
    'click',
    function () {
        utilFormRemoveValidationErrorMessage()

        $registerModal.modal();

        return false;
    });

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
)

/**
 * CSVエクスポート
 */
$exportCsvBtn.on(
    'click',
    function () {
        var selectedCount = $('.item-check:checked').length;

        if (selectedCount > 0) {
            var $form = $(this).closest('form');

            $form.find('input').remove();

            console.log($form.html());
            $('.item-check:checked').each(function (i, elm) {
                $("<input>", {
                    type: "hidden",
                    name: "ids[]",
                    value: $(elm).val()
                }).appendTo($form);
            });
        } else {
            alert(i18n('message.object_unselected'));
            return false;
        }
    }
)

/**
 * CSVインポート
 */
//import-modal
$showImportModalBtn.on(
    'click',
    function () {
        // buildSelect2();

        $importModal.modal();
        return false;
    });

//importイベント
$importCsvBtn.on(
    'click',
    function () {
        var $targetForm = $('form[name="ventilator-import"]');

        var parameters = new FormData($targetForm[0]);

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

            $importModal.modal('hide');
        }

        var extraSettings = {
            processData: false,
            contentType: false
        }

        utilAsyncExecuteAjax($importCsvBtn, parameters, true, successCallback, extraSettings);

        return false;
    });

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

buildSelect2();