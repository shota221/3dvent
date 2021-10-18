const
    $asyncCreate                            = $('#async-create'),
    $asyncOrganizationData                  = $('#async-organization-data'),
    $asyncUpdate                            = $('#async-update'),
    $asyncSearch                            = $('#async-search'),
    $cancelModal                            = $('button.modal-cancel'),
    $clearSearchForm                        = $('#clear-search-form'),
    $editModal                              = $('#edit-modal'),
    $editModalDisabledFlgInput              = $editModal.find('[name="disabled_flg"]'),
    $editModalEmailInput                    = $editModal.find('[name="email"]'),
    $editModalIdInput                       = $editModal.find('[name="id"]'),
    $editModalCodeInput                     = $editModal.find('[name="code"]'),
    $editModalNameInput                     = $editModal.find('[name="name"]'),
    $editModalPasswordInput                 = $editModal.find('[name="password"]'),
    $editModalPasswordConfirmationInput     = $editModal.find('[name="password_confirmation"]'),
    $editModalPasswordChangedInput          = $editModal.find('[name="password_changed"]'),
    $editModalPasswordChangeField           = $editModal.find('.password-change-field'),
    $datepicker                             = $('input.form-control.date'),
    $paginatedList                          = $('#paginated-list'),
    $registerModal                          = $('#register-modal'),
    $registerModalCodeInput                 = $registerModal.find('[name="code"]'),
    $registerModalDisabledFlgInput          = $registerModal.find('[name="disabled_flg"]'),
    $registerModalEmailInput                = $registerModal.find('[name="email"]'),
    $registerModalNameInput                 = $registerModal.find('[name="name"]'),
    $registerModalPasswordInput             = $registerModal.find('[name="password"]'),
    $registerModalPasswordConfirmationInput = $registerModal.find('[name="password_confirmation"]'),
    $searchForm                             = $('#async-search-form'),
    $searchFormAllInput                     = $searchForm.find('input'),
    $searchFormNameInput                    = $searchForm.find('[name="name"]'),
    $searchFormOrganizationInput            = $searchForm.find('[name="organization_name"]'),
    $searchFormRegisteredAtFromInput        = $searchForm.find('[name="registered_at_from"]'),    
    $searchFormRegisteredAtToInput          = $searchForm.find('[name="registered_at_to"]'),
    $select2OrganizationName                = $('#select2-organization-name'),
    $showRegisterModal                      = $('#show-register-modal');

// 編集モーダル表示
$paginatedList.on(
    'click',
    '.show-edit-modal',
    function () {
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
    function () {
        utilFormRemoveValidationErrorMessage()

        $form = $registerModal.find('form[name="create"]').eq(0);
        $form[0].reset();
        $registerModal.modal();

        return false;
    }
);

// モーダル非表示
$cancelModal.on(
    'click',
    function () {
        $(this).closest('.modal').modal('hide');

        return false;
    }
);

// 組織名の非同期取得
(function () {
    var parameters = {};

    var $element = $asyncOrganizationData ;

    var successCallback = function (data) {
        var organizations = [];

        data.forEach(function (datum) {
            var organization = {};
            organization['id'] = datum['id']; 
            organization['text'] = datum['name'];
            organizations.push(organization);
        })

        $select2OrganizationName.select2({
            data: organizations,
            placeholder: '',
            allowClear: true
        });
    }

    utilAsyncExecuteAjax($element, parameters, false, successCallback);
}(utilAsyncExecuteAjax));

// 検索
$asyncSearch.on(
    'click',
    function() {
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

// 更新
$asyncUpdate.on(
    'click',
    function(e) {

        var $modal = $('#edit-modal');
        var parameters = {};
        parameters['id']                    = $editModalIdInput.val()
        parameters['code']                  = $editModalCodeInput.val();
        parameters['name']                  = $editModalNameInput.val();
        parameters['email']                 = $editModalEmailInput.val();
        parameters['disabled_flg']          = $editModal.find('[name="disabled_flg"]:checked').val();
        parameters['password']              = $editModalPasswordInput.val();
        parameters['password_confirmation'] = $editModalPasswordConfirmationInput.val();
        
        if ($modal.find('[name="password_changed"]:checked').val() === undefined) {
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
            
            var successCallback = function(pagineted_list) {
                $paginatedList.html(pagineted_list);
            } 
            utilAsyncExecuteAjax($element, parameters, false, successCallback);
          
            $modal.modal('hide');
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
        
        var $modal = $('#register-modal');
        var parameters = {};
        parameters['code']                  = $registerModalCodeInput.val();
        parameters['name']                  = $registerModalNameInput.val();
        parameters['email']                 = $registerModalEmailInput.val();
        parameters['disabled_flg']          = $registerModal.find('[name="disabled_flg"]:checked').val();
        parameters['password']              = $registerModalPasswordInput.val();
        parameters['password_confirmation'] = $registerModalPasswordConfirmationInput.val();
        
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

            $registerModal.modal('hide');
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
);

// 検索フォーム初期化
$clearSearchForm.on(
    'click',
    function (e) {
        $searchFormAllInput.not(':checkbox').val('');
        $select2OrganizationName.val(null).trigger('change');
        $form.find(':checkbox').prop('checked', true);
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

    parameters['organization_id']    = $searchFormOrganizationInput.val()
    parameters['name']               = $searchFormNameInput.val()
    parameters['registered_at_from'] = $searchFormRegisteredAtFromInput.val()
    parameters['registered_at_to']   = $searchFormRegisteredAtToInput.val()
     
    $disabled_flg = $searchForm.find('[name="disabled_flg"]:checked');

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