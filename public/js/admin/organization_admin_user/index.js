// show register modal
$('#show-register-modal').on(
    'click',
    function (e) {
        utilFormRemoveValidationErrorMessage()

        $('#register-modal').modal();

        return false;
    }
);

// show edit modal
$('#paginated-list').on(
    'click',
    '.show-edit-modal',
    function (e) {
        
        $('.password-change-inputs').addClass('collapse');
        utilFormRemoveValidationErrorMessage()
        
        var parameters = {};
        parameters['id'] = $(this).data('id');
        
        var successCallback = function (data) {

            $form = $('#edit-modal').find('form[name="update"]').eq(0);

            utilFormInputParameters($form, data['result']);
            
            $('#edit-modal').modal();
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, false, successCallback);

        return false;
    }
)

// hide modal
$('button.modal-cancel').on(
    'click',
    function (e) {
        $(this).closest('.modal').modal('hide');

        return false;
    }
);

// async-update
$('#async-update').on(
    'click',
    function(e) {

        var $modal = $('#edit-modal');
        var parameters = {};
        parameters['id'] = $modal.find('[name=id]').val();
        parameters['code'] = $modal.find('[name=code]').val();
        parameters['name'] = $modal.find('[name=name]').val();
        parameters['email'] = $modal.find('[name=email]').val();
        parameters['disabled_flg'] = $modal.find('[name=disabled_flg]:checked').val();
        
        if ($modal.find('[name=password_changed]:checked').val() === undefined) {
            parameters['password_changed'] = "0";    
        } else {
            parameters['password_changed'] = "1";    
        }
        
        parameters['password'] = $modal.find('[name=password]').val();
        parameters['password_confirmation'] = $modal.find('[name=password_confirmation]').val();
        
        var successCallback = function (data) {
            var $element = $('.page-item' + '.active').children('button');
            var $parameters = {};
            var successCallback = function(pagineted_list) {
                $('#paginated-list').html(pagineted_list);
            } 
            utilAsyncExecuteAjax($element, $parameters, false, successCallback);
          
            $modal.modal('hide');
        }

        var $element = $(this);
        
        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
)

// async-create
$('#async-create').on(
    'click',
    function (e) {
        
        var $modal = $('#register-modal');
        var parameters = {};
        parameters['code'] = $modal.find('[name=code]').val();
        parameters['name'] = $modal.find('[name=name]').val();
        parameters['email'] = $modal.find('[name=email]').val();
        parameters['disabled_flg'] = $modal.find('[name=disabled_flg]:checked').val();
        parameters['password'] = $modal.find('[name=password]').val();
        parameters['password_confirmation'] = $modal.find('[name=password_confirmation]').val();
        
        var successCallback = function (data) {
            var $element = $('.page-item' + '.active').children('button');
            var $parameters = {};
            var successCallback = function(pagineted_list) {
                $('#paginated-list').html(pagineted_list);
            } 
            utilAsyncExecuteAjax($element, $parameters, false, successCallback);
            $('#register-modal').modal('hide');
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
);

// show password-inputs
$('#password-changed').on(
    'click',
    function (e) {
        $('.password-change-inputs').toggleClass('collapse');
    }
)