function utilFormInputParameters($targetForm, dataset) {
    $targetForm[0].reset();
    $.each($targetForm.find('input'), function (i, elm) {
        switch (elm.type) {
            case 'text':
            case 'hidden':
                $(elm).attr('value', dataset[elm.name]);
                break;
            case 'radio':
                $(elm).attr('checked', dataset[elm.name] === elm.value);
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