function utilFormInputParameters($targetForm, dataset) {
    $targetForm[0].reset();
    $.each($targetForm.find('input'), function (i, elm) {
        $elm = $(elm);
        switch ($elm.attr('type')) {
            case 'text':
            case 'hidden':
                $(elm).val(dataset[$elm.attr('name')]);
                break;
            case 'radio':
                $(elm).prop('checked', +dataset[$elm.attr('name')] === +$elm.val());
                break;
        }
    });
}

function utilFormDisplayValidationErrorMessage(errors) {
    errors.forEach(function (error) {
        var key = error.key;
        var message = error.message.translated;
        var errorMessageElement = '<small class="text-danger error-message">' + message + '</small>'
        $(errorMessageElement).insertAfter($('input[name="' + key + '"]'));
    });
}

function utilFormRemoveValidationErrorMessage() {
    $('.error-message').remove();
}