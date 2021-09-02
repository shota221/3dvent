const
    $asyncOrganizationData = $('#async-organization-data'),
    $asyncRegisteredUserData = $('#async-registered-user-data'),
    $asyncSearch = $('#async-search'),
    $bulkCheck = $('#bulk-check'),
    $searchForm = $('#async-search-form'),
    $searchFormAllInput = $('#async-search-form').find('input'),
    $searchFormOrganizationInput = $('#async-search-form').find('[name=organization_name]'),
    $searchFormPatientCodeInput = $('#async-search-form').find('[name=patient_code]'),
    $searchFormRegisteredAtFromInput = $('#async-search-form').find('[name=registered_at_from]'),    
    $searchFormRegisteredAtToInput = $('#async-search-form').find('[name=registered_at_to]'),
    $searchFormRegisteredUserNameInput = $('#async-search-form').find('[name=registered_user_name]'),
    $cancelModal = $('button.modal-cancel'),
    $clearSearchForm = $('#clear-search-form'),
    $editModal = $('#edit-modal'),
    $editModalAdverseEventContentsInput = $('#edit-modal').find('[name=adverse_event_contents]'),
    $editModalAdverseEventFlgInput = $('#edit-modal').find('[name=adverse_event_flg]'),
    $editModalAgeInput = $('#edit-modal').find('[name=age]'),
    $editModalDiscontinuationAtInput = $('#edit-modal').find('[name=discontinuation_at]'),
    $editModalHospitalInput = $('#edit-modal').find('[name=hospital]'),
    $editModalIdInput = $('#edit-modal').find('[name=id]'),
    $editModalNationalInput = $('#edit-modal').find('[name=national]'),
    $editModalOptOutFlgInput = $('#edit-modal').find('[name=opt_out_flg]'),
    $editModalOrganizationIdInput = $('#edit-modal').find('[name=organization_id]'),
    $editModalOtherDiseaseName1Input = $('#edit-modal').find('[name=other_disease_name_1]'),
    $editModalOtherDiseaseName2Input = $('#edit-modal').find('[name=other_disease_name_2]'),
    $editModalOutcomeInput = $('#edit-modal').find('[name=outcome]'),
    $editModalPatientCodeInput = $('#edit-modal').find('[name=patient_code]'),
    $editModalTreatmentInput = $('#edit-modal').find('[name=treatment]'),
    $editModalUsedPlaceInput = $('#edit-modal').find('[name=used_place]'),
    $editModalVentDiseaseNameInput = $('#edit-modal').find('[name=vent_disease_name]'),
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

// build select2(organization)
function buildSelect2() {

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

// change patient_code property  
$select2OrganizationName.on(
    'change',
    function(e) {

        $patientCode.prop('disabled', false);
        
        if ($select2OrganizationName.val() === '') {
            $patientCode.prop('disabled', true);
            return;
        }
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

// async-update
$('#async-update').on(
    'click',
    function(e) {

        var parameters = {};
        parameters['id'] = $editModalIdInput.val();
        parameters['organization_id'] = $editModalOrganizationIdInput.val();
        parameters['patient_code'] = $editModalPatientCodeInput.val();
        parameters['opt_out_flg'] = $editModalOptOutFlgInput.val();
        parameters['age'] = $editModalAgeInput.val();
        parameters['vent_disease_name'] = $editModalVentDiseaseNameInput.val();
        parameters['other_disease_name_1'] = $editModalOtherDiseaseName1Input.val();
        parameters['other_disease_name_2'] = $editModalOtherDiseaseName2Input.val();
        parameters['used_place'] =$editModalUsedPlaceInput.val();
        parameters['hospital'] = $editModalHospitalInput.val();
        parameters['national'] = $editModalNationalInput.val();
        parameters['discontinuation_at'] = $editModalDiscontinuationAtInput.val();
        parameters['outcome'] = $editModalOutcomeInput.val();
        parameters['treatment'] = $editModalTreatmentInput.val();
        parameters['adverse_event_flg'] = $editModalAdverseEventFlgInput.val();
        parameters['adverse_event_contents'] = $editModalAdverseEventContentsInput.val();
        
        var successCallback = function (data) {
        var $element = $('.page-item' + '.active').children('button');

            if (! $element.length) {
                $element = $asyncSearch;
            } 

            var $form = $searchForm;
            var parameters = buildSearchParameters($form);
            
            var successCallback = function(pagineted_list) {
                $paginatedList.html(pagineted_list);
            } 
            utilAsyncExecuteAjax($element, parameters, false, successCallback);
          
            $editModal.modal('hide');
        }

        var $element = $(this);
        
        utilAsyncExecuteAjax($element, parameters, true, successCallback);

        return false;
    }
)

// clear search form
$clearSearchForm.on(
    'click',
    function (e) {
        $searchFormAllInput.val('');
        $select2OrganizationName.val(null).trigger('change');
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
);

// bulk delete
$paginatedList.on(
    'click',
    '#btn-bulk-delete',
    function () {
        var selectedCount = $('.item-check:checked').length;

        if (selectedCount > 0) {
            var isConfirmed = confirm(i18n('message.delete_count_confirm', { count: selectedCount}));

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
        
                    var $form = $searchForm;
                    var parameters = buildSearchParameters($form);
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

// build search parameters
function buildSearchParameters() {
    var parameters = {};

    parameters['organization_id'] = $searchFormOrganizationInput.val();
    parameters['patient_code'] = $searchFormPatientCodeInput.val();
    parameters['registered_user_name'] = $searchFormRegisteredUserNameInput.val();
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