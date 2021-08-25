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

// async organization data
(function () {
    var parameters = {};

    var $element = $('#async-organization-data');

    var successCallback = function (data) {
        var organizations = [];

        data.forEach(function (datum) {
            var organization = {};
            organization['id'] = datum['id']; 
            organization['text'] = datum['name'];
            organizations.push(organization);
        })

        $('#select2-organization-name').select2({
            data: organizations,
            placeholder: '',
            allowClear: true
        });
    }

    utilAsyncExecuteAjax($element, parameters, false, successCallback);
}(utilAsyncExecuteAjax));

// set registered-user-name select2   
(function() {
    $('#select2-registered-user-name').select2();
}());

// set registered_user select2 and change patient_code property  
$('#select2-organization-name').on(
    'change',
    function(e) {

        $('#select2-registered-user-name').val(null).trigger('change');
        $('#select2-registered-user-name').find('option:not(:first)').remove();
        $('#select2-registered-user-name').prop('disabled', false);
        $('#patient_code').prop('disabled', false);
        
        if ($('#select2-organization-name').val() === '') {
            $('#select2-registered-user-name').prop('disabled', true);
            $('#patient_code').prop('disabled', true);
            return;
        }

        $element = $('#async-registered-user-data');
        
        var parameters = {};
        var $form = $('#async-search-form');

        parameters['organization_id'] = $form.find('[name=organization_name] option:selected').val();
        
        var successCallback = function (data) {
            var registered_users = [];
    
            data.forEach(function (datum) {
                var registered_user = {};
                registered_user['id'] = datum['id']; 
                registered_user['text'] = datum['name'];
                registered_users.push(registered_user);
            })
    
            $('#select2-registered-user-name').select2({
                data: registered_users,
                placeholder: '',
                allowClear: true
            });
        }

        utilAsyncExecuteAjax($element, parameters, false, successCallback);
    }
)

// async-search
$('#async-search').on(
    'click',
    function(e) {
        var $form = $('#async-search-form');
        var parameters = buildSearchParameters($form);

        var successCallback = function (paginated_list) {
            $('#paginated-list').html(paginated_list);
        }

        var $element = $(this);

        utilAsyncExecuteAjax($element, parameters, false, successCallback);

        return false;
    }
)

// clear search form
$('#clear-search-form').on(
    'click',
    function (e) {
        var $form = $('#async-search-form');
        $form.find('input').val('');
        $('#select2-registered-user-name').val(null).trigger('change');
        $('#select2-organization-name').val(null).trigger('change');
    }
)

// build search parameters
function buildSearchParameters($form) {
    var parameters = {};

    parameters['organization_name'] = $form.find('[name=organization_name] option:selected').text();
    parameters['patient_code'] = $form.find('[name=patient_code]').val();
    parameters['registered_user_name'] = $form.find('[name=registered_user_name] option:selected').text();
    parameters['registered_at_from'] = $form.find('[name=registered_at_from]').val();
    parameters['registered_at_to'] = $form.find('[name=registered_at_to]').val();

    return parameters;
}

// pagination
$('#paginated-list').on('click', '.page-link', function(e) {
    var $featureElement = $(this);
    
    var parameters = {};

    var successCallback = function(paginated_list) {
        $('#paginated-list').html(paginated_list);
    }

    utilAsyncExecuteAjax($featureElement, parameters, false, successCallback)
});