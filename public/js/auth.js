const 
    $applyPasswordResetModal = $('#apply-password-reset-modal'),
    $applyPasswordResetModalCodeInput  = $applyPasswordResetModal.find('[name="code"]'),
    $applyPasswordResetModalEmailInput = $applyPasswordResetModal.find('[name="email"]'),
    $asyncApplyPasswordReset           = $('#async-apply-password-reset'),
    $cancelModal                       = $('button.modal-cancel'),
    $nameInput                         = $('[name="name"]'),
    $passwordInput                     = $('[name="password"]'),
    $showPasswordResetModal            = $('#show-password-reset-modal');

// ログイン
$("#login").on(
    'click',
    function (e) {
        var parameters = {};
        parameters['name'] = $nameInput.val();
        parameters['password'] = $passwordInput.val();
        parameters['remember'] = $('[name="remember"]:checked').val();

        var $element = $(this);

        var successCallback = function(data) {
            var url = data['result']['redirect_to'];
            window.location.href = url;
        }

        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
)

// パスワード再設定メール送信モーダル表示
$showPasswordResetModal.on(
    'click',
    function () {
        utilFormRemoveValidationErrorMessage()

        $form = $applyPasswordResetModal.find('form[name="apply-password-reset"]').eq(0);
        $form[0].reset();
        
        $applyPasswordResetModal.modal();

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

// パスワード再設定メール送信
$asyncApplyPasswordReset.on(
    'click',
    function () {
        
        var parameters = {};
        parameters['code']  = $applyPasswordResetModalCodeInput.val();
        parameters['email'] = $applyPasswordResetModalEmailInput.val();

        var successCallback = function (data) {
            alert(i18n('message.accept_password_reset_application'));
            $applyPasswordResetModal.modal('hide');
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
);