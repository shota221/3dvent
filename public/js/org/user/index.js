const
    $asyncCreate                            = $('#async-create'),
    $asyncCsvImport                         = $('#async-csv-import'),
    $asyncUpdate                            = $('#async-update'),
    $asyncSearch                            = $('#async-search'),
    $bulkCheck                              = $('#bulk-check'),
    $cancelModal                            = $('button.modal-cancel'),
    $csvImportModal                         = $('#csv-import-modal'),
    $csvImportModalFileInput                = $csvImportModal.find('[name=csv_file]'),
    $clearSearchForm                        = $('#clear-search-form'),
    $editModal                              = $('#edit-modal'),
    $editModalAuthorityInput                = $editModal.find('[name=authority_type]'),
    $editModalDisabledFlgInput              = $editModal.find('[name=disabled_flg]'),
    $editModalEmailInput                    = $editModal.find('[name=email]'),
    $editModalIdInput                       = $editModal.find('[name=id]'),
    $editModalNameInput                     = $editModal.find('[name=name]'),
    $editModalPasswordInput                 = $editModal.find('[name=password]'),
    $editModalPasswordConfirmationInput     = $editModal.find('[name=password_confirmation]'),
    $editModalPasswordChangedInput          = $editModal.find('[name=password_changed]'),
    $editModalPasswordChangeField           = $editModal.find('.password-change-field'),
    $datepicker                             = $('input.form-control.date'),
    $paginatedList                          = $('#paginated-list'),
    $registerModal                          = $('#register-modal'),
    $registerModalAuthorityInput            = $registerModal.find('[name=authority_type]'),
    $registerModalDisabledFlgInput          = $registerModal.find('[name=disabled_flg]'),
    $registerModalEmailInput                = $registerModal.find('[name=email]'),
    $registerModalNameInput                 = $registerModal.find('[name=name]'),
    $registerModalPasswordInput             = $registerModal.find('[name=password]'),
    $registerModalPasswordConfirmationInput = $registerModal.find('[name=password_confirmation]'),
    $searchForm                             = $('#async-search-form'),
    $searchFormAllInput                     = $searchForm.find('input'),
    $searchFormAuthorityInput               = $searchForm.find('[name=authority_type]'),
    $searchFormNameInput                    = $searchForm.find('[name=name]'),
    $searchFormRegisteredAtFromInput        = $searchForm.find('[name=registered_at_from]'),    
    $searchFormRegisteredAtToInput          = $searchForm.find('[name=registered_at_to]'),
    $showCsvImportModal                     = $('#show-csv-import-modal'),
    $showRegisterModal                      = $('#show-register-modal');

// 編集モーダル表示
$paginatedList.on(
    'click',
    '.show-edit-modal',
    function (e) {
        $editModalPasswordChangeField.addClass('collapse')
        utilFormRemoveValidationErrorMessage()

        var parameters = {};

        var successCallback = function (data) {
            $form = $editModal.find('form[name="update"]').eq(0);

            utilFormInputParameters($form, data['result']);

            $editModal.modal();
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, false, successCallback);

        return false;
    }
)

// 新規登録モーダル表示
$showRegisterModal.on(
    'click',
    function (e) {
        utilFormRemoveValidationErrorMessage()

        $form = $registerModal.find('form[name="create"]').eq(0);
        $form[0].reset();
        $registerModal.modal();

        return false;
    }
);

// csvインポートモーダル表示
$showCsvImportModal.on(
    'click',
    function () {
        utilFormRemoveValidationErrorMessage()

        $form = $csvImportModal.find('form[name="csv-import"]').eq(0);
        $form[0].reset();
        $csvImportModal.modal();

        return false;
    }
)

// モーダル非表示
$cancelModal.on(
    'click',
    function (e) {
        $(this).closest('.modal').modal('hide');

        return false;
    }
)

