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
    $select2OrganizationName = $('.select2-organization-name'),
    $searchOrganizationName = $('#search-organization-name'),
    $importOrganizationName = $('#import-organization-name'),
    $ventilatorBugList = $('#ventilator-bug-list'),
    $checkExportQueueStatusElm = $('#check-export-queue-status'),
    $checkImportQueueStatusElm = $('#check-import-queue-status'),
    $exportCsvElm = $('#export-csv');
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

                var badRequestCallback = function (error) {
                    var result = JSON.parse(error.responseText);
                    result.errors.forEach(function (error) {
                        var message = error.message.translated;
                        alert(message);
                    });
                }

                utilAsyncExecuteAjax($featureElement, parameters, true, successCallback, badRequestCallback)
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
        //何も選択されていなければアラート表示
        var selectedCount = $('.item-check:checked').length;

        if (selectedCount > 0) {
            var withMessage = false;

            var $startQueueElm = $(this);

            var startQueueParams = { 'ids': [] }

            $('.item-check:checked').each(function (i, elm) {
                startQueueParams['ids'].push($(elm).val());
            });


            var startQueueSuccessCallback = function (data) {
                var ladda = Ladda.create($startQueueElm.get(0));

                ladda.start();

                var queue = data.result.queue;

                var checkQueueStatusParams = { 'queue': queue };

                //キューのステータスを確認した回数
                var pollingCount = 0;
                //キューのステータスを確認する回数上限
                var limitedPollingCount = 180
                //キューのステータスを確認する頻度（ms）
                var pollingInterval = 1000;

                var polling = function () {
                    pollingCount++
                    console.log(pollingCount);

                    if (pollingCount > limitedPollingCount) {
                        alert(i18n('message.csv_download_failed'));
                        return false;
                    }

                    var checkQueueStatusSuccessCallback = function (data) {
                        var isFinished = data.result.is_finished;
                        var hasError = data.result.has_error;

                        if (hasError) {
                            ladda.stop();
                            alert(i18n('message.csv_download_failed'));
                            return false;
                        }

                        if (isFinished) {
                            location.href = $exportCsvElm.data('url') + '?queue=' + queue;
                            ladda.stop();
                        } else {
                            polling();
                        }
                    }

                    setTimeout(
                        function () {
                            //非同期処理に伴うローディング表示を発生させないために要素を一時削除
                            var $detachedOverlay = $('#overlay').detach();
                            utilAsyncExecuteAjax($checkExportQueueStatusElm, checkQueueStatusParams, withMessage, checkQueueStatusSuccessCallback);
                            $paginatedList.append($detachedOverlay);
                        }
                        , pollingInterval
                    )

                }

                polling();
            };

            var badRequestCallback = function (error) {

            };

            utilAsyncExecuteAjax($startQueueElm, startQueueParams, withMessage, startQueueSuccessCallback)

            //ポーリング開始

        } else {
            alert(i18n('message.object_unselected'));
            return false;
        }
    }
)

/**
 * CSVインポート
 */
//import-modal表示
$showImportModalBtn.on(
    'click',
    function () {
        $importModal.modal();
        return false;
    });

//importイベント
$importCsvBtn.on(
    'click',
    function () {
        /**
         * 与えられたCSVのバリデーションチェック・重複チェックしジョブをキューに登録
         */
        var $startQueueElm = $(this);

        var $targetForm = $('form[name="ventilator-import"]');

        var startQueueParams = new FormData($targetForm[0]);

        /**
         * ジョブがキューに登録され次第、ポーリング開始。
         * サーバーを10秒ごとに見に行き、キューが処理されていれば
         * アラートを表示し、ページリロード。
         */
        var startQueueSuccessCallback = function (data) {

            $importModal.modal('hide');

            var ladda = Ladda.create($showImportModalBtn.get(0));

            ladda.start();

            var queue = data.result.queue;

            var checkQueueStatusParams = { 'queue': queue };

            //キューのステータスを確認した回数
            var pollingCount = 0;
            //キューのステータスを確認する回数上限
            var limitedPollingCount = 18
            //キューのステータスを確認する頻度（ms）
            var pollingInterval = 10000;

            //キューの様子を定期取得
            var polling = function () {
                pollingCount++
                console.log(pollingCount);
                if (pollingCount > limitedPollingCount) {
                    alert(i18n('message.csv_import_failed'));
                    return false;
                }

                var checkQueueStatusSuccessCallback = function (data) {
                    console.log(data);
                    var isFinished = data.result.is_finished;
                    var hasError = data.result.has_error;

                    if (hasError) {
                        ladda.stop();
                        alert(i18n('message.csv_import_canceled'));
                        return false;
                    }

                    if (isFinished) {
                        ladda.stop();
                        confirm(i18n('message.csv_imported')) && location.reload();
                    } else {
                        polling();
                    }
                }

                setTimeout(
                    function () {
                        utilAsyncExecuteAjax($checkImportQueueStatusElm, checkQueueStatusParams, false, checkQueueStatusSuccessCallback)
                    }
                    , pollingInterval
                )
            }

            polling();
        }

        var extraSettings = {
            processData: false,
            contentType: false
        }

        var badRequestCallback = function (error) { }

        utilAsyncExecuteAjax($startQueueElm, startQueueParams, true, startQueueSuccessCallback, badRequestCallback, extraSettings);

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
        $searchOrganizationName.val(null).trigger('change');
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
    step: 5,
    format: 'Y-m-d H:i:00'
})

$('input.form-control.date').datetimepicker({
    timepicker: false,
    format: 'Y-m-d'
})

buildSelect2();