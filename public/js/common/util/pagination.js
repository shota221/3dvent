$('#paginated-list').on('click', '.page-link', function(e) {
    var $featureElement = $(this).get(0);

    var parameters = {};

    var successCallback = function(paginated_list) {
        $('#paginated-list').html(paginated_list);
    }

    asyncExecuteAjax($featureElement, parameters, false, successCallback)
});