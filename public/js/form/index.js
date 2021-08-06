$('#async').on('click',function(e) {
    var $featureElement = $(this).get(0);

    var parameters = asyncBuildParameters($('form[name="organization_registration_form"]').get(0).elements);

    asyncExecuteAjax($featureElement, parameters, true);

    return false;
});