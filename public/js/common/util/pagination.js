$('#paginated-list').on('click', '.page-link', function(e) {
    var $featureElement = $(this);

    var parameters = {};

    var successCallback = function(paginated_list) {
        $('#paginated-list').html(paginated_list);
    }

    utilAsyncExecuteAjax($featureElement, parameters, false, successCallback)
});