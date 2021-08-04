$(document).on('click', '.page-link', function(e) {
    e.preventDefault();

    var $featureElement = $(this).get(0);

    var parameters = {};

    var successCallback = function(paginated_list) {
        $('#paginated-list').html(paginated_list);
    }

    executeAjax($featureElement, parameters, false, successCallback)
});