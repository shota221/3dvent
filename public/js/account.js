const
    $asyncProfileUpdate                        = $('#async-profile-update'),
    $showProfileEditModal                      = $('#show-profile-edit-modal'),
    $profileEditModal　　　　　　　　　　　      = $('#profile-edit-modal'),
    $profileEditModalEmailInput                = $profileEditModal.find('[name=email]'),
    $profileEditModalNameInput                 = $profileEditModal.find('[name=name]'),
    $profileEditModalPasswordInput             = $profileEditModal.find('[name=password]'),
    $profileEditModalPasswordConfirmationInput = $profileEditModal.find('[name=password_confirmation]'),
    $profileEditModalPasswordChangedInput      = $profileEditModal.find('[name=password_changed]'),
    $profileEditModalPasswordChangeField       = $profileEditModal.find('.password-change-field');

// 編集モーダル表示
$showProfileEditModal.on(
    'click',
    function () {
        $profileEditModalPasswordChangeField.addClass('collapse')
        utilFormRemoveValidationErrorMessage()
        
        var parameters = {};
        
        var successCallback = function (data) {

            $form = $profileEditModal.find('form[name="profile-update"]').eq(0);

            utilFormInputParameters($form, data['result']);
            
            $profileEditModal.modal();
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, false, successCallback);

        return false;
    }
);

// パスワード入力フォーム表示切替
$profileEditModalPasswordChangedInput.on(
    'click',
    function () {
        $profileEditModalPasswordChangeField.toggleClass('collapse');
    }
)

// 更新
$asyncProfileUpdate.on(
    'click',
    function() {

        var parameters = {};
        parameters['name']                  = $profileEditModalNameInput.val();
        parameters['email']                 = $profileEditModalEmailInput.val();
        parameters['password']              = $profileEditModalPasswordInput.val();
        parameters['password_confirmation'] = $profileEditModalPasswordConfirmationInput.val();
        
        if ($profileEditModal.find('[name=password_changed]:checked').val() === undefined) {
            parameters['password_changed'] = "0";    
        } else {
            parameters['password_changed'] = "1";    
        }
        
        var successCallback = function (data) {
            $profileEditModal.modal('hide');
            alert(i18n('message.registered_reload'));
            window.location.reload();
        }

        var $element = $(this);
        
        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
)