$(document).on('click','#async',function(e) {
    e.preventDefault();

    var $featureElement = $(this).get(0);

    var parameters = buildParameters(document.forms['organization_registration_form'].elements);

    executeAjax($featureElement, parameters, true);
});