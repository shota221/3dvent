const 
    $asyncPasswordReset                    = $('#async-password-reset'),
    $passwordResetForm                     = $('[name=password-reset]'),
    $passwordResetFormCodeInput            = $passwordResetForm.find('[name=code]'),
    $passwordResetFormEmailInput           = $passwordResetForm.find('[name=email]'),
    $passwordResetFormPasswordInput        = $passwordResetForm.find('[name=password]'),
    $passwordResetFormPasswordConfirmInput = $passwordResetForm.find('[name=password-confirmation]'),
    $passwordResetFormTokenInput           = $passwordResetForm.find('[name=token]');

// パスワード再設定
$asyncPasswordReset.on(
    'click',
    function () {

        var parameters = {};
        parameters['token']                 = $passwordResetFormTokenInput.val();
        parameters['code']                  = $passwordResetFormCodeInput.val();
        parameters['email']                 = $passwordResetFormEmailInput.val();
        parameters['password']              = $passwordResetFormPasswordInput.val();
        parameters['password_confirmation'] = $passwordResetFormPasswordConfirmInput.val();

        var successCallback = function (data) {
            var url = data['result']['home_url'];
            window.location.href = url;
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
);