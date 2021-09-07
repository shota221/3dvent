const
    $asyncUpdate = $('#async-update'),
    $asyncSearch = $('#async-search'),
    $bulkCheck = $('#bulk-check'),
    $cancelModal = $('button.modal-cancel'),
    $clearSearchForm = $('#clear-search-form'),
    $editModal = $('#edit-modal'),
    $editModalAdverseEventContentsInput = $('#edit-modal').find('[name=adverse_event_contents]'),
    $editModalAgeInput = $('#edit-modal').find('[name=age]'),
    $editModalDiscontinuationAtInput = $('#edit-modal').find('[name=discontinuation_at]'),
    $editModalHospitalInput = $('#edit-modal').find('[name=hospital]'),
    $editModalIdInput = $('#edit-modal').find('[name=id]'),
    $editModalNationalInput = $('#edit-modal').find('[name=national]'),
    $editModalOtherDiseaseName1Input = $('#edit-modal').find('[name=other_disease_name_1]'),
    $editModalOtherDiseaseName2Input = $('#edit-modal').find('[name=other_disease_name_2]'),
    $editModalOutcomeInput = $('#edit-modal').find('[name=outcome]'),
    $editModalPatientCodeInput = $('#edit-modal').find('[name=patient_code]'),
    $editModalTreatmentInput = $('#edit-modal').find('[name=treatment]'),
    $editModalUsedPlaceInput = $('#edit-modal').find('[name=used_place]'),
    $editModalVentDiseaseNameInput = $('#edit-modal').find('[name=vent_disease_name]'),
    $datepicker = $('input.form-control.date'),
    $datetimepicker = $('input.form-control.datetime'),
    $paginatedList = $('#paginated-list'),
    $patientCode = $('#patient_code'),
    $searchForm = $('#async-search-form'),
    $searchFormAllInput = $('#async-search-form').find('input'),
    $searchFormPatientCodeInput = $('#async-search-form').find('[name=patient_code]'),
    $searchFormRegisteredAtFromInput = $('#async-search-form').find('[name=registered_at_from]'),    
    $searchFormRegisteredAtToInput = $('#async-search-form').find('[name=registered_at_to]'),
    $searchFormRegisteredUserNameInput = $('#async-search-form').find('[name=registered_user_name]');
    
// show edit modal
$paginatedList.on(
    'click',
    '.show-edit-modal',
    function (e) {

        utilFormRemoveValidationErrorMessage()

        var parameters = {};
        parameters['id'] = $(this).data('id');

        var successCallback = function (data) {
            console.log(data);
            
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
)

// async-search
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

// clear search form 
$clearSearchForm.on(
    'click',
    function (e) {
        $searchFormAllInput.val('');
    }
)

// async-udpate
$asyncUpdate.on(
    'click',
    function(e) {

        var parameters = {};
        parameters['id'] = $editModalIdInput.val();
        parameters['patient_code'] = $editModalPatientCodeInput.val();
        parameters['opt_out_flg'] = $editModal.find('[name=opt_out_flg]:checked').val();
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
        parameters['adverse_event_flg'] = $editModal.find('[name=adverse_event_flg]:checked').val();
        parameters['adverse_event_contents'] = $editModalAdverseEventContentsInput.val();


        var successCallback = function (data) {
            var $element = $('.page-item' + '.active').children('button');

            if (! $element.length) {
                $element = $asyncSearch;
            }

            var $form = $searchForm;
            var parameters = buildSearchParameters($form);

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

    utilAsyncExecuteAjax($featureElement, parameters, false, successCallback);
});

// datetimepicker
$datetimepicker.datetimepicker({
    step:5,
    format:'Y-m-d H:i:00'
}) 

// datepicker
$datepicker.datetimepicker({
    timepicker:false,
    format:'Y-m-d'
}) 

