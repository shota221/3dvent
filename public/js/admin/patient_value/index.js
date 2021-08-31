const
    $asyncOrganizationData = $('#async-organization-data'),
    $asyncRegisteredUserData = $('#async-registered-user-data'),
    $asyncSearch = $('#async-search'),
    $searchForm = $('#async-search-form'),
    $searchFormAllInput = $('#async-search-form').find('input'),
    $searchFormPatientCodeInput = $('#async-search-form').find('[name=patient_code]'),
    $searchFormRegisteredAtFromInput = $('#async-search-form').find('[name=registered_at_from]'),    
    $searchFormRegisteredAtToInput = $('#async-search-form').find('[name=registered_at_to]'),
    $cancelModal = $('button.modal-cancel'),
    $clearSearchForm = $('#clear-search-form'),
    $editModal = $('#edit-modal'),
    $paginatedList = $('#paginated-list'),
    $patientCode = $('#patient_code'),
    $select2OrganizationName = $('#select2-organization-name'),
    $select2RegisteredUserName = $('#select2-registered-user-name');

// show edit modal
$paginatedList.on(
    'click',
    '.show-edit-modal',
    function (e) {
        
        utilFormRemoveValidationErrorMessage()
        
        var parameters = {};
        parameters['id'] = $(this).data('id');
        
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

// hide modal
$cancelModal.on(
    'click',
    function (e) {
        $(this).closest('.modal').modal('hide');

        return false;
    }
);

// build select2(organization registered_user)
function buildSelect2() {

    $select2RegisteredUserName.select2();

    var parameters = {};

    var $element = $asyncOrganizationData;

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
}

// set registered_user select2 and change patient_code property  
$select2OrganizationName.on(
    'change',
    function(e) {

        $select2RegisteredUserName.val(null).trigger('change');
        $select2RegisteredUserName.find('option:not(:first)').remove();
        $select2RegisteredUserName.prop('disabled', false);
        $patientCode.prop('disabled', false);
        
        if ($select2OrganizationName.val() === '') {
            $select2RegisteredUserName.prop('disabled', true);
            $patientCode.prop('disabled', true);
            return;
        }

        $element = $asyncRegisteredUserData;
        
        var parameters = {};
        var $form = $searchForm;

        parameters['organization_id'] = $form.find('[name=organization_name] option:selected').val();
        
        var successCallback = function (data) {
            var registered_users = [];
    
            data.forEach(function (datum) {
                var registered_user = {};
                registered_user['id'] = datum['id']; 
                registered_user['text'] = datum['name'];
                registered_users.push(registered_user);
            })
    
            $select2RegisteredUserName.select2({
                data: registered_users,
                placeholder: '',
                allowClear: true
            });
        }

        utilAsyncExecuteAjax($element, parameters, false, successCallback);
    }
)

// async-search
$asyncSearch.on(
    'click',
    function(e) {
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
$clearSearchForm.on(
    'click',
    function (e) {
        $searchFormAllInput.val('');
        $select2RegisteredUserName.val(null).trigger('change');
        $select2OrganizationName.val(null).trigger('change');
    }
)

// build search parameters
function buildSearchParameters($form) {
    var parameters = {};

    parameters['organization_name'] = $form.find('[name=organization_name] option:selected').text();
    parameters['patient_code'] = $searchFormPatientCodeInput.val();
    parameters['registered_user_name'] = $form.find('[name=registered_user_name] option:selected').text();
    parameters['registered_at_from'] = $searchFormRegisteredAtFromInput.val();
    parameters['registered_at_to'] = $searchFormRegisteredAtToInput.val();

    return parameters;
}

// pagination
$paginatedList.on('click', '.page-link', function(e) {
    var $featureElement = $(this);
    
    var parameters = {};

    var successCallback = function(paginated_list) {
        $paginatedList.html(paginated_list);
    }

    utilAsyncExecuteAjax($featureElement, parameters, false, successCallback)
});


buildSelect2()