// 検索
$asyncSearch.on(
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

// 検索フォーム初期化
$clearSearchForm.on(
    'click',
    function (e) {
        $searchFormAllInput.not(':checkbox').val('');
        $searchForm.find('select').val(0);
        $searchForm.find(':checkbox').prop('checked', true);
    }
)

// 更新
$asyncUpdate.on(
    'click',
    function (e) {
        var parameters = {};
        parameters['id']                    = $editModalIdInput.val()
        parameters['name']                  = $editModalNameInput.val();
        parameters['email']                 = $editModalEmailInput.val();
        parameters['authority_type']        = $editModalAuthorityInput.val()
        parameters['disabled_flg']          = $editModal.find('[name=disabled_flg]:checked').val();
        parameters['password']              = $editModalPasswordInput.val();
        parameters['password_confirmation'] = $editModalPasswordConfirmationInput.val();

        if ($editModal.find('[name=password_changed]:checked').val() === undefined) {
            parameters['password_changed'] = "0";
        } else {
            parameters['password_changed'] = "1";
        }

        var successCallback = function (data) {
            var $element = $('.page-item' + '.active').children('button');

            if (! $element.length) {
                $element = $asyncSearch;
            }

            var parameters = buildSearchParameters($searchForm);

            var successCallback = function(paginated_list) {
                $paginatedList.html(paginated_list);
            }

            utilAsyncExecuteAjax($element, parameters, false, successCallback);

            $editModal.modal('hide');
        }
        
        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
)

// 新規登録
$asyncCreate.on(
    'click',
    function (e) {
        var parameters = {};
        parameters['name']                  = $registerModalNameInput.val();
        parameters['email']                 = $registerModalEmailInput.val();
        parameters['authority_type']        = $registerModalAuthorityInput.val()
        parameters['disabled_flg']          = $registerModal.find('[name=disabled_flg]:checked').val();
        parameters['password']              = $registerModalPasswordInput.val();
        parameters['password_confirmation'] = $registerModalPasswordConfirmationInput.val();

        
        var successCallback = function (data) {
            var $element = $('.page-item' + '.active').children('button');

            if (! $element.length) {
                $element = $asyncSearch;
            }

            var parameters = buildSearchParameters($searchForm);

            var successCallback = function(paginated_list) {
                $paginatedList.html(paginated_list);
            }

            utilAsyncExecuteAjax($element, parameters, false, successCallback);

            $registerModal.modal('hide');
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
)

// csvインポート
$asyncCsvImport.on(
    'click',
    function () {

        var $csv_file  = $csvImportModalFileInput
        var parameters = new FormData();
        
        if ($csv_file.prop('files')[0] != undefined) {
            parameters.append('csv_file', $csv_file.prop('files')[0])
        }
        
        var successCallback = function (data) {
            var $element = $('.page-item' + '.active').children('button');

            if (! $element.length) {
                $element = $asyncSearch;
            }

            var parameters = buildSearchParameters($searchForm);

            var successCallback = function(paginated_list) {
                $paginatedList.html(paginated_list);
            }

            utilAsyncExecuteAjax($element, parameters, false, successCallback);

            $csvImportModal.modal('hide');
        }

        var $element = $(this);

        var extraSettings = {
            processData: false,
            contentType: false
        }

        var badRequestCallback = function (error) { }

        utilAsyncExecuteAjax($element, parameters, true, successCallback, badRequestCallback, extraSettings);

        return false;
    }
)

// checkbox管理
$paginatedList
.on(
    'click',
    '.item-check',
    function () {
        var checkboxCount = $('.item-check').length;
        var selectedCount = $('.item-check:checked').length;

        if (checkboxCount === selectedCount) {
            $bulkCheck.prop('indeterminate', false).prop('checked', true);
        } else if (selectedCount === 0) {
            $bulkCheck.prop('indeterminate', false).prop('checked', false);
        } else {
            $bulkCheck.prop('indeterminate', true).prop('checked', true);
        }
    }
).on(
    'click',
    '#bulk-check',
    function () {
        var isChecked = $(this).prop('checked');
        $('.item-check').each(function () {
            $(this).prop('checked', isChecked);
        });
    }
)

// バルクデリート
$paginatedList.on(
    'click',
    '#btn-bulk-delete',
    function () {
        var selectedCount = $('.item-check:checked').length;

        if (selectedCount > 0) {
            var isConfirmed = confirm(i18n('message.delete_count_confirm', { count: selectedCount }));

            if (isConfirmed) {
                var $element = $(this);
                var parameters = { 'ids': [] }

                $('.item-check:checked').each(function (i, elm) {
                    parameters['ids'].push($(elm).val());
                });

                var successCallback = function (data) {
                    var $element = $('.page-item' + '.active').children('button');
            
                    if (! $element.length) {
                        $element = $asyncSearch;
                    } 
        
                    var parameters = buildSearchParameters($searchForm);
                    var successCallback = function(pagineted_list) {
                        $paginatedList.html(pagineted_list);
                    } 

                    utilAsyncExecuteAjax($element, parameters, false, successCallback);
                }
                utilAsyncExecuteAjax($element, parameters, true, successCallback);
            }
        } else {
            alert(i18n('message.object_unselected'));
        }
    }
)

// パスワード入力フォーム表示切替
$editModalPasswordChangedInput.on(
    'click',
    function (e) {
        $editModalPasswordChangeField.toggleClass('collapse');
    }
)

// 検索用パラメータ作成
function buildSearchParameters($form) {
    var parameters = {};

    parameters['name']               = $searchFormNameInput.val()
    parameters['authority_type']     = $searchFormAuthorityInput.val()
    parameters['registered_at_from'] = $searchFormRegisteredAtFromInput.val()
    parameters['registered_at_to']   = $searchFormRegisteredAtToInput.val()
    
    $disabled_flg = $searchForm.find('[name=disabled_flg]:checked');

    if ($disabled_flg.length == 1) {
        parameters['disabled_flg'] = $disabled_flg.val();
    }

    return parameters;
}

// ページネーション
$paginatedList.on('click', '.page-link', function(e) {
    var $featureElement = $(this);

    var parameters = {};

    var successCallback = function(paginated_list) {
        $paginatedList.html(paginated_list);
    }

    utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);
});

// datepicker
$datepicker.datetimepicker({
    timepicker:false,
    format:'Y-m-d'
}) 














