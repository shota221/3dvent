const
    $registrationForm                         = $('form[name="organization_registration_form"]'), 
    $registrationFormLanguageCodeInput        = $registrationForm.find('select[name="language_code"]'), 
    $registrationFormOrganizationCode         = $registrationForm.find('input[name="organization_code"]'), 
    $registrationFormOrganizationNameInput    = $registrationForm.find('input[name="organization_name"]'), 
    $registrationFormRepresentativeEmailInput = $registrationForm.find('input[name="representative_email"]'), 
    $registrationFormRepresentativeNameInput  = $registrationForm.find('input[name="representative_name"]');

$('#async').on('click',function(e) {
    var $featureElement = $(this);

    var parameters = {};

    parameters['organization_name']    = $registrationFormOrganizationNameInput.val();
    parameters['representative_name']  = $registrationFormRepresentativeNameInput.val();
    parameters['representative_email'] = $registrationFormRepresentativeEmailInput.val();
    parameters['organization_code']    = $registrationFormOrganizationCode.val();
    parameters['language_code']        = $registrationFormLanguageCodeInput.val();

    utilAsyncExecuteAjax($featureElement, parameters, true);

    return false;
});