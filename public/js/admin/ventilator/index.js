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
    $exportCsvBtn = $('#btn-csv-export');


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
            $('#ventilator-bug-list').html(ventilator_bug_list);
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

                $('.item-check:checked').closest('tr').each(function (i, elm) {
                    parameters['ids'].push($(elm).data('id'));
                });

                var successCallback = function (data) {
                    var $featureElement = $('.page-item' + '.active').children('button');

                    var parameters = {};

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

            $('.item-check:checked').closest('tr').each(function (i, elm) {
                $('<input>').attr({
                    'type': 'hidden',
                    'name': 'ids[]',
                    'value': $(elm).data('id')
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
        $importModal.modal();
        buildSelect2();
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

    console.log($element.data('url'));
    console.log('test');

    var successCallback = function (data) {
        var organizations = [];

        var $select2OrganizationName = $('#select2-organization-name');

        data.forEach(function (datum) {
            var organization = {};
            organization['id'] = datum['id'];
            organization['text'] = datum['name'];
            organizations.push(organization);
        });

        $select2OrganizationName.select2({
            data: organizations,
            placeholder: '',
            allowClear: true
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